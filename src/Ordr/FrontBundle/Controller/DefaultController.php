<?php

namespace Ordr\FrontBundle\Controller;

use \Ordr\FrontBundle\Form\OrdrType;
use \Ordr\DataBundle\Entity\Ordr;
use \Ordr\DataBundle\Entity\OrdrMeta;
use \Ordr\FrontBundle\Form\OrdrMetaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
  /**
   * @Route("/", name="index")
   * @Template()
   * @return array
   */
  public function indexAction()
  {
    $entity = new OrdrMeta();
    $now = new \DateTime('now');
    $entity->setNextOrdr($now->add(new \DateInterval('P2D')));
    $form = $this->createForm(new OrdrMetaType(), $entity);

    return array(
      'entity' => $entity,
      'form' => $form->createView()
    );
  }

  /**
   * @Route("/create", name="ordrmeta_create")
   * @Method("post")
   * @Template("OrdrFrontBundle:Default:index.html.twig")
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function createAction()
  {
    $entity = new OrdrMeta();
    $request = $this->getRequest();
    $form = $this->createForm(new OrdrMetaType(), $entity);
    $form->bindRequest($request);

    if ($form->isValid()) {
      $em = $this->getEm();

      $token = str_split(base64_encode(hash('sha256', uniqid("", true))), 10);
      $entity->setToken($token[0]);
      $entity->setAdminToken($token[1]);
      $now = new \DateTime('now');
      $entity->setCreatedAt($now);

      $em->persist($entity);
      $em->flush();

      $request->getSession()->set('admin', $entity->getAdminToken());

      return $this->redirect($this->generateUrl('ordrmeta_show', array('token' => $entity->getToken())));
    }

    return array(
      'entity' => $entity,
      'form' => $form->createView()
    );
  }

  /**
   * @Route("/{token}", requirements={"token" = ".{10,10}"}, name="ordrmeta_show")
   * @param string $token
   * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
   * @Method("get")
   * @Template()
   * @return array
   */
  public function showAction($token)
  {
    $request = $this->getRequest();
    list($em, $ordrMeta) = $this->fetchOrdr($token);

    if($request->get('admin') === $ordrMeta->getAdminToken()) {
      $request->getSession()->set('admin', $ordrMeta->getAdminToken());
      return $this->redirect($this->generateUrl('ordrmeta_show', array('token' => $ordrMeta->getToken())));
    }

    $sum = $this->sumOrdrs($ordrMeta);

    list($entity, $form) = $this->buildShowForm();

    list($ordrs, $otherOrdrs, $hasOrdrs) = $this->fetchOrdrsForShow($em, $token);

    $condensedList = $this->condenseList($ordrs, $otherOrdrs);

    return array(
      'entity' => $entity,
      'condensedList' => $condensedList,
      'form' => $form->createView(),
      'sum' => $sum,
      'ordrMeta' => $ordrMeta,
      'hasOrdrs' => $hasOrdrs,
      'token' => $token,
      'adminToken' => $request->getSession()->get('admin'),
      'ownOrdrs' => $ordrs,
      'otherOrdrs' => $otherOrdrs,
      'closed' => new \DateTime('now') > $ordrMeta->getNextOrdr(),
      'now' => new \DateTime('now')
    );
  }

  private function buildShowForm()
  {
    $entity = new Ordr();
    $form = $this->createForm(new OrdrType(), $entity);
    return array($entity, $form);
  }

  private function fetchOrdr($token)
  {
    $em = $this->getDoctrine()->getEntityManager();

    $ordrMeta = $em->getRepository('OrdrDataBundle:OrdrMeta')->findOneByToken($token);

    if (!$ordrMeta) {
      throw $this->createNotFoundException('This ordr.io does not exist');
    }
    return array($em, $ordrMeta);
  }

  private function sumOrdrs($ordrMeta)
  {
    $sum = 0;
    foreach ($ordrMeta->getOrdrs() as $ordr) {
      $sum += $ordr->getAmount();
    }
    return $sum;
  }

  private function fetchOrdrsForShow($em, $token)
  {
    $ordrs = $em->getRepository('OrdrDataBundle:Ordr')->findBySessionAndToken($this->getRequest()->getSession()->getId(), $token);
    $otherOrdrs = $em->getRepository('OrdrDataBundle:Ordr')->findByTokenExcludeSession($token, $this->getRequest()->getSession()->getId());
    $origCount = count($ordrs);
    $hasOrdrs = $origCount > 0 ? 'yes' : 'no';
    return array($ordrs, $otherOrdrs, $hasOrdrs);
  }

  private function condenseList($ordrs, $otherOrdrs)
  {
    $condensedList = array();
    foreach (array_merge($ordrs, $otherOrdrs) as $_ordr) {
      if (!isset($condensedList[$_ordr->getExtra()+$_ordr->getPrice()])) {
        $condensedList[$_ordr->getExtra()+$_ordr->getPrice()] = array(
          'amount' => $_ordr->getAmount(),
          'sum' => $_ordr->getPrice() * $_ordr->getAmount(),
          'extra' => $_ordr->getExtra()
        );
      }
      else {
        $item = $condensedList[$_ordr->getExtra()+$_ordr->getPrice()];
        $item['sum'] = ($_ordr->getPrice() * $_ordr->getAmount()) + $item['sum'];
        $item['amount'] = $_ordr->getAmount() + $item['amount'];
        $condensedList[$_ordr->getExtra()+$_ordr->getPrice()] = $item;
      }
    }

    return $condensedList;
  }

  /**
   * Creates a new Ordr entity.
   *
   * @Route("/{token}/create", requirements={"token" = ".{10,10}"}, name="ordr_create")
   * @Method("post")
   * @Template("OrdrFrontBundle:Default:show.html.twig")
   * @param $token
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function createOrdrAction($token)
  {
    $entity = new Ordr();
    $request = $this->getRequest();
    $form = $this->createForm(new OrdrType(), $entity);
    $form->bindRequest($request);

    $em = $this->getDoctrine()->getEntityManager();
    $ordrMeta = $em->getRepository('OrdrDataBundle:OrdrMeta')->findOneByToken($token);
    $adminToken = $ordrMeta->getAdminToken();
    $routeParams = array('token' => $token);
    if ($request->get('admin') == $adminToken) {
      $routeParams['admin'] = $adminToken;
    }
    else {
      $adminToken = 'none';
    }

    if ($form->isValid()) {
      $entity->setSession($request->getSession()->getId());
      $entity->setCreatedAt(new \DateTime('now'));
      $entity->setOrdr($ordrMeta);
      $entity->setPublic(true);
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('ordrmeta_show', $routeParams));
    }

    $sum = 0;
    foreach ($ordrMeta->getOrdrs() as $ordr) {
      $sum += $ordr->getAmount();
    }

    list($ordrs, $otherOrdrs, $hasOrdrs) = $this->fetchOrdrsForShow($em, $token);

    $condensedList = $this->condenseList($ordrs, $otherOrdrs);

    return array(
      'entity' => $entity,
      'session' => $request->getSession(),
      'form' => $form->createView(),
      'adminToken' => $adminToken,
      'ordrMeta' => $ordrMeta,
      'sum' => $sum,
      'hasOrdrs' => $hasOrdrs,
      'ownOrdrs' => $ordrs,
      'otherOrdrs' => $otherOrdrs,
      'condensedList' => $condensedList,
      'closed' => new \DateTime('now') > $ordrMeta->getNextOrdr(),
      'now' => new \DateTime('now')
    );
  }

  /**
   * Creates a new Ordr entity.
   *
   * @Route("/{token}/revert", requirements={"token" = ".{10,10}"}, name="ordr_revert")
   * @Method("post")
   * @Template("OrdrFrontBundle:Default:show.html.twig")
   * @param $token
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function revertOrdrAction($token)
  {
    $entity = new Ordr();
    $request = $this->getRequest();
    $form = $this->createForm(new OrdrType(), $entity);
    $form->bindRequest($request);

    $em = $this->getDoctrine()->getEntityManager();
    $ordrMeta = $em->getRepository('OrdrDataBundle:OrdrMeta')->findOneByToken($token);
    $adminToken = $ordrMeta->getAdminToken();
    $routeParams = array('token' => $token);
    if ($request->get('admin') == $adminToken) {
      $routeParams['admin'] = $adminToken;
    }

    $ordrs = $em->getRepository('OrdrDataBundle:Ordr')->findBySessionAndToken($request->getSession()->getId(), $token);
    if ($request->get('revert-all-ordrs') == 'revert' || $request->get('revert-one-ordr') == 'revert') {

      if ($request->get('revert-all-ordrs') == 'revert') {
        foreach ($ordrs as $ordr) {
          $em->remove($ordr);
        }
      }
      elseif ($request->get('revert-one-ordr') == 'revert') {
        $cCount = count($ordrs);
        if ($cCount > 0) {
          $em->remove($ordrs[$cCount - 1]);
        }
      }

      $em->flush();

    }

    return $this->redirect($this->generateUrl('ordrmeta_show', $routeParams));
  }

  /**
   * @Route("/{token}/{admin}/{id}/delete", requirements={"token" = ".{10,10}", "admin" = ".{10,10}"}, name="ordr_delete")
   * @Method("post")
   * @Template("OrdrFrontBundle:Default:index.html.twig")
   * @param $token
   * @param $admin
   * @param $id
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function deleteOrdrAction($token, $admin, $id)
  {
    $em = $this->getEm();
    $ordr = $em->getRepository('OrdrDataBundle:Ordr')->findByTokenAdminTokenAndId($token, $admin, $id);

    if ($ordr) {
      $em->remove($ordr);
      $em->flush();
    }

    return $this->redirect($this->generateUrl('ordrmeta_show', array('token' => $token, 'admin' => $admin)));
  }

  /**
   * @Route("/{token}/{id}/user_delete", requirements={"token" = ".{10,10}"}, name="ordr_user_delete")
   * @Method("post")
   * @Template("OrdrFrontBundle:Default:index.html.twig")
   * @param $token
   * @param $id
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function userDeleteOrdrAction($token, $id)
  {
    $request = $this->getRequest();
    $em = $this->getEm();
    $ordrMeta = $em->getRepository('OrdrDataBundle:OrdrMeta')->findOneByToken($token);
    $session = $this->getRequest()->getSession()->getId();
    $ordr = $em->getRepository('OrdrDataBundle:Ordr')->findBySessionTokenAndId($session, $token, $id);

    if ($ordr) {
      $em->remove($ordr);
      $em->flush();
    }

    $routeParams = array('token' => $token);
    if ($ordrMeta->getAdminToken() == $request->get('admin')) {
      $routeParams['admin'] = $request->get('admin');
    }

    return $this->redirect($this->generateUrl('ordrmeta_show', $routeParams));
  }

  /**
   * @Route("/{token}/{admin}/{id}/check", requirements={"token" = ".{10,10}"}, name="ordr_check")
   * @Method("post")
   * @Template("OrdrFrontBundle:Default:index.html.twig")
   * @param $token
   * @param $admin
   * @param $id
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function checkOrdrAction($token, $admin, $id)
  {
    $em = $this->getEm();
    $ordr = $em->getRepository('OrdrDataBundle:Ordr')->findByTokenAdminTokenAndId($token, $admin, $id);
    if ($ordr) {
      $ordr->setChecked(!$ordr->getChecked());
      $em->persist($ordr);
      $em->flush();
    }

    return $this->redirect($this->generateUrl('ordrmeta_show', array('token' => $token, 'admin' => $admin)));
  }

  /**
   * @Route("/sitemap.xml")
   * @Template("OrdrFrontBundle:Default:sitemap.xml.twig")
   * @return array()
   */
  public function sitemapAction()
  {
    $em = $this->getEm();
    $ordrMeta = $em->getRepository('OrdrDataBundle:OrdrMeta')->findByPublic(true);

    return array("ordrMetas" => $ordrMeta);
  }

  /**
   * @return \Doctrine\ORM\EntityManager
   */
  private function getEm()
  {
    return $this->getDoctrine()->getEntityManager();
  }
}
