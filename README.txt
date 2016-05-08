# BIKO DRUPAL UTILS

---SUMARIO---

Utilidades comunes para Drupal 8


---INSTALACIÓN---

Añadir el repositorio privado de BitBucket a composer.json

```javascript
    "repositories": [
        {
            "type": "vcs",
            "url": "git@bitbucket.org:biko2/biko-drupal-utils.git"
        }
    ],
```

Añadir el paquete a composer.json, haciendo que coincida con la versión de Drupal que usemos (8.1.* -> 8.1, 8.2.* -> 8.2, etc)
```javascript
    "require": {
        ...
        "biko2/biko_drupal_utils": "8.1"
    },
```

*IMPORTANTE*
Ejecutar composer con *--prefer-source* para que haga clone de este repositorio y podamos seguir trabajando en él directamente.

```
composer install biko2/biko_drupal_utils --prefer-source

```
