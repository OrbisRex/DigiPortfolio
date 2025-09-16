<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[\Symfony\Component\Routing\Attribute\Route(path: '/dashboard', name: 'dashboard')]
    public function index(): \Symfony\Component\HttpFoundation\Response
    {
        // Check access
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
