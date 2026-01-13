<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class ServiceTest extends AbstractTest
{
    /**
     * @covers \Workbunny\WebmanNacos\Provider\ServiceProvider::create
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testCreate()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->service->create('test', [
            'groupName'        => 'testGroup',
            'namespaceId'      => 'testNamespace',
            'protectThreshold' => 0.99,
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/service',
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
            'groupName=testGroup&namespaceId=testNamespace&protectThreshold=0.99&serviceName=test',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'POST',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ServiceProvider::createAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testCreateAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->service->createAsync('test', [
            'groupName'        => 'testGroup',
            'namespaceId'      => 'testNamespace',
            'protectThreshold' => 0.99,
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/service',
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
            'groupName=testGroup&namespaceId=testNamespace&protectThreshold=0.99&serviceName=test',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'POST',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ServiceProvider::delete
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDelete()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->service->delete('test', 'testGroup', 'testNamespace');

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/service',
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
            'serviceName=test&groupName=testGroup&namespaceId=testNamespace',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'DELETE',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ServiceProvider::deleteAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDeleteAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->service->deleteAsync('test', 'testGroup', 'testNamespace');

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/service',
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
            'serviceName=test&groupName=testGroup&namespaceId=testNamespace',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'DELETE',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ServiceProvider::update
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdate()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->service->update('test', [
            'groupName'        => 'testGroup',
            'namespaceId'      => 'testNamespace',
            'protectThreshold' => 0.99,
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/service',
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
            'groupName=testGroup&namespaceId=testNamespace&protectThreshold=0.99&serviceName=test',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'PUT',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ServiceProvider::updateAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdateAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->service->updateAsync('test', [
            'groupName'        => 'testGroup',
            'namespaceId'      => 'testNamespace',
            'protectThreshold' => 0.99,
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/service',
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
            'groupName=testGroup&namespaceId=testNamespace&protectThreshold=0.99&serviceName=test',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'PUT',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ServiceProvider::get
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGet()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->service->get('test', 'testGroup', 'testNamespace');

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/service',
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
            'serviceName=test&groupName=testGroup&namespaceId=testNamespace',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ServiceProvider::getAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->service->getAsync('test', 'testGroup', 'testNamespace');

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/service',
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
            'serviceName=test&groupName=testGroup&namespaceId=testNamespace',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ServiceProvider::list
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testList()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->service->list(1, 10, 'testGroup', 'testNamespace');

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/service/list',
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
            'pageNo=1&pageSize=10&groupName=testGroup&namespaceId=testNamespace',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\ServiceProvider::listAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testListAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->service->listAsync(1, 10, 'testGroup', 'testNamespace');

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/service/list',
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
            'pageNo=1&pageSize=10&groupName=testGroup&namespaceId=testNamespace',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }
}
