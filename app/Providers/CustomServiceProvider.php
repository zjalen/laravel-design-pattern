<?php

namespace App\Providers;

use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserService;
use App\Services\UserServiceInterface;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class CustomServiceProvider extends ServiceProvider
{
    public $bindings = [

    ];

    public $singletons = [
        UserRepositoryInterface::class => UserRepository::class,
        UserServiceInterface::class => UserService::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
//        $this->app->bind(UserServiceInterface::class, UserService::class);
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

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function bindings() {

    }
}
