# Version 1

## Environnement de travail
1. [x] Creation repository GitHub - ETU004196
2. [x] Initialisation CodeIgniter 4 - ETU004196
3. [x] Configuration SQLite - ETU004231

## Conception database
1. [x] Reflexion generale - Ensemble
2. [x] Creation de `base.sql` - ETU004231
3. [x] Configuration `app/Config/Database.php` - ETU004196
4. [x] Creation des migrations - ETU004231

## Creation des models
1. [x] `OperateurModel` - ETU004231
2. [x] `TypeOperationsModel` - ETU004231
3. [x] `FraisModel` - ETU004231
4. [x] `ClientModel` - ETU004196
5. [x] `CompteClientModel` - ETU004196
6. [x] `TransactionModel` - ETU004196
7. [x] `HistoriqueTransactionModel` - ETU004196

## Gestion des operateurs
1. [x] Routes dans `Routes.php` - ETU004196
   - `/operateur`
   - `/operateur/create`
   - `/operateur/edit/(:num)`
   - `/operateur/delete/(:num)`

2. [x] `OperateurController` - ETU004196
   - `index()` : affiche la liste des operateurs
   - `create()` : affiche le formulaire d'ajout
   - `store()` : enregistre un nouvel operateur
   - `edit(int $id)` : affiche le formulaire de modification
   - `update(int $id)` : modifie un operateur
   - `delete(int $id)` : supprime un operateur

3. [x] View `operateur/index.php` - ETU004231
   - bouton voir types operations
   - bouton ajouter operateur
   - tableau des operateurs : numero identifiant, nom, prefixe
   - actions : modifier, supprimer

4. [x] View `operateur/form.php` - ETU004231
   - formulaire ajout/modification operateur
   - champs : nom, prefixe telephone
   - boutons enregistrer et annuler

## Gestion des types d'operations et baremes de frais
1. [x] Routes dans `Routes.php` - ETU004196
   - `/TypeOperation`
   - `/TypeOperation/create`
   - `/TypeOperation/store`
   - `/TypeOperation/update/(:num)`
   - `/TypeOperation/delete/(:num)`
   - `/frais/store`
   - `/frais/update/(:num)`
   - `/frais/delete/(:num)`

2. [x] `TypeOperationController` - ETU004196
   - `index()` : affiche les types d'operations et les baremes de frais
   - `create()` : affiche le formulaire de creation
   - `store()` : cree un type d'operation avec ses tranches
   - `update(int $id)` : modifie un type d'operation
   - `delete(int $id)` : supprime un type d'operation

3. [x] `FraisController` - ETU004196
   - `store()` : ajoute un bareme de frais
   - `update(int $id)` : modifie un bareme de frais
   - `delete(int $id)` : supprime un bareme de frais
   - verification des tranches qui se chevauchent

4. [x] View `typeOperation/TypeOperation.php` - ETU004231
   - bouton nouvelle operation
   - tableau des types d'operations
   - actions : modifier, supprimer, voir tranches
   - filtre des tranches par type d'operation
   - formulaire ajout bareme de frais
   - champs : type, operateur, min, max, valeur
   - tableau des tranches filtrables
   - actions : modifier, supprimer

5. [x] View `typeOperation/form.php` - ETU004231
   - input nom de la nouvelle operation
   - tableau pour ajouter des tranches
   - champs : operateur, min, max, valeur
   - bouton creer

## Situation gain via les differents frais
1. [x] Route `/SituationGain` dans `Routes.php` - ETU004196

2. [x] `SituationGainController.php` - ETU004196
   - `index()` : affiche la situation des gains
   - filtre par date debut, date fin et operation
   - calcul du gain total
   - calcul du montant traite
   - calcul du nombre d'operations

3. [x] View `situation/SituationGain.php` - ETU004231
   - filtre date debut, date fin, operation
   - boutons filtrer et reinitialiser
   - cards : gain total, montant traite, nombre operations
   - tableau gain par type d'operation
   - tableau detail des transactions
   - colonnes : date, type operation, client, numero telephone, montant, frais gagne

## Login automatique avec numero telephone
1. [x] Routes dans `Routes.php` - ETU004196
   - `/`
   - `/login/client`
   - `/admin/password`
   - `/logout`

2. [x] `LoginController.php` - ETU004196
   - `index()` : affiche `login.php`
   - `clientConnexion()` : connecte le client par numero telephone
   - validation du format du numero
   - verification de l'operateur existant
   - verification du compte client existant
   - redirection vers `/compte`
   - connexion admin avec `admin` et mot de passe

3. [x] View `login.php` - ETU004231
   - input numero telephone
   - bouton acceder au compte
   - affichage message succes/erreur

4. [x] View `admin_password.php` - ETU004231
   - input mot de passe admin
   - bouton connexion
   - bouton retour

## Situation des comptes clients
1. [x] Routes dans `Routes.php` - ETU004196
   - `/compte`
   - `/compte/depot`
   - `/compte/retrait`
   - `/compte/transfert`

2. [x] `CompteClientController.php` - ETU004196
   - `index()` : affiche le compte du client connecte
   - `depot()` : effectue un depot automatique
   - `retrait()` : effectue un retrait automatique avec frais
   - `transfert()` : effectue un transfert vers un autre client
   - verification du solde
   - enregistrement dans transaction et historique

3. [x] View `client/compte.php` - ETU004231
   - affichage du client connecte
   - affichage du solde
   - formulaire depot
   - formulaire retrait
   - formulaire transfert
   - historique des operations

4. [x] Historique client - ETU004196
   - historique des depots
   - historique des retraits
   - historique des transferts
   - affichage date, operation, montant, frais et statut

## Design et CSS
1. [x] Creation du fichier `public/style/styles.css` - ETU004231
2. [x] Suppression des styles inline dans les vues principales - ETU004231
3. [x] Integration du CSS dans les pages login et admin - ETU004231
4. [x] Amelioration visuelle des pages TypeOperation, Operateur, SituationGain et Compte client - ETU004231

## Tests et verification
1. [x] Verification des routes avec `php spark routes` - ETU004196
2. [x] Verification syntaxe PHP avec `php -l` - ETU004196
3. [x] Test des pages principales dans le navigateur - Ensemble
4. [x] Test des boutons ajouter, modifier, supprimer - Ensemble
5. [x] Test login client - Ensemble
6. [x] Test login admin - Ensemble

## Repartition globale
- ETU004196 : configuration, routes, controllers, logique metier, tests techniques.
- ETU004231 : base de donnees, models, vues, CSS, presentation et experience utilisateur.
- Ensemble : reflexion, tests navigateur, validation finale.

# Version 2

## Configuration des prefixes valable pour les autres operateurs 
1. [] 
2. [] 

## Configuration des commissions en pourcentage pour autres operateurs
1. [] Analyse de la logique actuelle des frais - Ensemble
   - verifier la table `frais`
   - verifier le champ `type_frais`
   - verifier le calcul actuel dans `FraisModel`
   - verifier le calcul actuel dans `CompteClientController`

2. [ ] Mise a jour du modele `FraisModel` - ETU004231
   - autoriser `type_frais` avec les valeurs `fixe` et `pourcentage`
   - modifier `calculerFrais()` pour calculer :
     - frais fixe : montant direct
     - frais pourcentage : montant * pourcentage / 100

3. [ ] Configuration des frais en pourcentage - ETU004231
   - ajouter le choix du type de frais dans le formulaire des baremes
   - afficher si la valeur est en Ar ou en %
   - permettre la modification du type de frais

4. [ ] Detection des transferts vers autres operateurs - ETU004196
   - detecter l'operateur du client source avec son numero telephone
   - detecter l'operateur du destinataire avec son numero telephone
   - comparer les deux operateurs
   - identifier si le transfert est :
     - meme operateur
     - autre operateur

5. [ ] Application de la commission supplementaire - ETU004196
   - appliquer les frais normaux du transfert
   - ajouter une commission en pourcentage si le destinataire est chez un autre operateur
   - verifier le solde avec montant + frais total
   - enregistrer le frais total dans la transaction

## Separation des gains par operateur et autres operateurs
1. [ ] Mise a jour de la requete des gains - ETU004196
   - recuperer le telephone du client source
   - recuperer le telephone du destinataire pour les transferts
   - detecter les operateurs source et destinataire
   - classer les transferts en meme operateur ou autres operateurs

2. [ ] Mise a jour de `TransactionModel` - ETU004196
   - separer les gains de retrait
   - separer les gains de transfert meme operateur
   - separer les gains de transfert autres operateurs
   - calculer le nombre d'operations pour chaque categorie
   - calculer le montant traite pour chaque categorie
   - calculer le gain total pour chaque categorie

3. [ ] Mise a jour de `SituationGainController` - ETU004196
   - envoyer les nouveaux regroupements a la vue
   - garder les filtres par date debut, date fin et operation
   - recalculer les totaux selon les nouvelles categories

4. [ ] Mise a jour de la vue `situation/SituationGain.php` - ETU004231
   - afficher un tableau separe :
     - Retrait
     - Transfert meme operateur
     - Transfert autres operateurs
   - afficher dans le detail des transactions la categorie du transfert
   - ajouter les colonnes operateur source et operateur destinataire si necessaire

## Tests et verification
1. [ ] Test transfert meme operateur - Ensemble
   - exemple : Telma vers Telma
   - verifier que seuls les frais normaux sont appliques

2. [ ] Test transfert autres operateurs - Ensemble
   - exemple : Telma vers Airtel
   - verifier que les frais normaux + commission en pourcentage sont appliques

3. [ ] Test affichage SituationGain - Ensemble
   - verifier le gain des retraits
   - verifier le gain des transferts meme operateur
   - verifier le gain des transferts autres operateurs

4. [ ] Verification technique - ETU004196
   - verifier les routes
   - verifier la syntaxe PHP
   - verifier les requetes SQL
   - verifier que les anciens frais fixes fonctionnent encore
