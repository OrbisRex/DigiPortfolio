<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of DefaultController
 *
 * @author David Ehrlich
 */
class DefaultController extends BasicController
{
     #[Route(path: "/", name: "home")]
    public function index(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('default/index.html.twig', array(
            'username' => NULL,
        ));
    }
}
