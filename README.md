------------------------------------------------------------------------------
 rlinkf 1.1 README
 (c) Green FiLin <me@zfilin.org.ua>, 2010
------------------------------------------------------------------------------

Contents:
---------
  1. About
  2. License
  3. Requirements
  4. Installation
  5. HowTo


1. About
--------
This PHP script collects Refs.s and creates your own simple redirects.

It usefull for you when:
  * You want to shorten the long url like this:
       http://www.eclipse.org/downloads/download.php?file=/technology/epp/downloads/release/galileo/SR2/eclipse-php-galileo-SR2-win32.zip
    to the url like:
       http://www.youdomain.com/download-eclipse
  * You want to make reference to information, the url of which can change. For example, the following url:
       http://www.youdomain.com/my-foto
    in 2009 refer to:
       http://www.photohoster.org/you-profile
    and in 2010 refer to:
       http://www.flickr.com/you-profile
  * You want to increase amount of Refs.s to your site (domain name) in Internet.
    

2. License
----------
This software was published under MIT License. See LICENSE in same folder.


3.Requirements
--------------

  * Apache 2.x or higher with mod_rewrite and module gettext
  * PHP 5.x

  
4. Installation
---------------

  1. Unpack and copy folder "rlinkf" to your hosting.
  2. Edit settings.php:
     * set correct path to your linkbase file (in "db_options - file");
     * change admin's username and password (warning: you must change username and password in purpose of secutiry);
  3. Setup permissions. Linkbase file must be writable by the web process. 
     For example in UNIX-like systems it can be done by a command:
        chmod 775 linkbase
  4. Enjoy!
  

5. HowTo
--------

For administration your list of links go to url: http://youdomain/script-folder/_admin_
Tag "_admin_" is a system tag and can't be redefined.

