# Clooud Media Script

Thanks for purchasing Clooud Media. If you ever end up having any questions, do not hesitate to contact us. There is a good chance that some of your questions might be answered here already:

https://chrispalmerbreuer.gitbooks.io/clooud-media/content/

Thanks again,

Clooud Media Team

## Installation

1. Begin by cloning the project from GitHub.

```bash
git clone https://github.com/Chinese1904/Clooud.git
```

2. Next, `cd` into your project and run `composer install` to install all the dependencies:

```bash
composer install
```

3. Let's rename the `.env.example`-file to `.env`

```bash
mv .env.example .env
```
 
4. Now, you will have to edit your `.env`-file with the right variables, so we can migrate the database next. Make sure the following variables are set correctly for your environment.

```bash
DB_HOST=127.0.0.1
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

5. Finally, let's migrate the database and fill it up with some settings and the admin and guest user:

```bash
php artisan migrate --seed
```

6. You may now visit your site and login via username `admin@clooud.tv` and the password being `admin`.

## Contributing

If you like to contribute, feel free to send in a pull request!
