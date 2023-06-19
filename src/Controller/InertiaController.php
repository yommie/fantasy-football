<?php

namespace App\Controller;

use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InertiaController extends AbstractController
{
    #[Route('/inertia', name: 'app_inertia')]
    public function index(InertiaInterface $inertia): Response
    {
        return $inertia->render('Inertia', [
            'name' => 'InertiaController',
        ]);
    }
}
