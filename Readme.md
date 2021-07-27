![GitHub repo size](https://img.shields.io/github/repo-size/danielrmsantos/symfony_messenger)
![GitHub last commit](https://img.shields.io/github/last-commit/danielrmsantos/symfony_messenger)
### Symfony Test
Symfony 5 + PHP 7.4 + MySQL + Docker

### Setup
Run this commands
```
docker-compose up -d
docker-compose exec php bash
cd sf4
composer install
php bin/console doctrine:database:create
```

#### Access phpmyadmin
http://local.symfony-messenger.test:8080

#### Access maildev
http://local.symfony-messenger.test:8001

#### Consume messages from queue
```
php bin/console messenger:consume async -vv
```

## Author

**Daniel Santos**

* [github/danielrmsantos](https://github.com/danielrmsantos)