<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_registration')]
    public function index(InertiaInterface $inertia): Response
    {
        return $inertia->render("Auth/Register");
    }

    public function createUser(
        Request $request,
        EntityManagerInterface $entityManager,
        PasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user);

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RegistrationController.php',
        ]);
    }
}
