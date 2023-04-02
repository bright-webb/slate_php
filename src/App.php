<?php
    namespace Slate;

    class App{
        public $connection;

        public function __construct(){
            $this->connection = new Connection();
        }

        public static function start(){
            if(self::isDev()){
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
                $project = explode('/', $_SERVER['REQUEST_URI'])[1];
            }
            else{
                ini_set('display_errors', 0);
                ini_set('display_startup_errors', 0);
                error_reporting(0);
            }
            define('APP_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/'.$project.'/www');
            $url = $_SERVER['REQUEST_URI'];
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            if(preg_match('/\.[A-Za-z0-9]+$/', $path)){
               $path = preg_replace('/\.[A-Za-z0-9]+$/', '', $path);
            }
            $path = str_replace('/'.$project.'/', '', $path);

            $file_path = APP_ROOT . '/'. $path . '.php';
            if(file_exists($file_path)){
                return require($file_path);
            }
            else{
                if($url === '/' || $url === '/'.$project.'/'){
                    require(APP_ROOT . '/default.php');
                }
                else{
                    echo "file not found";
                }
            }
          
        }
        public function query($sql){
            return $this->connection->query($sql);
            // usage
            // $app->query('SELECT * FROM users');
        }

        /* 
        * select data from the database
        * @param string $table
        * @param array $columns
        * @param array $where
        * @param array $order
        * @param array $limit
        * @return array
        */

        public function selectWhere($table, $columns, $where, $order = [], $limit = []){
            $sql = "SELECT ";
            $sql .= implode(', ', $columns);
            $sql .= " FROM $table";
            $sql .= " WHERE ";
            $sql .= implode(' AND ', $where);
            if(count($order) > 0){
                $sql .= " ORDER BY ";
                $sql .= implode(', ', $order);
            }
            if(count($limit) > 0){
                $sql .= " LIMIT ";
                $sql .= implode(', ', $limit);
            }
            return $this->connection->select($sql);
            
        }

        /*
        * select data from the database
        * @param string $table
        * @param array $columns
        * @param array $order
        * @param array $limit
        * @return array
        */

        public function select($table, $columns, $order = [], $limit = []){
            $sql = "SELECT ";
            $sql .= implode(', ', $columns);
            $sql .= " FROM $table";
            if(count($order) > 0){
                $sql .= " ORDER BY ";
                $sql .= implode(', ', $order);
            }
            if(count($limit) > 0){
                $sql .= " LIMIT ";
                $sql .= implode(', ', $limit);
            }
            return $this->connection->select($sql);
           
        }

        /*
        * insert data into the database
        * @param string $table
        * @param array $data
        * @return bool
        */

        public function insert($table, $data){
            
            $sql = "INSERT INTO $table (";
            $sql .= implode(', ', array_keys($data));
            $sql .= ") VALUES (";
            $sql .= "'" . implode("', '", array_values($data)) . "'";
            $sql .= ")";
            if($this->connection->insert($sql)){
                return true;
            }
            else{
                return false;
            }
        }

        /*
        * update data in the database
        * @param string $table
        * @param array $data
        * @param array $where
        * @return bool
        */

        public function update($table, $data, $where){
            $sql = "UPDATE $table SET ";
            $sql .= implode(', ', $data);
            $sql .= " WHERE ";
            $sql .= implode(' AND ', $where);

            if($this->connection->update($sql)){
                return true;
            }
            else{
                return false;
            }
        }

        /*
        * delete data from the database
        * @param string $table
        * @param array $where
        * @return bool
        */

        public function delete($table, $where){
            $sql = "DELETE FROM $table WHERE ";
            $sql .= implode(' AND ', $where);

            if($this->connection->delete($sql)){
                return true;
            }
            else{
                return false;
            }
        }

        /*
        * get the last inserted id
        * @return int
        */

        public function lastId(){
            return $this->connection->lastId();
        }

        // error handling
        public function error($code){
            $root = $_SERVER['DOCUMENT_ROOT'];
            $path = "error";
            $view = $code;
            $data = [];
            render($view, $path, $data);
        }

        // get the current url
        public function url(){
            $url = $_SERVER['REQUEST_URI'];
            $url = explode('?', $url);
            return $url[0];

    
        }

        // custom error handler
        public function errorHandler($code, $message, $file, $line){
            $this->error($code);

            // log the error
            $this->log($message, $file, $line);
           
        }

        // custom exception handler
        public function exceptionHandler($exception){
            $this->error(500);

            // log the error
            $this->log($exception->getMessage(), $exception->getFile(), $exception->getLine());
            
        }

        // log the error
        public function log($message, $file, $line){
            $log = $message . ' in ' . $file . ' on line ' . $line;
            $log = '[' . date('Y-m-d H:i:s') . '] ' . $log . PHP_EOL;
            $log = str_replace('\\', '/', $log);
            $log = str_replace(' ', '_', $log);
            $log = str_replace(':', '-', $log);
            $log = str_replace('/', '-', $log);
            $log = str_replace(']', '', $log);
            $log = str_replace('[', '', $log);
        }

        // get the current page
        public function page(){
            $url = $this->url();
            $url = explode('/', $url);
            $url = array_filter($url);
            $url = array_values($url);
            return $url[0];

        }

        // middleware for authentication and authorization 
        public function middleware($middleware){
            $middleware = new $middleware;
            return $middleware->handle();
        }

        // redirect to a page
        public function redirect($page){
            header('Location: ' . $page);
            exit;
        }

        // get the current user
        public function user(){
            if(isset($_SESSION['user'])){
                return $_SESSION['user'];
            }
            else{
                return false;
            }
        }

        // get the current user id
        public function userId(){
            if(isset($_SESSION['user'])){
                return $_SESSION['user']['id'];
            }
            else{
                return false;
            }
        }

       // method to extend another part of a page in another page
       public function extend($view, $path, $data = []){
        $root = $_SERVER['DOCUMENT_ROOT'];
        $path = $path;
        $view = $view;
        $data = $data;
        render($view, $path, $data);

       }

         // method to include another part of a page in another page
            public function include($view, $path, $data = []){
                $root = $_SERVER['DOCUMENT_ROOT'];
                $path = $path;
                $view = $view;
                $data = $data;
                render($view, $path, $data);
            }

        // method to render a view
        public function render($view, $path, $data = []){
            $root = $_SERVER['DOCUMENT_ROOT'];
            $path = $path;
            $view = $view;
            $data = $data;
            render($view, $path, $data);
        }

        public static function locale(){
           if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
                $language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                $parts = explode(',', $language);
                $locale = substr($parts[0], 0, -2);
               echo $locale;
           }
           else{
               echo 'en';
           }
        }

        public function truncate($string, $length = 100, $append = "&hellip;"){
            $string = trim($string);
        
            if(strlen($string) > $length){
                $string = wordwrap($string, $length);
                $string = explode("\n", $string, 2);
                $string = $string[0] . $append;
            }
        
            return $string;
        }

        public function isActive($page){
            $current_page = $this->page();
            if($current_page == $page){
                return 'active';
            }
            else{
                return '';
            }
        }

        public function isEmail($email){ // checks if email is valid
            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isUrl($url){ // checks if url is valid
            if(filter_var($url, FILTER_VALIDATE_URL)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isIp($ip){ // checks if ip is valid
            if(filter_var($ip, FILTER_VALIDATE_IP)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isInt($int){ // checks if int is valid
            if(filter_var($int, FILTER_VALIDATE_INT)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isFloat($float){ // checks if float is valid
            if(filter_var($float, FILTER_VALIDATE_FLOAT)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isAlpha($string){ // checks if string is alpha
            if(preg_match('/^[a-zA-Z]+$/', $string)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isAlphanum($string){ // checks if string is alpha numeric
            if(preg_match('/^[a-zA-Z0-9]+$/', $string)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isAlphanum_space($string){ // checks if string is alpha numeric with spaces
            if(preg_match('/^[a-zA-Z0-9 ]+$/', $string)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isAlphanum_dash($string){ // checks if string is alpha numeric with dashes
            if(preg_match('/^[a-zA-Z0-9-]+$/', $string)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isPhone($phone){ // checks if phone number is valid
            if(preg_match('/^[0-9]{10}+$/', $phone)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isPassword($password){ // checks if password is valid
            if(preg_match('/^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z!@#$%]{8,12}$/', $password)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isDate($date){ // checks if date is valid
            if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isTime($time){ // checks if time is valid
            if(preg_match('/^[0-9]{2}:[0-9]{2}:[0-9]{2}$/', $time)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isDatetime($datetime){ // checks if datetime is valid
            if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $datetime)){
                return true;
            }
            else{
                return false;
            }
        }

        public function isImage($image){ // checks if image is valid
            $allowed = ['jpg', 'jpeg', 'png', 'gif']; // add more extensions if you want
            $ext = pathinfo($image, PATHINFO_EXTENSION);
            if(in_array($ext, $allowed)){
                return true;
            }
            else{
                return false;
            }
        }

        public static function isDev(){
            if($_SERVER['SERVER_NAME'] == 'localhost'){
                return true;
            }
            else{
                return false;
            }
        }
        

    }
?>