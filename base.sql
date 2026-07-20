
. operateur: id(int), nom(varchar) , prefixe(int)
. frais: id(int), tranche_min(int), tranche_max(int), montant_frais(decimal)
. type_operations: id(int), nom(varchar)
. transaction : id(int), id_type_operations(int), montant(decimal), date(date), id_compte_client(int), montant_frais(decimal)
. historique_transaction: id(int), id_transaction(int), date(date), montant(decimal), id_type_operations(int),solde_avant(decimal), solde_apres(decimal)
. client: id(int), nom(varchar), prénom(varchar), date_naissance(date), adresse(varchar), email(varchar), téléphone(varchar)
. compte_client: id(int), id_client(int), date_création(date)
