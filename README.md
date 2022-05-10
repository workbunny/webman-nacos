# webman-nacos


    webman的nacos-client插件包

---
### 描述

    目前这套代码在我司生产环境运行，我会做及时的维护；

    整体代码与Tinywan/nacos差不多，但实现思路不一样，主要体现在配置的同步处理上；

    nacos的配置监听项采用了服务端长轮询，有点类似于stream_select，当配置没有改变的时候，
    会阻塞至请求结束；但当配置有变化时候，会立即返回其配置dataId；
    
    这里我的做法是开启一个Timer对配置进行监听，定时器间隔与长轮询最大阻塞时间一致；使用
    Guzzle的异步请求对配置监听器进行请求处理；

### 注：
    1. 这里存在个问题，假设定时器间隔30秒，长轮询30秒，但在第5秒有配置改变时，那么就会存在
    25秒的空置时间无法监听，原本打算开启多个Timer来监听，但同一个进程内多个Timer是阻塞且顺
    序执行的，如果多开过多进程又不太好管理，暂时先这样；
    
    2. 为了不侵入整体业务代码且方便使用，插件加载了helpers.php，重写了config()函数，配合
    ConfigListener可以合理的重载Config配置项，这样可以保证业务端在使用配置的时候无需关注
    nacos，配置的同步操作全权交给ConfigListener；

    3. 由于PHP取消了function的重写，所以在该项目中采取runkit7拓展来动态重写webman注册的
    config()函数，如果项目中没有引入runkit7拓展，还可以采取一下两种方案处理：

        1. 将所有使用config()的文件头中加入use Workbunny\WebmanNacos\config;

        2. 手动重写webman的support\helpers.php的config()，
            return Workbunny\WebmanNacos\config($key, $default);


