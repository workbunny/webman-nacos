<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Provider;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Workbunny\WebmanNacos\Traits\Authentication;
use Workbunny\WebmanNacos\Traits\ErrorMsg;
use Workbunny\WebmanNacos\Client as NacosClient;

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

    /** @var NacosClient  */
    protected NacosClient $client;

    /** @var Client|null  */
    protected ?Client $httpClient = null;

    /** @var string  */
    protected string $host = '127.0.0.1';

    /** @var int  */
    protected int $port = 8848;

    /** @var string|null  */
    protected ?string $username = null;

    /** @var string|null  */
    protected ?string $password = null;

    /**
     * AbstractProvider constructor.
     * @param NacosClient $client
     * @param array|null $config
     */
    public function __construct(NacosClient $client, ?array $config = null)
    {
        $this->client = $client;
        $config = $config ?? config('plugin.workbunny.webman-nacos.app');
        isset($config['host']) && $this->host = (string) $config['host'];
        isset($config['port']) && $this->port = (int) $config['port'];
        isset($config['username']) && $this->username = (string) $config['username'];
        isset($config['password']) && $this->password = (string) $config['password'];
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
        if(!$this->httpClient instanceof Client){
            $config = [
                'base_uri' => sprintf('http://%s:%d', $this->host ?? '127.0.0.1', $this->port ?? 8848),
                'timeout' => config('plugin.workbunny.webman-nacos.app.long_pulling_interval', 60) + 10,
            ];
            $this->httpClient = new Client($config);
        }
        return $this->httpClient;
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
            if($token = $this->issueToken()){
                $options[RequestOptions::QUERY]['accessToken'] = $token;
            }
            $response = $this->httpClient()->request($method, $uri, $options);
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                if (200 != $exception->getResponse()->getStatusCode()) {
                    return $this->setError(false, $exception->getResponse()->getBody()->getContents());
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
            if($token = $this->issueToken()){
                $options[RequestOptions::QUERY]['accessToken'] = $token;
            }
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
     * @param ResponseInterface $response
     * @return array
     */
    protected function handleResponse(ResponseInterface $response): array
    {
        try {
            $decode = json_decode((string) $response->getBody(), true, 512, 0 | JSON_THROW_ON_ERROR);
        } catch (\Throwable $exception) {
            throw new \InvalidArgumentException($exception->getMessage(), $exception->getCode());
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
}