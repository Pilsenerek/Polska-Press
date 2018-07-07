<?php

namespace App\Repository;

use App\Entity\District;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method District|null find($id, $lockMode = null, $lockVersion = null)
 * @method District|null findOneBy(array $criteria, array $orderBy = null)
 * @method District[]    findAll()
 * @method District[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DistrictRepository extends ServiceEntityRepository {

    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry) {
        
        parent::__construct($registry, District::class);
    }

    /**
     * @param string $column
     * @param string $search
     * @return QueryBuilder
     */
    public function findAllQb(string $column = null, string $search = null): QueryBuilder {
        $qb = $this->createQueryBuilder('a');
        $qb->select('a', 'b');
        $qb->join('a.city', 'b');
        if (!empty($column) && !empty($search)) {
            $qb->where($qb->expr()->like($column, ":search"));
            $qb->setParameter('search', "%" . $search . "%");
        }

        return $qb;
    }

}
