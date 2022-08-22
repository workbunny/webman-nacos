<?php
declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ConfigTest extends AbstractTest
{
    /**
     * @covers \Workbunny\WebmanNacos\Provider\ConfigProvider::get
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testConfigGet(){
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->config->get('testId', 'testGroup', 'testTenant');

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/cs/configs',
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
            'dataId=testId&group=testGroup&tenant=testTenant',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ConfigProvider::getAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testConfigGetAsync(){
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->config->getAsync('testId', 'testGroup', 'testTenant');

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/cs/configs',
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
            'dataId=testId&group=testGroup&tenant=testTenant',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

}