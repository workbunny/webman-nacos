<?php

declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class InstanceTest extends AbstractTestCase
{
    /**
     * @covers \Workbunny\WebmanNacos\Provider\InstanceProvider::register
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRegister()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->instance->register('127.0.0.1', 8000, 'test', [
            'groupName' => 'testGroup',
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/instance',
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
            'groupName=testGroup&serviceName=test&ip=127.0.0.1&port=8000',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'POST',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\InstanceProvider::registerAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testRegisterAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->instance->registerAsync('127.0.0.1', 8000, 'test', [
            'groupName' => 'testGroup',
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/instance',
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
            'groupName=testGroup&serviceName=test&ip=127.0.0.1&port=8000',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'POST',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\InstanceProvider::delete
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDelete()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->instance->delete('test', 'testGroup', '127.0.0.1', 8000, [
            'namespaceId' => 'testNamespace',
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/instance',
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
            'namespaceId=testNamespace&serviceName=test&groupName=testGroup&ip=127.0.0.1&port=8000',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'DELETE',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\InstanceProvider::deleteAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDeleteAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->instance->deleteAsync('test', 'testGroup', '127.0.0.1', 8000, [
            'namespaceId' => 'testNamespace',
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/instance',
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
            'namespaceId=testNamespace&serviceName=test&groupName=testGroup&ip=127.0.0.1&port=8000',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'DELETE',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\InstanceProvider::update
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdate()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->instance->update('127.0.0.1', 8000, 'test', [
            'groupName' => 'testGroup',
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/instance',
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
            'groupName=testGroup&serviceName=test&ip=127.0.0.1&port=8000',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'PUT',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\InstanceProvider::updateAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdateAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->instance->updateAsync('127.0.0.1', 8000, 'test', [
            'groupName' => 'testGroup',
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/instance',
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
            'groupName=testGroup&serviceName=test&ip=127.0.0.1&port=8000',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'PUT',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\InstanceProvider::detail
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDetail()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->instance->detail('127.0.0.1', 8000, 'test', [
            'groupName' => 'testGroup',
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/instance',
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
            'groupName=testGroup&ip=127.0.0.1&port=8000&serviceName=test',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\InstanceProvider::detailAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testDetailAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->instance->detailAsync('127.0.0.1', 8000, 'test', [
            'groupName' => 'testGroup',
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/instance',
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
            'groupName=testGroup&ip=127.0.0.1&port=8000&serviceName=test',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\InstanceProvider::updateHealth
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdateHealth()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->instance->updateHealth('127.0.0.1', 8000, 'test', true, [
            'groupName' => 'testGroup',
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/health/instance',
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
            'groupName=testGroup&ip=127.0.0.1&port=8000&serviceName=test&healthy=1',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'PUT',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\InstanceProvider::updateHealthAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdateHealthAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->instance->updateHealthAsync('127.0.0.1', 8000, 'test', true, [
            'groupName' => 'testGroup',
        ]);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/health/instance',
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
            'groupName=testGroup&ip=127.0.0.1&port=8000&serviceName=test&healthy=1',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'PUT',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\InstanceProvider::beat
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testBeat()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->instance->beat('test', [
            'ip'          => '127.0.0.1',
            'port'        => 8000,
            'serviceName' => 'test',
        ], 'testGroup', 'testNamespace');

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/instance/beat',
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
            'serviceName=test&ip=127.0.0.1&port=8000&groupName=testGroup&namespaceId=testNamespace&beat={"ip":"127.0.0.1","port":8000,"serviceName":"test"}',
            urldecode($request->getUri()->getQuery())
        );
        $this->assertEquals(
            'PUT',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\InstanceProvider::beatAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testBeatAsync()
    {
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->instance->beatAsync('test', [
            'ip'          => '127.0.0.1',
            'port'        => 8000,
            'serviceName' => 'test',
        ], 'testGroup', 'testNamespace');

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/instance/beat',
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
            'serviceName=test&ip=127.0.0.1&port=8000&groupName=testGroup&namespaceId=testNamespace&beat={"ip":"127.0.0.1","port":8000,"serviceName":"test"}',
            urldecode($request->getUri()->getQuery())
        );
        $this->assertEquals(
            'PUT',
            $request->getMethod()
        );
    }
}
