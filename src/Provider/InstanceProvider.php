<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Provider;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Promise\PromiseInterface;

class InstanceProvider extends AbstractProvider
{
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
        return $this->request('POST', 'nacos/v1/ns/instance', [
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
     *     'weight' => 99.0,
     *     'metadata' => '',
     *     'enabled' => true,
     *     'ephemeral' => false, // 是否临时实例
     * ]
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function registerAsync(string $ip, int $port, string $serviceName, array $optional = []){
        return $this->requestAsync('POST', 'nacos/v1/ns/instance', [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
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
     * @return bool|string
     * @throws GuzzleException
     */
    public function delete(string $serviceName, string $groupName, string $ip, int $port, array $optional = [])
    {
        return $this->request('DELETE', 'nacos/v1/ns/instance', [
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
        return $this->requestAsync('DELETE', 'nacos/v1/ns/instance', [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
                'groupName' => $groupName,
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
     *     'enabled' => false,
     *     'ephemeral' => false,
     * ]
     * @return bool|string
     * @throws GuzzleException
     */
    public function update(string $ip, int $port, string $serviceName, array $optional = [])
    {
        return $this->request('PUT', 'nacos/v1/ns/instance', [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
                'ip' => $ip,
                'port' => $port,
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
     * @return bool|string
     * @throws GuzzleException
     */
    public function list(string $serviceName, array $optional = [])
    {
        return $this->request('GET', 'nacos/v1/ns/instance/list', [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
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
    public function detail(string $ip, int $port, string $serviceName, array $optional = [])
    {
        return $this->request('GET', 'nacos/v1/ns/instance', [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'ip' => $ip,
                'port' => $port,
                'serviceName' => $serviceName,
            ])),
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
     * @return bool|string
     * @throws GuzzleException
     */
    public function beat(
        string $serviceName,
        array $beat = [],
        ?string $groupName = null,
        ?string $namespaceId = null,
        ?bool $ephemeral = null,
        bool $lightBeatEnabled = false
    )
    {
        return $this->request('PUT', 'nacos/v1/ns/instance/beat', [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'ip' => $beat['ip'] ?? null,
                'port' => $beat['port'] ?? null,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
                'ephemeral' => $ephemeral,
                'beat' => ! $lightBeatEnabled ? json_encode($beat) : '',
            ]),
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
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function beatAsync(
        string $serviceName,
        array $beat = [],
        ?string $groupName = null,
        ?string $namespaceId = null,
        ?bool $ephemeral = null,
        bool $lightBeatEnabled = false
    )
    {
        return $this->requestAsync('PUT', 'nacos/v1/ns/instance/beat', [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'ip' => $beat['ip'] ?? null,
                'port' => $beat['port'] ?? null,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
                'ephemeral' => $ephemeral,
                'beat' => ! $lightBeatEnabled ? json_encode($beat) : '',
            ]),
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
        return $this->request('PUT', 'nacos/v1/ns/health/instance', [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'ip' => $ip,
                'port' => $port,
                'serviceName' => $serviceName,
                'healthy' => $healthy,
            ])),
        ]);
    }
}
