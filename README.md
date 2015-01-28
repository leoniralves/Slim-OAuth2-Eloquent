# Slim-OAuth2-Eloquent

Este repositório contém código para desenvolvedores que precisam iniciar uma aplicação utilizando os conceitos RESTFul com [Slim Framework](http://www.slimframework.com/), porém, não querem passar pala fase de estruturação e configuração de ferramentas como [Eloquent ORM](http://laravel.com/docs/4.2/eloquent#), [OAuth](http://oauth.net/) e [Slim-Monolog](https://github.com/Flynsarmy/Slim-Monolog). *Slim-OAuth2-Eloquent* já contém todas estas ferramentas configuradas e prontas para uso.

## Requerimentos
PHP >= 5.4.0

## Instalação
* Download [`composer.phar`](https://github.com/composer/composer) 
```sh
$ curl -sS https://getcomposer.org/installer | php
```
* Instalar dependências
```sh
$ composer install
```
* Criar banco de testes [SQLite](http://www.sqlite.org/)
```sh
$ php share/init/init.php
```

## Usar

O banco de dados que foi criando, dentro do subdiretório *share/init*, contém um usuário de teste. Este será usando para ilustrar o uso da API.
````
username: usertest
password: test
```

* Solicitar um token para acesso - `POST`
```sh
$ curl -X POST -F "grant_type=password" -F "client_id=testclient" -F "client_secret=secret" -F "username=usertest" -F "password=test" http://localhost/Slim-OAuth2-Eloquent/public/api/v1/oauth/token
```

* Utilizar token de acesso (Access token) - `GET`
```sh
$ curl -X GET http://localhost/Slim-OAuth2-Eloquent/public/api/v1/users?access_token=código retornado na solicitação de token de acesso
```
* Atualizar token (Refresh token) - `POST`
```sh
$ curl -X POST -F "grant_type=refresh_token" -F "client_id=testclient" -F "client_secret=secret" -F "refresh_token=código retornado no parametro refresh_token" http://localhost/Slim-OAuth2-Eloquent/public/api/v1/oauth/token
```
## Links
* [Slim Framework](http://www.slimframework.com/)
* [Eloquent ORM](http://laravel.com/docs/4.2/eloquent#)
* [OAuth](http://oauth.net/)
* [Slim-Monolog](https://github.com/Flynsarmy/Slim-Monolog)
* [Composer](https://github.com/composer/composer)
* [SQLite](http://www.sqlite.org/)
* [PHP](http://php.net/)
* [oauth2-server](https://github.com/thephpleague/oauth2-server)

## Referências utilizadas
* [Best Practices for Designing a Pragmatic RESTful API](http://www.vinaysahni.com/best-practices-for-a-pragmatic-restful-api)
* [An Introduction to OAuth 2](http://www.slideshare.net/aaronpk/an-introduction-to-oauth-2)

## Suporte
Bugs, features, sugestões ou dúvidas utilizar [GitHub](https://github.com/leoniralves/Slim-OAuth2-Eloquent/issues)

## Autor
<<<<<<< HEAD
Leonir Alves - https://twitter.com/leo_alvesjf
=======
Leonir Alves - https://twitter.com/leonir_ad
>>>>>>> 5a42a3d... Refactor dos namespaces carregados na PSR-0;
