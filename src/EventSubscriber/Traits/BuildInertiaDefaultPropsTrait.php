<?php

namespace App\EventSubscriber\Traits;

use ArrayObject;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

trait BuildInertiaDefaultPropsTrait
{
    protected function buildDefaultProps(Request $request, ?User $user): array
    {
        return [
            "auth"      => $this->buildAuthProps($user),
            "flash"     => $this->buildFlashProps($request),
            "errors"    => new ArrayObject()
        ];
    }

    private function buildAuthProps(?User $user): array
    {
        return [
            "user" => $user === null ? null : $this->buildUserProps($user)
        ];
    }

    private function buildUserProps(User $user): array
    {
        return [
            'id'            => $user->getId(),
            'email'         => $user->getEmail(),
            'last_name'     => $user->getLastName(),
            'first_name'    => $user->getFirstName()
        ];
    }

    private function buildFlashProps(Request $request): array
    {
        $flashMessages = [
            "error"     => null,
            "success"   => "This is a test"
        ];

        if (!$request->hasSession()) {
            return $flashMessages;
        }

        $session = $request->getSession();

        if ($session->getFlashBag()->has('success')) {
            $flashMessages["error"]     = $this->getFlashErrorMessage($session);
            $flashMessages["success"]   = $this->getFlashSuccessMessage($session);
        }

        return $flashMessages;
    }

    private function getFlashSuccessMessage(SessionInterface $session): ?string
    {
        $flashSuccessMessages = $session->getFlashBag()->get('success');
        return reset($flashSuccessMessages);
    }

    private function getFlashErrorMessage(SessionInterface $session): ?string
    {
        $flashErrorMessages = $session->getFlashBag()->get('error');
        return reset($flashErrorMessages);
    }
}
