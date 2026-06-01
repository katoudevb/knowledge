<?php

namespace App\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

/**
 * Doctrine Event Subscriber responsible for automatically
 * managing audit information on entities.
 *
 * It sets:
 * - creation date (createdAt)
 * - update date (updatedAt)
 * - creator user (createdBy)
 * - last editor user (updatedBy)
 *
 * This avoids duplicating the same logic in controllers or services.
 */
class AuditSubscriber implements EventSubscriber
{
    /**
     * Injects Symfony's Security service
     * to retrieve the currently authenticated user.
     */
    public function __construct(private Security $security)
    {
    }

    /**
     * Returns the Doctrine events listened to by this subscriber.
     *
     * prePersist: triggered before an entity is inserted.
     * preUpdate : triggered before an entity is updated.
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    /**
     * Executed automatically before an entity is persisted.
     *
     * Used to initialize audit fields during entity creation.
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        // Retrieve the entity being persisted.
        $entity = $args->getObject();

        // Retrieve the currently authenticated user.
        $user = $this->security->getUser();

        /**
         * Check whether the entity supports timestamp fields.
         * This makes the subscriber reusable across multiple entities.
         */
        if (method_exists($entity, 'setCreatedAt') && method_exists($entity, 'setUpdatedAt')) {

            // Create a single immutable timestamp.
            $now = new \DateTimeImmutable();

            // Set creation date.
            $entity->setCreatedAt($now);

            // Set initial update date.
            $entity->setUpdatedAt($now);
        }

        /**
         * Check whether the entity supports user tracking fields.
         */
        if ($user && method_exists($entity, 'setCreatedBy') && method_exists($entity, 'setUpdatedBy')) {

            // Store the user who created the entity.
            $entity->setCreatedBy($user);

            // Store the first editor (same as creator).
            $entity->setUpdatedBy($user);
        }
    }

    /**
     * Executed automatically before an entity is updated.
     *
     * Updates audit fields related to modifications.
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        // Retrieve the entity being updated.
        $entity = $args->getObject();

        // Retrieve the currently authenticated user.
        $user = $this->security->getUser();

        /**
         * Update the modification timestamp.
         */
        if (method_exists($entity, 'setUpdatedAt')) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }

        /**
         * Update the user responsible for the modification.
         */
        if ($user && method_exists($entity, 'setUpdatedBy')) {
            $entity->setUpdatedBy($user);
        }
    }
}