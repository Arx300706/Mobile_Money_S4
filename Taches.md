# Version 1

## environnement de travail  []
1. création repository github [x]   ETU004196
2. initialisation codeIgniter4 + config SQLite []  ETU004196
## conception database []
1. reflexion [x]  ensemble
2. base.sql  [x]  ETU004231
3. app/Config/Database.php [x]  ETU004196
4. migrations [x] 
## Creation Model 
## Gestion des prefixes []
1.  Routes.php /prefixeListe , /prefixeFormulaire
2.  Création Operateur.php
3.  CRUD ajouter/modifier/supprimer un prefixe
4.  Création PrefixeController.php
5.  View/prefixeListe.php
6.  View/prefixeFormulaire.php
## Gestion des types d'opérations et baremes de frais
1.  Routes.php /TypeOperation
2.  TypeOperationModel.php   , FraisModel.php
3.  TypeOprationController.php  ,CRUD types d'opération (dépôt, retrait, transfert) 
4.  FraisController.php , CRUD frais( bareme) par tranche de montant (min, max, frais fixe ou %) 
5.  View/TypeOperations.php 
- formulaire création type d'operation avec creation de tranches 
- filtre pour voir le tableau des tranche d'une operation  
