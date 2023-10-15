<?php

namespace App\Events;

use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
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
    // they receive an argument of type Post*EventArgs, which gives you access
    // to both the entity object of the event and the entity manager itself
    public function postPersist(PostPersistEventArgs $args): void
    {
        $this->logActivityPostPersist($args);
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $this->logActivityPostRemove($args);
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->logActivityPostUpdate($args);
    }

    private function logActivityPostPersist($args): void
    {
        $entity = $args->getObject();

        /*
        switch ($entity) {
            case $entity instanceof Produit:
                //break;
            case $entity instanceof Entreprise:
                //break;
        }
        */
    }

    private function logActivityPostRemove($args): void
    {
        $entity = $args->getObject();

    }

    private function logActivityPostUpdate($args): void
    {
        $entity = $args->getObject();
    }
}