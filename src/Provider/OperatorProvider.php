<?php
declare(strict_types=1);

namespace Workbunny\WebmanNacos\Provider;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class OperatorProvider extends AbstractProvider
{
    /**
     * @return bool|string
     * @throws GuzzleException
     */
    public function getSwitches()
    {
        return $this->request('GET', 'nacos/v1/ns/operator/switches');
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
        return $this->request('PUT', 'nacos/v1/ns/operator/switches', [
            RequestOptions::QUERY => $this->filter([
                'entry' => $entry,
                'value' => $value,
                'debug' => $debug,
            ]),
        ]);
    }

    /**
     * @return bool|string
     * @throws GuzzleException
     */
    public function getMetrics()
    {
        return $this->request('GET', 'nacos/v1/ns/operator/metrics');
    }

    /**
     * @param bool|null $healthy
     * @return bool|string
     * @throws GuzzleException
     */
    public function getServers(?bool $healthy = null)
    {
        return $this->request('GET', 'nacos/v1/ns/operator/servers', [
            RequestOptions::QUERY => $this->filter([
                'healthy' => $healthy,
            ]),
        ]);
    }

    /**
     * @return bool|string
     * @throws GuzzleException
     */
    public function getLeader()
    {
        return $this->request('GET', 'nacos/v1/ns/raft/leader');
    }
}
