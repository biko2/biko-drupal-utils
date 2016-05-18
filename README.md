# BIKO DRUPAL UTILS

Utilidades comunes para Drupal 8


## INSTALACIÓN ##

Añadir el repositorio privado de BitBucket a composer.json

```json
    "repositories": [
        {
            "type": "vcs",
            "url": "git@bitbucket.org:biko2/biko-drupal-utils.git"
        }
    ],
```

Añadir el paquete a composer.json, haciendo que coincida con la versión de Drupal que usemos (8.1.x -> 8.1, 8.2.x -> 8.2, etc)
```json
    "require": {
        ...
        "biko2/biko_drupal_utils": "8.1"
    },
```

## IMPORTANTE ##
Ejecutar composer require con **--prefer-source** para que haga clone de este repositorio y podamos seguir trabajando en él directamente.

```
composer require biko2/biko_drupal_utils --prefer-source
```

Si ya se ha instalado el módulo sin repositorio pero queremos tenerlo, hay que desinstalar el módulo, eliminarlo de composer, y hacer "composer update biko2/biko_drupal_utils". Una vez hecho, ya podemos añadirlo de nuevo a composer.json y ejecutar composer require con --prefer-source.

## CONFIGURACIÓN DE PHPSTORM ##
Para que PhpStorm trabaje con dos repos a la vez:

* Settings > Version Control
* Add new directory

De esta forma podremos trabajar de forma simultánea con el repo global del proyecto y con el del módulo.