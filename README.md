# Team manager app with Symfony 6.3 

# Requirements

- PHP 8.1 or higher;
- composer : https://getcomposer.org/download/
- Symfony CLI : https://symfony.com/download#step-1-install-symfony-cli
- and the usual Symfony application requirements.


# Creating the project
Clone project from repository :
```sh
git clone https://github.com/joandrian/team_manager.git

cd team_manager/

composer install

```

# Setting up
- Add Database connection setting in the .env file 
<pre><code> DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
</code></pre>

Launch the commands below to setting up the project :
```sh
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

# Launch the app
```sh
    symfony server:start
```
- The home list all the teams with there players (three teams per page)
- click on "Create neaw Team" to create new teams with there players
- Click on "Buy/Sell player" to sell or buy from one team to another.
# Result

![Capture](https://github.com/joandrian/team_manager/blob/master/Capture_home.png?raw=true)
