<?php

namespace App\Controller;

use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamsController extends AbstractController
{
    #[Route('/', name: 'app_teams')]
    public function index(InertiaInterface $inertia): Response
    {
        return $inertia->render(
            "Teams/Index",
            [
                "count" => 1,
                "counting" => "Yes, it is counting"
            ]
        );
    }
}
