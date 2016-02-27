## Getting started

### Vocabulary

Before using this bundle, you need to feel comfortable with some words or expressions related to mobile notifications.

  - `Apple Push Notification Service` (aka. `APNS`) is a notification service provided by Apple. It supports iOS applications.
  - `Google Cloud Messaging` (aka. `GCM`) is a notification service provided by Google. It supports **both Android and iOS** applications (see [this](https://developers.google.com/cloud-messaging/ios/client) to know how to set up GCM on iOS applications).
  - A `token` or `device token` is a string used to identify a device (mobile / tablet / etc.) on which is installed the targeted application. So you'll have to keep 1 token by device by application to be able to notify a specific user of your application.
  - [APNS specific vocabulary] A `certificate` or `certificate bundle` is a file which can be delivered by Apple (through the iOS developer center interface). It is often delivered as `.p12` format and must be converted to `.pem` format before you can pass it to `ssl_pem_path` configuration. The final certificate bundle PEM file should be composed of a "CERTIFICATE" and a "PRIVATE KEY" and it may require the use of a passphrase. Apple provides different certificates for development (sandbox) and production mode.



### Examples

#### Push notification "Hello world!" using GCM

```yaml
# app/config.yml

link_value_mobile_notif:
    clients:
        gcm:
            my_gcm_application:
                params:
                    api_access_key: 'my_google_cloud_messaging_api_key'
```

You can now try to push a notification with the following command:

```bash
php bin/console link_value_mobile_notif:gcm:push "my_gcm_application_device_token" "Hello world!"
```

Note: If you're using Symfony 2.x, you may have to replace `bin/console` by `app/console`.

If you did not receive the notification on your device (corresponding to "my_gcm_application_device_token"), fix your issue (probably a wrong device token or a bad api_access_key) and try again before going further.

Now, let's send the same notification with your own code in a controller.

To be able to use each configured client easily, each GCM client has its own service identified like this: `link_value_mobile_notif.clients.gcm.name_of_my_client`.

```php
// src/AppBundle/Controller/MyController.php

// ...
use LinkValue\MobileNotif\Model\GcmMessage;
// ...

class MyController extends Controller
{
    public function pushHelloWorldAction()
    {
        $message = new GcmMessage();
        $message
            ->setNotificationBody('Hello world!')
            ->setNotificationTitle('Title')
            ->setNotificationIcon('myicon')
            ->addToken('my_gcm_application_device_token')
        ;
        
        $this->container->get('link_value_mobile_notif.clients.gcm.my_gcm_application')->push($message);
        
        return new Response();
    }
}
```



#### Push notification "Hello world!" using APNS

```yaml
# app/config.yml

link_value_mobile_notif:
    clients:
        apns:
            my_apns_application:
                params:
                    ssl_pem_path: "%kernel.root_dir%/../my-apns-application_certificate-bundle_dev.pem"
```

You can now try to push a notification with the following command:

```bash
php bin/console link_value_mobile_notif:apns:push "my_apns_application_device_token" "Hello world!"
```

Note: If you're using Symfony 2.x, you may have to replace `bin/console` by `app/console`.

If you did not receive the notification on your device (corresponding to "my_apns_application_device_token"), fix your issue (probably a wrong device token or a bad certificate bundle) and try again before going further.

Now, let's send the same notification with your own code in a controller.

To be able to use each configured client easily, each APNS client has its own service identified like this: `link_value_mobile_notif.clients.apns.name_of_my_client`.

```php
// src/AppBundle/Controller/MyController.php

// ...
use LinkValue\MobileNotif\Model\ApnsMessage;
// ...

class MyController extends Controller
{
    public function pushHelloWorldAction()
    {
        $message = new ApnsMessage();
        $message
            ->setSimpleAlert('Hello world!')
            ->addToken('my_apns_application_device_token')
        ;
        
        $this->container->get('link_value_mobile_notif.clients.apns.my_apns_application')->push($message);
        
        return new Response();
    }
}
```



#### Push notification to multiple applications

```yaml
# app/config.yml

link_value_mobile_notif:
    clients:
        apns:
            apns_app1_dev:
                params:
                    ssl_pem_path: "%kernel.root_dir%/../my-apns-application_certificate-bundle_dev.pem"
            apns_app1_prod:
                params:
                    ssl_pem_path: "%kernel.root_dir%/../my-apns-application_certificate-bundle_prod.pem"
                    ssl_passphrase: "this certificate requires a passphrase!"
                    endpoint: "tls://gateway.push.apple.com:2195" # APNS production endpoint
        gcm:
            gcm_app1:
                params:
                    api_access_key: 'my_google_cloud_messaging_api_key'
            an_other_app:
                params:
                    api_access_key: 'my_google_cloud_messaging_api_key'
```

To ease the handling of multiple clients, all clients are grouped in a ClientCollection (which extends Doctrine's ArrayCollection) accessible through the service `link_value_mobile_notif.clients`.

```php
// src/AppBundle/Controller/MyController.php

// ...
use LinkValue\MobileNotif\Client\ClientInterface;
use LinkValue\MobileNotif\Model\ApnsMessage;
use LinkValue\MobileNotif\Model\GcmMessage;
// ...

class MyController extends Controller
{
    public function pushHelloWorldAction()
    {
        $clients = $this->container->get('link_value_mobile_notif.clients');
        
        // This message will be sent by APNS clients
        $apnsMessage = new ApnsMessage();
        $apnsMessage
            ->setAlertTitle('Foo Bar')
            ->setAlertBody('So original!')
            ->setBadge(1)
        ;
        
        // And this one will be sent by GCM clients
        $gcmMessage = new GcmMessage();
        $gcmMessage
            ->setNotificationTitle('GCM notification title')
            ->setNotificationBody('GCM notification body')
            ->setNotificationIcon('myicon')
        ;
        
        // Now push these messages to relevant device tokens using the appropriate client
        $clients->forAll(function ($key, ClientInterface $client) use ($apnsMessage, $gcmMessage) {
            if ($client instanceof ApnsMessage) {
                $apnsMessage->setTokens(
                    $this->getAllDeviceTokensForThisParticularClientKey($key)
                );
                $client->push($apnsMessage);
                return true;
            }
            if ($client instanceof GcmMessage) {
                $gcmMessage->setTokens(
                    $this->getAllDeviceTokensForThisParticularClientKey($key)
                );
                $client->push($gcmMessage);
                return true;
            }
        });
        
        return new Response();
    }
}
```

