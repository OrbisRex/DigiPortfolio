<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of DefaultController.
 *
 * @author David Ehrlich
 */
class DefaultController extends BasicController
{
    #[Route(path: '/', name: 'home')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'username' => null,
        ]);
    }
}
