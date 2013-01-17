BgOauthProvider
===============

A ZF2 OAuth 1.0a provider module (WIP)

This module allows your ZF2 app to act as an OAuth 1.0a Provider, allowing you to protect you api endpoints with OAuth.

It is still a WIP, any input or pull request are very much welcomed.

## Requirements
### ZF2 modules: 
* [ZfcUser](https://github.com/ZF-Commons/ZfcUser) (latest master)
* [ZfcUserDoctrineORM](https://github.com/ZF-Commons/ZfcUserDoctrineORM) (latest master)

### Pecl Extensions
* [Pecl OAuth](http://pecl.php.net/package/oauth)

##Installation

1. Add this project to your composer.json file.

    ```json
    "require": {
        "bgallagher/bgoauthprovider": "dev-master"
    }
    ```

2. php composer.phar update

## Config Example

```php
'BgOauthAcl' => array(
    array('routeName' => 'api/endpoint', 'role' => '3l', 'method' => array('post')),
    array('routeName' => 'api/endpoint/another', 'role' => '2l', 'method' => array('delete')),
),
```
