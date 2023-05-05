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

namespace Workbunny\WebmanNacos;

use GuzzleHttp\Handler\MockHandler;
use Workbunny\WebmanNacos\Exception\NacosException;
use Workbunny\WebmanNacos\Provider\ConfigProvider;
use Workbunny\WebmanNacos\Provider\InstanceProvider;
use Workbunny\WebmanNacos\Provider\OperatorProvider;
use Workbunny\WebmanNacos\Provider\ServiceProvider;
use Workbunny\WebmanNacos\Provider\AuthProvider;
use function Workbunny\WebmanNacos\config;

/**
 * Class NacosClient
 * @author liuchangchen
 * @property AuthProvider $auth
 * @property ConfigProvider $config
 * @property InstanceProvider $instance
 * @property OperatorProvider $operator
 * @property ServiceProvider $service
 */
class Client
{
    /** @var MockHandler|null  */
    public static ?MockHandler $mockHandler = null;
    /** @var bool debug mode */
    public static bool $debug = false;

    /** @var string|null Channel Name */
    protected ?string $name = null;

    /**
     * @var array|string[]
     */
    protected array $alias = [
        'auth'     => AuthProvider::class,
        'config'   => ConfigProvider::class,
        'instance' => InstanceProvider::class,
        'operator' => OperatorProvider::class,
        'service'  => ServiceProvider::class
    ];

    /** @var array  */
    protected array $configs = [];

    /** @var array  */
    protected array $providers = [];

    /**
     * @var Client[]
     */
    protected static array $clients = [];

    /**
     * @param string $name
     * @return Client
     */
    public static function channel(string $name = 'default'): Client
    {
        $channel = config('plugin.workbunny.webman-nacos.channel', []);
        if(empty($config = $channel[$name] ?? [])){
            throw new NacosException("Channel config $name is invalid.");
        }
        return self::$clients[$name] ?? (self::$clients[$name] = new static($config));
    }

    /**
     * @return void
     */
    public function cancel(): void
    {
        if($name = $this->getName() and isset(self::$clients[$name])){
            unset(self::$clients[$name]);
        }
    }

    /**
     * NacosClient constructor.
     * @param array|null $config = [
     *  'host' => '',
     *  'port' => 8848,
     *  'username' => '',
     *  'password' => ''
     * ]
     */
    public function __construct(?array $config = null)
    {
        $this->configs = $config ?? [];
    }

    /**
     * @return array
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @author liuchangchen
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (!isset($name) || !isset($this->alias[$name])) {
            throw new NacosException("{$name} is invalid.");
        }

        if (isset($this->providers[$name])) {
            return $this->providers[$name];
        }
        $class = $this->alias[$name];
        return $this->providers[$name] = $this->getConfigs() ?
            new $class($this, $this->getConfigs()) :
            new $class($this);
    }

}