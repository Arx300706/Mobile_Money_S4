Tâches communes (à faire ensemble, en premier — J1 matin)
Init projet CodeIgniter 4 + config SQLite (app/Config/Database.php)
Conception du schéma base.sql (tables : operateurs_prefixes, types_operations, baremes_frais, comptes, transactions)
Structure Git (branches, .gitignore, premier commit, tag v1 prévu en fin de partie)
Création du Taches.md avec la convention de suivi
👤 Étudiant etu04231 — Côté Opérateur (Back-office / Config)

1. Gestion des préfixes

Modèle PrefixeModel + migration/table
CRUD (ajouter/modifier/supprimer un préfixe, ex: 033, 037)
Vue liste + formulaire

2. Gestion des types d'opérations et barèmes

Modèle TypeOperationModel + BaremeModel
CRUD types d'opération (dépôt, retrait, transfert)
CRUD barèmes par tranche de montant (min, max, frais fixe ou %) — CRUD modifiable
Vue tableau des tranches avec édition inline ou modal

3. Reporting opérateur

Dashboard "Situation des gains" : somme des frais perçus par type d'opération, filtrable par période
Dashboard "Situation des comptes clients" : liste des comptes, soldes, dernière activité

4. Authentification opérateur (simple)

Login opérateur basique (peut être un compte unique en dur ou table admins)
👤 Étudiant etu4196 — Côté Client (Opérations & UX)

1. Authentification client

Formulaire "login" = saisie numéro de téléphone
Vérification du préfixe (doit correspondre à un préfixe opérateur configuré)
Création automatique du compte si le numéro n'existe pas (solde initial 0)
Session client

2. Opérations client

Page solde (affichage du solde courant)
Dépôt : formulaire montant → calcul frais selon barème → crédit du compte
Retrait : formulaire montant → vérif solde suffisant → calcul frais → débit
Transfert : saisie numéro destinataire → vérif existence/préfixe valide → calcul frais → débit/crédit des deux comptes

3. Logique métier partagée (à coordonner avec etu04231)

Service/Helper FraisCalculatorService : calcule les frais selon le type d'opération + tranche de montant (utilisé par le Model client, alimenté par les données que etu04231 configure)

4. Historique

Page historique des transactions du client connecté (dépôts, retraits, transferts envoyés/reçus)
Filtres simples (par type, par date)