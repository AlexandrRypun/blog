<?
namespace Framework\DI;





class Service {

    private static $instance = null;
    private static $services = array();
    
    private function __construct(){
        
    }

    protected function __clone(){

    }

    public static function getInstance(){
        if (is_null(self::$instance)) {
            self::$instance = new Service();
        }
        return self::$instance;
    }

    public static function get($service_name){
        if (array_key_exists($service_name, self::$services)){
            return self::$services[$service_name];
        }else{
            echo "Sorry, service not found!!!";die();
        }
    }


    public function set($service_name, $obj){

        if (!array_key_exists($service_name, self::$services)){
                self::$services[$service_name] = $obj;
        }
    }

}
?>