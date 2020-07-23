# laravel-model-hash

Automatically create short hashes for your Laravel models.

## Why?

Database IDs in URLs such as [stackoverflow.com/users/12280](https://stackoverflow.com/users/12280) never quite feel ok to me

- having the index of an entity allows website size and growth to be estimated
- accessing something that shouldn't be accessible is just a careless developer and a curious visitor away

But at the same time, I didn't want to completely replace the database ID with a UUID, as some packages do - as I was nervous about how this might affect performance.

laravel-model-hash allows you to generate a short, random hash when an item is created while still using auto-incrementing IDs for database relationships.
