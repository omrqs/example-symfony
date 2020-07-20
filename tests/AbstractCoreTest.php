<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\Process;
use Doctrine\DBAL\DBALException;

abstract class AbstractCoreTest extends WebTestCase
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
     * @var Doctrine\ORM\EntityManager
     */
    public $em;

    /**
     * @var Symfony\Bundle\FrameworkBundle\Console\Application
     */
    public $application;

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
     * @var array
     */
    public static $failureLoggedHeaders = [];

    /**
     * @var array
     */
    public static $deniedAttrLoggedHeaders = [];

    /**
     * @var array
     */
    public static $unkownAttrLoggedHeaders = [];

    /**
     * @var array
     */
    public static $paginatorKeys = [
        'current', 'last', 'current', 'numItemsPerPage', 'first', 'pageCount', 'totalCount', 'pageRange', 'startPage', 'endPage', 'pagesInRange', 'firstPageInRange', 'lastPageInRange', 'currentItemCount', 'firstItemNumber', 'lastItemNumber'
    ];

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
            'HTTP_X_API_KEY' => getenv('DEFAULT_ACCESS_TOKEN'),
            ]);
            
        self::$failureLoggedHeaders = array_merge(self::$defaultHeaders, [
            'HTTP_X_API_KEY' => '',
        ]);

        self::$deniedAttrLoggedHeaders = array_merge(self::$defaultHeaders, [
            'HTTP_X_API_KEY' => getenv('DEFAULT_ACCESS_TOKEN2'),
        ]);

        self::$unkownAttrLoggedHeaders = array_merge(self::$defaultHeaders, [
            'HTTP_X_API_KEY' => getenv('DEFAULT_ACCESS_TOKEN3'),
        ]);

        $this->buildDb();
    }

    /**
     * Build test database.
     **/
    protected function buildDb()
    {
        try {
            // Drop database and recreate schema, then load fixtures and run migrations.
            $this->application->run(new ArrayInput([
                'doctrine:database:drop',
                '--force' => null,
                '--env' => 'test',
                '--no-interaction' => null,
                '--quiet' => null,
                '--no-debug' => null,
            ]));

            $this->application->run(new ArrayInput([
                'doctrine:database:create',
                '--env' => 'test',
                '--no-interaction' => null,
                '--quiet' => null,
                '--no-debug' => null,
            ]));

            $this->application->run(new ArrayInput([
                'doctrine:schema:create',
                '--env' => 'test',
                '--no-interaction' => null,
                '--quiet' => null,
                '--no-debug' => null,
            ]));

            $this->application->run(new ArrayInput([
                'doctrine:fixtures:load',
                '--env' => 'test',
                '--no-interaction' => null,
                '--quiet' => null,
                '--no-debug' => null,
            ]));

            $this->application->run(new ArrayInput([
                'doctrine:migrations:migrate',
                '--env' => 'test',
                '--allow-no-migration' => null,
                '--no-interaction' => null,
                '--quiet' => null,
                '--no-debug' => null,
            ]));

            return true;
        } catch (DBALException $e) {
            throw $e;
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
