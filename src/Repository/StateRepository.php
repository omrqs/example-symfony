<?php

namespace App\Repository;

use App\Entity\State;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * State entity repository.
 *
 * @inheritdoc
 */
class StateRepository extends AbstractRepository
{
    /**
     * Constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, State::class);
    }
}
