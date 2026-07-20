# Version 1

## environnement de travail  []
1. [x] création repository github    ETU004196
2. [x] initialisation codeIgniter4 + config SQLite []  ETU004196
## conception database []
1. [x] reflexion  ensemble
2. [x] base.sql   ETU004231
3. [x] app/Config/Database.php  ETU004196
4. [x] migrations 
## [x] Creation des Model  ETU004231
## [] Gestion des Operateurs
1. [] Routes.php /operateur , /operateur/create , /operateur/edit/(:num) , /operateur/delete/(:num) 
2. [] OperateurController
- function index() -> redirection view operateur/index
- function create() -> save nouvel operateur 
- function delete(int $id) 
- function edit(int $id)
3. [] view operateur/index.php 
- bouton voir types operations
- bouton ajouter operateur
- tableau des operateurs ( numero identifiant , nom , prefixe , action { modifier , supprimer })
4. [] view operateur/form.php
- formulaire ajout operateur ( nom , prefixe telephone )
- bouton enregistrer , annuler 
## [] Gestion des types d'opérations et baremes de frais
1. [] Routes.php /TypeOperation , 
2. [] TypeOperationCOntroller
- function index -> redirect view TypeOperations.php 
3. [] View/typeOperation/TypeOperation.php 
- bouton nouvelle operation
- tableau des types d'operations ( numero identifiant , nom , actions { modifier , supprimer }) 
- input filtrations de tranches ( type d'operation )
- formulaire ajout d'un bareme de frais ( select type , select operateur , input min et max et valeur )
- bouton apour ajouter le bareme 
- tableau des tranches filtrable pas le filtre ( operateur , type , min , max ,  valeur , actions { modifier , supprimer })
4. [] View/typeOperation/form.Php
- input nom de la nouvelle operation
- tableau a remplir pour ajouter des tranches ( operateur , min , max , valeur)
- bouton crer 
## [] Situation gain via les differents frais ( retrait et transfert )
1. [] Routes.php /SituationGain
2. [] SituationGainController.php
- function index() -> redirect vers SituationGain 
3. [] view situation/SituationGain.php
- filtre pour voir les situations ( date debut , date fin , operation )
- bouton filtrer et reinitialiser pour le filtre 
- card ( gain total , montant traité , nombre opérations )
- tableau gain par type d'operation ( operation , nombre , montant traité , gain )
- tableau daté pour le détail des transaction ( date de la transaction , type d'operation , nom du client , numero tel , montant , frais gagné )
## [] LOgin automatique avec num téléphone 
1. [] Routes.Php / , /login/client 
2. [] LoginController.php
- function index -> redirect view  login.php
- function clientConnexion   -> redirect vers compte 
```js
validation du numero ( format correct et operateur existant)
```
3. [] view login.php
- input numero telephone 
- bouton acceder au compte 

## situation des comptes clients 
1. [] Routes.Php 
2. 
## compte du client 
