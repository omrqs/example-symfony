<?php
namespace App\DocumentRepository;
 
use Doctrine\ODM\MongoDB\DocumentRepository;
 
class CityRepository extends DocumentRepository
{
    /**
     * @var string
     */
    static $collection = 'City';

    public function findAll(): mixed
    {
        return
            $this->createQueryBuilder(self::$collection)
                ->sort('createdAt', 'desc')
                ->getQuery()
                ->execute();
    }
 
    public function findOneByProperty(string $field, string $data): ?mixed
    {
        return
            $this->createQueryBuilder(self::$collection)
                ->field($field)->equals($data)
                ->getQuery()
                ->getSingleResult();
    }
}
