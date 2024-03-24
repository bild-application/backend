<?php

namespace App\Repository;

use App\Entity\Package;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Package>
 *
 * @method Package|null find($id, $lockMode = null, $lockVersion = null)
 * @method Package|null findOneBy(array $criteria, array $orderBy = null)
 * @method Package[]    findAll()
 * @method Package[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Package::class);
    }

    public function get(string $id): ?Package
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Package[]
     */
    public function list(string $userId, ?string $profileId): array
    {
        $builder = $this->createQueryBuilder('q')
            ->join('q.profile', 'qp')
            ->join('qp.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId);

        if ($profileId) {
            $builder = $builder
                ->andWhere('q.profile = :profileId')
                ->setParameter('profileId', $profileId);
        }

        return $builder->getQuery()
            ->getResult();
    }
}
