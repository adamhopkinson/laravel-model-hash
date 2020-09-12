<?php

namespace AdamHopkinson\LaravelModelHash\Traits;

use AdamHopkinson\LaravelModelHash\Exceptions\InvalidCharactersInAlphabet;
use AdamHopkinson\LaravelModelHash\Exceptions\UniqueHashNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait ModelHash
{
    public static function bootModelHash()
    {
        // call this function when the _creating_ model event is fired
        static::creating(function ($model) {
            $model->makeAndAssignHash($model);
        });
    }

    public function makeAndAssignHash(Model $model = null): bool
    {
        $model = ($model == null) ? $this : $model;

        // get the name of the property to use for the hash
        $name = $model->getHashName();

        if ($model->{$name} != null) {
            return true;
        }

        // get the length of the hash
        $length = $model->getHashLength();

        // get the alphabet to use for the hash
        $alphabet = $model->getHashAlphabet();

        // get the maximum number of attempts to check for uniqueness
        $maxAttempts = $model->getHashMaximumAttempts();

        // keep a count of the number of attempts
        $attempts = 0;

        // keep a log of attempted hashes, to avoid unnecessary database queries
        $hashHistory = [];

        do {
            $attempts++;

            // make a hash by shuffling the alphabet and taking the first x characters
            $hash = substr(str_shuffle($alphabet), 0, $length);

            // see if we've already looked for a model with this hash
            $alreadyTried = in_array($hash, $hashHistory);

            if (!$alreadyTried) {
                // if it's a new hash, add it to the stack
                array_push($hashHistory, $hash);

                // and look for any existing models with this hash
                $hits = $model::where($name, $hash)->count();

                if ($hits == 0) {
                    // if it's totally new, assign it
                    $model->{$name} = $hash;
                    //$model->save();
                }
            }
        } while (($alreadyTried || $hits > 0) && $attempts < $maxAttempts);

        if ($model->{$name} == null) {
            // if we didn't manage to find a hash, throw an error to prevent the instance being created with a hash
            throw new UniqueHashNotFoundException(sprintf(
                'Could not find a hash for new %s after %d/%d attempts. Hash name is %s; required hash length is %d; alphabet length is %d; last hash attempted was %s - consider increasing the maximum number of attempts, the length of either the hash or the alphabet size.',
                get_class($model),
                $attempts,
                $maxAttempts,
                $name,
                $length,
                strlen($alphabet),
                $hash
            ));
        }

        return true;
    }

    /*
     * get the name of the property to use for the hash
     * first check to see if the model has a property
     * then fallback to the default value from config
     */
    public function getHashName()
    {
        if (property_exists($this, 'hashName')) {
            return $this->hashName;
        } else {
            return config('laravelmodelhash.default_name');
        }
    }

    /*
     * get the length of the hash
     * first check to see if the model has a property
     * then fallback to the default value from config
     */
    public function getHashLength()
    {
        if (property_exists($this, 'hashLength')) {
            return $this->hashLength;
        } else {
            return config('laravelmodelhash.default_length');
        }
    }

    /*
     * get the alphabet to use for the hash
     * first check to see if the model has a property
     * then fallback to the default value from config
     */
    public function getHashAlphabet()
    {
        if (property_exists($this, 'hashAlphabet')) {
            if (Str::contains($this->hashAlphabet, ['?', '#', ':', '/', '?', '#', '[', ']', '@', '!', '$', '&', '(', ')', '*', '+', ',', ';', '='])) {
                throw new InvalidCharactersInAlphabet('Invalid Characters have been found in your alphabet. These must be removed before continuing.');
            }

            return $this->hashAlphabet;
        } else {
            return config('laravelmodelhash.default_alphabet');
        }
    }

    /*
     * get the maximum number of attempts to check for uniqueness
     * first check to see if the model has a property
     * then fallback to the default value from config
     */
    public function getHashMaximumAttempts()
    {
        if (property_exists($this, 'hashMaximumAttempts')) {
            return $this->hashMaximumAttempts;
        } else {
            return config('laravelmodelhash.maximum_attempts');
        }
    }

    /*
     * get the use_hash_in_routes boolean
     * first check to see if the model has a property
     * then fallback to the default value from config
     */
    public function getUseHashInRoutes()
    {
        if (property_exists($this, 'useHashInRoutes')) {
            return $this->useHashInRoutes;
        } else {
            return config('laravelmodelhash.use_hash_in_routes');
        }
    }

    public function getRouteKeyName()
    {
        if (!$this->getUseHashInRoutes()) {
            return 'id';
        } else {
            // get the name of the property to use for the hash
            // first check to see if the model has a setter
            // then fallback to the default value from config
            if (method_exists($this, 'getHashName')) {
                $name = $this->getHashName();
            } else {
                $name = config('laravelmodelhash.default_name');
            }

            return $name;
        }
    }
}
