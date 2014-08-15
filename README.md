# Twilio Skeleton Application built on the Laravel framework

Use this skeleton application to quickly setup and start working on a new Twilio application. This application uses the latest Laravel and Twilio repositories.

This skeleton application was built for Composer. This makes setting up a new Twilio application quick and easy.

## Install Composer

If you have not installed Composer, do that now. I prefer to install Composer globally in `/usr/local/bin`, but you may also install Composer locally in your current working directory. For this tutorial, I assume you have installed Composer locally.

<http://getcomposer.org/doc/00-intro.md#installation>

## Install the Application

After you install Composer, run this command from the directory in which you want to install your new Laravel Framework application.

    php composer.phar create-project freekrai/twilio-skeleton [my-app-name]

Replace <code>[my-app-name]</code> with the desired directory name for your new application. You'll want to point your virtual host document root to your new application's directory.

## Configure the Application

In a text editor, go to app/config/ and open the twilio.php file.

Edit your AccountSid, AuthToken and FromNumber variables with your Twilio account information.