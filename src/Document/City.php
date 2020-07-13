<?php
namespace App\Document;

/**
 * State entity.
 * @MongoDB\Document(
 *  collection="city", 
 *  repositoryClass="App\DocumentRepository\CityRepository"
 * )
 * @MongoDB\HasLifecycleCallbacks
 */
class City
{
    use AccessPropertyTrait;
    use AuthorshipTrait;

    /**
     * @var \App\Document\State
     */
    private $state;

    /**
     * @var int
     *
     * @MongoDB\Id
     */
    private $id;

    /**
     * @var string
     *
     * @MongoDB\String
     */
    private $name;

    public function __toString(): string
    {
        return $this->name;
    }
}
