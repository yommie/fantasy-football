<?php

namespace App\Controller;

use App\Entity\User;
use App\Util\Form\FormErrors;
use App\Util\Request\JsonRequest;
use App\Repository\UserRepository;
use App\Form\RegistrationFormType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly Security                    $security,
        private readonly InertiaInterface            $inertia,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    #[Route('/register', name: 'app_registration')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        if ($request->getMethod() === Request::METHOD_POST) {
            return $this->handleRegistration($request, $userRepository);
        }

        return $this->inertia->render("Auth/Register");
    }

    private function handleRegistration(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();

        $form = $this->createForm(RegistrationFormType::class, $user)
            ->submit(JsonRequest::getJson($request));

        if ($form->isValid()) {
            $user->setPassword(
                $this->userPasswordHasher->hashPassword($user, $form->get('password')->getData())
            );

            $userRepository->save($user, true);

            $this->security->login($user);

            return $this->redirectToRoute("app_teams");
        }

        $errorProps = array_merge(
            $this->inertia->getShared("errors"),
            FormErrors::getErrors($form)
        );

        $this->inertia->share("errors", $errorProps);

        return $this->inertia->render("Auth/Register");
    }
}
