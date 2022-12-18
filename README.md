<p align="center"><img width="260px" src="https://chaz6chez.cn/images/workbunny-logo.png" alt="workbunny"></p>

**<p align="center">workbunny/webman-nacos</p>**

**<p align="center">🐇  PHP implementation of Nacos OpenAPI for webman plugin. 🐇</p>**

# A PHP implementation of Nacos OpenAPI for webman plugin

<div align="center">
    <a href="https://github.com/workbunny/webman-nacos/actions">
        <img src="https://github.com/workbunny/webman-nacos/actions/workflows/CI.yml/badge.svg" alt="Build Status">
    </a>
    <a href="https://github.com/workbunny/webman-nacos/releases">
        <img alt="Latest Stable Version" src="https://badgen.net/packagist/v/workbunny/webman-nacos/latest">
    </a>
    <a href="https://github.com/workbunny/webman-nacos/blob/main/composer.json">
        <img alt="PHP Version Require" src="https://badgen.net/packagist/php/workbunny/webman-nacos">
    </a>
    <a href="https://github.com/workbunny/webman-nacos/blob/main/LICENSE">
        <img alt="GitHub license" src="https://badgen.net/packagist/license/workbunny/webman-nacos">
    </a>
</div>

## Naocs插件问答帖

[https://www.workerman.net/q/9134](https://www.workerman.net/q/9134)

## 简介

### 什么是Nacos？

[Nacos](https://nacos.io/) 致力于帮助您发现、配置和管理微服务；是微服务/SOA架构体系中服务治理环节的重要成员服务；简单的可以把Nacos理解为一个配置中心和一个服务注册中心。

### 什么时候用Nacos？

- 当我们的服务越来越大、越来越复杂，需要配置的地方越来越多，配置存放的地方也越来越多的时候，为了可以方便统一管理配置，这时候就可以引入[Nacos](https://nacos.io/)。

- 当我们的服务越来越多，有些时候部署需要做到弹性伸缩，需要用到一些负载策略的时候，可以引入[Nacos](https://nacos.io/)进行服务的治理。

### 生态

- Webman-naocs是基于PHP开发的[Webman](https://github.com/walkor/webman)插件生态下的Nacos客户端；

- 本项目来源于 [Tinywan/nacos](https://www.workerman.net/plugin/25)，对 Tinywan 表示感谢！区别于 [Tinywan/nacos](https://www.workerman.net/plugin/25)，[workbunny/webman-nacos](https://github.com/workbunny/webman-nacos)在配置监听和实例注册上有不同的实现方式，其他的使用方法与之无异；

- Webman-nacos使用的主要组件：
    - [workerman/http-client](https://github.com/walkor/http-client)
    - [guzzlehttp/guzzle](https://github.com/guzzle/guzzle/)

## 安装
~~~
composer require workbunny/webman-nacos
~~~

## 使用

### 1. Nacos文档地址

- **[Nacos Open-API文档](https://nacos.io/zh-cn/docs/open-api.html)**

### 2. 服务的使用

1. 创建连接通道
```php
// 使用默认通道【建议使用】
$client = \Workbunny\WebmanNacos\Client::channel();
// 使用channel.php中键名为ABC的连接配置
$client = \Workbunny\WebmanNacos\Client::channel('ABC');
```
**注：该方案默认使用channel.php中的连接配置，支持多通道连接，建议使用！**

**注：获取一个不存在的配置信息时，会抛出一个 NacosException 异常。**

```php
// 旧版保留方式【不建议使用】
$client = new Workbunny\WebmanNacos\Client();
```
**注：该方案默认使用app.php中的连接配置，后续会将其移除，不建议继续使用！**

2. 以监听配置举例
```php
   
$client = \Workbunny\WebmanNacos\Client::channel();
// 异步非阻塞监听
// 注：在webman中是异步非阻塞的，不会阻塞当前进程
$response = $client->config->listenerAsyncUseEventLoop();
// 异步阻塞监听
// 注：在webman中是异步阻塞的，返回的是Guzzle/PromiseInterface，配合wait()可以多条请求并行执行；
//     请求会阻塞在 **wait()** 直到执行完毕；详见 **ConfigListernerProcess.php** 
$response = $client->config->listenerAsync();
// 同步阻塞监听
$response = $client->config->listener();
```

3. 断开连接
```php
$client = \Workbunny\WebmanNacos\Client::channel();
$client->cancel();
```

#### 配置说明：

1. app.php 为基础配置；
2. channel.php 为连接通道配置；
3. process.php 为默认启动进程配置；

### 3. Nacos相关服务
#### 配置相关：

- 监听配置 

webman-nacos组件默认会启动一个名为 **config-listener** 的进程，用于监听在配置文件
**plugin/workbunny/webman-nacos/app.php** 中 **config_listeners**
下的配置内容。

如果想自行掌控调用，可以使用如下服务：
```php
$client = \Workbunny\WebmanNacos\Client::channel();

// 异步非阻塞监听
// 注：在webman中是异步非阻塞的，不会阻塞当前进程
$response = $client->config->listenerAsyncUseEventLoop();

// 异步阻塞监听
// 注：在webman中是异步阻塞的，返回的是Guzzle/PromiseInterface，配合wait()可以多条请求并行执行；
//     请求会阻塞在 **wait()** 直到执行完毕；详见 **ConfigListernerProcess.php** 
$response = $client->config->listenerAsync();

# 同步阻塞监听
$response = $client->config->listener();
```

- 获取配置

```php
$client = \Workbunny\WebmanNacos\Client::channel();
$response = $client->config->get('database', 'DEFAULT_GROUP');
if (false === $response) {
    var_dump($nacos->config->getMessage());
}
```

- 提交配置

```php
$client = \Workbunny\WebmanNacos\Client::channel();
$response = $client->config->publish('database', 'DEFAULT_GROUP', file_get_contents('.env'));
if (false === $response) {
    var_dump($nacos->config->getMessage());
}
```

- 移除配置

```php
$client = \Workbunny\WebmanNacos\Client::channel();
$response = $client->config->delete('database', 'DEFAULT_GROUP');;
if (false === $response) {
    var_dump($nacos->config->getMessage());
}
```

#### 服务相关：

- 实例注册

webman-nacos组件默认会启动一个名为 **instance-registrar** 的进程，用于注册在配置文件
**plugin/workbunny/webman-nacos/app.php** 中 **instance-registrar**
下的配置内容。

如需动态注册实例，请使用：

```php
$client = \Workbunny\WebmanNacos\Client::channel();
$response = $client->instance->register('127.0.0.1', 8848, '猜猜我是谁', [
    'groupName' => 'DEFAULT_GROUP',
]);
if (false === $response) {
    var_dump($nacos->config->getMessage());
}
```

- 移除实例

```php
$client = \Workbunny\WebmanNacos\Client::channel();
$response = $client->instance->delete('猜猜我是谁', 'DEFAULT_GROUP', '127.0.0.1', 8848, []);
if (false === $response) {
    var_dump($nacos->config->getMessage());
}
```

- 实例列表

```php
$client = \Workbunny\WebmanNacos\Client::channel();
$response = $client->instance->list('猜猜我是谁', []);
if (false === $response) {
    var_dump($nacos->config->getMessage());
}
```

**注：实例与服务的区别请参看Nacos文档；**

#### 其他：

- **具体使用参数都在源码内已标注，使用方法很简单，参考Nacos官方文档即可；**

- **后缀为Async的方法是Guzzle异步请求，在当前业务执行周期中阻塞，多个请求可并行执行；**

- **后缀为AsyncUseEventLoop的方法是workerman/http-client异步请求，在当前业务周期中非阻塞；**

```php
$client = \Workbunny\WebmanNacos\Client::channel();

# 配置相关接口
$client->config;

# 鉴权相关接口
$client->auth;

# 实例相关接口
$client->instance;

# 系统相关接口
$client->operator;

# 服务相关接口
$client->service;
```


## 说明

- 目前这套代码在我司生产环境运行，我会做及时的维护，**欢迎 issue 和 PR**；

- 对于不知道Nacos有什么用的/在什么时候用，可以参考这篇文章 [Nacos在我司的应用及SOA初尝](https://www.workerman.net/a/1339);

- nacos的配置监听项采用了服务端长轮询，有点类似于stream_select，当配置没有改变的时候，会阻塞至请求结束；但当配置有变化时候，会立即返回其配置dataId；这里我的做法是开启一个Timer对配置进行监听，定时器间隔与长轮询最大阻塞时间一致:

    1. ConfigListenerProcess使用Guzzle的异步请求对配置监听器进行请求处理，
       onWorkerStart中的Guzzle客户端会阻塞请求，workerman status中会显示BUSY状态；

    2. AsyncConfigListenerProcess使用wokerman/http-client异步请求对配置监听
       器进行请求，workerman/http-client使用了workerman的event-loop进行I/O处理，
       不会阻塞当前进程，推荐使用；

- 所有的配置同步后会触发 **workerman reload** 对所有进程进行重载，保证了config的刷新，包括已经在内存中的各种单例，如 数据库连接、Redis连接等，保证即时将配置传达至需要的业务点；

- 使用配置方式不必改变，使用webman的config()即可，降低封装组件的心智负担;


## 其他
- **[趣谈程序的演变过程](https://www.workerman.net/a/1341)**
- **[Nacos在我司的应用及SOA初尝](https://www.workerman.net/a/1339)**
- **[Nacos Open-API文档](https://nacos.io/zh-cn/docs/open-api.html)**
- **[workbunny/webman-nacos 项目地址](https://github.com/workbunny/webman-nacos)**
