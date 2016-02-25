# Laravel PatrolServer

A Laravel 4/5 package that integrates the [PatrolServer PHP SDK](https://github.com/PatrolServer/patrolsdk-php) in your project.

## Requirements
PHP 5.3.3 and later.

Laravel 5.x.

## Installation
You can install the package using [Composer](https://getcomposer.org/). You can install it by running this command in your root Laravel folder:

```
composer require patrolserver/laravel-patrol
```

## Laravel
The package provides a facade for easy integration.

Simply add the ``PatrolServer\Patrol\PatrolServiceProvider`` provider to the providers array in ``config/app.php``.

```php
'providers' => [
  ...
  'PatrolServer\Patrol\PatrolServiceProvider',
],
```

And add the ``Patrol`` alias to access the facade:

```php
'aliases' => [
  ...
  'Patrol' => 'PatrolServer\Patrol\Facades\Patrol',
],
```

## Config file
You have to enter the API key and secret in the config file. To publish this file:
```
php artisan vendor:publish --provider="PatrolServer\Patrol\PatrolServiceProvider"
```
When the config is succesfully published, a file named ``patrol.php`` will be available in the config folder of your application, you can then enter your API credentials and various other options in this config file.

## Examples

### SDK
You can access your data with the Patrol facade, which is a wrapper function for the SDK object. The available options and more information can be found at the [PatrolServer API Documentation](https://api.patrolserver.com/) page.

```php
$user = Patrol::user();

$servers = Patrol::servers();

foreach ($servers as $server) 
{
    Log::info($server);
}
```

### Webhook
Webhooks are real time events to alert you whenever an event occurs in PatrolServer. For example, your server finished scanning and has new issues. A webhook will be triggered and as a developer, you can now interact based on this new information.


In your ``routes.php`` file, add the following rule:
```php
Route::post('patrolserver/webhook', 'PatrolServerController@webhook');
```
In your controller, you can now define the webhook function. This is a quick example on how to use the SDK:
```php
<?php App\Http\Controllers;

use Illuminate\Routing\Controller;
use Patrol;
use Log;

class PatrolServerController extends Controller 
{
	public function webhook() 
	{
		Patrol::webhook('new_server_issues', function ($event) 
		{
    		$server_id = array_get($event, 'server_id');

			if (!$server_id)
				Log::info('Server could not be found');

			$server = Patrol::get('servers/' . $server_id);
			Log::info($server);
		});

		Patrol::webhook('test', function ($event) 
		{
			Log::info($event);
		});
	}
}
```

### Auto-update Laravel dependencies
This Laravel package provides an easy method to update your Laravel dependencies when they become outdated. PatrolServer will send a command to your Laravel installation the moment packages become outdated and will execute the ``composer update`` command in your root folder.


#### 1. Run the command through a cronjob on a daily basis

The package contains a command to send all the installed Laravel dependencies to PatrolServer by simply running ``php artisan patrol:run`` in the terminal. If you have a cronjob running which, powers the [Scheduler](https://laravel.com/docs/master/scheduling), all you have to do is add the following line to schedule function in your Kernel file.

```php
protected function schedule(Schedule $schedule)
{
    // This command will scan your modules on a daily basis, at midnight.
	$schedule->command('patrol:run')->dailyAt('00:00');
}
```

#### 2. Enable default webhooks

Open the config file and set the ``enable_webhooks`` to true. This will enable the URL endpoint (http://mywebsite.com/patrolserver/webhook), which contains the auto update code.

#### 3. Add the endpoint to your account

Login to [https://app.patrolserver.com](https://app.patrolserver.com), and navigate to the API page. Your webhook URL will be in the format of ``http://mywebsite.com/patrolserver/webhook``. Add your URL to the webhook section and you're good to go.

Once you configured the auto updating correctly, the following events will happen: once a day, at midnight, your server will send the software packages and their versions to the PatrolServer scanner. Once the server is scanned, a webhook will be triggered to your patrolserver/webhook URL and when packages are outdated, the package will trigger composer to update these.

[![Analytics](https://ga-beacon.appspot.com/UA-65036233-1/PatrolServer/laravel-patrol?pixel)](https://github.com/igrigorik/ga-beacon)
