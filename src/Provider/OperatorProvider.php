<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Provider;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\RequestOptions;

class OperatorProvider extends AbstractProvider
{
    const GET_SWITCHES_URL = 'nacos/v1/ns/operator/switches';
    const GET_SWITCHES_METHOD = 'GET';

    const UPDATE_SWITCHES_URL = 'nacos/v1/ns/operator/switches';
    const UPDATE_SWITCHES_METHOD = 'PUT';

    const GET_METRICS_URL = 'nacos/v1/ns/operator/metrics';
    const GET_METRICS_METHOD = 'GET';

    const GET_SERVERS_URL = 'nacos/v1/ns/operator/servers';
    const GET_SERVERS_METHOD = 'GET';

    const GET_LEADER_URL = 'nacos/v1/ns/raft/leader';
    const GET_LEADER_METHOD = 'GET';

    /**
     * @return bool|string
     * @throws GuzzleException
     */
    public function getSwitches()
    {
        return $this->request(self::GET_SWITCHES_METHOD, self::GET_SWITCHES_URL);
    }

    /**
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function getSwitchesAsync()
    {
        return $this->requestAsync(self::GET_SWITCHES_METHOD, self::GET_SWITCHES_URL);
    }

    /**
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function getSwitchesAsyncUseEventLoop(?callable $success = null, ?callable $error = null): bool
    {
        return $this->requestAsyncUseEventLoop(self::GET_SWITCHES_METHOD, self::GET_SWITCHES_URL, [
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @param string $entry
     * @param string $value
     * @param bool|null $debug
     * @return bool|string
     * @throws GuzzleException
     */
    public function updateSwitches(string $entry, string $value, ?bool $debug = null)
    {
        return $this->request(self::UPDATE_SWITCHES_METHOD, self::UPDATE_SWITCHES_URL, [
            RequestOptions::QUERY => $this->filter([
                'entry' => $entry,
                'value' => $value,
                'debug' => $debug,
            ]),
        ]);
    }

    /**
     * @param string $entry
     * @param string $value
     * @param bool|null $debug
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function updateSwitchesAsync(string $entry, string $value, ?bool $debug = null)
    {
        return $this->requestAsync(self::UPDATE_SWITCHES_METHOD, self::UPDATE_SWITCHES_URL, [
            RequestOptions::QUERY => $this->filter([
                'entry' => $entry,
                'value' => $value,
                'debug' => $debug,
            ]),
        ]);
    }

    /**
     * @param array $options = [
     *      'entry' => '',
     *      'value' => '',
     *      'debug' => false
     * ]
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function updateSwitchesAsyncUseEventLoop(array $options, ?callable $success = null, ?callable $error = null): bool
    {
        $this->verify($options, [
            ['entry', 'is_string', true],
            ['value', 'is_string', true],
            ['debug', 'is_bool', false]
        ]);
        return $this->requestAsyncUseEventLoop(self::UPDATE_SWITCHES_METHOD, self::UPDATE_SWITCHES_URL, [
            RequestOptions::QUERY => $this->filter([
                'entry' => $options['entry'] ?? null,
                'value' => $options['value'] ?? null,
                'debug' => $options['debug'] ?? null,
            ]),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @return bool|string
     * @throws GuzzleException
     */
    public function getMetrics()
    {
        return $this->request(self::GET_METRICS_METHOD, self::GET_METRICS_URL);
    }

    /**
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function getMetricsAsync()
    {
        return $this->requestAsync(self::GET_METRICS_METHOD, self::GET_METRICS_URL);
    }

    /**
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function getMetricsAsyncUseEventLoop(?callable $success = null, ?callable $error = null): bool
    {
        return $this->requestAsyncUseEventLoop(self::GET_METRICS_METHOD, self::GET_METRICS_URL, [
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @param bool|null $healthy
     * @return bool|string
     * @throws GuzzleException
     */
    public function getServers(?bool $healthy = null)
    {
        return $this->request(self::GET_SERVERS_METHOD, self::GET_SERVERS_URL, [
            RequestOptions::QUERY => $this->filter([
                'healthy' => $healthy,
            ]),
        ]);
    }

    /**
     * @param bool|null $healthy
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function getServersAsync(?bool $healthy = null)
    {
        return $this->requestAsync(self::GET_SERVERS_METHOD, self::GET_SERVERS_URL, [
            RequestOptions::QUERY => $this->filter([
                'healthy' => $healthy,
            ]),
        ]);
    }

    /**
     * @param bool|null $healthy
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function getServersAsyncUseEventLoop(?bool $healthy = null, ?callable $success = null, ?callable $error = null): bool
    {
        return $this->requestAsyncUseEventLoop(self::GET_SERVERS_METHOD, self::GET_SERVERS_URL, [
            RequestOptions::QUERY => $this->filter([
                'healthy' => $healthy,
            ]),
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }

    /**
     * @return bool|string
     * @throws GuzzleException
     */
    public function getLeader()
    {
        return $this->request(self::GET_LEADER_METHOD, self::GET_LEADER_URL);
    }

    /**
     * @return bool|PromiseInterface
     * @throws GuzzleException
     */
    public function getLeaderAsync()
    {
        return $this->requestAsync(self::GET_LEADER_METHOD, self::GET_LEADER_URL);
    }

    /**
     * @param callable|null $success = function(\Workerman\Http\Response $response){}
     * @param callable|null $error = function(\Exception $exception){}
     * @return bool
     * @throws GuzzleException
     */
    public function getLeaderAsyncUseEventLoop(?callable $success = null, ?callable $error = null): bool
    {
        return $this->requestAsync(self::GET_LEADER_METHOD, self::GET_LEADER_URL, [
            OPTIONS_SUCCESS => $success,
            OPTIONS_ERROR => $error
        ]);
    }
}
