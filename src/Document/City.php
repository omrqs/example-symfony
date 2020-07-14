<?php
namespace App\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

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
     * @var int
     *
     * @MongoDB\Id
     */
    private $id;

    /**
     * @var \App\Document\State
     * 
     * @MongoDB\String
     * @Assert\NotBlank(message = "model.not_blank.state")
     */
    private $state;

    /**
     * @var string
     *
     * @MongoDB\String
     * @Assert\NotBlank(message = "model.not_blank.name")
     */
    private $name;

    public function __toString(): string
    {
        return $this->name;
    }
}
