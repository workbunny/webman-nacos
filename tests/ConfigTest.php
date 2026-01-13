<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ConfigTest extends AbstractTestCase
{
    /**
     * @covers \Workbunny\WebmanNacos\Provider\ConfigProvider::get
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testConfigGet()
    {
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
    public function testConfigGetAsync()
    {
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

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ConfigProvider::publish
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testConfigPublish()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->config->publish('testId', 'testGroup', 'testContent', 'testType', 'testTenant');

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
            'dataId=testId&group=testGroup&tenant=testTenant&type=testType&content=testContent',
            $request->getBody()->getContents()
        );
        $this->assertEquals(
            'POST',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ConfigProvider::publishAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testConfigPublishAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->config->publishAsync('testId', 'testGroup', 'testContent', 'testType', 'testTenant');

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
            'dataId=testId&group=testGroup&tenant=testTenant&type=testType&content=testContent',
            $request->getBody()->getContents()
        );
        $this->assertEquals(
            'POST',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ConfigProvider::delete
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testConfigDelete()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->config->delete('testId', 'testGroup', 'testTenant');

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
            'DELETE',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ConfigProvider::deleteAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testConfigDeleteAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->config->deleteAsync('testId', 'testGroup', 'testTenant');

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
            'DELETE',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ConfigProvider::listener
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testConfigListener()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->config->listener('testId', 'testGroup', 'testMD5', 'testTenant', 60);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/cs/configs/listener',
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
            'Listening-Configs=testId%02testGroup%02testMD5%02testTenant%01',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            [60],
            $request->getHeader('Long-Pulling-Timeout')
        );
        $this->assertEquals(
            'POST',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ConfigProvider::listenerAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testConfigListenerAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->config->listenerAsync('testId', 'testGroup', 'testMD5', 'testTenant', 60);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/cs/configs/listener',
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
            'Listening-Configs=testId%02testGroup%02testMD5%02testTenant%01',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            [60],
            $request->getHeader('Long-Pulling-Timeout')
        );
        $this->assertEquals(
            'POST',
            $request->getMethod()
        );
    }
}
