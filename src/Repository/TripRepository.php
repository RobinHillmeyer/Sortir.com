<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Trip;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trip>
 *
 * @method Trip|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trip[]    findAll()
 * @method Trip[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function add(Trip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Trip $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTrips()
    {
        $queryBuilder = $this->createQueryBuilder('t');
        $queryBuilder->leftJoin('t.state', 's')
            ->addSelect('s')
            ->leftJoin('t.users', 'u')
            ->addSelect('u')
            ->join('t.promoter', 'p')
            ->addSelect('p');

        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        return $result;
    }

    public function findSearch(SearchData $searchData, User $user): array
    {
        $query = $this->createQueryBuilder('t')
            ->join('t.campus', 'c')
            ->join('t.users', 'u')
            ->join('t.promoter', 'p')
            ->select('t', 'c', 'u', 'p');

        if (!empty($searchData->campus)) {
            $query = $query->andWhere('c.id IN (:campus)')
                ->setParameter('campus', $searchData->campus);
        }

        if (!empty($searchData->keyWord)) {
            $query = $query->andWhere('t.name LIKE :keyWord')
                ->setParameter('keyWord', "%{$searchData->keyWord}%");
        }

        if (!empty($searchData->dateFrom)) {
            $query = $query->andWhere('t.startDateTime >= :dateFrom')
                ->setParameter('dateFrom', $searchData->dateFrom);
        }

        if (!empty($searchData->dateTo)) {
            $query = $query->andWhere('t.startDateTime <= :dateTo')
                ->setParameter('dateTo', $searchData->dateTo);
        }

        if (!empty($searchData->isPromoter)) {
            $query = $query->andWhere('p.id = :userId')
                ->setParameter('userId', $user->getId());
        }

        if (!empty($searchData->isSub)) {
            $query = $query->andWhere('u.id = :userId')
                ->setParameter('userId', $user->getId());
        }

        if (!empty($searchData->isntSub)) {
            $query = $query->andWhere('u.id != :userId')
                ->setParameter('userId', $user->getId());
        }

        if (!empty($searchData->isTripEnd)) {
            $query = $query->andWhere('t.state = 6');
        }

        return $query->getQuery()->getResult();
    }
}
