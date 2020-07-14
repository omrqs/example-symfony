<?php
namespace App\DocumentRepository;
 
use Doctrine\ODM\MongoDB\DocumentRepository;
 
abstract class AbstractRepository extends DocumentRepository
{
    protected function findAll(): mixed
    {
        return $this->createQueryBuilder(self::$collection)
            ->sort('createdAt', 'desc')
            ->getQuery()
            ->execute();
    }
 
    protected function findOneByProperty(string $field, string $value): ?mixed
    {
        return $this->createQueryBuilder(self::$collection)
            ->field($field)->equals($value)
            ->getQuery()
            ->getSingleResult();
    }
 
    protected function find(string $id): ?mixed
    {
        return $this->createQueryBuilder(self::$collection)
            ->field('id')->equals($id)
            ->getQuery()
            ->getSingleResult();
    }
}
