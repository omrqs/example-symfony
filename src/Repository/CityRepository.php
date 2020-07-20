<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * City entity repository.
 *
 * @inheritdoc
 */
class CityRepository extends AbstractRepository
{
    /**
     * Constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }
    
    /**
     * Query to custom paginator.
     */
    public function queryToPaginate(array $params): \Doctrine\ORM\QueryBuilder
    {
        $dql = parent::queryToPaginate($params);

        $dql->leftJoin('e.state', 's');
        
        return $dql;
    }
}
