# 一个 `Laravel/Lumen` 项目的分层实践

## 简介
本项目主要介绍一种更符合规范设计模式的 `Laravel/Lumen` 项目分层编程样例。

许多 `php` 程序员——尤其是新手，面对框架提供给我们的琳琅满目的功能，各种便捷的调用方法，很容易被灵活调用方法所打乱思路，导致各个模块、类依赖过于严重，不但不方便测试，后期升级、维护、重构等也会十分困难。

本文参考基于设计原则（SOLID、KISS、YANGI、DRY、LOD）以及设计模式（常见的 23 种设计模式），结合一些实践中常见的分层方式，在 `Laravel` 框架的基础上，演示一种相对完善规范的项目设计例子，希望能给各位提供一个参考思路，帮助大家规范自己的项目。

## TODO

- [x] MVC 分层，包含 Repository 及 Service 子级分层
- [x] 用接口依赖注入解耦
- [x] Request 分离验证规则
- [x] Exceptions 全局异常及业务异常统一格式
- [x] 单元测试示例
- [ ] 设计原则示例
- [ ] 设计模式示例

## 框架代码结构

### routes 路由

因为 `Lumen` 一般只面向 api 请求处理，因此不必区分 `web` 与 `api`，但如果路由组特别多，或需要更明确的分组时，例如区分 api 版本，也可以拆分成多个文件以供使用，注意新增加的文件，需要在 `bootstrap/app.php` 中添加或使用 `ServiceProvider` 注册。

### Http 请求控制

#### Middleware 中间件

中间件的作用是对 api 进行拦截，执行身份鉴权等操作。

#### ApiResponse 格式化 Api 输出规范

该类继承自 `Illuminate\Support\Facades\Response`，实现两个静态方法，`success` 表示返回成功的 api 请求结果，`fail` 表示返回失败的 api 请求结果。

`Lumen` 中默认不开启 `Facede` 门面类，推荐直接在 `Http` 中定义一个类 `ApiResponse`；

```php
<?php


namespace App\Http;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

/**
 * Notes:
 * User: jialinzhang
 * DateTime: 2021/5/17 15:17
 */
class ApiResponse extends Response
{
    public static function success($data = null, $code = 1, $statusCode = 200): JsonResponse
    {
        $content = [
            'code' => $code,
            'data' => $data
        ];
        $statusCode = $statusCode ?: $code;
        return response()->json($content, $statusCode)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public static function fail($errMsg = null, $code = 400, $statusCode = 400): JsonResponse
    {
        $content = [
            'code' => $code,
            'errMsg' => $errMsg
        ];
        $statusCode = $statusCode ?: $code;
        return response()->json($content, $statusCode)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
```
`Laravel` 该项目中，演示了直接在 `CustomServiceProvider` 中使用 `macro` 方法挂载两个新的方法的使用方式：

```php
/**
 * Register services.
 *
 * @return void
 */
public function register()
{
    Response::macro('success', function ($data = null, $code = 1, $statusCode = 200) {
        $content = [
            'code' => $code,
            'data' => $data
        ];
        $statusCode = $statusCode ?: $code;
        return Response::json($content, $statusCode)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    });

    Response::macro('fail', function ($errorMessage = null, $code = 400, $statusCode = 400) {
        $content = [
            'code' => $code,
            'errMsg' => $errorMessage
        ];
        $statusCode = $statusCode ?: $code;
        return Response::json($content, $statusCode)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    });
}
```

`Response` 请求结果包含 3 个部分，其中 `code` 与 `httpCode` 公用，成功时另外包含 `data`，失败时另外包含 `errMsg`：
- `data` api 成功数据部分，表示正确请求结果的数据内容
- `code` api 状态码，用以区分返回结果的不同级别，默认正确返回 `1`，错误返回 `http` 状态码
- `httpCode` api http 状态码，根据不同的请求结果定义不同的状态吗，如 `200`、`400`、`301` 等
- `errMsg` api 失败信息，当 api 请求发生异常，未得到预期结果，返回友好的提示信息给调用方

#### Requests 请求类

该类主要定义请求的参数验证，默认实现了在验证失败时，抛出自定义的业务参数异常，该异常将被业务异常处理类所捕获，格式化异常输出结果（详见 [Business Exception](#BusinessExceptions)）。而其它类则继承自该类，只需定义验证方法和异常提示信息即可。

#### Controllers 控制器

`Controller.php` 该基类定义了两个 api 输出方法，一个 `success` 表示返回成功结果，`fail` 表示返回失败结果。其它控制器均继承自该类，按需返回信息。

### Exceptions 异常控制类

#### Handler.php 全局异常处理

`Larvavel` 当前版本中，使用了 `register` 方法来注册 `reportable` 和 `renderable` 两种不同处理逻辑，旧版本以及 `Lumen` 版本中采用的是单独的方法来处理相应逻辑，原理是一致的。

- `$dontReport` 用以记录无需记录日志的异常类，如 `HttpException` 一般直接输出给 api 请求方，无需记录
- `report` 方法用以处理异常日志的记录，无需输出渲染
- `render` 方法用以处理需要渲染的异常，例如 `HttpException` 请求，拦截后格式化为 json 规范格式，返回给前端 api 请求方

#### <h4 id="BusinessExceptions">BusinessExceptions 业务异常</h4>

- `BaseBusinessExcpetion` 业务异常基类，其它指定类将继承自该类，并重写构造方法，以单独定义每种类的 `code`、`statusCode`、`message` 等内容
    - `report` 参见 `Handler.php`，作用相同，不同之处是只要异常类定义了 `report` 方法则直接拦截处理，将不再由 `Handler.php` 统一处理
    - `render` 同上作用
- 其它异常业务类，继承自业务异常基类，全局均可直接抛出对应的异常业务类，该异常将自动被 `BaseBusinessException@render` 方法所捕获，输出为 api json 格式

### Providers 服务提供者

#### CustomServiceProvider 自定义服务提供者

使用**接口**开发的时候，抽象接口类与实现类将完全分开，类与类一般只依赖接口，不依赖实现类，而实现类通过服务提供者来注入容器中，代替接口来实现具体逻辑。

自定义服务提供者里面就可以定义抽象类与实现类的对应关系，Lumen 容器在启动时将自动替换抽象类为实现类，达到更方便的解耦，也方便后续替换升级等。

```php
// CustomServiceProvider
public $bindings = [
    UserInterface::class => UserRepository::class
];

// Service
/**
 * @var UserInterface
 */
private $userRepository;

public function __construct()
{
    $this->userRepository = app(UserInterface::class);
}
```

### Services 业务逻辑

业务逻辑使用接口 `interface` 与实现 `implement` 分开解耦，类与类之间依赖抽象接口，而不依赖于实际实现。

### Repositories 数据逻辑类

数据逻辑主要用以控制数据库相关的增删改查业务逻辑，推荐以精确的方式，而非用模糊方法统一调用接口，如：
```php
// 不推荐
function getUser($where)
{
    return User::where($where)->first();
}
// 推荐
function getUserByName(string $name)
{
    return User::where('name', $name)->first();
}

function getUserById(int $id)
{
    return User::find($id);
}
```
推荐的方式符合**单一职责原则**，在后续业务逻辑变更时所引起的影响更小，粒度更小复用时所产生的*意外*也会更少。

## MVC 分层处理

框架处理流程为，api 请求进入到 `Laravel/Lumen` 框架，经由控制器（Controller 层）调用服务层（Service 层）进行业务处理，服务层可能需要调用其它同一层级的服务，或需要调用数据层（Repository 层）进行数据库业务调用，数据层调用数据模型（Model 层）进行数据库操作。最后将处理结果返回给 api 调用方。

其中 Service、Repository、Model 从划分上属于 MVC 中的 **Model 层**，Controller 属于 MVC 中的 **Controller 层**，api 属于前后端分离，后端只提供数据给前端，不涉及到数据展示，因此不存在 **View 层**，或者我们可以将 `ApiResponse` 中将数据格式化为符合前端要求的 json 看所是 **View 层** 的作用。

- `Controller` 控制层，负责 api 请求的业务控制，将业务请求派发给指定的服务进行处理，与官方演示代码不同，Controller 一般不直接放任何实际的业务逻辑
- `Service` 服务层，负责处理主要的业务逻辑和算法，数据处理的业务逻辑将调用数据控制层处理
- `Repository` 数据控制层，负责处理数据增删改查等与数据操作直接相关的业务逻辑
- `Model` 数据模型，Model 在 `Laravel/Lumen` 中主要负责辅助 Repository 层来进行数据库操作，它的成员变量**应该**与数据库字段一一对应，以方便在实际操作中更容易做到针对性的赋值、取值等操作，一些属性格式化等操作可以放置在 Model 中，但一般不放实际的业务逻辑

