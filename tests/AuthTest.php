<?php
declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class AuthTest extends AbstractTest
{
    /**
     * @covers \Workbunny\WebmanNacos\Provider\AuthProvider::login
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testAuthLogin()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->auth->login('test', '123123');

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/auth/users/login',
            $request->getUri()->getPath()
        );
        $this->assertEquals(
            '127.0.0.1',
            $request->getUri()->getHost()
        );
        $this->assertEquals(
            8848,
            $request->getUri()->getPort()
        );
        $this->assertEquals(
            'username=test',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'password=123123',
            $request->getBody()->getContents()
        );
        $this->assertEquals(
            'POST',
            $request->getMethod()
        );
    }
}