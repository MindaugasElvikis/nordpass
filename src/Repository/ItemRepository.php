<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Generator;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function save(Item $item): void
    {
        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();
    }

    public function delete($item): void
    {
        $this->getEntityManager()->remove($item);
        $this->getEntityManager()->flush();
    }

    public function findByIdAndUser(int $id, User $user): ?Item
    {
        return $this->findOneBy(['id' => $id, 'user' => $user]);
    }

    /**
     * @return Item[]|Generator
     */
    public function getAllUnencryptedItems(): Generator
    {
        $queryBuilder = $this->createQueryBuilder('i');
        $queryBuilder->andWhere($queryBuilder->expr()->isNull('i.encryptionServiceName'));

        $itemsIterator = $queryBuilder->getQuery()->iterate();

        foreach ($itemsIterator as [$item]) {
            yield $item;
            $this->getEntityManager()->clear();
        }
    }
}
