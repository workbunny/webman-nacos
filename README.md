# webman-nacos

[webman的nacos-client插件包](https://www.workerman.net/plugin/50)

---
## 简介
- 目前这套代码在我司生产环境运行，我会做及时的维护；

- 整体代码与Tinywan/nacos差不多，但实现思路不一样，主要体现在配置的同步处理上；

- nacos的配置监听项采用了服务端长轮询，有点类似于stream_select，当配置没有改变的时候，会阻塞至请求结束；但当配置有变化时候，会立即返回其配置dataId；
    
- 这里我的做法是开启一个Timer对配置进行监听，定时器间隔与长轮询最大阻塞时间一致:
        
    1. ConfigListenerProcess使用Guzzle的异步请求对配置监听器进行请求处理，
        onWorkerStart中的Guzzle客户端会阻塞请求，workerman status中会显示BUSY状态；

    2. AsyncConfigListenerProcess使用wokerman/http-client异步请求对配置监听
        器进行请求，workerman/http-client使用了workerman的event-loop进行I/O处理，
        不会阻塞当前进程，推荐使用；

- 所有的配置同步后会调用workerman::reloadAllWorkers对所有进程进行重载，保证了config的刷新，包括已经在内存中的各种单例，如 数据库连接、Redis连接等，保证即时将配置传达至需要的业务点；

## 安装
~~~
composer require workbunny/webman-nacos
~~~

## 使用

### 1. Nacos文档地址

**[Nacos Open-API文档](https://nacos.io/zh-cn/docs/open-api.html)**

### 2. 代码示例

**1. 以获取配置举例**

~~~
$client = new Workbunny\WebmanNacos\Client();
$response = $client->config->get('database', 'DEFAULT_GROUP');
if (false === $response) {
    var_dump($nacos->config->getMessage());
}
~~~

**2. 其他接口**

~~~
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
~~~

**3. 后缀为Async的方法是Guzzle异步请求，在workerman的on回调中依旧是阻塞，只是多个请求可以并发执行**

**4. 后缀为AsyncUseEventLoop的方法是workerman/http-client异步请求，在workerman的on回调中是非阻塞的**

### 3. 说明

1. 整体使用除了配置监听同步部分与 **[Tinywan/nacos](https://www.workerman.net/plugin/25)** 没有区别，**对 Tinywan 表示感谢**！

2. workbunny/src/AsyncConfigListenerProcess 为异步非阻塞监听器

3. workbunny/src/ConfigListenerProcess 为异步阻塞监听器，在同一个进程的Timer周期中会阻塞下一个Timer周期

4. 配置监听器会reload进程，保证配置即时触达业务点，刷新单例或者常驻的连接

5. 使用配置方式不必改变，使用webman的config即可，降低封装组件的心智负担

