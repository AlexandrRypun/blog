<?

/**
 * Create class Loader
 */

class Loader {

    private static $namespaces = array();
    private static $instance = null;

    private function __construct(){

    }

    /**
     * Create method "autoload"
     * Form address of necessary file with help of class' name
     * if file exist - include it, otherwise - search right namespace in $namespaces
     */

    private function autoload($class){
        $class = str_replace('\\', '/', $class);
        $file_dir = '../'.$class.'.php';

        if (file_exists($file_dir)){
            require_once $file_dir;
        }else{
            $parts = explode('/', $class);
            $first_part = array_shift($parts);

            if (self::$namespaces[$first_part]){
                $file_dir = self::$namespaces[$first_part].'/'.implode('/', $parts).'.php';
                require_once $file_dir;
            }else{
                new \Framework\Exception\NotFound();
            }
        }
        
    }    


    public static function addNamespacePath($name, $dir){
        if (is_dir($dir)) {
            self::$namespaces[$name] = str_replace('\\', '/', $dir);
        }
    }
    
    public function register(){
        spl_autoload_register('self::autoload');
    }
    
    public static function getInstance(){
        if (is_null(self::$instance)) {
            self::$instance = new Loader();
        }
        return self::$instance;
    }

    protected function __clone(){

    }

}

$loader = Loader::getInstance();
$loader->register();


?>