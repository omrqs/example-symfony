<?php

namespace App\Repository;

use App\Entity\State;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * State entity repository.
 *
 * @method State|null find($id, $lockMode = null, $lockVersion = null)
 * @method State|null findOneBy(array $criteria, array $orderBy = null)
 * @method State[]    findAll()
 * @method State[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StateRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, State::class);
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
                $orWhere[] = sprintf("%s LIKE '%s'", str_replace('_', '.', $field), '%'.$value.'%');
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
