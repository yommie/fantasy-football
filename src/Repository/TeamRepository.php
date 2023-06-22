<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Team>
 *
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function save(Team $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Team $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllMatchingFilter(
        ?string $search,
        ?int $limit,
        ?int $offset
    ): array {
        $qb = $this->createQueryBuilderForFilter($search);

        $qb
            ->addOrderBy('team.name')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $qb->getQuery()->execute();
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function countAllMatchingFilter(?string $search): int
    {
        $qb = $this->createQueryBuilderForFilter($search);

        return (int) $qb->select('COUNT(team.id)')->getQuery()->getSingleScalarResult();
    }

    protected function createQueryBuilderForFilter(?string $search): QueryBuilder
    {
        $qb = $this->createQueryBuilder('team');

        if ($search !== null) {
            $qb
                ->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->like('team.name', ':searchTerm')
                    )
                )
                ->setParameter('searchTerm', '%' . $search . '%');
        }

        return $qb;
    }

//    /**
//     * @return Team[] Returns an array of Team objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Team
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
