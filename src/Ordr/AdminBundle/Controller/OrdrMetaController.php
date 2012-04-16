<?php

namespace Ordr\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ordr\DataBundle\Entity\OrdrMeta;
use Ordr\AdminBundle\Form\OrdrMetaType;

/**
 * OrdrMeta controller.
 *
 * @Route("/admin/ordrmeta")
 */
class OrdrMetaController extends Controller
{
  /**
   * Lists all OrdrMeta entities.
   *
   * @Route("/", name="admin_ordrmeta")
   * @Template()
   * @return array
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getEntityManager();

    $entities = $em->getRepository('OrdrDataBundle:OrdrMeta')->findAll();

    return array('entities' => $entities);
  }

  /**
   * Finds and displays a OrdrMeta entity.
   *
   * @Route("/{id}/show", name="admin_ordrmeta_show")
   * @Template()
   * @param $id
   * @throws #M#C\Ordr\DataBundle\Controller\OrdrMetaController.createNotFoundException|?
   * @return array
   */
  public function showAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();

    $entity = $em->getRepository('OrdrDataBundle:OrdrMeta')->find($id);

    if (!$entity) {
      throw $this->createNotFoundException('Unable to find OrdrMeta entity.');
    }

    $deleteForm = $this->createDeleteForm($id);

    return array(
      'entity' => $entity,
      'delete_form' => $deleteForm->createView(),);
  }

  /**
   * Displays a form to create a new OrdrMeta entity.
   *
   * @Route("/new", name="admin_ordrmeta_new")
   * @Template()
   * @return array
   */
  public function newAction()
  {
    $entity = new OrdrMeta();
    $form = $this->createForm(new OrdrMetaType(), $entity);

    return array(
      'entity' => $entity,
      'form' => $form->createView()
    );
  }

  /**
   * Creates a new OrdrMeta entity.
   *
   * @Route("/create", name="admin_ordrmeta_create")
   * @Method("post")
   * @Template("OrdrDataBundle:OrdrMeta:new.html.twig")
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function createAction()
  {
    $entity = new OrdrMeta();
    $request = $this->getRequest();
    $form = $this->createForm(new OrdrMetaType(), $entity);
    $form->bindRequest($request);

    if ($form->isValid()) {
      $em = $this->getDoctrine()->getEntityManager();
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('admin_ordrmeta_show', array('id' => $entity->getId())));

    }

    return array(
      'entity' => $entity,
      'form' => $form->createView()
    );
  }

  /**
   * Displays a form to edit an existing OrdrMeta entity.
   *
   * @Route("/{id}/edit", name="admin_ordrmeta_edit")
   * @Template()
   * @param $id
   * @throws #M#C\Ordr\DataBundle\Controller\OrdrMetaController.createNotFoundException|?
   * @return array
   */
  public function editAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();

    $entity = $em->getRepository('OrdrDataBundle:OrdrMeta')->find($id);

    if (!$entity) {
      throw $this->createNotFoundException('Unable to find OrdrMeta entity.');
    }

    $editForm = $this->createForm(new OrdrMetaType(), $entity);
    $deleteForm = $this->createDeleteForm($id);

    return array(
      'entity' => $entity,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
    );
  }

  /**
   * Edits an existing OrdrMeta entity.
   *
   * @Route("/{id}/update", name="admin_ordrmeta_update")
   * @Method("post")
   * @Template("OrdrDataBundle:OrdrMeta:edit.html.twig")
   * @param $id
   * @throws #M#C\Ordr\DataBundle\Controller\OrdrMetaController.createNotFoundException|?
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function updateAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();

    $entity = $em->getRepository('OrdrDataBundle:OrdrMeta')->find($id);

    if (!$entity) {
      throw $this->createNotFoundException('Unable to find OrdrMeta entity.');
    }

    $editForm = $this->createForm(new OrdrMetaType(), $entity);
    $deleteForm = $this->createDeleteForm($id);

    $request = $this->getRequest();

    $editForm->bindRequest($request);

    if ($editForm->isValid()) {
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('admin_ordrmeta_edit', array('id' => $id)));
    }

    return array(
      'entity' => $entity,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
    );
  }

  /**
   * Deletes a OrdrMeta entity.
   *
   * @Route("/{id}/delete", name="admin_ordrmeta_delete")
   * @Method("post")
   * @param $id
   * @throws #M#C\Ordr\DataBundle\Controller\OrdrMetaController.createNotFoundException|?
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   */
  public function deleteAction($id)
  {
    $form = $this->createDeleteForm($id);
    $request = $this->getRequest();

    $form->bindRequest($request);

    if ($form->isValid()) {
      $em = $this->getDoctrine()->getEntityManager();
      $entity = $em->getRepository('OrdrDataBundle:OrdrMeta')->find($id);

      if (!$entity) {
        throw $this->createNotFoundException('Unable to find OrdrMeta entity.');
      }

      $em->remove($entity);
      $em->flush();
    }

    return $this->redirect($this->generateUrl('admin_ordrmeta'));
  }

  private function createDeleteForm($id)
  {
    return $this->createFormBuilder(array('id' => $id))
      ->add('id', 'hidden')
      ->getForm();
  }
}
