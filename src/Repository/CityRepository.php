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
}
