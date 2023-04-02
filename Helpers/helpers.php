<?php

use Slate\Str;
    /*
        Helper functions to make life easier
        you can add your own functions here
    */

    if(!function_exists('dd')){ // dd() is a function that dumps data and dies
        function dd($data){
            echo "<pre>";
            var_dump($data);
            echo "</pre>";
            die();
        }
    }

    if(!function_exists('env')){ // env() is a function that gets the value of an environment variable
        function env($key, $default = null){
            $value = getenv($key);
            if($value === false){
                return $default;
            }
            return $value;
        }
    }

    if(!function_exists('config')){ // config() is a function that gets the value of a config variable
        function config($key, $default = null){
            $value = getenv($key);
            if($value === false){
                return $default;
            }
            return $value;
        }
    }

    if(!function_exists('render')){ // view() is a function that renders a view
        function render($view, $path, $data = []){
            $view = str_replace('.', '/', $view);
            $root = $_SERVER['DOCUMENT_ROOT'];
            $project = explode('/', $_SERVER['REQUEST_URI'])[1];
            $view = $root . "/". $project . "/" . $path . '.php';
            
            
            if(file_exists($view)){
                extract($data);
                require $view;
            }
            else{
                echo "View not found";
            }
        }
    }

    if(!function_exists('extend')){
        function extend($path, $data = []){
            $root = $_SERVER['DOCUMENT_ROOT'];
            $project = explode('/', $_SERVER['REQUEST_URI'])[1];
            $path = $root . "/". $project . "/" . $path . '.php';
           
      
            if(file_exists($path)){
                extract($data);
                require $path;
            }
            else{
                echo "View not found";
            }
        }
    }
    


   if(!function_exists('array_get')){ // array_get() is a function that gets the value of an array key
        function array_get($array, $key, $default = null){
            if(isset($array[$key])){
                return $array[$key];
            }
            return $default;
        }
   }

    if(!function_exists('array_set')){ // array_set() is a function that sets the value of an array key
          function array_set(&$array, $key, $value){
                $array[$key] = $value;
          }
    }   

    if(!function_exists('array_has')){ // array_has() is a function that checks if an array key exists
        function array_has($array, $key){
            return isset($array[$key]);
        }
    }

    if(!function_exists('array_forget')){ // array_forget() is a function that removes an array key
        function array_forget(&$array, $key){
            unset($array[$key]);
        }
    }

    if(!function_exists('array_pull')){ // array_pull() is a function that removes an array key and returns its value
        function array_pull(&$array, $key, $default = null){
            $value = array_get($array, $key, $default);
            array_forget($array, $key);
            return $value;
        }
    }

    if(!function_exists('array_only')){ // array_only() is a function that returns an array with only the specified keys
        function array_only($array, $keys){
            return array_intersect_key($array, array_flip((array) $keys));
        }
    }

    if(!function_exists('array_except')){ // array_except() is a function that returns an array with all keys except the specified keys
        function array_except($array, $keys){
            return array_diff_key($array, array_flip((array) $keys));
        }
    }

    if(!function_exists('array_first')){ // array_first() is a function that returns the first element of an array
        function array_first($array, $callback = null, $default = null){
            if(is_null($callback)){
                if(empty($array)){
                    return value($default);
                }
                foreach($array as $item){
                    return $item;
                }
            }
            foreach($array as $key => $value){
                if(call_user_func($callback, $value, $key)){
                    return $value;
                }
            }
            return value($default);
        }
    }

    if(!function_exists('array_last')){ // array_last() is a function that returns the last element of an array
        function array_last($array, $callback = null, $default = null){
            if(is_null($callback)){
                return empty($array) ? value($default) : end($array);
            }
            return array_first(array_reverse($array, true), $callback, $default);
        }
    }

    if(!function_exists('value')){ // value() is a function that returns the value of a variable
        function value($value){
            return $value instanceof Closure ? $value() : $value;
        }
    }

    if(!function_exists('array_where')){ // array_where() is a function that returns an array with all elements that pass a given truth test
        function array_where($array, $callback){
            $filtered = [];
            foreach($array as $key => $value){
                if(call_user_func($callback, $value, $key)){
                    $filtered[$key] = $value;
                }
            }
            return $filtered;
        }
    }

    if(!function_exists('array_collapse')){ // array_collapse() is a function that collapses a multi-dimensional array into a single array
        function array_collapse($array){
            $results = [];
            foreach($array as $values){
                if($values instanceof Traversable){
                    $values = iterator_to_array($values);
                }
                elseif(!is_array($values)){
                    continue;
                }
                $results = array_merge($results, $values);
            }
            return $results;
        }
    }


if(!function_exists('str_random')){ // str_random() is a function that generates a random string
    function str_random($length = 10, $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'){
        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }
}

if(!function_exists('str_contains')){ // str_contains() is a function that checks if a string contains a given substring
    function str_contains($haystack, $needles){
        foreach((array) $needles as $needle){
            if($needle != '' && mb_strpos($haystack, $needle) !== false){
                return true;
            }
        }
        return false;
    }
}

if(!function_exists('str_finish')){ // str_finish() is a function that adds a given value to the end of a string
    function str_finish($value, $cap){
        $quoted = preg_quote($cap, '/');
        return preg_replace('/(?:'.$quoted.')+$/', '', $value).$cap;
    }
}

if(!function_exists('str_is')){ // str_is() is a function that checks if a string matches a given pattern
    function str_is($pattern, $value){
        if($pattern == $value){
            return true;
        }
        $pattern = preg_quote($pattern, '#');
        $pattern = str_replace('\*', '.*', $pattern);
        $pattern = '#' . $pattern . '#u';
        return (bool) preg_match($pattern, $value);
    }
}

if(!function_exists('str_limit')){ // str_limit() is a function that limits the number of characters in a string
    function str_limit($value, $limit = 100, $end = '...'){
        if(mb_strwidth($value, 'UTF-8') <= $limit){
            return $value;
        }
        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')).$end;
    }
}

if(!function_exists('str_plural')){ // str_plural() is a function that returns the plural form of a word
    function str_plural($value, $count = 2){
        return Str::plural($value, $count);
    }
}

if(!function_exists('str_singular')){ // str_singular() is a function that returns the singular form of a word
    function str_singular($value){
        return Str::singular($value);
    }
}

if(!function_exists('str_slug')){ // str_slug() is a function that generates a URL friendly "slug" from a given string
    function str_slug($title, $separator = '-'){
        $title = Str::ascii($title);
        $flip = $separator == '-' ? '_' : '-';
        $title = preg_replace('!['.preg_quote($flip).']+!u', $separator, $title);
        $title = preg_replace('!['.preg_quote($separator).'\s]+!u', $separator, $title);
        return trim($title, $separator);
    }
}

if(!function_exists('go')){ // redirect() is a function that redirects the user to a given URL
    function go($page){
        header("Location: $page");
        exit;
    }
}

if(!function_exists('static_page')){ // static_page() is a function that returns the content of a static page
    function static_page($page){
        return file_get_contents("pages/$page.html");
    }
}


if(!function_exists('truncate')){
    function truncate($string, $length = 100, $append = '...'){
        $string = trim($string);
        if(strlen($string) > $length){
            $string = wordwrap($string, $length);
            $string = explode("\n", $string, 2);
            $string = $string[0].$append;
        }
        return $string;
    }
}
if(!function_exists('is_email')){
    function is_email($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }
}

if(!function_exists('is_url')){
    function is_url($url){
        if(filter_var($url, FILTER_VALIDATE_URL)){
            return true;
        }
        else{
            return false;
        }
    }
}

if(!function_exists('is_ip')){
    function is_ip($ip){
        if(filter_var($ip, FILTER_VALIDATE_IP)){
            return true;
        }
        else{
            return false;
        }
    }
}

if(!function_exists('is_mac')){
    function is_mac($mac){
        if(filter_var($mac, FILTER_VALIDATE_MAC)){
            return true;
        }
        else{
            return false;
        }
    }
}


if(!function_exists('is_int')){
    function is_int($int){
        if(filter_var($int, FILTER_VALIDATE_INT)){
            return true;
        }
        else{
            return false;
        }
    }
}

if(!function_exists('is_float')){
    function is_float($float){
        if(filter_var($float, FILTER_VALIDATE_FLOAT)){
            return true;
        }
        else{
            return false;
        }
    }
}

if(!function_exists('is_alpha')){
    function is_alpha($alpha){
        if(filter_var($alpha, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z]+$/")))){
            return true;
        }
        else{
            return false;
        }
    }
}

if(!function_exists('is_alphanum')){
    function is_alphanum($alphanum){
        if(filter_var($alphanum, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9]+$/")))){
            return true;
        }
        else{
            return false;
        }
    }
}

if(!function_exists('is_alphanum_dash')){
    function is_alphanum_dash($alphanum_dash){
        if(filter_var($alphanum_dash, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9_-]+$/")))){
            return true;
        }
        else{
            return false;
        }
    }
}

if(!function_exists('alphanum_with_space')){
    function alphanum_with_space($alphanum_with_space){
        if(filter_var($alphanum_with_space, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z0-9 ]+$/")))){
            return true;
        }
        else{
            return false;
        }
    }
}

if(!function_exists('is_active')){
    function is_active($page){
        $current_page = basename($_SERVER['PHP_SELF']);
        $current_page = str_replace('.php', '', $current_page);
        if($current_page == $page){
           return true;
        }
       
    }
}