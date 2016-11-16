About This [![](https://travis-ci.org/Clarence-pan/phpdotenv-dotphp)](https://travis-ci.org/Clarence-pan/phpdotenv-dotphp)
==========

This is a optimize for `vlucas/phpdotenv` in PHP fastcgi application. 

`vlucas/phpdotenv` is excellent. However we found it takes a lot time to read and parse `.env` file in fastcgi.
Thus this library comes out to use native PHP file as environment configuration file.

Advantages:

1. `.env.php` can be cached by opcache. It takes much less time than `.env`. 
2. Any PHP code can be in `.env.php`. You can do more things (i.e. include files, calculate something, concatenating strings...).

Disadvantages:

1. `.env.php` cannot be loaded by other languages like JavaScript, Java, Ruby... But who cares. :)

Install
=======

Composer is suggested to use:

```
composer require clarence/phpdotenv-dotphp
```

You can also download the zip file and write your own autoloader.


.env.php
========

You can use everything of PHP in `.env.php`. But it is suggested to do as less as possible. An array must be returned:

```php
// A simple .env.php

return [
    "APP_ENV" => "local",
    "APP_DEBUG" => true,
    // ....
];

```

It is advised that:

1. Do **NOT** add `.env.php` file to your version system (i.e. git/svn).
2. `.env.php` should only store configurations which diffs and which are secrets.


Usage
=====

It is very similar with `vlucas/phpdotenv`. After you have created a `.env.php` file, you can use the following code to load it:

```php

// assume .env.php is in current directory (__DIR__)
(new \Clarence\DotEnv\DotEnv(__DIR__))->load();

```

You can use `env($key)` function to get the value:

```

echo env('APP_ENV'); // => local

```

All environment variables are also stored in `$_ENV`, `$_SERVER`. You can also use these super globals.  


Note: the default behavior of `->load()` is not to override existing environment variables. If you wanna override, please use `->load(true)` or `->overload()`.
