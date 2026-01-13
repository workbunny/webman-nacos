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
use GuzzleHttp\RequestOptions;

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
     * @return void
     * @throws GuzzleException
     */
    public function issueToken(array &$options = [])
    {
        if ($this->username === null || $this->password === null) {
            $this->mseAuth($options);

            return;
        }
        if (!$this->isExpired()) {
            $options[RequestOptions::QUERY]['accessToken'] = $this->accessToken;

            return;
        }

        $result = $this->handleResponse(
            $this->client()->auth->login($this->username, $this->password)
        );

        $this->accessToken = $result['accessToken'];
        $this->expireTime = $result['tokenTtl'] + time();
        $options[RequestOptions::QUERY]['accessToken'] = $this->accessToken;
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

    /**
     * 阿里云微服务引擎MSE鉴权
     * @param array $options
     * @return void
     */
    protected function mseAuth(array &$options = [])
    {
        if ($this->accessKeyId === null || $this->accessKeySecret === null) {
            return;
        }

        $paramsToSign = $options[RequestOptions::QUERY] ?? $options[RequestOptions::FORM_PARAMS] ?? [];

        $signStr = '';
        $millisecondTs = (int) (microtime(true) * 1000);

        // config signature
        if (isset($paramsToSign['tenant']) && $paramsToSign['tenant']) {
            $signStr .= $paramsToSign['tenant'] . '+';
        }
        if (isset($paramsToSign['group']) && $paramsToSign['group']) {
            $signStr .= $paramsToSign['group'] . '+';
        }
        $signStr .= $millisecondTs;

        // naming signature
        if (isset($paramsToSign['serviceName'])) {
            $signStr = $millisecondTs;
            if (mb_strpos($paramsToSign['serviceName'], '@@') !== false
                || !isset($paramsToSign['groupName'])
                || $paramsToSign['groupName'] == '') {
                $signStr .= '@@' . $paramsToSign['serviceName'];
            } else {
                $signStr .= '@@' . $paramsToSign['groupName'] . '@@' . $paramsToSign['serviceName'];
            }
        }

        // 签名
        $signature = base64_encode(hash_hmac('sha1', $signStr, $this->accessKeySecret, true));

        // config增加header
        $options[RequestOptions::HEADERS] = [
                'timeStamp'      => $millisecondTs,
                'Spas-AccessKey' => $this->accessKeyId,
                'Spas-Signature' => $signature,
            ] + ($options[RequestOptions::HEADERS] ?? []);

        // naming增加query
        $options[RequestOptions::QUERY] = [
                'data'      => $signStr,
                'ak'        => $this->accessKeyId,
                'signature' => $signature,
            ] + ($options[RequestOptions::QUERY] ?? []);
    }
}
