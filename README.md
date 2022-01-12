# Secrets storage

[![codecov](https://codecov.io/gh/jc-it/secrets/branch/master/graph/badge.svg)](https://codecov.io/gh/jc-it/secrets)
[![Continous integration](https://github.com/jc-it/secrets/actions/workflows/ci.yaml/badge.svg)](https://github.com/jc-it/secrets/actions/workflows/ci.yaml)
![Packagist Total Downloads](https://img.shields.io/packagist/dt/jc-it/secrets)
![Packagist Monthly Downloads](https://img.shields.io/packagist/dm/jc-it/secrets)
![GitHub tag (latest by date)](https://img.shields.io/github/v/tag/jc-it/secrets)
![Packagist Version](https://img.shields.io/packagist/v/jc-it/secrets)

This package provides secret storage. 

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require jc-it/secrets
```

or add

```
"jc-it/secrets": "^<latest version>"
```

to the `require` section of your `composer.json` file.

## Configuration

It is recommended to use this package only in configuration files before your application is loaded, this way they won't
be dumped by your application on chrashes or something unexpected.

```php
$secrets = new \JCIT\secrets\Secrets(
    new \JCIT\secrets\storages\Chained(
        new \JCIT\secrets\storages\Cache(getenv()),
        new \JCIT\secrets\storages\Json('/run/env.json'),
        new \JCIT\secrets\storages\Filesystem(__DIR__ . '/secrets'),
    )
);
```

Note that the order in the `Chained` storage does matter, wherever a secret is found first that value will be returned.

## Usage

After initialization, just call the following code:
```php
$secrets->get('<secret>', '<optional default value>');
```

To be sure the secret is set use:
```php
$secrets->getAndThrowOnNull('<secret>');
```

## Extension

In order to implement your own storage, just extend the `\JCIT\secrets\interfaces\StorageInterface`.

## TODO
- Write extractor based on https://github.com/JC-IT/yii2-secrets/blob/master/src/actions/Extract.php

## Credits
- [Joey Claessen](https://github.com/joester89)
