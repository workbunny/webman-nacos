<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Provider;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class ServiceProvider extends AbstractProvider
{
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
        return $this->request('POST', 'nacos/v1/ns/service', [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
            ])),
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
        return $this->request('DELETE', 'nacos/v1/ns/service', [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
            ]),
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
        return $this->request('PUT', 'nacos/v1/ns/service', [
            RequestOptions::QUERY => $this->filter(array_merge($optional, [
                'serviceName' => $serviceName,
            ])),
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
        return $this->request('GET', 'nacos/v1/ns/service', [
            RequestOptions::QUERY => $this->filter([
                'serviceName' => $serviceName,
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
     * @return bool|string
     * @throws GuzzleException
     */
    public function list(int $pageNo, int $pageSize, ?string $groupName = null, ?string $namespaceId = null)
    {
        return $this->request('GET', 'nacos/v1/ns/service/list', [
            RequestOptions::QUERY => $this->filter([
                'pageNo' => $pageNo,
                'pageSize' => $pageSize,
                'groupName' => $groupName,
                'namespaceId' => $namespaceId,
            ]),
        ]);
    }
}
