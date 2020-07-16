<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * City Fixtures.
 */
class CityFixtures extends Fixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    public $cities = [
        'Rio de Janeiro' => 'rj',
        'Itaipava' => 'rj',
        'Paraty' => 'rj',
    ];

    /**
     * Load fixtures.
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->cities as $name => $state) {
            $city = new City();
            $city->name = $name;
            $city->setState($this->getReference(sprintf('state-%s', strtolower($state))));
            $manager->persist($city);
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
        return 1;
    }
}
