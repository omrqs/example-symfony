<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Date;


/**
 * Authorship trait.
 *
 * @ORM\HasLifecycleCallbacks()
 */
trait AuthorshipTrait
{
    /**
     * @var \DateTime
     *
     * @MongoDB\Date
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @MongoDB\Date
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
