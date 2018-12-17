Getting started with the Lexik products test bundles
====================================================

This Symfony project is a catalog of products that you can visualize in a list, show each product details and photo. As an anonymous visitor, you can add and remove products from your cart. As an administrator, you can manage products with an easyadmin interface.
It uses Webpack Encore to generate assets and a data fixtures module to generate database data. A unit test and a functional test module are also available to test the home page.

## Project installation

You can clone the bundle from Github with a shell session.
Point to your project folder :
```bash
cd my/project/folder/path
```
Clone the project from Github :
```bash
git clone https://github.com/sicomore/lexik.git
```
Point to the Lexik project folder :
```bash
cd lexik
```
Install all required bundles and dependencies with composer (make sure you have composer installed on your machine and accessible globally) :
```bash
composer install
```
Install node_modules with yarn in order to use Webpack Encore :
```bash
yarn install
```
cd lexik
composer install
yarn install

## Setting up your database

### Schema

Create a database with the name you want.
In `.env` file at the root of the project, modify the generic name `lexik.db`, in the `DATABASE_URL` parameter, with the name of your database.
In your terminal, launch the command :
```bash
php bin/console doctrine:database:create
```
or as a shorcut :
```bash
php bin/console d:d:c
```

### Produits table

Update the database with the console command :
```bash
php bin/console doctrine:schema:update --dump-sql --force
```
or as a shorcut :
```bash
php bin/console d:s:u --dump-sql --force
```
That's it! Your database is fully functional.


## Run the project

### Launching the server

You can use your local server or you can use the internal symfony project server with :
```bash
php bin/console server:run
```

### Building the assets

You can rather build all your assets in dev mode, temporarily or in a continuous way, or in production mode.

Open a new terminal session at the root of your project.
Generating the assets in dev mode :
you can run either those 2 commands.
To build them just once after any css/js modification :
```bash
yarn encore dev
```
or if you want a continuous update on-the-run, use :
```bash
yarn encore dev --watch
```
Generating the assets in production mode :
You will have to build your assets in the `public/build` folder.
Then just run :
```bash
yarn encore production
```
That's it!

Now open your favorite browser and go to :
`http://localhost:8000`
Enjoy!
Ok, the list is totally empty.

So next step is meant to show you how to feed your table with fake data.


## Filling up the database with fake data

Instead of filling up your table manually, your can launch a command that will fill up the table with datas for you.

Open a new terminal session in your project folder and run :
```bash
php bin/console doctrine:fixtures:load
```
or as a shorcut :
```bash
php bin/console d:f:l
```
Answer 'yes' to the question asked (your database should be empty any way).

Ok, so far so good!
Refresh your browser page and ooooh! wonderful list of products.


## Run the tests

There is a unit test available, testing the total amount of the cart.
And there is also a functional test, testing correct list of products load and checking that the number of products is actually 12 (corresponding to the original number of products generated with the data fixtures)
If you want to launch these tests, just write this command :
```bash
php bin/phpunit
```
You should see that 2 tests and 3 assertions were successfully run.


## Accessing administration interface

In order to access the admin interface, just click on the `administration` button at the top of the webpage.
Login : `admin`
Password : `password`


## Changing the language of the interface

Just click on the `English version` button at the top to swap languages between French and English


## Exporting product list in CSV Format
You can export the whole list of available products running this console command at the root of your project folder :
```bash
php bin/console app:export-csv
```
The file will directly downloaded at the root of your project.
