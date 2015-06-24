Yii2 PSR3 Logging Adapter
=========================

A quick and dirty adapter class that will allow third party classes expecting a
PSR3 compatible logger to log using the Yii2 logger.

Note that Yii2 has a limited number of logging levels so this class will
attempt to use the closest Yii2 equivalent for the provided PSR3 level.

Installation
------------

As this package is a work in progress you will need to add this repository
manually to your composer.json as follows:

```
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/orteko/yii2-psr3-log-adapter.git"
        }
    ],
```

Then add the library dependency:

```
composer require orteko/yii2-psr3-log-adapter dev-master
```

Usage
-----

```
use Orteko\PSR3LogAdapter\Logger;
use Psr\Log\LogLevel;

$psrLogger = new Logger('my-library-category-name-here');
$psrLogger->log(LogLevel::ALERT, 'A PSR3 alert message, logged as a Yii2 error message');
$psrLogger->notice('A PSR3 notice message, logged as a Yii2 info message');

$x = new SomeThirdPartyLibrary();
$x->setLogger(new Logger('library-category-name'));
```

@TODO
-----

Set up on packagist once this is a little more fleshed out.
