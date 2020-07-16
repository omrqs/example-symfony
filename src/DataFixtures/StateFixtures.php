<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * State Fixtures.
 */
class StateFixtures extends Fixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    private $states = [
        'Rio de Janeiro' => 'rj',
        'São Paulo' => 'sp',
        'Paraná' => 'pr',
    ];

    /**
     * Load fixtures.
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->states as $name => $abrev) {
            $state = new State();
            $state->name = $name;
            $state->abrev = $abrev;
            $manager->persist($state);

            $this->addReference(sprintf('state-%s', $abrev), $state);
        }
        
        $manager->flush();
        $manager->clear();
    }

    /**
     * Get order number.
     *
     * @return int
     */
    public function getOrder()
    {
        return 0;
    }
}
