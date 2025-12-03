<?php
// src/Repository/TrashRepository.php
namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;

class TrashRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function findAll(): array
    {
        $metaDataList = $this->em->getMetadataFactory()->getAllMetadata();
        $deletedItems = [];

        foreach ($metaDataList as $metaData) {
            $className = $metaData->getName();

            if (
                !$metaData->hasField('deletedAt') ||
                !(new ReflectionClass($className))->isInstantiable()
            ) {
                continue;
            }

            $qb = $this->em->getRepository($className)->createQueryBuilder('e');
            $qb->where('e.deletedAt IS NOT NULL');

            $results = $qb->getQuery()->getResult();

            foreach ($results as $entity) {
                $deletedItems[] = [
                    'entity' => (new \ReflectionClass($entity))->getShortName(),
                    'slug' => $entity->getEntityLibelle() ?? 'Indefini',
                    'class' => $className,
                    'id' => $entity->getId(),
                    'deletedAt' => $entity->getDeletedAt(),
                    'deletedUser' => method_exists($entity, 'getDeletedUser') ? $entity->getDeletedUser() : 'Inconnu',
                    // 'deletedIp' => method_exists($entity, 'getDeletedIp') ? $entity->getDeletedIp() : 'N/A',
                    'instance' => $entity,
                ];
            }
        }

        return $deletedItems;
    }
}