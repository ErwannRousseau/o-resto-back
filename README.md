# o-resto-back

## Installation of project and deploy

> Follow this step by step :

## Import this project

> Connecting on your server ssh, exemple :

```bash
 ssh student@{{YOUR_SERVER_NAME}}-server.eddi.cloud
```

> We need to go on the good folder

Create a new folder `oresto`

```bash
mkdir /var/www/html/oresto
cd /var/www/html/oresto
```

> Copy the key SSH on github and do git clone :

```bash
 git clone git@github.com:ErwannRousseau/o-resto-back.git
```

> Go into the project folder

```bash
cd o-resto-back
```

> Write in your terminal

```bash
 composer install
```

## Parameters for this project

> Environement variable in .env

- `SITE_URL`: The website URL used for constructing links. Example: `http://{{YOUR_SERVER_NAME}}-server.eddi.cloud/o-resto/`.

- `CORS_ALLOW_ORIGIN`: The domain allowed to access the API via Cross-Origin Resource Sharing (CORS). Example: `^http?://{{YOUR_SERVER_NAME}}-server\.eddi\.cloud$`.

- `MY_DOMAIN`: The server's domain. Example: `http://{{YOUR_SERVER_NAME}}-server.eddi.cloud`.

Make sure to replace `{{YOUR_SERVER_NAME}}` with your own server name.

## Example Usage

To configure the application for your own server, follow these steps:

1. Open the `.env` file in your preferred text editor, run this :

```bash
vi .env
```

2. Find the line containing `SITE_URL` and update the website URL by replacing `{{YOUR_SERVER_NAME}}` with your server name.

3. Find the line containing `CORS_ALLOW_ORIGIN` and update the allowed domain by replacing `{{YOUR_SERVER_NAME}}` with your server name.

4. Find the line containing `MY_DOMAIN` and update the server's domain by replacing `{{YOUR_SERVER_NAME}}` with your server name.

To perform these modifications more quickly in the Vim editor, you can use the following command:

```bash
:%s/{{YOUR_SERVER_NAME}}/YOUR_REPLACEMENT/g
```

Make sure to replace `{{YOUR_SERVER_NAME}}` with your server name and `YOUR_REPLACEMENT` with the desired replacement value. Then, save and exit the editor using `:wq`.

> Parameter of Doctrine : .env.local

We use a login/password for mysql who has all access on BDD

To testing the right login and password on mysql do :

```bash
vi .env.local
```

Paste :

```
DATABASE_URL="mysql://{{LOGIN}}:{{PASSWORD}}@127.0.0.1:3306/{{NAMEOFBDD}}?serverVersion=mariadb-10.3.38&charset=utf8mb4"

MAILER_DSN=mailgun+smtp://resaoresto@sandboxcf706e4b541d4520a54edcaeba52d9e8.mailgun.org:5f31ca4e8f3cf7071f09cc95495c1abe-e5475b88-272c35d0@default?region=us
```

`{{LOGIN}}` = write your login

`{{PASSWORD}}` = write your password

`{{NAMEOFBDD}}` = name of your BDD

When it's finish, do `esc` and `:wq` to save and exit

> Creation of BDD :

```bash
 bin/console doctrine:database:create
```

Created database oresto for connection named default

> Creation of BDD structure

```bach
bin/console doctrine:migrations:migrate
```

> Go on Adminer to add manually datas, import on SQL the file give to you.

> Create the JWT Token

```bash
bin/console lexik:jwt:generate-keypair
```

Run the following command to assign the appropriate permissions to the student user:
```bash
sudo chown -R student:www-data .
```

> And that's it .


To try the good login/password of mysql

```bash
mysql -u LOGIN -p
Enter password:
```

If you have :

```Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 40
Server version: 10.3.38-MariaDB-0ubuntu0.20.04.1 Ubuntu 20.04

Copyright (c) 2000, 2018, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [(none)]>
```

If I see that it's good :p

`MariaDB [(none)]>exit Bye`
