# Model, View, Controller Framework: MVC
Following along the MVC courses on Udemy.com by Dave Hollingworth [daveh.io](daveh.io).

  # Directory Setup

- App
	- Controllers
	- Models
	- Views
	- Auth.php
	- Config.php
	- Flash.php
	- Token.php
- Core
	- Controller.php
	- Error.php
	- Model.php
	- Router.php
	- View.php
- logs
- public
	- .htaccess
	- index.php
- vendor
- composer.json


## "App"lication Classes
The **Models**, **Views**, and **Controllers** can be found in the **App** sub-directory, which is also the class **namespace** for all classes saved here. Classes are named in StudlyCaps.

The Auth.php, Config.php, Flash.php, and Token.php files can also be found in this sub-directory.


### MODELS

Model files written in PHP are used in the Controllers to validate form data, make database connections, and prepare/bind/execute queries.

#### Post Model

The Post model interacts with the "posts" table in the database on behalf of the Posts controller.

**Post Model Methods**

* **getAll()** ```@param void @return array```
Retrieve all records.

#### User Model

The User model interacts with the "users" table in the database on behalf of the Users controller.

**User Model Methods**

* **__construct($data)** ```@param array @return void```
To create a new User object, loop through the $_POST superglobal array, saving each index and value to the calling object.
		
* **emailExists($email)** ```@param string @return boolean```
Receive the object from findByEmail and return whether the object is true or false.
```return static::findByEmail($email) !== false```

* findByEmail($email) ```@param string @return mixed object```
Check whether a given email already exists within the "users" table of the database and return the array given by the PDO->fetch() method on the prepared SQL statement.
		
* **save()** ```@param void @return boolean```
Run the private validate() function, and depending on whether the errors array is empty, prepare/bind/execute an insertion query to save form data into the database.
		
*  **validate()** ```@param void @return void```
Inspect the calling object, $this, and depending on a series of IF statements, add a new index and error message to the errors[] array.

---

### VIEWS

The Views sub-directories organize the HTML template files that create the website front-end. All templates inherit from the base template. Additionally, there are templates used when 404 and 500 HTTP status codes are sent from the Core\Error class using ```http_response_code($code)```.

The main View subdirectories are listed here.
* Admin		*Work-in-progress*
* Home		*Home page*
* Posts		*View all posts*
* Signup	*Create a new user record*
* Password	*Reset a forgotten password*

Templates are rendered using renderTemplate method of the [Twig 3.0](https://twig.symfony.com/) templating engine.

---

### CONTROLLERS

Controllers are classes which extend the Core\Controller class. Controllers have a number of methods which are called actions. Controllers, actions, and optional information (such as post ID's) are pulled from dispatching routes; e.g., http://localhost/posts/123/open


#### Home Controller

Given an empty query string (http://localhost/), the Home controller renders the home page from View\Home\index.html.

**Home Controller Methods**
* before() ```@param void @return void```
This is an optional action filter which can be used before rendering the template; for example, checking whether someone is logged in.
* after() ```@param void @return void```
This is an optional action filter to run after rendering the template.
* indexAction() ```@param void @return void```
```View::renderTemplate('Home/index.html')```

#### Password Controller

The Password Controller, which extends the Core Controller, is in charge of rendering the reset password form.

**Password Controller Methods**
* forgotAction() ```@param void @return void```
Render the Password/forgot View.
* requestResetAction() ```@param void @return void```
Deal with the reset password form in POST.


#### Posts Controller
This controller gets called upon when the query string contains "posts".

**Posts Controller Methods**
* addNewAction() ```@param void @return void```
*Work-in-progress*
* editAction() ```@param void @return void```
*Work-in-progress*
* indexAction() ```@param void @return void```
Use the App\Models\Post object to get all posts - this returns an array. Next, render the array of posts using the Core\View object.
	```php
	$posts = Post::getAll();
	View::renderTemplate('Posts/index.html',[
		'posts'=>$posts
	]);
	```

#### Signup Controller

The Signup controller relies on the Core\View to display forms (with sticky data as an optional argument) and App\Models\User to validate form data.

**Signup Controller Methods**
* createAction() ```@param void @return void```
Create a new User object from $_POST. If $user->save() returns TRUE, then redirect (303) to the success method and exit the script. Otherwise, re-render the form page, passing in $user as an argument to allow for sticky form data.
	```php
	View::renderTemplate('Signup/new.html',[
	'user'  =>  $user
	]);
	```

* newAction() ```@param void @return void```
Render new.html.
* successAction() ```@param void @return void```
Render success.html.


#### Admin/Users Controller
The Users controller is a work-in-progress.

**Admin/Users Methods**
* before() ```@param void @return void```
This is an optional action filter which could be used before the index action; this could be useful for authentication.
* after() ```@param void @return void```
This is an optional action filter which runs after the index method.
* indexAction() ```@param void @return void```
Use the View object to render the index.html file under the Admin sub-directory.

#### Login Controller

The Login controller allows the user to provide a username (email) and password to a form using the View object.

**Login Controller Methods**
* newAction() ```@param void @return void```
Render the login form
* creationAction() ```@param void @return void```
Authenticate the user

---

### Miscellaneous App Classes

#### Auth class

The Auth class is a plain yet busy class used to login from a email/password pair or from a "remember me" token, remembering URI's for later redirection, expiring cookies, and logging out.

**Auth class Methods**
* forgetLogin() ```@param void @return void```
If a remember_me COOKIE exists, then get its $token_hash value, delete the corresponding record from the rememberedLogins table, and then expire the COOKIE.
* getReturnToPage() ```@param void @return string```
Return either the SESSION-saved URI or '/', the root (home) route.
* getUser() ```@param void @return mixed```
If the SESSION user_id is set, then get the User model by user_id; if that doesn't work, then try to login from a remember_me token; if both fail, then return null (indicating no one has logged in).
* login($email,$password) ```@param string @param string @return void```
Create a new SESSION ID, save the user_id to a SESSION variable, and if the remember_me checkbox was sent with POST, then set the remember_me COOKIE.
* loginFromRememberedCookie() ```@param void @return mixed```
This protected static function looks for a remember_me COOKIE, and if found, finds the corresponding row in the database table using the RememberedLogin Model. So long as the token is not expired, the user is logged in from the COOKIE.
* logout() ```@param void @return void```
Nuke everything! Unset all SESSION variables, expire the SESSION ID cookie, destroy the SESSION, and then call the static forgetLogin method.
* rememberRequestedPage() ```@param void @return void```
Set a SESSION variable to store ```$_SERVER['REQUEST_URI']```.

#### Config class

#### Flash class

#### Mail class


#### Token class

---

## Core Classes

The Core classes are used to extend App Controllers and Models.

### Controller

### Error

### Model

### Router

### View