# Installation

This bundle can be installed using composer by adding the following in the require section of your `composer.json` file:

```json
"require": {
    "linkvalue/mobile-notif-bundle": "^0.2"
},
```

Or using composer CLI:

```bash
composer require linkvalue/mobile-notif-bundle
```

After downloading the bundle, register it in AppKernel:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new LinkValue\JarvisBundle\LinkValueJarvisBundle()
    );
}
```
