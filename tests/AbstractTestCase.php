<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Workbunny\WebmanNacos\Client;

abstract class AbstractTestCase extends TestCase
{
    protected array $_config = [
        'host'     => '127.0.0.1',
        'port'     => 8848,
        'username' => null,
        'password' => null,
    ];

    protected ?Client $_client = null;

    protected ?Provider $_provider = null;

    protected function client(): Client
    {
        return $this->_client;
    }

    protected function provider(): Provider
    {
        return $this->_provider;
    }

    protected function setUp(): void
    {
        Client::$debug = true;
        $this->_client = new Client($this->_config);
        $this->_provider = new Provider($this->_client);
        parent::setUp();
    }
}
