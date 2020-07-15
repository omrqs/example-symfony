<?php
namespace App\Document;

use App\AccessPropertyTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;

/**
 * City entity.
 * 
 * @MongoDB\Document(
 *  collection="city",
 *  repositoryClass="App\DocumentRepository\CityRepository"
 * )
 * @MongoDBUnique(fields={"state","name"})
 * @MongoDB\HasLifecycleCallbacks
 */
class City
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
     * @var \App\Document\State
     *
     * @MongoDB\EmbedOne(targetDocument=State::class)
     * @Assert\NotBlank(message="model.not_blank.state")
     */
    private $state;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank(message="model.not_blank.name")
     */
    private $name;

    public function __toString(): string
    {
        return $this->name;
    }
}
