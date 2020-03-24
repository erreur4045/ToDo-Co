# ToDo-Co
[Rapport Audit Qualité/Performance](https://github.com/erreur4045/ToDo-Co/blob/master/Docs/Audit_Performance.pdf)

[Rapport Securité sous symfony](https://github.com/erreur4045/ToDo-Co/blob/master/Docs/L'Authentification_Symfony.pdf)

[Document pour la contribution](https://github.com/erreur4045/ToDo-Co/blob/master/Contributing.md)


[![Codacy Badge](https://api.codacy.com/project/badge/Grade/691883ef591043579fd453e30df6f0d7)](https://www.codacy.com/manual/erreur4045/ToDo-Co?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=erreur4045/ToDo-Co&amp;utm_campaign=Badge_Grade)

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

 * username : 
    * adminUsername
 * mot de passe : 
    * password
