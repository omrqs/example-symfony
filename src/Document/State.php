<?php
namespace App\Document;

use App\AccessPropertyTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;

/**
 * State document.
 *
 * @MongoDB\Document(
 *  collection="state",
 *  repositoryClass="App\DocumentRepository\StateRepository"
 * )
 * @MongoDBUnique(fields="abrev")
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
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank(message="model.not_blank.name")
     */
    private $name;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank(message="model.not_blank.abrev")
     */
    private $abrev;

    public function __toString(): string
    {
        return strtoupper($this->abrev);
    }
}
