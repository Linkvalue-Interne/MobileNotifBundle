# Configuration

Configuration reference:

```yaml
link_value_mobile_notif:
    clients:

        # APNS configuration
        apns:
            ios_app1:

                params:

                    # required
                    # Path to the PEM certificate file (used to establish connection with APNS server),
                    # be aware that the PEM file is different for development and production mode
                    ssl_pem_path: "/my/pem/file/to/connect/with/APNS/in/production/mode.pem"

                    # optional
                    # Will be used along "ssl_pem_path", could be useful if the certificate is protected with a passphrase
                    ssl_passphrase: "my PEM file is secured!"

                    # optional -> Default value is "tls://gateway.sandbox.push.apple.com:2195" (the APNS development endpoint)
                    # APNS endpoint,
                    # you will have to set this parameter with the value below in order to send Notifications in production mode
                    endpoint: "tls://gateway.push.apple.com:2195"

                services:

                    # optional -> Default value is "logger"
                    # Logger service ID
                    logger: "my_custom_logger_service_id"

                    # optional -> Default value is "link_value_mobile_notif.profiler.notif_profiler"
                    # Profiler service ID
                    profiler: "my_custom_profiler_service_id"

            ios_app2:
                [...]

        # GCM configuration
        gcm:
            android_app1:

                params:

                    # required
                    # Google Cloud Messaging API Key (used to authenticate against GCM API)
                    api_access_key: "%parameter which point to my API Key because it should not be public%"

                    # optional -> Default value is "https://android.googleapis.com/gcm/send"
                    # Google Cloud Messaging endpoint
                    endpoint: "https://android.googleapis.com/gcm/send"

                services:

                    # optional -> Default value is "logger"
                    # Logger service ID
                    logger: "my_custom_logger_service_id"

                    # optional -> Default value is "link_value_mobile_notif.profiler.notif_profiler"
                    # Profiler service ID
                    profiler: "my_custom_profiler_service_id"

            android_or_ios_app2:
                [...]

```
