<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Id;

/**
 * State document.
 *
 * @MongoDB\Document(
 *  collection="state",
 *  repositoryClass="App\DocumentRepository\StateRepository"
 * )
 * @MongoDB\HasLifecycleCallbacks
 */
class State
{
    use AccessPropertyTrait;
    use AuthorshipTrait;

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

    /**
     * @var string
     *
     * @MongoDB\String
     */
    private $abrev;

    public function __toString(): string
    {
        return $this->name;
    }
}
