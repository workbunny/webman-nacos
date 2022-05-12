<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Provider;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;

class ConfigProvider extends AbstractProvider
{

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
        return $this->request('GET', 'nacos/v1/cs/configs', [
            RequestOptions::QUERY => $this->filter([
                'dataId' => $dataId,
                'group'  => $group,
                'tenant' => $tenant
            ])
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
        return $this->request('POST', 'nacos/v1/cs/configs', [
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
     * 监听配置
     * @param string $dataId
     * @param string $group
     * @param string $contentMD5
     * @param string|null $tenant
     * @param int|null $timeout
     * @return bool|string
     * @throws GuzzleException
     */
    public function listener(
        string $dataId,
        string $group,
        string $contentMD5,
        ?string $tenant = null,
        ?int $timeout = null)
    {
        // 监听数据报文。格式为 dataId^2Group^2contentMD5^2tenant^1或者dataId^2Group^2contentMD5^1。
        $ListeningConfigs = $dataId . self::WORD_SEPARATOR .
            $group . self::WORD_SEPARATOR .
            $contentMD5 . self::WORD_SEPARATOR .
            $tenant . self::LINE_SEPARATOR;
        return $this->request('POST', '/nacos/v1/cs/configs/listener', [
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
    public function listenerAsync(
        string $dataId,
        string $group,
        string $contentMD5,
        ?string $tenant = null,
        ?int $timeout = null)
    {
        // 监听数据报文。格式为 dataId^2Group^2contentMD5^2tenant^1或者dataId^2Group^2contentMD5^1。
        $ListeningConfigs = $dataId . self::WORD_SEPARATOR .
            $group . self::WORD_SEPARATOR .
            $contentMD5 . self::WORD_SEPARATOR .
            $tenant . self::LINE_SEPARATOR;
        return $this->requestAsync('POST', '/nacos/v1/cs/configs/listener', [
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
     * @return bool|void
     * @throws GuzzleException
     */
    public function listenerAsyncUseEventLoop(array $options, ?callable $success = null, ?callable $error = null)
    {
        // 监听数据报文。格式为 dataId^2Group^2contentMD5^2tenant^1或者dataId^2Group^2contentMD5^1。
        $ListeningConfigs = ($options['dataId'] ?? null) . self::WORD_SEPARATOR .
            ($options['group'] ?? null) . self::WORD_SEPARATOR .
            ($options['contentMD5'] ?? null) . self::WORD_SEPARATOR .
            ($options['tenant'] ?? null) . self::LINE_SEPARATOR;
        return $this->requestAsyncUseEventLoop('POST', '/nacos/v1/cs/configs/listener', [
            RequestOptions::QUERY   => [
                'Listening-Configs' => $ListeningConfigs,
            ],
            RequestOptions::HEADERS => [
                'Long-Pulling-Timeout' => $timeout ?? config('plugin.workbunny.webman-nacos.app.long_pulling_timeout'),
            ],
            'success' => $success,
            'error' => $error
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
        return $this->request('DELETE', 'nacos/v1/cs/configs', [
            RequestOptions::QUERY => $this->filter([
                'dataId' => $dataId,
                'group'  => $group,
                'tenant' => $tenant
            ]),
        ]);
    }
}