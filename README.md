# laravel-model-hash

Automatically create short hashes for your Laravel models.

## Why?

Database IDs in URLs such as [stackoverflow.com/users/12280](https://stackoverflow.com/users/12280) never quite feel ok to me

- having the index of an entity allows website size and growth to be estimated
- accessing something that shouldn't be accessible is just a careless developer and a curious visitor away

But at the same time, I didn't want to completely replace the database ID with a UUID, as some packages do - as I was nervous about how this might affect performance.

laravel-model-hash allows you to generate a short, random hash when an item is created while still using auto-incrementing IDs for database relationships.

### How it works

This package provides a trait which - when attached to a model - creates a random string hash when new instances of the model are created.

The hash is created by shuffling an *alphabet*, taking the first *length* characters, and checking for uniqueness in the database (up to a maximum number of times). If a unique hash wasn't found within the *maximum attempts*, an exception of type `UniqueHashNotFoundException` will be thrown and the model instance will not be saved.

The package is configured either globally using a config file, or by defining model-specific properties. 

## Installation

You can install the package via Composer:

```
composer require adamhopkinson/laravel-model-hash
```

If you would like to tweak any of the package configuration, you must first publish the config file:

```
php artisan vendor:publish --provider="AdamHopkinson\LaravelModelHash\LaravelModelHashServiceProvider"
```

## Usage

To add a hash to model, add the `LaravelModelHash` trait:

```
use AdamHopkinson\LaravelModelHash\Traits\ModelHash;

class MyModel extends Model
{
    use ModelHash;
    //...
}
``` 

And create a migration to add a field to store the hash - by default, the field is called `hash` and has a length of 5:

```
$table->string('hash', 5);
```

When new instances of the model are created, they will be assigned a random string.

## Configuration

There are three configurable parts to the hash:

- the alphabet used (ie the set of allowed characters)
- the name of the hash field
- the length of the hash

And two other configuration properties

- the maximum number of attempts to find a hash which isn't already present in the database before an exception is thrown
- whether to automatically use the hash in route model binding

Each of these is set in the default configuration, which can be overridden in `/config/laravelmodelhash.php` (this must first be published - see the Installation section for details).

If you want to change these settings per model, they can be changed by adding the following model properties:

```
protected $hashName; // string
protected $hashLength; // int
protected $hashAlphabet; // string
protected $hashMaximumAttempts; // int
protected $useHashInRoutes; // boolean
```

## Thanks

I've gotten this far thanks to the following:

- [John Braun](https://github.com/Jhnbrn90) and his excellent guide to [Laravel Package Development](https://laravelpackage.com/)
- [Jeff Way](https://github.com/JeffreyWay) of [Laracasts](https://laracasts.com/) fame
- the amazing Laravel community