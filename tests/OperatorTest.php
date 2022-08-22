<?php
declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class OperatorTest extends AbstractTest
{
    /**
     * @covers \Workbunny\WebmanNacos\Provider\OperatorProvider::getSwitches
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetSwitches(){
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->operator->getSwitches();

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/operator/switches',
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
            '',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\OperatorProvider::getSwitchesAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetSwitchesAsync(){
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->operator->getSwitchesAsync();

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/operator/switches',
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
            '',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\OperatorProvider::updateSwitches
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdateSwitches(){
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->operator->updateSwitches('entry','value', true);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/operator/switches',
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
            'entry=entry&value=value&debug=1',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'PUT',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\OperatorProvider::updateSwitchesAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdateSwitchesAsync(){
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->operator->updateSwitchesAsync('entry','value', true);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/operator/switches',
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
            'entry=entry&value=value&debug=1',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'PUT',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\OperatorProvider::getMetrics
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetMetrics(){
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->operator->getMetrics();

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/operator/metrics',
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
            '',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\OperatorProvider::getMetricsAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetMetricsAsync(){
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->operator->getMetricsAsync();

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/operator/metrics',
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
            '',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\OperatorProvider::getServers
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetServers(){
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->operator->getServers(true);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/operator/servers',
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
            'healthy=1',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\OperatorProvider::getServersAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetServersAsync(){
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->operator->getServersAsync(true);

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/operator/servers',
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
            'healthy=1',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\OperatorProvider::getLeader
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetLeader(){
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->operator->getLeader();

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/raft/leader',
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
            '',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\OperatorProvider::getLeaderAsync
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetLeaderAsync(){
        $this->client()::$mockHandler = new MockHandler([new Response()]);
        $this->client()->operator->getLeaderAsync();

        $request = $this->client()::$mockHandler->getLastRequest();

        $this->assertEquals(
            '/nacos/v1/ns/raft/leader',
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
            '',
            $request->getUri()->getQuery()
        );
        $this->assertEquals(
            'GET',
            $request->getMethod()
        );
    }

}