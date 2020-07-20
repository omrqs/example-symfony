<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * Update fields by persistence type.
     *
     * @ORM\PrePersist()
     */
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Update fields by persistence type.
     *
     * @ORM\PreUpdate()
     */
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
