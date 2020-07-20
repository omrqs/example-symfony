<?php

namespace App\Repository;

use Doctrine\ORM\NoResultException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * City entity repository.
 *
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(ManagerRegistry $registry, string $entity)
    {
        parent::__construct($registry, $entity);
    }

    /**
     * Query to custom paginator.
     */
    public function queryToPaginate(array $params): \Doctrine\ORM\QueryBuilder
    {
        unset($params['page'], $params['limit']);

        $dql = $this->createQueryBuilder('e');

        // sorting
        if (isset($params['sort'])) {
            $sort = sprintf("e.%s", $params['sort']);
            unset($params['sort']);
            
            $order = isset($params['order']) ? $params['order'] : 'asc';
            
            $dql->orderBy($sort, $order);
        }

        unset($params['order']);

        // clausules
        if (0 < count($params)) {
            $orWhere = [];
            foreach ($params as $field => $value) {
                $orWhere[] = sprintf("e.%s LIKE '%s'", $field, '%'.$value.'%');
            }

            $clausule = (string) implode(' OR ', $orWhere);
            $dql->andWhere($clausule);
        }

        // SELECT e FROM App\Entity\City e WHERE e.name LIKE '%state%' ORDER BY e.name desc

        return $dql;
    }
}
