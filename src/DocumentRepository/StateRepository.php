<?php
namespace App\DocumentRepository;
 
use Doctrine\ODM\MongoDB\DocumentRepository;
 
class StateRepository extends DocumentRepository
{
    /**
     * @var string
     */
    static $collection = 'State';

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
