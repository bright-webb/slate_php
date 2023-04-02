<?php
    namespace Slate;

    class Connection{
        public $pdo;

        public function __construct(){
            try{
                $this->pdo = new \PDO(
                    'mysql:host='.env('DB_HOST').';dbname='.env('DB_NAME'),
                    env('DB_USER'),
                    env('DB_PASSWORD'),
                    [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                    ]
                );
                
            }
            catch(\PDOException $e){
                die($e->getMessage());
            }
        }

        public function query($sql){
            return $this->pdo->query($sql);
        }

        public function execute($sql){
            return $this->pdo->exec($sql);
        }

        public function select($sql){
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            return $statement->fetchAll(\PDO::FETCH_OBJ);
        }

        public function insert($sql){
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            if($statement->rowCount() > 0){
                return true;
            }
            else{
                return false;
            }
        }

        public function insertGetId($sql){
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            if($statement->rowCount() > 0){
                return $this->pdo->lastInsertId();
            }
            else{
                return false;
            }
        }

        public function lastId(){
            return $this->pdo->lastInsertId();
        }

        public function update($sql){
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            if($statement->rowCount() > 0){
                return true;
            }
            else{
                return false;
            }
        }

        public function delete($sql){
            $statement = $this->pdo->prepare($sql);
            $statement->execute();
            return $statement->rowCount();
        }

        public function prepare($sql){
            return $this->pdo->prepare($sql);
        }

        public function escape($value){ // escape string for sql query 
            return $this->pdo->quote($value); 
        }
    }
?>