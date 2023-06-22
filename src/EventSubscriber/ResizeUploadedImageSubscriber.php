<?php

namespace App\EventSubscriber;

use App\Entity\Team;
use App\Util\ImageHandler;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ResizeUploadedImageSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [Events::POST_UPLOAD => 'resizeUploadedImage'];
    }

    public function resizeUploadedImage(Event $event): void
    {
        if ($event->getMapping()->getMappingName() !== 'team_logo') {
            return;
        }

        /** @var Team $team */
        $team = $event->getObject();

        $pathToImage = sprintf(
            '%s/%s',
            rtrim($event->getMapping()->getUploadDestination(), DIRECTORY_SEPARATOR),
            $team->getLogo()
        );

        ImageHandler::createResizedImageFile($pathToImage, 60, 60);
    }
}
