# Widepaycash SMS Notification Channel

This package makes it easy to send SMS notifications using [Widepaycash SMS](https://widepaycash.com) API the laravel way

## Contents

- [Installation](#installation)
	- [Setting up the Fcm service](#setting-up-the-Fcm-service)
- [Usage](#usage)
	- [Available Message methods](#available-message-methods)
- [Changelog](#changelog)
- [Testing](#testing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)


## Installation

Install this package with Composer:

```bash
composer require kgoofori/widepay-sms-notification
```

### Setting up the FCM service

You need to register for a API id and key from Widepaycash for your application from here: 
[https://widepaycash.com/](https://widepaycash.com)

Once you've registered and generated ypu API id and key, add the API id and key to your configuration in `config/broadcasting.php`

```php
'connections' => [
    ....
    'widepay_sms' => [
            'id' => env('WIDEPAY_ID', ''),
            'key' => env('WIDEPAY_KEY', ''),
        ]
    ...
]
```

## Usage

You can now send SMS notifications via Widepay API by creating a `WidepaySmsMessage`:

```php
...
use NotificationChannels\WidepaySms\WidepaySmsChannel;
use NotificationChannels\WidepaySms\WidepaySmsMessage;

class UserActivated extends Notification
{
    public function via($notifiable)
    {
        return [WidepaySmsChannel::class];
    }

    public function toWidepaySms($notifiable)
    {
        return (new WidepaySmsMessage)
            ->sender('WidepaySms')
            ->message('Testing message')
            ->recipient('23324XXXXXXX'); //optional if routeNotificationForWidepaySms() is set on notifiable model
    }
}
```

You may have to set a `routeNotificationForWidepaySms()` method in your notifiable model. For example:

```php
class User extends Authenticatable
{
    use Notifiable;

    ....

    /**
     * Specifies the user's phone
     *
     * @return string
     */
    public function routeNotificationForWidepaySms()
    {
        return $this->phone;
    }
}
```

Once you have that in place, you can simply send a notification to the user via

```php
$user->notify(new AccountActivated);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Security

If you discover any security related issues, please email kgoofori@gmail.com instead of using the issue tracker.

## Credits

- [Gideon Ofori](https://github.com/kgoofori/)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
