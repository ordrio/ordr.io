<?php

namespace Ordr\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Default controller.
 *
 * @Route("/admin")
 */
class DefaultController extends Controller
{
  /**
   * @Route("/")
   * @Template()
   * @return array
   */
    public function indexAction()
    {
        return array('name' => 'foo');
    }
}
