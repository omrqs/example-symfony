<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Authorship trait.
 *
 * @ORM\HasLifecycleCallbacks()
 */
trait AuthorshipDocumentTrait
{
    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date")
     */
    protected $updatedAt;

    /**
     * Update fields by persistence type.
     *
     * @MongoDB\PrePersist
     */
    public function onPrePersist(): self
    {
        $this->setCreatedAt(new \DateTime());

        return $this;
    }

    /**
     * Update fields by persistence type.
     *
     * @MongoDB\PreUpdate
     */
    public function onPreUpdate(): self
    {
        $this->setUpdatedAt(new \DateTime());

        return $this;
    }
}
