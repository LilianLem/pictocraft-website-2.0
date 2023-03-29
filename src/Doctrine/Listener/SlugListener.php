<?php

namespace App\Doctrine\Listener;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class SlugListener
{
    protected SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    private function process(object $entity): void {
        if(!property_exists($entity, "slug")) {
            return;
        }

        $entityClass = get_class($entity);

        if(method_exists($entityClass, "getSlugProperty")) {
            $propertyGetter = "get".ucfirst($entityClass::getSlugProperty());
        } elseif(method_exists($entity, "getName")) {
            $propertyGetter = "getName";
        } else {
            return;
        }

        $newSlug = strtolower($this->slugger->slug($entity->$propertyGetter()));

        if(empty($entity->getSlug()) || (!empty($entity->getId()) && $entity->getSlug() !== $newSlug)) {
            $entity->setSlug($newSlug);
        }
    }

    public function prePersist(LifecycleEventArgs $event): void
    {
        $this->process($event->getObject());
    }

    public function preUpdate(LifecycleEventArgs $event): void
    {
        $this->process($event->getObject());
    }
}