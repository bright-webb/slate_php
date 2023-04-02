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
    }
?>