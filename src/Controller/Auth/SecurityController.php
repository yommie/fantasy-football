<?php

namespace App\Controller\Auth;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login', methods: ["GET"])]
    #[Route('/login', name: 'login_attempt', methods: ['POST'])]
    public function login(
        #[CurrentUser] ?User $user,
        InertiaInterface $inertia
    ): Response {
        if ($user !== null) {
            return $this->redirectToRoute('app_teams');
        }

        return $inertia->render('Auth/Login');
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): never
    {
    }
}
