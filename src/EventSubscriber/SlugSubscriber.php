<?php

namespace App\EventSubscriber;

use App\Attribute\AutoSlug;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\String\Slugger\SluggerInterface;
use ReflectionClass;

class SlugSubscriber implements EventSubscriber
{
    public function __construct(private SluggerInterface $slugger) {}

    public function getSubscribedEvents(): array
    {
        return [Events::prePersist, Events::preUpdate];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->handleSlug($args);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->handleSlug($args);
    }

    private function handleSlug(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $reflection = new ReflectionClass($entity);
        $attributes = $reflection->getAttributes(AutoSlug::class);

        if (empty($attributes)) {
            return;
        }

        /** @var AutoSlug $config */
        $config = $attributes[0]->newInstance();
        $sourceProp = $config->source;
        $targetProp = $config->target;

        $getter = 'get' . ucfirst($sourceProp);
        $setter = 'set' . ucfirst($targetProp);

        if (method_exists($entity, $getter) && method_exists($entity, $setter)) {
            $value = $entity->$getter();
            if ($value) {
                $slug = $this->slugger->slug($value)->lower();
                $entity->$setter($slug);
            }
        }
    }
}