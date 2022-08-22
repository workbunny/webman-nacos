<?php
/**
 * This file is part of workbunny.
 *
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    chaz6chez<250220719@qq.com>
 * @copyright chaz6chez<250220719@qq.com>
 * @link      https://github.com/workbunny/webman-nacos
 * @license   https://github.com/workbunny/webman-nacos/blob/main/LICENSE
 */
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Provider;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;

class ConfigProvider extends AbstractProvider
{
    const GET_URL = 'nacos/v1/cs/configs';
    const GET_METHOD = 'GET';

    const PUBLISH_URL = 'nacos/v1/cs/configs';
    const PUBLISH_METHOD = 'POST';

    const DELETE_URL = 'nacos/v1/cs/configs';
    const DELETE_METHOD = 'DELETE';

    const LISTENER_URL = 'nacos/v1/cs/configs/listener';
    const LISTENER_METHOD = 'POST';

    /**
     * 获取配置
     * @param string $dataId
     * @param string $group
     * @param string|null $tenant
     * @return bool|string
     * @throws GuzzleException
     */
    public function get(string $dataId, string $group, ?string $tenant = null)
    {
        return $this->request(self::GET_METHOD, self::GET_URL, [
            RequestOptions::QUERY => $this->filter([
                'dataId' => $dataId,
                'group'  => $group,
                'tenant' => $tenant
            ])
        ]);
    }

    /**
     * 获取配置
     * @param string $dataId
     * @param string $group
     * @param string|null $tenant
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function getAsync(string $dataId, string $group, ?string $tenant = null)
    {
        return $this->requestAsync(self::GET_METHOD, self::GET_URL, [
            RequestOptions::QUERY => $this->filter([
                'dataId' => $dataId,
                'group'  => $group,
                'tenant' => $tenant
            ])
        ]);
    }

    /**
     * 获取配置
     * @param array $options = [
     *  'dataId' => 'xxx',
     *  'group' => 'xxx',
     *  'tenant' => 'xxx'
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function getAsyncUseEventLoop(array $options, ?callable $success = null, ?callable $error = null): bool
    {
        $this->verify($options, [
            ['dataId', 'is_string', true],
            ['group', 'is_string', true],
            ['tenant', 'is_string', false]
        ]);
        return $this->requestAsyncUseEventLoop(self::GET_METHOD, self::GET_URL, [
            RequestOptions::QUERY => $this->filter([
                'dataId' => $options['dataId'] ?? null,
                'group'  => $options['group'] ?? null,
                'tenant' => $options['tenant'] ?? null
            ]),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }


    /**
     * 发布配置
     * @param string $dataId
     * @param string $group
     * @param string $content
     * @param string|null $type
     * @param string|null $tenant
     * @return bool|string
     * @throws GuzzleException
     */
    public function publish(string $dataId, string $group, string $content, ?string $type = null, ?string $tenant = null)
    {
        return $this->request(self::PUBLISH_METHOD, self::PUBLISH_URL, [
            RequestOptions::FORM_PARAMS => $this->filter([
                'dataId'  => $dataId,
                'group'   => $group,
                'tenant'  => $tenant,
                'type'    => $type,
                'content' => $content
            ]),
        ]);
    }

    /**
     * 发布配置
     * @param string $dataId
     * @param string $group
     * @param string $content
     * @param string|null $type
     * @param string|null $tenant
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function publishAsync(string $dataId, string $group, string $content, ?string $type = null, ?string $tenant = null)
    {
        return $this->requestAsync(self::PUBLISH_METHOD, self::PUBLISH_URL, [
            RequestOptions::FORM_PARAMS => $this->filter([
                'dataId'  => $dataId,
                'group'   => $group,
                'tenant'  => $tenant,
                'type'    => $type,
                'content' => $content
            ]),
        ]);
    }

    /**
     * 发布配置
     * @param array $options = [
     *  'dataId'  => $dataId,
     *  'group'   => $group,
     *  'tenant'  => $tenant,
     *  'type'    => $type,
     *  'content' => $content
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function publishAsyncUseEventLoop(array $options, ?callable $success = null, ?callable $error = null): bool
    {
        $this->verify($options, [
            ['dataId', 'is_string', true],
            ['group', 'is_string', true],
            ['content', 'is_string', true],
            ['type', 'is_string', false],
            ['tenant', 'is_string', false],
        ]);
        return $this->requestAsyncUseEventLoop(self::PUBLISH_METHOD, self::PUBLISH_URL, [
            RequestOptions::FORM_PARAMS => $this->filter([
                'dataId'  => $options['dataId'] ?? null,
                'group'   => $options['group'] ?? null,
                'tenant'  => $options['tenant'] ?? null,
                'type'    => $options['type'] ?? null,
                'content' => $options['content'] ?? null
            ]),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * 删除配置
     * @param string $dataId
     * @param string $group
     * @param string|null $tenant
     * @return bool|string
     * @throws GuzzleException
     */
    public function delete(string $dataId, string $group, ?string $tenant = null)
    {
        return $this->request(self::DELETE_METHOD, self::DELETE_URL, [
            RequestOptions::QUERY => $this->filter([
                'dataId' => $dataId,
                'group'  => $group,
                'tenant' => $tenant
            ]),
        ]);
    }

    /**
     * 删除配置
     * @param string $dataId
     * @param string $group
     * @param string|null $tenant
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function deleteAsync(string $dataId, string $group, ?string $tenant = null)
    {
        return $this->requestAsync(self::DELETE_METHOD, self::DELETE_URL, [
            RequestOptions::QUERY => $this->filter([
                'dataId' => $dataId,
                'group'  => $group,
                'tenant' => $tenant
            ]),
        ]);
    }

    /**
     * 删除配置
     * @param array $options = [
     *  'dataId' => '',
     *  'group'  => '',
     *  'tenant' => '',
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function deleteAsyncUseEventLoop(array $options, ?callable $success = null, ?callable $error = null): bool
    {
        $this->verify($options, [
            ['dataId', 'is_string', true],
            ['group', 'is_string', true],
            ['tenant', 'is_string', false],
        ]);
        return $this->requestAsyncUseEventLoop(self::DELETE_METHOD, self::DELETE_URL, [
            RequestOptions::QUERY => $this->filter([
                'dataId' => $options['dataId'] ?? null,
                'group'  => $options['group'] ?? null,
                'tenant' => $options['tenant'] ?? null,
            ]),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * 监听配置
     * @param string $dataId
     * @param string $group
     * @param string $contentMD5
     * @param string|null $tenant
     * @param int|null $timeout
     * @return bool|string
     * @throws GuzzleException
     */
    public function listener(string $dataId, string $group, string $contentMD5, ?string $tenant = null, ?int $timeout = null)
    {
        // 监听数据报文。格式为 dataId^2Group^2contentMD5^2tenant^1或者dataId^2Group^2contentMD5^1。
        $ListeningConfigs = $dataId . self::WORD_SEPARATOR .
            $group . self::WORD_SEPARATOR .
            $contentMD5 . self::WORD_SEPARATOR .
            $tenant . self::LINE_SEPARATOR;
        return $this->request(self::LISTENER_METHOD, self::LISTENER_URL, [
            RequestOptions::QUERY   => [
                'Listening-Configs' => $ListeningConfigs,
            ],
            RequestOptions::HEADERS => [
                'Long-Pulling-Timeout' => $timeout ?? config('plugin.workbunny.webman-nacos.app.long_pulling_timeout'),
            ],
        ]);
    }

    /**
     * 监听配置
     * @param string $dataId
     * @param string $group
     * @param string $contentMD5
     * @param string|null $tenant
     * @param int|null $timeout
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function listenerAsync(string $dataId, string $group, string $contentMD5, ?string $tenant = null, ?int $timeout = null)
    {
        // 监听数据报文。格式为 dataId^2Group^2contentMD5^2tenant^1或者dataId^2Group^2contentMD5^1。
        $ListeningConfigs = $dataId . self::WORD_SEPARATOR .
            $group . self::WORD_SEPARATOR .
            $contentMD5 . self::WORD_SEPARATOR .
            $tenant . self::LINE_SEPARATOR;
        return $this->requestAsync(self::LISTENER_METHOD, self::LISTENER_URL, [
            RequestOptions::QUERY   => [
                'Listening-Configs' => $ListeningConfigs,
            ],
            RequestOptions::HEADERS => [
                'Long-Pulling-Timeout' => $timeout ?? config('plugin.workbunny.webman-nacos.app.long_pulling_timeout'),
            ],
        ]);
    }

    /**
     * 监听配置
     * @param array $options = [
     *  'dataId' => '',
     *  'group' => '',
     *  'contentMD5' => '',
     *  'tenant' => ''
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function listenerAsyncUseEventLoop(array $options, ?callable $success = null, ?callable $error = null): bool
    {
        // 监听数据报文。格式为 dataId^2Group^2contentMD5^2tenant^1或者dataId^2Group^2contentMD5^1。
        $ListeningConfigs = ($options['dataId'] ?? null) . self::WORD_SEPARATOR .
            ($options['group'] ?? null) . self::WORD_SEPARATOR .
            ($options['contentMD5'] ?? null) . self::WORD_SEPARATOR .
            ($options['tenant'] ?? null) . self::LINE_SEPARATOR;
        return $this->requestAsyncUseEventLoop(self::LISTENER_METHOD, self::LISTENER_URL, [
            RequestOptions::QUERY   => [
                'Listening-Configs' => $ListeningConfigs,
            ],
            RequestOptions::HEADERS => [
                'Long-Pulling-Timeout' => $timeout ?? config('plugin.workbunny.webman-nacos.app.long_pulling_timeout'),
            ],
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }
}