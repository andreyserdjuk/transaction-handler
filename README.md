### Transaction handler

#### Run functional tests
```bash
php bin/phpunit
```

#### Project setup
For local env create `.env.local` from `.env` and set DATABASE_URL. 
```bash
composer install
php bin/console do:database:create
php bin/console do:mi:mi
php bin/console do:fi:lo
```
The last command creates Account with uid 'account-1'.

Run php local server:
```bash
cd public && php -S localhost:8000 ./index.php
```

Credit 1000:
```bash
curl -X POST --data '<?xml version="1.0"?><operations><credit amount="1000" tid="t1" uid="account-1"></credit></operations>' http://localhost:8000/transaction
```

Debit 1000:
```bash
curl -X POST --data '<?xml version="1.0"?><operations><debit amount="1000" tid="t2" uid="account-1"></debit></operations>' http://localhost:8000/transaction
```
