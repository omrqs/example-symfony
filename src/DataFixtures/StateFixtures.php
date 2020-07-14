<?php

namespace App\DataFixtures;

use App\Document\State;
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
        foreach ($this->cities as $name => $abrev) {
            $state = new State();
            $state->setName($name);
            $state->setAbrev($abrev);
            $manager->persist($state);

            $manager->flush();
            
            $this->addReference(sprintf('state-%s', $abrev), $state);
        }

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
