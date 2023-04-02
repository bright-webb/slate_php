<?php
    namespace Slate;
    class Auth{
        public static function check(){
            if(isset($_SESSION['user'])){
                return true;
            }
            else{
                return false;
            }
        }

        public static function user(){
            if(self::check()){
                return $_SESSION['user'];
            }
            else{
                return false;
            }
        }

        public static function id(){
            if(self::check()){
                return $_SESSION['user']->id;
            }
            else{
                return false;
            }
        }


        public static function attempt($email, $password){
            $user = (new Connection)->select("SELECT * FROM users WHERE email = '$email' AND password = '$password'");
            if(count($user) > 0){
                $_SESSION['user'] = $user[0];
                return true;
            }
            else{
                return false;
            }
        }

        public static function logout(){
            unset($_SESSION['user']);
        }

        public static function guest(){
            if(!self::check()){
                return true;
            }
            else{
                return false;
            }
        }

        public function handle(){
            if(!self::check()){
                return go('login');
            }
        }

        public function login($username, $password){
            $user = (new Connection)->select("SELECT * FROM users WHERE username = '$username' AND password = '$password'");
            if(count($user) > 0){
                $_SESSION['user'] = $user[0];
                return true;
            }
            else{
                return false;
            }
            
        }

        /*
        *   insert data into a table
        *   @param $table string
        *   @param $data array
        *   @return int
        */
        public function insert($table, $data = []){
            $fields = implode(',', array_keys($data));
            $values = implode("','", array_values($data));
            $sql = "INSERT INTO $table ($fields) VALUES ('$values')";
            $query = (new Connection)->query($sql);
            if($query){
                return (new Connection)->lastId();
            }
            else{
                return false;
            }
        }

        /* 
        *   register a new user and check if the user exists
        *   @param $table string
        *   @param $data array
        *   @return bool
        */

        public function register($table, $data = [], $column = 'email'){
            if($this->exists($table, $column, $data[$column])){
                return (new Connection)->lastId();
            }
            else{
                $this->insert($table, $data);
                return true;
            }
        }

        /*
        *   Check if a user exists
        *   @param $table string
        *   @param $field string
        *   @param $value string
        *   @return bool
        */

        public function exists($table, $field, $value){
            $sql = "SELECT * FROM $table WHERE $field = '$value'";
            $query = (new Connection)->query($sql);
            if($query->rowCount() > 0){
                return true;
            }
            else{
                return false;
            }
        }
    }
?>