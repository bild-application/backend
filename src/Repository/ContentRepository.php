<?php

namespace App\Repository;

use App\Entity\Content;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Content>
 *
 * @method Content|null find($id, $lockMode = null, $lockVersion = null)
 * @method Content|null findOneBy(array $criteria, array $orderBy = null)
 * @method Content[]    findAll()
 * @method Content[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Content::class);
    }

    public function get(string $id): ?Content
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Content[]
     */
    public function list(string $userId): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.user = :user_id')
            ->setParameter('user_id', $userId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Content[]
     */
    public function listForProfile(string $userId, string $profileId): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.user = :user_id')
            ->andWhere('c.profile is NULL OR c.profile = :prodile_id')
            ->setParameter('user_id', $userId)
            ->setParameter('prodile_id', $profileId)
            ->getQuery()
            ->getResult();
    }
}
