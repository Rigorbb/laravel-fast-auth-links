# Laravel Fast Auth Links
---
A package that allows you to add a special hash that temporarily authorizes the user. Without a database.

## Installation

Begin by installing this package through Composer.

```bash
composer require rigorbb/laravel-fast-auth-links
```

## Usage

You can use these helper methods to add a hash to your links


```php
auth_link_hourly($link, $user); //add hash with hourly active
auth_link_daily($link, $user); //add hash with daily active
auth_link_monthly($link, $user); //add hash with monthly active
```

And add HandleFastAuthLink::class middleware in your Kernel.php file.

That's all! Now if user goes through this link with a hash and the hash is active, it will be automatically authorized. 
