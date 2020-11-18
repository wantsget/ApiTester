# ApiTester 接口测试工具

最近使用HyperF的过程中，发现HyperF在启用中间件和JsonRpc之后就很难执行接口测试了。所以就手撸了这么一个简易的接口测试工具。

PHP环境需要 >=7.2，如果在windows上可以直接装一个PHPStudy。

# 使用方法

## 1. 创建测试类

在根目录下执行```php tester.php create demo```，程序会自动生成一个对应的```ApiTester\Cases\Demo```测试类。

在测试类中编写测试用例，测试类已经集成了Guzzle和PhpUnit，可以直接使用以下方法发送请求：

```php
$this->post('/xxx/xxx', ['param1' => 'value1', 'param2' => 'value2']);
$this->post('/xxx/xxx', ['param1' => 'value1'], ['referer' => 'http://www.baidu.com']);
$this->get('/xxx/xxx', ['param1' => 'value1']);
```

程序对请求结果已经解码成数组了，如不满足于ApiTester默认的封装，可以使用```$this->client```来获取Guzzle类调用对应的方法。

## 2. 启用测试类

在根目录的```config/cases.php```中添加```ApiTester\Cases\Demo```测试类

```php
<?php

declare(strict_types=1);

return [
    ApiTester\Cases\Case1::class,
    ApiTester\Cases\Case2::class,
    ApiTester\Cases\Demo::class,
];
```

程序会按照cases.php中定义的先后顺序来执行测试。

## 3. 执行测试

在根目录下执行```php tester.php run```或者```php tester.php run <任务名>```就可以执行所有或者单个任务。

执行顺序为测试类中的方法顺序。比如如下测试类的方法执行顺序就是 ```testA``` 、```testC```、```testB```：

```php
<?php

declare(strict_types=1);

namespace ApiTester\Cases;

class Demo extends HttpTestCase
{
    public function testA() {}
    public function testC() {}
    public function testB() {}
}
```