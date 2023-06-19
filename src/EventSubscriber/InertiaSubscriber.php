<?php

namespace App\EventSubscriber;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use App\EventSubscriber\Traits\BuildInertiaDefaultPropsTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InertiaSubscriber implements EventSubscriberInterface
{
    use BuildInertiaDefaultPropsTrait;

    public function __construct(
        protected Security $security,
        protected InertiaInterface $inertia
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        $defaultProps = $this->buildDefaultProps($request, $this->security->getUser());

        foreach ($defaultProps as $key => $defaultProp) {
            $this->inertia->share($key, $defaultProp);
        }
    }
}
