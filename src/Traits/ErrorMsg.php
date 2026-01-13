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

/**
 * Trait ErrorMsg
 * @author liuchangchen
 */
trait ErrorMsg
{
    /**
     * 错误消息.
     */
    public array $error = [
        'message' => '错误消息：',
        'data'    => [],
    ];

    /**
     * 设置错误
     * @param bool $success
     * @param string $message
     * @param array $data
     * @return bool
     */
    public function setError(bool $success, string $message, array $data = []): bool
    {
        $this->error = [
            'message' => $message,
            'data'    => $data,
        ];

        return $success;
    }

    /**
     * 获取错误信息
     * @return array
     */
    public function getError(): array
    {
        return $this->error;
    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getMessage(): string
    {
        return $this->error['message'];
    }

    /**
     * 返回数据
     * @param bool $success
     * @param string $message
     * @param int $code
     * @param array $data
     * @return array
     */
    public function returnData(bool $success, string $message = '', int $code = 0, array $data = []): array
    {
        return [
            'success' => $success,
            'message' => $message,
            'code'    => $code,
            'data'    => $data,
        ];
    }
}
