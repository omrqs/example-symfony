<?php

namespace App\DataFixtures;

use App\Document\City;
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

    public $cities = [
        'Rio de Janeiro' => 'RJ',
        'Itaipava' => 'RJ',
        'Paraty' => 'RJ',
    ];

    /**
     * Load fixtures.
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->cities as $name => $state) {
            $city = new City();
            $city->name($name);
            $city->state($this->addReference(sprintf('state-%s', $state)));
            $manager->persist($city);

            $manager->flush();
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
        return 1;
    }
}
