# Twilio Skeleton Application built on the Jolt framework

Use this skeleton application to quickly setup and start working on a new Twilio application. This application uses the latest Jolt and Twilio repositories.

This skeleton application was built for Composer. This makes setting up a new Twilio application quick and easy.

## Install Composer

If you have not installed Composer, do that now. I prefer to install Composer globally in `/usr/local/bin`, but you may also install Composer locally in your current working directory. For this tutorial, I assume you have installed Composer locally.

<http://getcomposer.org/doc/00-intro.md#installation>

## Install the Application

After you install Composer, run this command from the directory in which you want to install your new Jolt Framework application.

    php composer.phar create-project freekrai/twilio-skeleton [my-app-name] -s dev

Replace <code>[my-app-name]</code> with the desired directory name for your new application. You'll want to point your virtual host document root to your new application's directory.

Edit config.ini with your configuration settings, and that's it! Now go build something cool.

## Structure

The Twilio Skeleton Application comes complete with a few pieces already in place.

1. SQLite database located in content/data/data.sqlite, this database holds user information as well as a call log. It also has a post table for doing whatever else you may want it to do.

2. Views, all output is handled by the views folder located in content/views/ the filenames correspond to actions used by the system.

3. User system, you can quickly set up a user system with the /signup, /login and /dashboard actions that are already set up. Once logged in, you will also see a user dashboard and the ability to manage other users.

4. Twimlets, we've cloned the Twilio Twimlet library to work here, so you can use them to quickly build out your Twilio applications.

5. Basic call handling, as a demo, we've included the ability to accept an incoming call and store it in the call log. This can be quickly built on to handle whatever else you want your app to do.

