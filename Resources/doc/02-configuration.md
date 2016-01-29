# Configuration

Minimum configuration for android device:

```
link_value_mobile_notif:
	clients:
		gcm:
			myAndroidApplication:
				params:
					api_access_key: 'myKeyForMyAndroidDevice'
```

Minimum configuration for apple device:

```
link_value_mobile_notif:
	clients:
		apple:
			myAppleApplication:
				params:
					ssl_pem_path: __DIR__.'/data/myssl.pem'
					ssl_passphrase: 'MyPassphrase'
```

Configuration schema:

```
link_value_mobile_notif:
	clients:
		apple:
			appleApp1:
				services:
					logger: (default) logger
					profiler: (default) linkvalue.mobilenotif.profiler.notif_profiler
				params:
					endpoint: (default) tls://gateway.sandbox.push.apple.com:2195 
					ssl_pem_path: required
					ssl_passphrase: required
			appleApp2:
				[...]
		gcm:
			androidApp1:
				services:
					logger: (default) logger
					profiler: (default) linkvalue.mobilenotif.profiler.notif_profiler
				params:
					endpoint: (default) https://android.googleapis.com/gcm/send
					api_access_key: required
			androidApp2:
				[...]
```
