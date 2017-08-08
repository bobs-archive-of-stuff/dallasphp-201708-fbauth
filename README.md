# Adding Facebook Auth as an Afterthought

This is a little example application which uses Facebook authentication to log
in and create new users on the fly. While it is not designed to the base of
your next one million dollar project, the Auth router at the very least should
be of interest to see how to step through the Facebook process.

First load you should see a list of users and a Log In link. Clicking the link
will step you through the Facebook auth, after which you will be logged in
and shown information about the authentication token. If for some reason you
manage to fail auth, like by clicking cancel, you will be greeted with whatever
the error was preventing auth instead. If you were not already in the database
you will be added to it.

## Requires

 * PHP 7.1
 * PDO w\ SQLite3.
 * (Graph-SDK itself requires neither)

## Instructions

 * $git clone https://github.com/bobs-archive-of-stuff/dallasphp-201708-fbauth.git
 * $composer install
 * $php -S localhost:80 -t www
