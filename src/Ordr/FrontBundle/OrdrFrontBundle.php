<?php

namespace Ordr\FrontBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class OrdrFrontBundle extends Bundle
{
  public function boot()
  {
    $container = $this->container;
    // Set default PHP locale to match session locale
    if ($container->has('session')) {
      $session = $container->get('session');
      \Locale::setDefault($session->getLocale());
    }
  }
}
