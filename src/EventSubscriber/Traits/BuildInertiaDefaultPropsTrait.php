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
            "errors"    => [],
            "userTeam"  => $this->buildTeamProps($user)
        ];
    }

    private function buildAuthProps(?User $user): array
    {
        return [
            "user" => $user === null ? null : $this->buildUserProps($user)
        ];
    }

    private function buildTeamProps(?User $user): array|false
    {
        return ($user !== null && $user->getTeam() !== null) ?
            $user->getTeam()->getJsonResponse() :
            false;
    }

    private function buildUserProps(User $user): array
    {
        return [
            'id'            => $user->getId(),
            'email'         => $user->getEmail(),
            'lastName'      => $user->getLastName(),
            'firstName'     => $user->getFirstName()
        ];
    }

    private function buildFlashProps(Request $request): array
    {
        $flashMessages = [
            "error"     => null,
            "success"   => null
        ];

        if (!$request->hasSession()) {
            return $flashMessages;
        }

        $session = $request->getSession();

        if ($session->getFlashBag()->has('success')) {
            $flashMessages["success"]   = $this->getFlashSuccessMessage($session);
        }

        if ($session->getFlashBag()->has('error')) {
            $flashMessages["error"]     = $this->getFlashErrorMessage($session);
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
