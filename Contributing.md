#Contributions FR
##Init Project
#####Forker le depot avec les commandes suivantes :
```
$ git clone https://github.com/erreur4045/ToDo-Co.git
$ cd ToDo-Co
$ git fork
```
#####Mettez à jours le projet avec :
```
composer install
```
#####Installer la basse de donnée en fonction de votre environnement.
Exemple sous windows
```
php bin/console doctrine:database:create

php bin/console doctrine:schema:update --force
```
##Contributions
#####Ajouter vos modifications et exécuter la suite de tests.

Exemple sous windows :
```
vendor\bin\phpunit
```

Si et seulement si la suite de test se passe sans erreur ni warning, vous pouvez ajouter une pull request.
Les normes psr1 et ps12 sont mise en place sur ce projet.

#####Normes PSR 
Les commandes suivant permettent respectivement de lister les erreurs et de les fixer si cela est possible automatiquement
```
phpcs
phpcbf
```
Soyer clair dans vos messages de commit et commenter votre code si cela vous pare nécessaire.

#####Git flow
* Fork
* Creer un branche nommer [feature/{feature-Name}]
    * Ajouter vos modification
    * Passer la suite de tests
    * normer avec les regles PSR1 PSR2
* Creer une pull-request entre votre branche et la branche dev

Une fois ces etapes effectuer une pesonne tier verrifira testera de votre code, puis mergera votre code dans la branche master.

#ContributionsEN
##Init Project
#### Step 1 :
Fork the repository with the following commands:
```
$ git clone https://github.com/erreur4045/ToDo-Co.git
$ cd ToDo-Co
$ git fork
```
Update the project with :
```
composer install
```
#### Step 2 :
Install the database according to your environment.
Example under windows
```
php bin/console doctrine:database:create

php bin/console doctrine:schema:update --force
```

##Contributions
#### Step 1 :

Add your changes and run the test suite.

Example under windows :
```
vendor\bin\phpunit
```
#### Step 2 :
If the test suite runs without errors or warnings, you can add a pull request.
The psr1 and ps12 standards are implemented on this project.

The following commands can be used to list the errors and to set them automatically if possible
```
phpcs
phpcbf
```
Be clear in your commit messages and comment on your code if necessary

#####Git flow
* Fork
* Create a branch name [feature/{feature-Name}]
    * Add your changes
    * Passing the test suite
    * Standardize with the rules PSR1 PSR2
* Create a pull-request between your branch and the dev branch

Once these steps have been completed, a third party will check and test your code, then merge your code into the master branch.