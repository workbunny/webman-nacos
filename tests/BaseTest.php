<?php
declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class BaseTest extends AbstractTest
{
    /**
     * @covers \Workbunny\WebmanNacos\Provider\AbstractProvider::verify
     * @return void
     */
    public function testVerifyOfAbstractProvider()
    {
        // 必要参数 成功
        $result = null;
        try {
            $this->provider()->verifyTester([
                'serviceName' => 'serviceName',
                'ip' => '192.168.1.1',
                'port' => 8000,
            ], [
                ['serviceName', 'is_string', true],
                ['ip', 'is_string', true],
                ['port', 'is_int', true],
            ]);
        }catch (\InvalidArgumentException $exception){
            $result = $exception->getMessage();
        }
        $this->assertEquals(null, $result);

        // 携带非必要参数 成功
        $result = null;
        try {
            $this->provider()->verifyTester([
                'serviceName' => 'serviceName',
                'ip' => '192.168.1.1',
            ], [
                ['serviceName', 'is_string', true],
                ['ip', 'is_string', true],
                ['port', 'is_int', false],
            ]);
        }catch (\InvalidArgumentException $exception){
            $result = $exception->getMessage();
        }
        $this->assertEquals(null, $result);

        // 不存在的方法验证 忽略
        $result = null;
        try {
            $this->provider()->verifyTester([
                'serviceName' => 'serviceName',
                'ip' => '192.168.1.1',
            ], [
                ['serviceName', 'is_string', true],
                ['ip', 'is_string', true],
                ['port', 'is_i', false],
            ]);
        }catch (\InvalidArgumentException $exception){
            $result = $exception->getMessage();
        }
        $this->assertEquals(null, $result);

        // 不存在的方法验证 失败
        $result = null;
        try {
            $this->provider()->verifyTester([
                'serviceName' => 'serviceName',
                'ip' => '192.168.1.1',
            ], [
                ['serviceName', 'is_s', true],
                ['ip', 'is_string', true],
                ['port', 'is_int', false],
            ]);
        }catch (\InvalidArgumentException $exception){
            $result = $exception->getMessage();
        }
        $this->assertEquals('Invalid Function: is_s', $result);

        // 未通过验证 失败
        $result = null;
        try {
            $this->provider()->verifyTester([
                'serviceName' => 'serviceName',
                'ip' => '192.168.1.1',
            ], [
                ['serviceName', 'is_int', true],
                ['ip', 'is_string', true],
                ['port', 'is_int', false],
            ]);
        }catch (\InvalidArgumentException $exception){
            $result = $exception->getMessage();
        }
        $this->assertEquals('Invalid Argument: serviceName', $result);
    }

    /**
     * @covers \Workbunny\WebmanNacos\Provider\AbstractProvider::filter
     * @return void
     */
    public function testFilterOfAbstractProvider()
    {
        $this->assertEquals([
            'serviceName' => 'serviceName'
        ], $this->provider()->filterTester([
            'serviceName' => 'serviceName',
            'groupName' => null,
        ]));

        $this->assertEquals([
            'serviceName' => 'serviceName',
            'groupName' => 0,
        ], $this->provider()->filterTester([
            'serviceName' => 'serviceName',
            'groupName' => 0,
        ]));

        $this->assertEquals([
            'serviceName' => 'serviceName',
            'groupName' => '',
        ], $this->provider()->filterTester([
            'serviceName' => 'serviceName',
            'groupName' => '',
        ]));

        $this->assertEquals([
            'serviceName' => 'serviceName',
            'groupName' => 'null',
        ], $this->provider()->filterTester([
            'serviceName' => 'serviceName',
            'groupName' => 'null',
        ]));

        $this->assertEquals([

        ], $this->provider()->filterTester([
            'serviceName' => null,
        ]));
    }
}