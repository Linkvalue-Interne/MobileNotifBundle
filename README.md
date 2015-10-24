# MobileNotifBundle

This bundle aim to provide push notification.

#####THIS BUNDLE IS IN DEVELOPMENT

## Installation

This bundle can be installed using composer by adding the following in the require section of your composer.json file:

```
"require": {
        ...
        "linkvalue/mobile-notif-bundle": "dev-master"
    },
```

Or with composer cli:

```
composer require linkvalue/mobile-notif-bundle
```

And after installed the bundle, you must register it in your kernel:

```
<?php

// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(

        // ...

        new LinkValue\MobileNotifBundle\LinkValueMobileNotifBundle()
    );
}
```

##Documentation

For installation and how to use the bundle refer to [Resources/doc/index.md](Resources/doc/index.md)

## Contributing

This is an open source bundle. If you're submitting
a pull request, please follow the symfony guidelines in the [Submitting a Patch][1] section
and use [Pull Request Template][2].


[1]: https://symfony.com/doc/current/contributing/code/patches.html#check-list
[2]: https://symfony.com/doc/current/contributing/code/patches.html#make-a-pull-request
