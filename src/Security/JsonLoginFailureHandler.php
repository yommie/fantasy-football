<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class JsonLoginFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function __construct(protected InertiaInterface $inertia)
    {
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $errorProps = array_merge(
            (array) $this->inertia->getShared("errors"),
            [
                'email' => $exception->getMessage()
            ]
        );

        $this->inertia->share("errors", $errorProps);

        return $this->inertia->render('Auth/Login');
    }
}
