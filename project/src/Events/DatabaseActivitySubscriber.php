<?php

namespace App\Events;

use App\Entity\Produit;
use Doctrine\ORM\Events;
use App\Entity\Entreprise;
use App\Entity\Formulaire;
use App\Entity\RenderVous;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;

class DatabaseActivitySubscriber implements EventSubscriberInterface
{
    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    // callback methods must be called exactly like the events they listen to;
    // they receive an argument of type LifecycleEventArgs, which gives you access
    // to both the entity object of the event and the entity manager itself
    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->logActivity('persist', $args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->logActivity('remove', $args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->logActivity('update', $args);
    }

    private function logActivity(string $action, LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        // ... get the entity information and log it somehow

        switch ($action) {
            case 'persist':
                if ($entity instanceof Produit) {
                } elseif ($entity instanceof Entreprise) {
                } elseif ($entity instanceof Formulaire) {
                } elseif ($entity instanceof RenderVous) {
                    if ($entity->getTitle() == "string") {
                        throw new \Exception('Invalid name passed ', 500);
                    }
                }
                break;
            case 'remove':
                break;
            case 'update':
                break;
        }
    }
}