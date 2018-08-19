<?php

namespace pos\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends BaseController
{
    public function indexAction()
    {
        return $this->render('APIBundle:Default:index.html.twig');
    }
}
