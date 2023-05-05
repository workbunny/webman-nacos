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

namespace Workbunny\WebmanNacos\Traits;

use GuzzleHttp\Exception\GuzzleException;

/**
 * Trait Authentication
 * @author liuchangchen
 */
trait Authentication
{
    /**
     * @var string|null
     */
    private ?string $accessToken = null;

    /**
     * @var int
     */
    private int $expireTime = 0;

    /**
     * 获取token
     * @return string|null
     * @throws GuzzleException
     */
    public function issueToken(): ?string
    {
        if ($this->username === null || $this->password === null) {
            return null;
        }

        if (!$this->isExpired()) {
            return $this->accessToken;
        }

        $result = $this->handleResponse(
            $this->client()->auth->login($this->username, $this->password)
        );

        $this->accessToken = $result['accessToken'];
        $this->expireTime = $result['tokenTtl'] + time();

        return $this->accessToken;
    }

    /**
     * 是否过期
     * @return bool
     */
    protected function isExpired(): bool
    {
        if (isset($this->accessToken) && $this->expireTime > time() + 60) {
            return false;
        }
        return true;
    }
}
