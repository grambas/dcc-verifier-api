# DCC Verifier API

Demo API implementation with symfony 5.4 and php 8.1 based on [grambas/dcc-verifier-api](https://github.com/grambas/dcc-verifier) package in docker

* API Live: [https://dcc-verifier.de/](https://dcc-verifier.de/)
* Credentials request: : [https://dcc-verifier.de/api_credentials](https://dcc-verifier.de/api_credentials)


## Install / Usage

You are free to use and test it on [https://dcc-verifier.de/](https://dcc-verifier.de/), but if you want, you can deploy it also local:

* copy source
* create your env file app/.env.local with following envs
* overwrite envs from app./env by your environment and needs. dont forget `OAUTH2_ENCRYPTION_KEY`

* start docker containers
```
docker-compose up
```
* prepare your app
```
composer install 
bin/console doctrine:database:create
bin/console doctrine:schema:update --force
bin/console dcc:trust-list:update
```

* visit [http://localhost:81](http://localhost:81)

## Credits

- [Mindaugas Milius](https://github.com/grambas)


## License

The MIT License (MIT), see [License File](LICENSE.md) more information.
