<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of DefaultController
 *
 * @author David Yilma
 */
class DefaultController extends BasicController
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request) {
        
        return $this->render('default/index.html.twig', array(
            'username' => NULL,
        ));
    }
}
