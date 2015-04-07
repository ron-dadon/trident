# Trident
PHP MVC (Model View Controller) Framework

## Installation
Download the repository as a zip, and extract it to your development server.
It's recommended to keep the _application_ and _vendor_ directories outside of your public root directory.
Place the *public_html* directory content in your public root directory (don't forget the .htaccess and index.php files).
That is it. The framework is now installed and ready for use.

## Configuration
After you installed the framework, you need to configure the application so it will run in your development environment.
Open the _configuration.json_ file in the _application/configuration_ directory.
Edit the following to match your environment:
- Under **environment**, change the **time zone** to your time zone. Set **production** & **debug** as needed.
- Under **paths**, change **public** to your public URI.
- If you placed the directories in different places, under **paths**, change the required paths to fit your paths.
- If you intend to use database, then change **database** section properties as needed.

## Directory structure & naming rules
For the framework to work, you must keep a certain directory structure and naming rules.
The _application_ directory structure is as followed:
- application
-- configuration (place your configuration files here)
-- controllers (place your controller classes here)
-- entities (place your entities classes here)
-- models (place your model classes here)
-- views (place your views here, in separate directory for each controller carrying it's name)
--- shared (place your shared views such as header & footer here)

## Used projects
**The following free/open source projects are used inside the framework or included as an extension library:**
- Bootstrap 3.3.4 (https://github.com/twbs/bootstrap)
- jQuery 1.11.2 (https://jquery.com/)
- Font Awesome 4.3 (https://github.com/FortAwesome/Font-Awesome)
- Arimo Web Font (https://www.google.com/fonts/specimen/Arimo)
- Bootstrap File Input (https://github.com/kartik-v/bootstrap-fileinput/)
- Bootstrap Grid (Bootgrid) (https://github.com/rstaib/jquery-bootgrid)
- Bootstrap Select (https://github.com/silviomoreto/bootstrap-select)
- Bootstrap Tree (https://github.com/jonmiles/bootstrap-treeview)
- Bootstrap Validator (https://github.com/1000hz/bootstrap-validator)
- Bootstrap RTL (https://github.com/morteza/bootstrap-rtl)
- PHPMailer (https://github.com/PHPMailer/PHPMailer)
- PHP XLSXWriter (https://github.com/mk-j/PHP_XLSXWriter)
- PHP User Agent (https://github.com/donatj/PhpUserAgent)
