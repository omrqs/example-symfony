<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\Process;

abstract class CoreTest extends WebTestCase
{
    /**
     * @var Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

    /**
     * @var symfony\Component\Routing\Router
     */
    protected $router;

    /**
     * @var Symfony\Component\Process\Process
     */
    public $process;

    /**
     * @var array
     */
    public static $defaultHeaders = [
        'CONTENT_TYPE' => 'application/json',
        'ACCEPT' => 'application/json',
    ];

    /**
     * @var array
     */
    public static $loggedHeaders = [];

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->router = $this->client->getContainer()->get('router');
        $this->em = $this->client->getContainer()->get('doctrine.orm.entity_manager');

        $appKernel = $this->createKernel();
        $appKernel->boot();
        $this->application = new Application($appKernel);
        $this->application->setAutoExit(false);

        self::$loggedHeaders = array_merge(self::$defaultHeaders, [
            'X-API-KEY' => getenv('DEFAULT_ACCESS_TOKEN'),
        ]);

        // $this->buildDb();
    }

    /**
     * Build test database.
     **/
    protected function buildDb()
    {
        try {
            // Drop database and recreate schema, then load fixtures.
            $this->application->run(new ArrayInput([
                'doctrine:database:drop',
                '--force' => true,
                '--env' => 'test',
                '--no-interaction' => true,
                '--quiet' => true,
                '--no-debug' => true,
            ]));

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        parent::tearDown();

        if (!is_null($this->process)) {
            $this->process->stop();
        }
    }
}
