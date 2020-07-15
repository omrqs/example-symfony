<?php
namespace App\DocumentRepository;

use App\Document\State;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class StateRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, State::class);
    }
}
