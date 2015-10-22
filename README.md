# Laravel PatrolServer

A Laravel 4/5 package that integrates the [PatrolServer PHP SDK](https://github.com/PatrolServer/patrolsdk-php) in your project.

## Requirements
PHP 5.3.3 and later.

## Installation
You can install the package using [Composer](https://getcomposer.org/). You can install it by running this command in your root Laravel folder:

``
composer require patrolserver/laravel-patrol
``

## Laravel
The package provides a facade for easy integration.

Simply add the ``PatrolServer\PatrolSdk\PatrolSdkServiceProvider`` provider to the providers array in ``config/app.php`` (when using Laravel 4, use app/config.php)

```php
'providers' => [
  ...
  'PatrolServer\PatrolSdk\PatrolSdkServiceProvider',
],
```

And add the ``PatrolSdk`` alias to access the facade:

```php
'aliases' => [
  ...
  'PatrolSdk' => 'PatrolServer\PatrolSdk\Facades\PatrolSdk',
],
```

## Config file
You have to enter the API key and secret in the config file. To publish this file:
```
// Laravel 5, published at config/patrol-sdk.php
php artisan vendor:publish --provider="PatrolServer\PatrolSdk\PatrolSdkServiceProviderLaravel5"

// Laravel 4, published at app/config/packages/patrolserver/laravel-patrol/config.php
php artisan config:publish patrolserver/laravel-patrol
```

## Example
In your ``routes.php`` file, add the following rule:
```php
Route::post('patrolserver/webhook', 'PatrolServerController@webhook');
```
In your controller, you can now define the webhook function. This is a quick example on how to use the SDK:
```php
<?php App\Http\Controllers;

use Illuminate\Routing\Controller;
use PatrolSdk;

class PatrolServerController extends Controller {
	public function webhook() {
		PatrolSdk::webhook('webhook.new_server_issues', function ($event) {
    		$server_id = array_get($event, 'server_id');

			if (!$server_id) {
				Log::info('Server could not be found');
			}

			$server = PatrolSdk::get('servers/' . $server_id);
			Log::info($server);
		});

		PatrolSdk::webhook('webhook.test', function ($event) {
			Log::info($event);
		});
	}
}
```


[![Analytics](https://ga-beacon.appspot.com/UA-65036233-1/PatrolServer/laravel-patrol?pixel)](https://github.com/igrigorik/ga-beacon)
