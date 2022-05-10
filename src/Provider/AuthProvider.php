<?php
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
            $response = $this->httpClient()->request('POST', 'nacos/v1/auth/users/login', [
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
