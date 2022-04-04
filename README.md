# Model, View, Controller Framework: MVC
Following along the MVC courses on Udemy.com by Dave Hollingworth [daveh.io](daveh.io).

## How to use it
1. Git clone the public HTTPS. Remove the .git directory if you want a standalone project. Run ```git init``` to create your own git repo.
2. Run ```composer install```.
3. Edit the routes on `public/index.php` to match your own Controllers and actions.


## App Namespace
The **Models**, **Views**, and **Controllers** can be found in the **App** sub-directory, which is the class **namespace** for all classes saved here. These classes are auto-loaded via PSR-4 in `composer.json`. Classes are named in StudlyCaps.

The `Auth`, `Config`, `Flash`, `Mail`, and `Token` classes can also be found in the `App` namespace. These classes add functionality such as creating cookies, storing database credentials, saving error messages to `$_SESSION` variables, connecting with the Mailgun API, and creating token hashes.


### MODELS

Model files written in PHP are used in the Controllers to validate form data, make database connections, and prepare/bind/execute queries. This keeps SQL queries out of other scripts and presentation code. The PHP Data Object (PDO) is used, which is useful at the least because it auto-escapes queries.

### VIEWS

The Views sub-directories organize the HTML template files that create the website front-end. All templates inherit from the base template. Additionally, there are templates for 404 and 500 HTTP status codes, which are semt from the Core\Error class using ```http_response_code($code)```.

The main View subdirectories are listed here.
* Admin		*Work-in-progress*
* Home		*Home page*
* Posts		*View all posts*
* Profile	*Private view for logged-in user*
* Signup	*Create a new user record*
* Password	*Reset a forgotten password*

Templates are rendered using renderTemplate method of the [Twig 3.0](https://twig.symfony.com/) templating engine.


### CONTROLLERS

Controllers are classes which extend either the `Core\Controller` class, or for private pages, the `Authenticated` class. Controllers have a number of methods which are called actions. Controllers, actions, and optional information (such as post ID's) are pulled from dispatching routes; e.g., http://localhost/posts/123/open


## Core Namespace

The `Core` classes are used to extend App Controllers and Models. These are contained in the Core subdirectory, which is also the namespace, and loaded via PSR-4 in `composer.json`.

### Controller

This core `Controller` is the parent of all controllers. It has two magic methods __call and __construct which deal with creating new objects from the Router. It also has a method to redirect and restrict URLs.

### Error

The `Error` class has methods to deal with both errors and exceptions. Depending on the settings in the `App/Config` file, it will either display or log an error when something goes wrong on the site.

### Model

The core `Model` class has a common, static method to make a database connection. The database connection gets cached.

### Router

The core `Router` class accepts routes from the front controller, `index.php`, and adds them to the routing table. It also has supplementary methods to convert the URL to StudlyCaps or camelCase, get the namespace, remove query string variables, and more, all of which get used when dispatching the URL in the front controller.

### View

The core `View` class is what interfaces with the Twig templating engine. The `getTemplate` method loads the Twig environment, and the `renderTemplate` method takes it one step further by echoing that template.
