<?php 
namespace Core;
/* Core Router class */
class Router//Based on the route, gets the Controller and Action.
{
    /* Array for the Routing Table */
    protected $routes = [];

    /* Saved Properties of the Matched Route */
    protected $params = [];

    /* METHOD: add
    *   @param string   :   The route
    *   @param string   :   The controller or action or namespace (optional)
    *   @return void    :   ADD ROUTE TO ROUTING TABLE
    */
    public function add($route,$params = []) {//$params is apparently now an optional argument
        /* CONVERT ROUTES TO REGULAR EXPRESSIONS */
        //1 ESCAPE FWD SLASH ( / ==> \/ )
        $route = preg_replace('/\//','\\/',$route);

        //2 CONVERT {word} ==> named capture group
        $route = preg_replace('/\{([a-z]+)\}/','(?P<\1>[a-z-]+)',$route);//note: \1 is a backreference to [1] which contains the capture group

        //3 CONVERT VARIABLES (EX: {id:\d+})
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/','(?P<\1>\2)',$route);

        //4 ADD START / END DELIMITERS (^ and $) and Case-Insensitive tag, i
        $route = '/^' . $route . '$/i';

        //ADD THE ROUTE TO THE TABLE
        $this->routes[$route] = $params;
    }

    /* METHOD: dispatch
    *   @param string   :   The query string from the $_SERVER superglobal array
    *   @return void    :   If a route gets matched, then execute the matched controller action (or throw fits)
    */
    public function dispatch($url) {
        /* Remove the query string variables from the string using a custom protected method in the core Router class */
        $url = $this->removeQueryStringVariables($url);
        
        /* Compare the provided $url without variables to the routes in the routing table */
        if ($this->match($url)) {
            # Save the controller name to a variable
            $controller = $this->params['controller'];

            # Apply StudlyCaps to the controller name (since we named our PHP controller files with Uppercase)
            $controller = $this->convertToStudlyCaps($controller);

            /* Add the namespace to the controller using a custom protected method from the core Router class */
            $controller = $this->getNamespace() . $controller;
            
            /* Check whether the controller name even exists as a class within the namespace */
            if (class_exists($controller)) {

                /* If it exists, then create a new class instance */
                $controller_object = new $controller($this->params);//$this->params is the parameters coming from the Router
                
                /*  Stash the 'action' name from the calling Router object */
                $action = $this->params['action'];

                /*  Apply camelCase to the method name */
                $action = $this->convertToCamelCase($action);

                /*  Before executing the action, make sure that its name does not have "Action" appended to it, which could allow someone trying to bypass authentication */
                if (preg_match('/action$/i',$action)==0) {
                    # ALL GOOD: EXECUTE ACTION
                    $controller_object->$action();
                } else {
                    /*  The action name likely had "Action" appended to the end. 
                    *   We know that normally, the method name will not be found due to the missing suffix; subsequently, the __call magic method will be called. 
                    */
                    throw new \Exception("Method $action was not found in controller $controller.");
                }
            } else {
                /* Error: The class was not found in the namespace */
                throw new \Exception("Controller class $controller was not found.");
            }
        } else {
            /* Error: The $url was not found in the routing table */
            throw new \Exception("No route was matched.",404);
        }
    }

    /* METHOD: convertToStudlyCaps
    *   @param string   :   The controller name (e.g., Posts)
    *   @return string  :   The same controller name, ButWithStudlyCaps - same as controller PHP file names
    */
    protected function convertToStudlyCaps($string) {
        /*
        *1. Replace dashes with spaces      (my-name-is) --> (my name is)
        *2. Capitalize all words            (my name is) --> (My Name Is)
        *3. Remove spaces                   (My Name Is) --> (MyNameIs)
        */
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /* METHOD: convertToCamelCase
    *   @param string   :   The action name (e.g., new)
    *   @return string  :   The action name, in camelCase
    */
    protected function convertToCamelCase($string) {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /* METHOD: getNamespace
    *   @param void     :
    *   @return string  :   Either return the default namespace, or a custom namespace from the routing table
    */
    protected function getNamespace() {
        /* set the default namespace */
        $namespace = 'App\Controllers\\';

        /* Check whether a namespace was defined in the calling object */
        if(array_key_exists('namespace',$this->params)) {
            /* If a namespace was defined, then replace the default namespace. */
            $namespace .= $this->params['namespace'] . '\\';
        }
        /* send it back */
        return $namespace;
    }

    /* METHOD: getParams
    *   @param void     :
    *   @return array   :   Return the route parameters of the calling Router object.
    */
    public function getParams() {
        return $this->params;
    }

    /* METHOD: getRoutes
    *   @param void     :
    *   @return array   :   Show all the routes in the calling Router object.
    */
    public function getRoutes() {
        return $this->routes;
    }

    /* METHOD: match
    *   @param string   :   The route to match
    *   @return boolean :   Check whether the query string matches any route indices.
    */
    public function match($url) {
        /* Loop through each row in the routing table of the core Router object */
        foreach ($this->routes as $route => $params) {
            if (preg_match($route,$url,$matches)) {
                /* Save the values for all named capture groups
                $params = [];*/
                foreach ($matches as $index => $value) {
                    if (is_string($index)) {
                        $params[$index] = $value;
                    }//close if
                }//close foreach
                
                /* Set the params of the calling Router object equal to the saved parameters from the capture groups */
                $this->params = $params;
                return true;
            }//close if
        }
        return false;
    }

    /* METHOD: removeQueryStringVariables
    *   @param string   :   The entire query string from the $_SERVER superglobal array
    *   @return string  :   Strip the query string from the URL by looking for "&" and "=" symbols.
    */
    protected function removeQueryStringVariables($url) {
        /* CHECK WHETHER URL IS EMPTY */
        if ($url != '') {
            /* SPLIT THE URL AT THE AMPERSAND ("&") DELIMITER */
            $parts = explode('&',$url,2);

            /* SEARCH THE FIRST INDEX OF THE $parts ARRAY FOR THE EQUALS ("=") SYMBOL */
            if(strpos($parts[0],'=') === false) {
                /* IF NO EQUALS ("=") SIGN IS FOUND, THEN IT MEANS THE QUERY VARIABLES ARE GONE. */
                $url = $parts[0];
            } else {
                $url = '';
            }
        }//close if
        return $url;
    }
};
?>