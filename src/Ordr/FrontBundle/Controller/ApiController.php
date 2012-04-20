<?php

namespace Ordr\FrontBundle\Controller;

use \Symfony\Component\HttpFoundation\Response;
use \Ordr\FrontBundle\Form\OrdrType;
use \Ordr\DataBundle\Entity\Ordr;
use \Ordr\DataBundle\Entity\OrdrMeta;
use \Ordr\FrontBundle\Form\OrdrMetaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{

  /**
   * @Route("/{token}/complete/extra", name="api_complete_extra")
   * @param $token
   * @return array
   */
  public function completeDescriptionAction($token)
  {
    $request = $this->getRequest();
    $extraStartsWith = $request->get("extraStartsWith");
    $em = $this->getEm();
    $ordrMetaRepo = $em->getRepository("OrdrDataBundle:OrdrMeta");
    $ordrRepo = $em->getRepository("OrdrDataBundle:Ordr");
    $ordrMeta = $ordrMetaRepo->findOneByToken($token);
    $ordrs = $ordrRepo->findAllExtraLikeByToken($token, $extraStartsWith);
    $extras = array();

    $i18nPrice = $this->get('sonata.intl.templating.helper.number');

    if($ordrMeta->getPublic()) {
      foreach ($ordrs as $ordr) {
        $extras[] = array(
          'label' => $ordr['extra'],
          'value' => $ordr['extra'],
          'localePrice' => $i18nPrice->formatCurrency($ordr['price'],'EUR'),
          'price' => $i18nPrice->formatDecimal($ordr['price'])
        );
      }
    }

    $response = new Response(json_encode(array('extras' => $extras)));
    return $response;
  }

  /**
   * @return \Doctrine\ORM\EntityManager
   */
  private function getEm()
  {
    return $this->getDoctrine()->getEntityManager();
  }

}