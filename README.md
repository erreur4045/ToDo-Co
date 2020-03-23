# ToDo-Co
[Rapport Audit Qualité](https://github.com/erreur4045/ToDo-Co)

[Rapport Securité sous symfony](https://github.com/erreur4045/ToDo-Co)

[Document pour la contribution](https://github.com/erreur4045/ToDo-Co/master/Contributing.md)


[![Codacy Badge](https://api.codacy.com/project/badge/Grade/ce4a1463cb724de19ff9ef371f688422)](https://app.codacy.com/manual/erreur4045/ToDo-Co?utm_source=github.com&utm_medium=referral&utm_content=erreur4045/ToDo-Co&utm_campaign=Badge_Grade_Dashboard)
[![Maintainability](https://api.codeclimate.com/v1/badges/770472da3b7b6b6cbbab/maintainability)](https://codeclimate.com/github/erreur4045/BileMo/maintainability)


![symfony](https://symfony.com/images/logos/header-logo.svg)

* Symfony 3.4 framework
* CSS : Bootstrap 4

## Prérequis
* **Php 7.3.5**
* **Mysql 5.7**

## Testé avec:
- PHPUnit [more infos](https://phpunit.de/)

## Performance mesurées avec:
- BlackFire

## Init projet:
cloner le depot https://github.com/erreur4045/ToDo-Co

```
$ composer install
```
enter les parametres de votre basse de donnees dans le fichier 

*/app/config/parameters.yml*

creer et charger les tables avec les commandes suivantes.
```
$ php bin/console doctrine:database:create
```
```
$ php bin/console doctrine:schema:update --force
```
Charger les fixtures
```
$ php bin/console doctrine:fixtures:load
```
vous pouvez utiliser les auths suivant si vous avez ajouter les fixtures:

username : adminUsername
mot de passe : password
