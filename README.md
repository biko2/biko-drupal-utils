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

Añadir el paquete a composer.json, haciendo que coincida con la versión de Drupal que usemos (8.1.* -> 8.1, 8.2.* -> 8.2, etc)
```json
    "require": {
        ...
        "biko2/biko_drupal_utils": "8.1"
    },
```

## IMPORTANTE ##
Ejecutar composer con **--prefer-source** para que haga clone de este repositorio y podamos seguir trabajando en él directamente.

```
composer install biko2/biko_drupal_utils --prefer-source
```

Si ya se ha instalado el módulo sin repositorio pero queremos tenerlo, hay que desinstalar el módulo, eliminarlo de composer, y hacer composer update. Una vez hecho, ya podemos añadirlo de nuevo a composer.json y ejecutar composer install con --prefer-source.

## CONFIGURACIÓN DE PHPSTORM ##
Para que PhpStorm detecte que el módulo esta bajo git, hay que hacer:

* Settings > Version Control
* Add new directory

De esta forma podremos trabajar de forma simultánea con el repo global del proyecto, y con el del módulo.