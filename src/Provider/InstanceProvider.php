<?php
/**
 * This file is part of workbunny.
 *
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    chaz6chez<chaz6chez1993@outlook.com>
 * @copyright chaz6chez<chaz6chez1993@outlook.com>
 * @link      https://github.com/workbunny/webman-nacos
 * @license   https://github.com/workbunny/webman-nacos/blob/main/LICENSE
 */
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Provider;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Promise\PromiseInterface;

class InstanceProvider extends AbstractProvider
{
    const REGISTER_URL = 'nacos/v1/ns/instance';
    const REGISTER_METHOD = 'POST';

    const DELETE_URL = 'nacos/v1/ns/instance';
    const DELETE_METHOD = 'DELETE';

    const UPDATE_URL = 'nacos/v1/ns/instance';
    const UPDATE_METHOD = 'PUT';

    const DETAIL_URL = 'nacos/v1/ns/instance';
    const DETAIL_METHOD = 'GET';

    const LIST_URL = 'nacos/v1/ns/instance/list';
    const LIST_METHOD = 'GET';

    const UPDATE_HEALTH_URL = 'nacos/v1/ns/health/instance';
    const UPDATE_HEALTH_METHOD = 'PUT';

    const BEAT_URL = 'nacos/v1/ns/instance/beat';
    const BEAT_METHOD = 'PUT';

    /**
     * @param string $ip
     * @param int $port
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'clusterName' => '',
     *     'namespaceId' => '',
     *     'weight' => 99.0,
     *     'metadata' => '',
     *     'enabled' => true,
     *     'ephemeral' => false, // 是否临时实例
     * ]
     * @return bool|string
     * @throws GuzzleException
     */
    public function register(string $ip, int $port, string $serviceName, array $optional = [])
    {
        return $this->request(self::REGISTER_METHOD, self::REGISTER_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
                'ip' => $ip,
                'port' => $port,
            ])),
        ]);
    }

    /**
     * @param string $ip
     * @param int $port
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName'   => '',
     *     'clusterName' => '',
     *     'namespaceId' => '',
     *     'weight'      => 99.0,
     *     'metadata'    => '',
     *     'enabled'     => 'true',  // 是否上线
     *     'ephemeral'   => 'false', // 是否临时实例
     * ]
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function registerAsync(string $ip, int $port, string $serviceName, array $optional = []){
        return $this->requestAsync(self::REGISTER_METHOD, self::REGISTER_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
                'ip' => $ip,
                'port' => $port,
            ])),
        ]);
    }

    /**
     * @param array $options = [
     *  'serviceName' => '',
     *  'ip' => '',
     *  'port' => ''
     * ]
     * @param array $optional = [
     *     'groupName' => '',
     *     'clusterName' => '',
     *     'namespaceId' => '',
     *     'weight'      => 99.0,
     *     'metadata'    => '',
     *     'enabled'     => 'true',  // 是否上线
     *     'ephemeral'   => 'false', // 是否临时实例
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function registerAsyncUseEventLoop(array $options, array $optional = [], ?callable $success = null, ?callable $error = null): bool
    {
        $this->verify($options, [
            ['ip', 'is_string', true],
            ['port', 'is_int', true],
            ['serviceName', 'is_string', true],
        ]);
        return $this->requestAsyncUseEventLoop(self::REGISTER_METHOD, self::REGISTER_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $options['serviceName'] ?? null,
                'ip' => $options['ip'] ?? null,
                'port' => $options['port'] ?? null,
            ])),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @param string $serviceName
     * @param string $groupName
     * @param string $ip
     * @param int $port
     * @param array $optional = [
     *     'clusterName' => '',
     *     'namespaceId' => '',
     *     'ephemeral' => false,
     * ]
     * @return bool|string
     * @throws GuzzleException
     */
    public function delete(string $serviceName, string $groupName, string $ip, int $port, array $optional = [])
    {
        return $this->request(self::DELETE_METHOD, self::DELETE_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
                'groupName' => $groupName,
                'ip' => $ip,
                'port' => $port,
            ])),
        ]);
    }

    /**
     * @param string $serviceName
     * @param string $groupName
     * @param string $ip
     * @param int $port
     * @param array $optional = [
     *     'clusterName' => '',
     *     'namespaceId' => '',
     *     'ephemeral' => false,
     * ]
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function deleteAsync(string $serviceName, string $groupName, string $ip, int $port, array $optional = [])
    {
        return $this->requestAsync(self::DELETE_METHOD, self::DELETE_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
                'groupName' => $groupName,
                'ip' => $ip,
                'port' => $port,
            ])),
        ]);
    }

    /**
     * @param array $options = [
     *      'serviceName' => '',
     *      'groupName' => '',
     *      'ip' => '',
     *      'port' => ''
     * ]
     * @param array $optional = [
     *     'clusterName' => '',
     *     'namespaceId' => '',
     *     'ephemeral' => false,
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function deleteAsyncUseEventLoop(array $options, array $optional = [], ?callable $success = null, ?callable $error = null): bool
    {
        $this->verify($options, [
            ['serviceName', 'is_string', true],
            ['groupName', 'is_string', true],
            ['ip', 'is_string', true],
            ['port', 'is_int', true],
        ]);
        return $this->requestAsyncUseEventLoop(self::DELETE_METHOD, self::DELETE_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $options['serviceName'] ?? null,
                'groupName'   => $options['groupName'] ?? null,
                'ip'          => $options['ip'] ?? null,
                'port'        => $options['port'] ?? null,
            ])),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @param string $ip
     * @param int $port
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'clusterName' => '',
     *     'namespaceId' => '',
     *     'weight' => 0.99,
     *     'metadata' => '', // json
     *     'enabled' => 'false',
     *     'ephemeral' => 'false',
     * ]
     * @return bool|string
     * @throws GuzzleException
     */
    public function update(string $ip, int $port, string $serviceName, array $optional = [])
    {
        return $this->request(self::UPDATE_METHOD, self::UPDATE_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
                'ip' => $ip,
                'port' => $port,
            ])),
        ]);
    }

    /**
     * @param string $ip
     * @param int $port
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'clusterName' => '',
     *     'namespaceId' => '',
     *     'weight' => 0.99,
     *     'metadata' => '', // json
     *     'enabled' => 'false',
     *     'ephemeral' => 'false',
     * ]
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function updateAsync(string $ip, int $port, string $serviceName, array $optional = [])
    {
        return $this->requestAsync(self::UPDATE_METHOD, self::UPDATE_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
                'ip' => $ip,
                'port' => $port,
            ])),
        ]);
    }

    /**
     * @param array $options = [
     *      'serviceName' => '',
     *      'ip' => '',
     *      'port' => '',
     * ]
     * @param array $optional = [
     *     'groupName' => '',
     *     'clusterName' => '',
     *     'namespaceId' => '',
     *     'weight' => 0.99,
     *     'metadata' => '', // json
     *     'enabled' => 'false',
     *     'ephemeral' => 'false',
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function updateAsyncUseEventLoop(array $options, array $optional = [], ?callable $success = null, ?callable $error = null): bool
    {
        $this->verify($options, [
            ['serviceName', 'is_string', true],
            ['ip', 'is_string', true],
            ['port', 'is_int', true],
        ]);
        return $this->requestAsyncUseEventLoop(self::UPDATE_METHOD, self::UPDATE_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $options['serviceName'] ?? null,
                'ip'          => $options['ip'] ?? null,
                'port'        => $options['port'] ?? null,
            ])),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'clusters' => '', // 集群名称(字符串，多个集群用逗号分隔)
     *     'healthyOnly' => false,
     * ]
     * @return bool|string
     * @throws GuzzleException
     */
    public function list(string $serviceName, array $optional = [])
    {
        return $this->request(self::LIST_METHOD, self::LIST_URL, [
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
     *     'clusters' => '', // 集群名称(字符串，多个集群用逗号分隔)
     *     'healthyOnly' => false,
     * ]
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function listAsync(string $serviceName, array $optional = [])
    {
        return $this->requestAsync(self::LIST_METHOD, self::LIST_URL, [
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
     *     'clusters' => '', // 集群名称(字符串，多个集群用逗号分隔)
     *     'healthyOnly' => false,
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function listAsyncUseEventLoop(string $serviceName, array $optional = [], ?callable $success = null, ?callable $error = null): bool
    {
        return $this->requestAsyncUseEventLoop(self::LIST_METHOD, self::LIST_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
            ])),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @param string $ip
     * @param int $port
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'cluster' => '',
     *     'healthyOnly' => false,
     *     'ephemeral' => false,
     * ]
     * @return bool|string
     * @throws GuzzleException
     */
    public function detail(string $ip, int $port, string $serviceName, array $optional = [])
    {
        return $this->request(self::DETAIL_METHOD, self::DETAIL_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'ip' => $ip,
                'port' => $port,
                'serviceName' => $serviceName,
            ])),
        ]);
    }

    /**
     * @param string $ip
     * @param int $port
     * @param string $serviceName
     * @param array $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'cluster' => '',
     *     'healthyOnly' => false,
     *     'ephemeral' => false,
     * ]
     * @return bool|string
     * @throws GuzzleException
     */
    public function detailAsync(string $ip, int $port, string $serviceName, array $optional = [])
    {
        return $this->request(self::DETAIL_METHOD, self::DETAIL_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'ip' => $ip,
                'port' => $port,
                'serviceName' => $serviceName,
            ])),
        ]);
    }

    /**
     * @param array $options = [
     *      'serviceName' => '',
     *      'ip' => '',
     *      'port' => '',
     * ]
     * @param array $optional = [
     *     'groupName' => '',
     *     'namespaceId' => '',
     *     'cluster' => '',
     *     'healthyOnly' => false,
     *     'ephemeral' => false,
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool|string
     * @throws GuzzleException
     */
    public function detailAsyncUseEventLoop(array $options, array $optional = [], ?callable $success = null, ?callable $error = null): bool
    {
        $this->verify($options, [
            ['serviceName', 'is_string', true],
            ['ip', 'is_string', true],
            ['port', 'is_int', true],
        ]);
        return $this->requestAsyncUseEventLoop(self::DETAIL_METHOD, self::DETAIL_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'ip' => $options['ip'] ?? null,
                'port' => $options['port'] ?? null,
                'serviceName' => $options['serviceName'] ?? null,
            ])),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @param string $ip
     * @param int $port
     * @param string $serviceName
     * @param bool $healthy
     * @param array $optional = [
     *     'namespaceId' => '',
     *     'groupName' => '',
     *     'clusterName' => '',
     * ]
     * @return bool|string
     * @throws GuzzleException
     */
    public function updateHealth(string $ip, int $port, string $serviceName, bool $healthy, array $optional = [])
    {
        return $this->request(self::UPDATE_HEALTH_METHOD, self::UPDATE_HEALTH_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'ip' => $ip,
                'port' => $port,
                'serviceName' => $serviceName,
                'healthy' => $healthy,
            ])),
        ]);
    }

    /**
     * @param string $ip
     * @param int $port
     * @param string $serviceName
     * @param bool $healthy
     * @param array $optional = [
     *     'namespaceId' => '',
     *     'groupName' => '',
     *     'clusterName' => '',
     * ]
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function updateHealthAsync(string $ip, int $port, string $serviceName, bool $healthy, array $optional = [])
    {
        return $this->requestAsync(self::UPDATE_HEALTH_METHOD, self::UPDATE_HEALTH_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'ip' => $ip,
                'port' => $port,
                'serviceName' => $serviceName,
                'healthy' => $healthy,
            ])),
        ]);
    }

    /**
     * @param array $options = [
     *      'serviceName' => '',
     *      'ip' => '',
     *      'port' => 8000,
     *      'healthy => true
     * ]
     * @param array $optional = [
     *     'namespaceId' => '',
     *     'groupName' => '',
     *     'clusterName' => '',
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function updateHealthAsyncUseEventLoop(array $options, array $optional = [], ?callable $success = null, ?callable $error = null): bool
    {
        $this->verify($options, [
            ['serviceName', 'is_string', true],
            ['ip', 'is_string', true],
            ['port', 'is_int', true],
            ['healthy', 'is_bool', true]
        ]);
        return $this->requestAsyncUseEventLoop(self::UPDATE_HEALTH_METHOD, self::UPDATE_HEALTH_URL, [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'ip' => $options['ip'] ?? null,
                'port' => $options['port'] ?? null,
                'serviceName' => $options['serviceName'] ?? null,
                'healthy' => $options['healthy'] ?? null,
            ])),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @param string $serviceName
     * @param array $beat = [
     *     'ip' => '',
     *     'port' => 9501,
     *     'serviceName' => '',
     *     'cluster' => '',
     *     'weight' => 1,
     * ]
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @param bool|null $ephemeral
     * @param bool $lightBeatEnabled
     * @param float $timeout
     * @return bool|string
     * @throws GuzzleException
     */
    public function beat(
        string $serviceName,
        array $beat = [],
        ?string $groupName = null,
        ?string $namespaceId = null,
        ?bool $ephemeral = null,
        bool $lightBeatEnabled = false,
        float $timeout = 5.0
    )
    {
        return $this->request(self::BEAT_METHOD, self::BEAT_URL, [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'ip' => $beat['ip'] ?? null,
                'port' => $beat['port'] ?? null,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
                'ephemeral' => $ephemeral,
                'beat' => ! $lightBeatEnabled ? json_encode($beat) : '',
            ]),
            RequestOptions::TIMEOUT => $timeout,
        ]);
    }

    /**
     * @param string $serviceName
     * @param array $beat = [
     *     'ip' => '',
     *     'port' => 9501,
     *     'serviceName' => '',
     *     'cluster' => '',
     *     'weight' => 1,
     * ]
     * @param string|null $groupName
     * @param string|null $namespaceId
     * @param bool|null $ephemeral
     * @param bool $lightBeatEnabled
     * @param float $timeout
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function beatAsync(
        string $serviceName,
        array $beat = [],
        ?string $groupName = null,
        ?string $namespaceId = null,
        ?bool $ephemeral = null,
        bool $lightBeatEnabled = false,
        float $timeout = 5.0
    )
    {
        return $this->requestAsync(self::BEAT_METHOD, self::BEAT_URL, [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'ip' => $beat['ip'] ?? null,
                'port' => $beat['port'] ?? null,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
                'ephemeral' => $ephemeral,
                'beat' => ! $lightBeatEnabled ? json_encode($beat) : '',
            ]),
            RequestOptions::TIMEOUT => $timeout,
        ]);
    }

    /**
     * @param array $options = [
     *      'serviceName' => '',
     *      'groupName' => '',
     *      'namespaceId' => '',
     *      'ephemeral' => false, // bool
     *      'beat' => [
     *          'ip' => '',
     *          'port' => 9501,
     *          'serviceName' => '',
     *          'cluster' => '',
     *          'weight' => 1,
     *      ]
     * ]
     * @param bool $lightBeatEnabled
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function beatAsyncUseEventLoop(array $options, bool $lightBeatEnabled = false, ?callable $success = null, ?callable $error = null): bool
    {
        $this->verify($options, [
            ['serviceName', 'is_string', true],
            ['beat', 'is_array', true],
            ['groupName', 'is_string', false],
            ['namespaceId', 'is_string', false],
            ['ephemeral', 'is_bool', false],
            ['timeout', 'is_float', false],
        ]);
        return $this->requestAsync(self::BEAT_METHOD, self::BEAT_URL, [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $options['serviceName'] ?? null,
                'ip' => $options['beat']['ip'] ?? null,
                'port' => $options['beat']['port'] ?? null,
                'groupName' => $options['groupName'] ?? null,
                'namespaceId' => $options['namespaceId'] ?? null,
                'ephemeral' => $options['ephemeral'] ?? null,
                'beat' => ! $lightBeatEnabled ? json_encode($options['beat'] ?? []) : '',
            ]),
            RequestOptions::TIMEOUT => $options['timeout'] ?? 5.0,
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }
}
