# Installation

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
