# Trident
PHP MVC (Model View Controller) Framework

## Important note:
Trident framework was developed for learning purposes only. The only intention was to have better understanding of how PHP MVC frameworks work. This is NOT a full framework and not intented for use in real environments.

## Installation
Download the repository as a zip, and extract it to your development server.
It's recommended to keep the **application** and **vendor** directories outside of your public root directory.
Place the **public_html** directory content in your public root directory (don't forget the .htaccess and index.php files).
That is it. The framework is now installed and ready for use.

## Configuration
After you installed the framework, you need to configure the application so it will run in your development environment.
Open the **configuration.json** file in the **application/configuration** directory.
Edit the following to match your environment:
- Under **environment**, change the **time zone** to your time zone. Set **production** & **debug** as needed.
- Under **paths**, change **public** to your public URI.
- If you placed the directories in different places, under **paths**, change the required paths to fit your paths.
- If you intend to use database, then change **database** section properties as needed.

Change the **index.php** file if needed, to point to the location of the **trident.php** within the **vendor/trident** directory.

## Directory structure & naming rules
For the framework to work, you must keep a certain directory structure and naming rules.
The **application** directory structure is as followed:
- application
  - configuration (place your configuration files here)
  - controllers (place your controller classes here)
  - entities (place your entities classes here)
  - models (place your model classes here)
  - views (place your views here, in separate directory for each controller carrying it's name)
    - shared (place your shared views such as header & footer here)

Each class (controller, model etc.) will be placed in a single file, with the same name of the class in lower case and the **.class** suffix. For example: controller class named **Users_Controller** will be in a file named **users_controller.class.php** inside of the **controllers** directory.

### Creating controllers
Controller name must contain the suffix **_Controller** in the name, and extend from the class **Trident_Abstract_Controller**.
So a controller for users may be like:
```php
class Users_Controller extends Trident_Abstract_Controller
{
// Your controller functions
}
```
### Creating entities
Entity name must contain the suffix **_Entity** in the name, and extend from the class **Trident_Abstract_Entity**.
So an entity for user may be like:
```php
class User_Entity extends Trident_Abstract_Entity
{
// Your entity variables
}
```
### Creating models
Model name must contain the suffix **_Model** in the name, and extend from the class **Trident_Abstract_Model**.
So a model for users may be like:
```php
class Users_Model extends Trident_Abstract_Model
{
// Your model functions
}
```
### Creating views
View name must contain the suffix **_View** in the name, the relevant controller name as a prefix, the relevant function name in between and extend from the class **Trident_Abstract_View**.
So the view for the controller **Users_Controller** function **show** will be:
```php
class Users_Show_View extends Trident_Abstract_View
{
// Your view functions
}
```

## Routing
Routing is where the magic of matching a URI to actions within your web application happens. The framework routing engine is simple.
Routes by default are set in the **routes.json** file within the **configuration** directory (the file name and location can be changed in the configuration).
Each route contain 3 attributes:
```json
{
    "pattern": "pattern to match",
    "controller": "associated controller name",
    "function": "associated controller function name"
}
```
### Pattern
The pattern will match the content after your request URI. For example, if your application is at **http://www.example.com**, and you type in the browser **http://www.example.com/login**, the **/login** part is the request URI.
If you have a route with a pattern "/login", than the framework will load the required controller of the route and execute the required function from the controller.
If you have variable content, you can use parameters. For example, consider a request URI like this: **/users/profile/2** where **2** is the user id, so you can change it to any user id you want to show the profile of. In that case, the parameter can be set in the pattern like so **/users/profile/(id)**.

There are 2 types of parameters:
- Alphanumeric: to set an alphanumeric parameter, wrap it with curly braces. For example: **/articles/{title}**. This will only match if the supplied parameter contains only letters, digits, under score or a dash.
- Numeric: to set a numeric parameters, wrap it with regular braces. For example: **/profile/(id)**. This will only match if the supplied parameters contains only digits.

You can mix the 2 types within the same pattern, and you can place them freely (at the beginning, middle or end of the pattern).

### Default route
The default route is the route that will be used if no matching route was found. The default route is optional, but it is recommended to set it.
The default route doesn't require the pattern attribute, because it will match any pattern that doesn't match any of the defined routes patterns.

### Routes file
The routes file is defined as followed:
```json
{
    "default": {
        "controller": "controller to use as default",
        "function": "function within the default controller"
    },
    "routes": [
        {
            "pattern": "/",
            "controller": "base route controller",
            "function": "base route function"
        },
        {
            "pattern": "/route 1 pattern",
            "controller": "route 1 controller",
            "function": "route 1 function"
        }
        ...
    ]
}
```

The base route pattern is a simple slash "/", and will be used when the client doesn't include a request URI within his URL.

## Controllers
To implement a controller, simply extend it from the abstract class **Trident_Abstract_Controller**.

Within the controller you will have access to the following instances:
- **configuration**: a class that allows you to get configuration information.
- **request**: a wrapper to all of the request information (post, get, cookies etc.).
- **session**: a wrapper for handling PHP's session.
- **io**: a wrapper for file system io functions.
- **log**: a simple logging class.
- **database**: a database wrapper class for PHP's PDO (available only after loading in with **load_database** function).

### load_database
Use this function before you are trying to use the database object. This function will initialize the required database instance if one was not already initialized.

### load_model
Use this function to create a Model instance easily. All the required constructor injections will be handled for you.

### load_view
Use this function to create a View instance easily. All the required constructor injections will be handled for you.
This function takes 2 parameters. The first is the **view_data**. It's recommended to pass an _associative_ array as the view data, in order to address data values easily.
The second one is the **view** and it is set by default to null. If the **view** parameter is null, the function will automaticaly try to create a view according to the calling controller name and function.
For example:
```php
class Users_Controller extends Trident_Abstract_Controller
{
    public function index()
    {
        $view = $this->load_view();
    }
}
```
The **load_view** function in this case will attempt to load a class called **Users_Index_View**, and will look for the file **users_index_view.class.php** within the **application/views/users** directory.

## Database
Trident includes an abstract class named **Trident_Abstract_Database** that extends to PDO class, and an abstract query class called **Trident_Abstract_Query** that allows for simple handling of queries.
You can implement those class for any database engine supported by PHP's PDO. The framework already includes an implementation for MySql for those 2 abstract classes.

### MySql Database
There are 2 classes named **Trident_Database_MySql** and **Trident_Query_MySql** that are used for handling database functionality. Those classes require that the **database** section in the configuration will be properly configured, and in order for them to be used, the **load_database** function in the controller must be called before they are used.

#### Trident_Query_MySql class
The class contains the following important attributes:
- **query_string**: contains the query string (for example: SELECT * FROM users)
- **parameters**: contains an array of key => value pairs of the query parameters.
- **success**: after the query was executed, sets to **true** for successful execution, or **false** if failed.
- **row_count**: after the query was executed, sets to the number of rows that where returned in a select statement, or changed in an update statement.
- **result_set**: after the query was executed, sets to the resulting rows of a select statement.
- **last_inserted_id**: after the query was executed, and the query was an insert query of a table with an primary key that is auto incrementing, sets to the last value that was entered by the query in the database.
- **error_code**: after the query was executed, contain the MySql error code if any occurred.
- **error_description**: after the query was executed, contain the MySql error description if any occurred.
- **type**: contains the type of the query (select, insert, delete etc.).

The class also contains 4 quick query builders for the common query types (select, insert, update, delete):
- **select**: builds the **query_string** attribute for a select statement according to the parameters supplied to the function.
- **insert**: builds the **query_string** attribute for an insert statement according to the parameters supplied to the function.
- **update**: builds the **query_string** attribute for an update statement according to the parameters supplied to the function.
- **delete**: builds the **query_string** attribute for a delete statement according to the parameters supplied to the function.

#### Trident_Database_MySql
When the configuration section **database** attribute **type** is set to **mysql**, the **load_database** function of the controller will create an instance of this class, using the credentials set in the configuration.
The class contains all the PDO class functions, as it extends from the class **Trident_Abstract_Database** which in turns, extends from PDO, and also a function named **run_query**.
The **run_query** function takes a **Trident_Query_MySql** instance as a parameter, executes the query as needed and return a **Trident_Query_MySql** object with the result of the execution.

Simple example from within a Trident_Abstract_Model implementation instance:
```php
class Users_Model extends Trident_Abstract_Model
{
    public function get_all()
    {
        $q = new Trident_Query_MySql();
        $q->select('users'); // Will set $q->query_string to "SELECT * FROM users WHERE 1"
        $q = $this->database->run_query($q);
        if ($q->success)
        {
            return $q->result_set;
        }
        else
        {
            // Handle error
        }
    }
}
```
### Database and Entities
One of the most powerful features of **Trident** is the ability to perform an "ORM" like functions. **Trident** is not an ORM framework, and it doesn't try to be one, but to make life a little easier, there are 4 functions to bridge between entity objects and the database tables that represent them.

#### select_entity
This function takes 4 parameters:
- **entity**: the name of the entity class (with or without the **_Entity** suffix).
- **parameters**: query parameters if needed.
- **query**: the select query.
- **prefix**: a field prefix if one is present.

The function return a Query object, where the **result_set** attribute contains an array of objects of the require entity instead of an associative array.
```php
class Users_Model extends Trident_Abstract_Model
{
    public function get_all()
    {
        $q = $this->database->select_entity('user', 'SELECT * FROM users', [], 'user_');
        return $q->result_set;
    }
}
```

#### insert_entity
This function takes 3 parameters:
- **entity**: an entity object.
- **table**: the table name within the database.
- **prefix**: field prefix in the database.

The function return a Query object with the result of the query.
```php
class Users_Model extends Trident_Abstract_Model
{
    public function add($entity)
    {
        $q = $this->database->insert_entity($entity, 'users', 'user_');
        return $q->success;
    }
}
```

#### update_entity
This function takes 4 parameters:
- **entity**: an entity object.
- **table**: the table name within the database.
- **id_field**: the name of the primary key field.
- **prefix**: field prefix in the database.

The function return a Query object with the result of the query.
```php
class Users_Model extends Trident_Abstract_Model
{
    public function update($entity)
    {
        $q = $this->database->update_entity($entity, 'users', 'id', 'user_');
        return $q->success;
    }
}
```

#### delete_entity
This function takes 4 parameters:
- **entity**: an entity object.
- **table**: the table name within the database.
- **id_field**: the name of the primary key field.
- **prefix**: field prefix in the database.

The function return a Query object with the result of the query.
```php
class Users_Model extends Trident_Abstract_Model
{
    public function remove($entity)
    {
        $q = $this->database->delete_entity($entity, 'users', 'id', 'user_');
        return $q->success;
    }
}
```

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
- Animate.css (https://github.com/daneden/animate.css)
- PHPMailer (https://github.com/PHPMailer/PHPMailer)
- PHP XLSXWriter (https://github.com/mk-j/PHP_XLSXWriter)
- PHP User Agent (https://github.com/donatj/PhpUserAgent)

## License:
This framework is released under the MIT license. See LICENSE for more information.
Feel free to use, change and redistribute the framework.
