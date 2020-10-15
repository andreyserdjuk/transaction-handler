<?php

namespace App\Tests\Functional;

use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseTestCase extends WebTestCase
{
    use FixturesTrait;

    protected static KernelBrowser $client;

    protected function setUp(): void
    {
        self::$client = self::createClient();
        $this->loadFixtureFiles([
            __DIR__ . '/fixtures/account.yaml',
            __DIR__ . '/fixtures/transaction.yaml',
        ],
            false,
            null,
            'doctrine',
            ORMPurger::PURGE_MODE_TRUNCATE
        );
    }
}
