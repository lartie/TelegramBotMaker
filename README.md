# Telegram Bot Maker for Laravel

## Install

```sh
composer require "lartie/telegram-bot-maker"
```

```php
'providers' => [
    ...
    LArtie\TelegramBotMaker\TelegramBotMakerServiceProvider::class, 
]
```

```sh
php artisan vendor:publish
```

## Usage

```sh
php artisan make:telegram-bot NameBot
```

# License
MIT