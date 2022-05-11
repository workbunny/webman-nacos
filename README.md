# webman-nacos


    webman的nacos-client插件包

---
### 描述

    - 目前这套代码在我司生产环境运行，我会做及时的维护；

    - 整体代码与Tinywan/nacos差不多，但实现思路不一样，主要体现在配置的同步处理上；

    - nacos的配置监听项采用了服务端长轮询，有点类似于stream_select，当配置没有改变的时候，
    会阻塞至请求结束；但当配置有变化时候，会立即返回其配置dataId；
    
    - 这里我的做法是开启一个Timer对配置进行监听，定时器间隔与长轮询最大阻塞时间一致:
        
        1. ConfigListenerProcess使用Guzzle的异步请求对配置监听器进行请求处理，
        onWorkerStart中的Guzzle客户端会阻塞请求，workerman status中会显示BUSY状态；

        2. AsyncConfigListenerProcess使用wokerman\http-client异步请求对配置监听
        器进行请求，workerman\http-client使用了workerman的event-loop进行I/O处理，
        不会阻塞当前进程，推荐使用；

    - 所有的配置同步后会调用workerman::reloadAllWorkers对所有进程进行重载，保证了config
    的刷新，包括已经在内存中的各种单例，如 数据库连接、Redis连接等，保证即时将配置传达至需要
    的业务点；
        

### 注：
1. 整体使用除了配置监听同步部分与 [Tinywan/nacos](https://www.workerman.net/plugin/25) 没有区别


