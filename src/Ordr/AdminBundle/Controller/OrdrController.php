<?php

namespace Ordr\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ordr\DataBundle\Entity\Ordr;
use Ordr\AdminBundle\Form\OrdrType;

/**
 * Ordr controller.
 *
 * @Route("/admin/ordr")
 */
class OrdrController extends Controller
{
    /**
     * Lists all Ordr entities.
     *
     * @Route("/", name="admin_ordr")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('OrdrDataBundle:Ordr')->findAll();

        return array('entities' => $entities);
    }

  /**
   * Finds and displays a Ordr entity.
   *
   * @Route("/{id}/show", name="admin_ordr_show")
   * @Template()
   * @param $id
   * @throws #M#C\Ordr\AppBundle\Controller\OrdrController.createNotFoundException|?
   * @return array
   */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('OrdrDataBundle:Ordr')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ordr entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Ordr entity.
     *
     * @Route("/new", name="admin_ordr_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Ordr();
        $form   = $this->createForm(new OrdrType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Ordr entity.
     *
     * @Route("/create", name="admin_ordr_create")
     * @Method("post")
     * @Template("OrdrDataBundle:Ordr:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Ordr();
        $request = $this->getRequest();
        $form    = $this->createForm(new OrdrType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ordr_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Ordr entity.
     *
     * @Route("/{id}/edit", name="admin_ordr_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('OrdrDataBundle:Ordr')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ordr entity.');
        }

        $editForm = $this->createForm(new OrdrType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Ordr entity.
     *
     * @Route("/{id}/update", name="admin_ordr_update")
     * @Method("post")
     * @Template("OrdrDataBundle:Ordr:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('OrdrDataBundle:Ordr')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ordr entity.');
        }

        $editForm   = $this->createForm(new OrdrType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_ordr_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Ordr entity.
     *
     * @Route("/{id}/delete", name="admin_ordr_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('OrdrDataBundle:Ordr')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ordr entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_ordr'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
