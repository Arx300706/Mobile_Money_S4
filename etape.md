- cd writable
- touch database.db
- cd ..
- chmod -R 777 writable
- php spark make:migration CreateProduitTable
- php spark make:migration CreateCaisseTable
- php spark make:migration CreateAchatTable
- php spark migrate
- php spark db:table

## insertion 
- php spark make:controller SeedController

## verification donnée 
- php spark make:controller TestController


## creation models 
- php spark make:model CaisseModel
- php spark make:model ProduitModel
- php spark make:model AchatModel
- 