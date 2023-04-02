<?php
    namespace Slate;

use Throwable;

    class SlateException extends \Exception{
        public function __construct($message, $code = 0, \Exception $previous = null){
            parent::__construct($message, $code, $previous);
        }

        public function __toString(){
            return __CLASS__ . ": [{$this->code}]: {$this->message}]n";
        }

        public function customFunction(){
            echo "A custom function for this type of exception";
        }

        public function log(){
            $log = $this->message . ' in ' . $this->file . ' on line ' . $this->line;
            $log = '[' . date('Y-m-d H:i:s') . '] ' . $log . PHP_EOL;
            $log = str_replace('\\', '/', $log);
            $log = str_replace(' ', '_', $log);
            $log = str_replace(':', '-', $log);
            $log = str_replace('/', '-', $log);
            $log = str_replace(']', '', $log);
            $log = str_replace('[', '', $log);
            file_put_contents('logs/' . $log, $log, FILE_APPEND);
        }

        public function error(){
            $this->log();
            $this->customFunction();
            $this->console();
        }

        public function console(){
            echo '<script>console.log("' . $this->message . '")</script>';
        }

        public function maximumExecutionTime(){
            try{
                set_time_limit(30);
            }
            catch(Throwable $e){
                if($e instanceof \Error && $e->getCode() === 1 && strpos($e->getMessage(), 'Maximum execution time') !== false){
                    echo 'Maximum execution time of 30 seconds exceeded';
                }
                else{
                    throw $e;
                }
            }
        }
        
    }
?>