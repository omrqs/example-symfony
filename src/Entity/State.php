<?php
namespace App\Entity;

use App\AccessPropertyTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * State entity.
 *
 * @ORM\Table(name="state",
 *   options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"},
 *   indexes={
 *     @ORM\Index(name="state_name", columns={"name"}),
 *     @ORM\Index(name="state_abrev", columns={"abrev"}),
 *   }
 * )
 *
 * @UniqueEntity(fields={"name"}, message="unique.name")
 * @UniqueEntity(fields={"abrev"}, message="unique.abrev")
 *
 * @ORM\Entity(repositoryClass="App\Repository\StateRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class State
{
    use AccessPropertyTrait;
    use AuthorshipTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="state", initialValue=1, allocationSize=100)
     */
    private $id;

    /**
     * @var \App\Entity\City
     *
     * @ORM\OneToMany(targetEntity="\App\Entity\City", cascade={"persist", "refresh"}, mappedBy="state", fetch="EXTRA_LAZY")
     * @ORM\OrderBy({"id"="desc"})
     */
    private $cities;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "model.not_blank.name")
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "model.not_blank.abrev")
     * @ORM\Column(name="abrev", type="string", length=2)
     */
    private $abrev;

    /**
     * String.
     */
    public function __toString(): string
    {
        return strtoupper($this->abrev);
    }

    /**
     * Object to array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'abrev' => $this->abrev,
            'name' => $this->name,
        ];
    }
}
