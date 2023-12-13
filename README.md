# Laravel Schedule Control
[![Latest Stable Version](https://poser.pugx.org/acdphp/laravel-schedule-control/v)](https://packagist.org/packages/acdphp/laravel-schedule-control)

Use this if you need to:
- :white_check_mark: Stop and start scheduled commands without redeploying.
- :white_check_mark: Execute commands without logging into server console.
- :white_check_mark: Keep the visibility, control, and reviewability of the schedule configurations in your codebase.

## Installation
1. Install the package
    ```shell
    composer require acdphp/laravel-schedule-control
    ```

2. Run the migration.
    ```shell
    php artisan migrate
    ```

3. Update your Console Kernel to extend `Acdphp\ScheduleControl\Console\Kernel` instead of `Illuminate\Foundation\Console\Kernel`.
    ```php
    namespace App\Console;
    
    use Acdphp\ScheduleControl\Console\Kernel as ConsoleKernel;
    
    class Kernel extends ConsoleKernel
    ...
    ```
   
## Config
You may override the config by publishing it.
```shell
php artisan vendor:publish --provider="Acdphp\ScheduleControl\ServiceProvider"
```

You may also just define environment variables if you don't need to publish the config.
- Disable command execution in the dashboard.
```dotenv
SCHEDULE_CONTROL_ALLOW_EXECUTE_CMD=false
```

- Add prefix to routes.
```dotenv
SCHEDULE_CONTROL_URL_PREFIX=your-prefix
```

## Dashboard
After installation, you may access the dashboard via the `/schedule-control` route.

### Authorization
By default, you will only be able to access this dashboard in the local environment. However, you may specify authorization for non-local environments by defining `viewScheduleControl` gate, typically within the `boot` method of the `App\Providers\AuthServiceProvider` class.

```php
public function boot(): void
{
    Gate::define('viewScheduleControl', function (User $user) {
        // return true or false
    });
}
```

## License
The MIT License (MIT). Please see [License File](LICENSE) for more information.
