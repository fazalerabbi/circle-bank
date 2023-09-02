# Circle Bank System
Create a symfony console app that has 3 different commands built in.

Command 1
Write a single function that will accept 2 arguments - user_id and key. Command should return the value that is under the given user and value key. For example: Given user_id = 3, key = 'account_balance', the command should return 6875.31

Command 2
Write a single function that will sort and print the data by given key no matter the level it occurs within the data structure. For example: Given 'account_balance' it will return user 2 first, user 1 last

Command 3
Using given example data write two or more classes that can handle withdrawal, deposit and transfer money between the users.

- On saving account the balance can't be less then 0
- On normal account the balance can go below 0 but for each 1$ below 0 it should add 20% interest, for example:
  Account got 10$, making withdrawal of 110$, account balance should be -120$
  Account got 0$, making withdrawal of 10$, account balance should be -12$
  Account got -100$, making withdrawal of 100$, account balance should be -220$
- Script should be able to print account balance after each transaction
- Script should have option to edit first_name, last_name and gender for each user
- Script should have option to edit account type but it should return error when trying to change normal account to saving one with balance less then 0
## Setup on local

To run this application, you will need:

- Clone the repo
- Install docker and docker compose
- At root of project run `docker comopose build`
- Enter into docker using command `docker exec -it circle-bank-php bash`
- Run following commands
  -- `composer install`
- Sample `.env` file
```env
APP_ENV=dev
APP_SECRET=e124ecc798e841ecc230326a2a83f3a8

DB_CONNECTION=pgsql
DB_HOST=pgsql
DB_PORT=5432
DB_DATABASE=circle_bank
DB_USERNAME=circle_bank_user
DB_PASSWORD=circle_bank_password
DATABASE_URL="postgresql://circle_bank_user:circle_bank_password@pgsql:5432/circle_bank?serverVersion=15&charset=utf8"
```
## Commands need to execute in docker for database setup
- Login to docker using the same command `docker exec -it circle-bank-php bash`.
- `symfony console doctrine:migrations:migrate`
- `symfony console doctrine:fixtures:load`

## Command 1
- `symfony console app:get-user-info {USER_ID} {key}`
- For Example: `symfony console app:get-user-info 5 first_name`
- Get Command Help: `symfony console app:get-user-info --help`

## Command 2
- `symfony console app:get-sorted-data {key}`
- For Example: `symfony console app:get-sorted-data account_balance`
- Get Command Help: `symfony console app:get-sorted-data --help`
- Note: This command will print the data in sorted order by given key

## Command 3
- `symfony console app:do-bank-transactions {account_number} {amount} {--receiver_account_number} {--transaction_type} {--account_type}`
- Example for Transfer Amount: `symfony console app:do-bank-transactions 123456789 1000 --receiver_account_number=987654321 --transaction_type=transfer`
- Example for Deposit Amount: `symfony console app:do-bank-transactions 123456789 1000 --transaction_type=deposit`
- Example for Withdraw Amount: `symfony console app:do-bank-transactions 123456789 1000 --transaction_type=widthdraw`
- Get Command Help: `symfony console app:do-bank-transactions --help`

## Command 4
- `symfony console app:user-profile-settings {USER_ID} {--first_name} {--last_name} {--gender}`
- Example for Update First Name: `symfony console app:user-profile-settings 1 --first_name=John`

````
## License
This project is open-source software licensed under the MIT license.
