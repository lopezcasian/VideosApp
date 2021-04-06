# Videos App

Videos App is a PHP webapp where you can create an user and upload, see and delete videos.

## Installation

Use the dependency manager [composer](https://getcomposer.org/download/) to install Videos App dependencies.

```bash
composer install
```
Create a .env file and add your configurations.

```bash
cp .env.example .env
```
Use artisan to install the project resources.

```artisan
php artisan key:generate
php artisan migrate
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.