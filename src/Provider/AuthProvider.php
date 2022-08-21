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
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Workbunny\WebmanNacos\Exception\NacosAuthException;
use Workbunny\WebmanNacos\Exception\NacosRequestException;

class AuthProvider extends AbstractProvider
{
    const LOGIN_URL = 'nacos/v1/auth/users/login';
    const LOGIN_METHOD = 'POST';

    /**
     * 授权登录
     * @param string $username
     * @param string $password
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function login(string $username, string $password): ResponseInterface
    {
        try {
            $response = $this->httpClient()->request(self::LOGIN_METHOD, self::LOGIN_URL, [
                RequestOptions::QUERY => [
                    'username' => $username,
                ],
                RequestOptions::FORM_PARAMS => [
                    'password' => $password,
                ],
            ]);
        } catch (RequestException $exception) {
            if (403 === $exception->getCode()) {
                throw new NacosAuthException($exception->getMessage(), $exception->getCode(), $exception);
            }
            throw new NacosRequestException($exception->getMessage(), $exception->getCode());
        }
        return $response;
    }
}
