<?php
namespace App\DocumentRepository;

use App\Document\City;
use Doctrine\Bundle\MongoDBBundle\ManagerRegistry;

class CityRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }
}
