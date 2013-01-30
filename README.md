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

1. Add the module to your composer.json requirments.

    ```json
    "require": {
        "bgallagher/bgoauthprovider": "dev-master"
    }
    ```

2. composer.phar update

3. Add "BgOauthProvider" to your application.config.php file.

## Config Example

```php
<?php

return array(

    'bgoauthprovider' => array(
        'disable_layout_on_authorisation_page' => true,

        'acl_config' => array(
            array('routeName' => 'api/endpoint', 'role' => '3l', 'method' => array('post')),
            array('routeName' => 'api/endpoint/another', 'role' => '2l', 'method' => array('delete')),
        ),
    ),

);
```
