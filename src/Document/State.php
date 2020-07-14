<?php
namespace App\Document;

use Symfony\Component\Validator\Constraints as Assert;  
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

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
     * @Assert\NotBlank(message="model.not_blank.name")
     */
    private $name;

    /**
     * @var string
     *
     * @MongoDB\String
     * @Assert\NotBlank(message="model.not_blank.abrev")
     */
    private $abrev;

    public function __toString(): string
    {
        return $this->name;
    }
}
