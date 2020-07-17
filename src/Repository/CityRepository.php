<?php

namespace App\Repository;

use App\Entity\City;
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
class CityRepository extends ServiceEntityRepository
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
        unset($params['page'], $params['limit']);

        $dql = $this->createQueryBuilder('e');

        // sorting
        if (isset($params['sort']) && isset($params['direction'])) {
            $dql->orderBy($params['sort'], $params['direction']);

            unset($params['sort'], $params['direction']);
        }

        // clausules
        if (count($params) > 0) {
            $orWhere = [];
            foreach ($params as $field => $value) {
                $orWhere[] = sprintf("e.%s LIKE '%s'", $field, '%'.$value.'%');
            }

            $dql->andWhere((string) implode(' OR ', $orWhere));
        }

        return $dql;
    }

    /**
     * Count rows by field.
     */
    public function countBy(string $key, string $value): \Doctrine\ORM\QueryBuilder
    {
        try {
            return $this->createQueryBuilder('e')
                ->select('e.id')
                ->where(sprintf('e.%s = :value', $key))
                    ->setParameter('value', $value)
                ->getQuery()
                ->getOneOrNullResult()
            ;
        } catch (NoResultException $e) {
            throw $e;
        }
    }
}
