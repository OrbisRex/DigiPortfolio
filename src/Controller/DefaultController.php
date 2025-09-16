<?php

namespace App\Controller;

/**
 * Description of DefaultController.
 *
 * @author David Ehrlich
 */
class DefaultController extends BasicController
{
    #[\Symfony\Component\Routing\Attribute\Route(path: '/', name: 'home')]
    public function index(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('default/index.html.twig', [
            'username' => null,
        ]);
    }
}
