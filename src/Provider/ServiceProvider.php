<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Provider;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;

class ServiceProvider extends AbstractProvider
{
    const CREATE_URL = 'nacos/v1/ns/service';
    const CREATE_METHOD = 'POST';

    const DELETE_URL = 'nacos/v1/ns/service';
    const DELETE_METHOD = 'DELETE';

    const UPDATE_URL = 'nacos/v1/ns/service';
    const UPDATE_METHOD = 'PUT';

    const GET_URL = 'nacos/v1/ns/service';
    const GET_METHOD = 'GET';

    const LIST_URL = 'nacos/v1/ns/service/list';
    const LIST_METHOD = 'GET';

    /**
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'protectThreshold' => 0.99,
     *     'metadata' => '',
     *     'selector' => '', // json字符串
     * ]
     * @return bool|string
     * @throws GuzzleException
     */
    public function create(string $serviceName, array $optional = [])
    {
        return $this->request(self::CREATE_METHOD, self::CREATE_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
            ])),
        ]);
    }

    /**
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'protectThreshold' => 0.99,
     *     'metadata' => '',
     *     'selector' => '', // json字符串
     * ]
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function createAsync(string $serviceName, array $optional = [])
    {
        return $this->requestAsync(self::CREATE_METHOD, self::CREATE_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
            ])),
        ]);
    }

    /**
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'protectThreshold' => 0.99,
     *     'metadata' => '',
     *     'selector' => '', // json字符串
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function createAsyncUseEventLoop(string $serviceName, array $optional = [], ?callable $success = null, ?callable $error = null): bool
    {
        return $this->requestAsyncUseEventLoop(self::CREATE_METHOD, self::CREATE_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
            ])),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @param string $serviceName
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @return bool|string
     * @throws GuzzleException
     */
    public function delete(string $serviceName, ?string $groupName = null, ?string $namespaceId = null)
    {
        return $this->request(self::DELETE_METHOD, self::DELETE_URL, [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
            ]),
        ]);
    }

    /**
     * @param string $serviceName
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function deleteAsync(string $serviceName, ?string $groupName = null, ?string $namespaceId = null)
    {
        return $this->requestAsync(self::DELETE_METHOD, self::DELETE_URL, [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
            ]),
        ]);
    }

    /**
     * @param array $options = [
     *      'serviceName' => '',
     *      'groupName' => '',
     *      'namespaceId' => ''
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function deleteAsyncUseEventLoop(array $options, ?callable $success = null, ?callable $error = null): bool
    {
        $this->verify($options, [
            ['serviceName', 'is_string', true],
            ['groupName', 'is_string', false],
            ['namespaceId', 'is_string', false]
        ]);
        return $this->requestAsync(self::DELETE_METHOD, self::DELETE_URL, [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $options['serviceName'] ?? null,
                'groupName' => $options['groupName'] ?? null,
                'namespaceId' => $options['namespaceId'] ?? null,
            ]),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'protectThreshold' => 0.99,
     *     'metadata' => '',
     *     'selector' => '', // json字符串
     * ]
     * @return bool|string
     * @throws GuzzleException
     */
    public function update(string $serviceName, array $optional = [])
    {
        return $this->request(self::UPDATE_METHOD, self::UPDATE_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
            ])),
        ]);
    }

    /**
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'protectThreshold' => 0.99,
     *     'metadata' => '',
     *     'selector' => '', // json字符串
     * ]
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function updateAsync(string $serviceName, array $optional = [])
    {
        return $this->requestAsync(self::UPDATE_METHOD, self::UPDATE_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
            ])),
        ]);
    }

    /**
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'protectThreshold' => 0.99,
     *     'metadata' => '',
     *     'selector' => '', // json字符串
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function updateAsyncUseEventLoop(string $serviceName, array $optional = [], ?callable $success = null, ?callable $error = null): bool
    {
        return $this->requestAsync(self::UPDATE_METHOD, self::UPDATE_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
            ])),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @param string $serviceName
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @return bool|string
     * @throws GuzzleException
     */
    public function get(string $serviceName, ?string $groupName = null, ?string $namespaceId = null)
    {
        return $this->request(self::GET_METHOD, self::GET_URL, [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
            ]),
        ]);
    }

    /**
     * @param string $serviceName
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function getAsync(string $serviceName, ?string $groupName = null, ?string $namespaceId = null)
    {
        return $this->requestAsync(self::GET_METHOD, self::GET_URL, [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
            ]),
        ]);
    }

    /**
     * @param array $options = [
     *      'serviceName' => '',
     *      'groupName' => '',
     *      'namespaceId' => ''
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function getAsyncUseEventLoop(array $options, ?callable $success = null, ?callable $error = null): bool
    {
        $this->verify($options, [
            ['serviceName', 'is_string', true],
            ['groupName', 'is_string', false],
            ['namespaceId', 'is_string', false]
        ]);
        return $this->requestAsync(self::GET_METHOD, self::GET_URL, [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $options['serviceName'] ?? null,
                'groupName' => $options['groupName'] ?? null,
                'namespaceId' => $options['namespaceId'] ?? null,
            ]),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @param int $pageNo
     * @param int $pageSize
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @return bool|string
     * @throws GuzzleException
     */
    public function list(int $pageNo, int $pageSize, ?string $groupName = null, ?string $namespaceId = null)
    {
        return $this->request(self::LIST_METHOD, self::LIST_URL, [
            RequestOptions::QUERY => $this->filter([
                'pageNo' => $pageNo,
                'pageSize' => $pageSize,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
            ]),
        ]);
    }

    /**
     * @param int $pageNo
     * @param int $pageSize
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function listAsync(int $pageNo, int $pageSize, ?string $groupName = null, ?string $namespaceId = null)
    {
        return $this->requestAsync(self::LIST_METHOD, self::LIST_URL, [
            RequestOptions::QUERY => $this->filter([
                'pageNo' => $pageNo,
                'pageSize' => $pageSize,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
            ]),
        ]);
    }

    /**
     * @param array $options = [
     *      'pageNo' => 1,
     *      'pageSize' => 10,
     *      'groupName' => '',
     *      'namespaceId' => ''
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function listAsyncUseEventLoop(array $options, ?callable $success = null, ?callable $error = null): bool
    {
        $this->verify($options, [
            ['pageNo', 'is_int', true],
            ['pageSize', 'is_int', true],
            ['groupName', 'is_string', false],
            ['namespaceId', 'is_string', false]
        ]);
        return $this->requestAsync(self::LIST_METHOD, self::LIST_URL, [
            RequestOptions::QUERY => $this->filter([
                'pageNo' => $options['pageNo'] ?? null,
                'pageSize' => $options['pageSize'] ?? null,
                'groupName' => $options['groupName'] ?? null,
                'namespaceId' => $options['namespaceId'] ?? null,
            ]),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }
}
