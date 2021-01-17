<?php


namespace App\events;

use App\Entity\User;
use ApiPlatform\Core\EventListener\EventPriorities;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class InformationsToRequestSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setInformationsToRequest', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setInformationsToRequest(ViewEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $user = $this->security->getUser();

        $entity_methods = get_class_methods($entity);

        $instance = str_replace("App\Entity\\", "", get_class($entity));

        if (!is_object($entity) || $method === "GET")
            return;

        if ($method === "POST") {

            foreach ($entity_methods as $value) {

                switch ($value) {
                    case 'setLibrary':
                        if ($user instanceof User)
                            $entity->setLibrary($user->getLibrary());
                        break;

                    case "setCreatedAt":
                        $entity->setCreatedAt(new DateTime());
                        break;
                }
            }

            switch ($instance) {
                // Set User who is creating a library
                case 'Library':
                    $entity->addUser($user);
                    break;
            }
        }
        if ($method === "PUT") {
            foreach ($entity_methods as $value) {
                switch ($value) {
                    case "setUpdatedAt":
                        $entity->setUpdatedAt(new DateTime());
                        break;
                }
            }
        }
    }
}