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

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Workbunny\WebmanNacos\Client as NacosClient;

use function Workbunny\WebmanNacos\config;

use Workbunny\WebmanNacos\Traits\Authentication;
use Workbunny\WebmanNacos\Traits\ErrorMsg;
use Workerman\Http\Client as AsyncClient;
use Workerman\Http\Response;

/**
 * Class AbstractProvider
 * @author liuchangchen
 */
abstract class AbstractProvider
{
    use Authentication;
    use ErrorMsg;

    public const WORD_SEPARATOR = "\x02";

    public const LINE_SEPARATOR = "\x01";

    /** @var NacosClient */
    protected NacosClient $client;

    /** @var Client|null */
    protected ?Client $httpClient = null;

    /** @var AsyncClient|null */
    protected ?AsyncClient $httpClientAsync = null;

    /** @var string */
    protected string $host = '127.0.0.1';

    /** @var int */
    protected int $port = 8848;

    /** @var string|null */
    protected ?string $username = null;

    /** @var string|null */
    protected ?string $password = null;

    /** @var string|null */
    protected ?string $accessKeyId = null;

    /** @var string|null */
    protected ?string $accessKeySecret = null;

    /**
     * AbstractProvider constructor.
     * @param NacosClient $client
     * @param array|null $config
     */
    public function __construct(NacosClient $client, ?array $config = null)
    {
        $this->client = $client;
        $config = empty($this->client->getConfigs())
            ? config('plugin.workbunny.webman-nacos.app', $config)
            : $this->client->getConfigs();
        isset($config['host']) && $this->host = (string) $config['host'];
        isset($config['port']) && $this->port = (int) $config['port'];
        isset($config['username']) && $this->username = (string) $config['username'];
        isset($config['password']) && $this->password = (string) $config['password'];
        isset($config['access_key_id']) && $this->accessKeyId = (string) $config['access_key_id'];
        isset($config['access_key_secret']) && $this->accessKeySecret = (string) $config['access_key_secret'];
    }

    /**
     * @return NacosClient
     */
    public function client(): NacosClient
    {
        return $this->client;
    }

    /**
     * 创建guzzle客户端
     * @return Client
     */
    public function httpClient(): Client
    {
        if (!$this->httpClient instanceof Client) {
            $config = [
                'base_uri' => sprintf('http://%s:%d', $this->host, $this->port),
                'timeout'  => config('plugin.workbunny.webman-nacos.app.long_pulling_interval', 30) + 60,
                'headers'  => [
                    'Connection' => 'keep-alive',
                ],
            ];
            if ($this->client()::$mockHandler !== null) {
                $config['handler'] = $this->client()::$mockHandler;
            }
            $this->httpClient = new Client($config);
        }

        return $this->httpClient;
    }

    /**
     * 创建异步http客户端
     * @return AsyncClient
     */
    public function httpClientAsync(): AsyncClient
    {
        if (!$this->httpClientAsync instanceof AsyncClient) {
            $config = [
                'connect_timeout'   => config('plugin.workbunny.webman-nacos.app.long_pulling_interval', 30),
                'timeout'           => config('plugin.workbunny.webman-nacos.app.long_pulling_interval', 30) + 60,
            ];
            if ($this->client()::$mockHandler !== null) {
                $config['handler'] = $this->client()::$mockHandler;
            }
            $this->httpClientAsync = new AsyncClient($config);
        }

        return $this->httpClientAsync;
    }

    /**
     * 请求
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return bool|string
     * @throws GuzzleException
     */
    public function request(string $method, string $uri, array $options = [])
    {
        try {
            $this->issueToken($options);
            $response = $this->httpClient()->request($method, $uri, $options);
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                if (200 != $statusCode = $exception->getResponse()->getStatusCode()) {
                    return $this->setError(
                        false,
                        "[$statusCode] : " . $exception->getResponse()->getBody()->getContents()
                    );
                }
            }

            return $this->setError(false, 'server notice：' . $exception->getMessage());
        }

        return $response->getBody()->getContents();
    }

    /**
     * 请求
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function requestAsync(string $method, string $uri, array $options = [])
    {
        try {
            $this->issueToken($options);

            return $this->httpClient()->requestAsync($method, $uri, $options);
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                if (200 != $exception->getResponse()->getStatusCode()) {
                    return $this->setError(false, $exception->getResponse()->getBody()->getContents());
                }
            }

            return $this->setError(false, 'server notice：' . $exception->getMessage());
        }
    }

    /**
     * 利用workerman事件循环的异步http客户端
     * @param string $method
     * @param string $uri
     * @param array $options = [
     *                       RequestOptions::QUERY => [],
     *                       RequestOptions::HEADERS => [],
     *                       'data' => [],
     *                       'success' => function(\Workerman\Http\Response $response){},
     *                       'error' => function(\Exception $exception){}
     *                       ]
     * @return bool
     * @throws GuzzleException
     */
    public function requestAsyncUseEventLoop(string $method, string $uri, array $options = []): bool
    {
        try {
            # 同步阻塞获取token
            $this->issueToken($options);
            $queryString = http_build_query($options[RequestOptions::QUERY] ?? []);
            $headers = array_merge($options[RequestOptions::HEADERS] ?? [], [
                'Connection' => 'keep-alive',
            ]);
            $this->httpClientAsync()->request(
                sprintf('http://%s:%d/%s?%s', $this->host, $this->port, $uri, $queryString),
                [
                    'method'    => $method,
                    'version'   => '1.1',
                    'headers'   => $headers,
                    'data'      => $options['data'] ?? [],
                    'success'   => $options['success'] ?? function (Response $response) {
                    },
                    'error'     => $options['error'] ?? function (\Exception $exception) {
                    },
                ]
            );

            return true;
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                if (200 != $exception->getResponse()->getStatusCode()) {
                    return $this->setError(false, $exception->getResponse()->getBody()->getContents());
                }
            }

            return $this->setError(false, 'server notice：' . $exception->getMessage());
        }
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    protected function handleResponse(ResponseInterface $response): array
    {
        try {
            $decode = json_decode((string) $response->getBody(), true, 512, 0 | JSON_THROW_ON_ERROR);
        } catch (\Throwable $exception) {
            throw new InvalidArgumentException($exception->getMessage(), $exception->getCode());
        }

        return $decode;
    }

    /**
     * @param array $input
     * @return array
     */
    protected function filter(array $input): array
    {
        $result = [];
        foreach ($input as $key => $value) {
            if ($value !== null) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * @param array $options
     * @param array $validators = [
     *                          ['serviceName', 'is_string'],
     *                          ['port', 'is_int']
     *                          ]
     * @return void
     * @throws InvalidArgumentException
     */
    protected function verify(array $options, array $validators): void
    {
        foreach ($validators as $validator) {
            list($key, $handler, $required) = $validator;
            if (isset($options[$key])) {
                if (!function_exists($handler)) {
                    throw new InvalidArgumentException('Invalid Function: ' . $handler);
                }
                if ($handler($options[$key])) {
                    continue;
                }
                goto fail;
            }
            if ($required) {
                fail:
                throw new InvalidArgumentException('Invalid Argument: ' . $key);
            }
        }
    }
}
