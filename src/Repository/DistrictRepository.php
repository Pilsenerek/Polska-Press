<?php

namespace App\Repository;

use App\Entity\District;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Elastica\Query;
use Elastica\Query\Wildcard;
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
    
    /**
     * @param string $propertyName
     * @param string $search
     * @return Query
     */
    public function findAllEs(string $propertyName = null, string $search = null): Query {
        $query = new Query();
        if (!empty($propertyName) && !empty($search)) {
            $matchAll = new Wildcard($propertyName, '*' . $search . '*');
            $query->setQuery($matchAll);
        }

        return $query;
    }

}
