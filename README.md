# lib-proctorio

![TAO Logo](https://github.com/oat-sa/taohub-developer-guide/raw/master/resources/tao-logo.png)

## Description
[Proctorio](https://proctorio.com/)


Proctorio library allows us to create a signed request to the Proctorio provider

Proctorio ensures the total learning integrity of every assessment. 
Proctorio may eliminates human error, bias, and much of the expense associated with remote proctoring and identity verification.

## Installation instructions

These instructions assume that you have already a TAO installation on your system. If you don't, go to
[package/tao](https://github.com/oat-sa/package-tao) and follow the installation instructions over there.

Add the library to your TAO composer and to the autoloader:

Note that `oat-sa/lib-proctorio` is not registered on [Packagist](https://packagist.org/) so that you will need to add
a reference to your _composer.json_ before you can use `composer require`.
```
"repositories": [
    {
        "type": "vcs",
        "url": "git@github.com:oat-sa/lib-proctorio.git"
    }
],
```
Now you can add it to `composer.json`:
```bash
composer require oat-sa/lib-proctorio
```

## Library Wiki

# RemoteProctoringService

Proctorio service allow to communicate with Proctorio api. 

## Implementation
ProctorioService is an implementation of RemoteProctoringServiceInterface that allow:
- calling remote proctoring
- buillding a config for remote proctoring call


## Example
To use library you can use `ProctorioService` class

```php
<?php

use oat\Proctorio\ProctorioService;
use oat\Proctorio\ProctorioConfig;

$proctorioService = new ProctorioService();

$params = [
    ProctorioConfig::LAUNCH_URL => 'http://proctorio.url.example',
    ProctorioConfig::USER_ID => 'user_id',
    ProctorioConfig::EXAM_TAKE => 'https:\/\/tao.platform.instance\/.*',
    ProctorioConfig::EXAM_END => 'https:\/\/tao.platform.instance\/.*',
    ProctorioConfig::EXAM_SETTINGS => ['webtraffic']
];

$urls = $proctorioService->callRemoteProctoring($params, 'your_oauth_key', 'secret');
}

```