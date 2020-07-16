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
 * City entity.
 *
 * @ORM\Table(name="city",
 *   options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"},
 *   indexes={
 *     @ORM\Index(name="city_name", columns={"name"}),
 *   }
 * )
 *
 * @UniqueEntity(fields={"state","name"}, message="unique.name")
 *
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class City
{
    use AccessPropertyTrait;
    use AuthorshipTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\SequenceGenerator(sequenceName="city", initialValue=1, allocationSize=100)
     */
    private $id;

    /**
     * @var \App\Entity\State
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\State", cascade={"persist", "refresh"}, inversedBy="cities", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=false, onDelete="cascade")
     */
    private $state;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "model.not_blank.name")
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * String.
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Set state as fk.
     */
    public function setState(State $state): self
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Object to array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'state' => (string) $this->state,
            'name' => $this->name,
        ];
    }
}
