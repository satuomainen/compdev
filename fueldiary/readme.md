# Tankkeri (fueldiary)

Tankkeri is a web application that keeps track of fuel fill-ups to your vehicles. After registering you can log in and
create vehicles and every time you buy petrol you can save the litres filled, amount paid and the odometer reading. At
the moment the vehicle view shows only the average consumption between the new fill-up and the previous one. 

The data is entered through a responsive web page that should be easy and efficient to use on a mobile browser right 
at the pump.

The web application provides a RESTful API for handling vehicles and fill-ups so it can serve as a backend to potential
apps. 

## Background

I started this project to develop some PHP skills. PHP and MySQL are available on every web hosting service, even the
free ones, so it is useful to know how to develop web applications for those situations as well. I have not developed
in PHP professionally and probably will not either. Sometimes I might do some voluntary work to help out a volunteer 
organization or a sports club or something with their web application needs. They usually have no budget at all so
it's good to know how get a web application online without spending a lot of money on dedicated server or a 
professional hosting service.

I always save the receipt when I buy fuel to my car, I mark it with the odometer reading and save the data in an
excel sheet in order to keep up with fuelling frequency and the average fuel consumption. Saving the receipts and
writing the data into the spreadsheet is inconvenient and I would much rather just save the data into a database 
right away after filling up. My only use case so far is to see the average consumption and how it changes over time
but maybe there will be some other ideas later.

## Stack

Tankkeri is a PHP application built on the Laravel 5.1 framework. It uses MySQL database to store its data. Styles are
based on Bootstrap. No modern front-end (yet), Blade templates are used with some jQuery ajax. PHP 5.5 is required
by Laravel 5.1, it will not work with PHP 5.4. Which is a shame. Didn't check that before starting. Also to build and 
install you need node, npm and gulp.

## Building and installing

Clone the repo, then go into the fueldiary/ directory and run:
* $ php composer.phar install
* $ npm install
* $ gulp

After that, set up .htaccess in the installation directory (sorry, instructions not included).

Create the database you intend to use.

Set up all the secret password stuff in a .env file (which you must create) in the installation directory. See
the example file .env.example. These variables are needed:
* APP_ENV=local
* APP_DEBUG=true
* APP_KEY=[make up your key here]
* DB_HOST=[db server address, e.g. localhost]
* DB_DATABASE=[name of you database here]
* DB_USERNAME=[db user's username]
* DB_PASSWORD=[db user's password]
* CACHE_DRIVER=file
* SESSION_DRIVER=file
* QUEUE_DRIVER=sync
* MAIL_DRIVER=[smtp, mailgun, see the config/mail.php]
* MAILGUN_DOMAIN=[your domain, if you use mailgun]
* MAILGUN_SECRET=[your key]
* MAIL_HOST=[your smtp host]
* MAIL_PORT=587
* MAIL_USERNAME=[your mail username for the smtp server]
* MAIL_PASSWORD=[you smtp server password]
* MAIL_ENCRYPTION=tls
* MAIL_SENDER_ADDRESS=[sender address]
* MAIL_SENDER_NAME=[sender name]
* MAIL_PRETEND_TO_SEND=false

Finally, create the tables into the database using the migrate command:
* $ php artisan migrate

To test the installation you can run it with the built-in server:
* $ php artisan serve

## Status

This is the initial minimum viable product. New features on the roadmap:
* more UI features (delete, edit vehicle registration, maybe add description field to vehicle) 
* localization (fi, sv, en)
* graphs
* Angular front-end
* improved UI design

### License

The Tankkeri web application is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
