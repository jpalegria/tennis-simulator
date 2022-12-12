# Proyecto tennis-simulator
_A tennis tournaments simulator with api-rest as a microservice_

## Construcción 🛠️
_El web service fué construido con el framework Laravel. Bajo un entorno GNU/Linux._
* [PHP](https://www.php.net/) - PHP ^8.0
* [Laravel](https://laravel.com/) - Framework de Laravel
* [Docker](https://www.docker.com/) - Contenedores
* [Composer](https://getcomposer.org/) - Manejador de dependencias para PHP

## Credenciales para la API🔧
_En esta versión, no son necesarias las credenciales para utilizar la API, pero la configuración necesaria se encuentra en el archivo .env.example._

## Ejecución 🚀
_1. Ubicarse en la carpeta del proyecto e instalar las dependencias con Composer:_

```$ docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php81-composer:latest composer install --ignore-platform-reqs```

_2. Luego construye las imágenes de Laravel y el servicio Sail de Laravel ejecutando en la consola:_

```$ php artisan sail:build --no-cache```

_3. Crea y configura el archivo .env, si necesitas ayuda usa como base el archivo .env.example._

_4. Arranca los servicios de los contenedores:_

```$ ./vendor/bin/sail up```

_Si necesitas más info puedes guiarte con: https://laravel.com/docs/9.x/sail_

_5. Recuerda que este proyecto tiene un docker-compose customizado para reconocer los servicios facilmente! Encontrarás tennis_sim_laravel y tennis_sim_mariadb_

```$ docker-compose ps | grep tennis```

_6. Ejecuta las migraciones con las seed, así tendrás las tablas listas y algunos jugadores pregenerados._

```$ ./vendor/bin/sail artisan migrate --seed```

_7. Finalmente ingresa en el navegador la siguiente URL de ejemplo:_
```
http://localhost:8080/api/players/
```
_Listo! Ya puedes crear y simular un torneo de tenis! 🎾_

## Open API Doc 📖
_Puedes encontrar la documentación de los endpoints en: resources/openapi/._

## Autor ⌨️
_Desarrollado por:_
* **Juan Pablo Alegría** - [correo](jalegria.trabajo|at|gmail)

@copyright (c) 2022 Noviembre

---
¡Gracias! 😊