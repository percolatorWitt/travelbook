<?php
/**
* Main class
*/
class Witt{
    private $db;
    public $dwooData;
    public $dwoo;
    public $dwooTemplate = '';
    
    private $action;
    private $functionname;

    public function __construct(){
 
        //get db connection
        $this->setDb();
        $this->templates();
    
        //get configuration
        if(!$this->getLogin()){
            //set template for login
            $this->setDwooTemplate('./templates/outsidecms.tpl');
            $this->dwooData->assign('template', './templates/login.tpl');
            $this->getOutput();
        }else{
            $userdata = $this->getUserdata();
            $this->dwooData->assign('userdata', $userdata);
            $this->initController();
            
        }
    }
    
    //@todo andere Pfade (Bilder, CSS, Logout)
    private function initController(){
        define( 'INCLUDE_DIR', dirname( __FILE__ ) . '/controller/' );
        
        //wenn Controller oder Funktion nicht gefunden --> Fehlermeldung
        //wenn GET auf /public/* --> durchlassen
        //wenn 
        
        $rules = array(
            'userindex'     => "/user/(\d+)",              // '/user/12/'
            'user'     => "/user/(\d+)",              // '/user/function/'
            'user_controller'     => "/user/(\w+)/*([\w]*)",              // '/user/function/asdfa232'
            
            
            'travel'     => "/travel/(\d+)",              // '/user/12/'
            'travel_controller'     => "/travel/(\w+)/*([\w]*)",              // '/user/function/asdfa232'
            
            'public'    => "/public/(.*)",
            
            'home'      => "/?(.*)"                                      // '/'
        );

        $uri = rtrim( dirname($_SERVER["SCRIPT_NAME"]), '/' );
        $uri = '/' . trim( str_replace( $uri, '', $_SERVER['REQUEST_URI'] ), '/' );
        $uri = urldecode( $uri );
        
        foreach ( $rules as $action => $rule ) {
            if ( preg_match( '~^'.$rule.'$~i', $uri, $params ) ) {
                //Eingloggt aber Aufruf des Indexes? --> Zur pers�nlichen Seite
                if($action == "home"){
                 
                    //Eingeloggt und Aufruf des Indexes?
                    if( isset($_SESSION['userid']) ) {
                       header('Location: /user/'.$_SESSION['userid']);
                    }
                    
                    break;
                }else{
                    //Controller, Funktionen und Parameter ermitteln
                    if($action == "userindex"){
                        $action = "user_controller";
                        
                        $controller = $action;
                        $function = 'index';
                        $param = $params[1];
                    }else{
                        $controller = $action;
                        $function = $params[1];
                        $param = $params[2];
                    }
                    
                    //set template path
                    $this->action = $action;
                    $this->functionname = $function;

                    //init controller
                    $controller = new $action($function, $param);
                    
                    /** all 4 view **/
                    if(strpos($function, 'ajax') === FALSE){
                        
                    }else{
                        $this->dwooTemplate = new Dwoo_Template_File('./templates/ajax.tpl');
                    }
                    
                    //set variables for view
                    foreach($controller->viewVariables as $key => $row){
                        $this->dwooData->assign($key, $row);
                    }
                    
                    //$this->dwooData->assign('template', "user/index.tpl");
                    //set template for controller output
                    $this->dwooData->assign('template', $action."/".$function.".tpl");
                    
                    $this->getOutput();
                    break;
                    
                    // exit to avoid the 404 message 
                   // exit();
               }
            }
        }
    }
    
    public function __destruct(){
        
    }
    
    private function setDb(){
        $db = NEW database();
        
        $this->db = $db;
    }
    
    public function getDb(){
        return $this->db;
    }
    
    //not logged in? --> back to index
    public function getLogin(){
        //not valid session?
        if( !isset($_SESSION['userid']) ) {

            //valid login started?
            if(!$this->setLogin()){
                return FALSE;
            }
        }
            
        return TRUE;
    }
    
    //todo -->UserController???
    public function setLogin(){
        if(isset($_GET['login'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $db = $this->getDb();
            
            $sql = "SELECT * FROM users WHERE email = :email limit 1";
            $user = $db->getStatement($sql, array(0 => array('name' => 'email', 'value' => $email, 'param' => "PARAM_STR")));
            $user = $user[0];

            //Überprüfung des Passworts
            if ($user !== false && password_verify($password, $user['password'])) {
                $_SESSION['userid'] = $user['id'];
                //Login erfolgreich.
                header('Location: /user/'.$user['id']);
            } else {
                $errorMessage = "E-Mail oder Passwort war ungültig";
                $this->dwooData->assign('errorMessage', $errorMessage);
            }
        }
        
        return FALSE;
    }

    private function templates(){
        // Create the controller, this is reusable
        $this->dwoo = new Dwoo();

        // Load a template file (name it as you please), this is reusable
        // if you want to render multiple times the same template with different data

        // Create a data set, if you don't like this you can directly input an
        // associative array in $dwoo->output()

        $data = new Dwoo_Data();
        // Fill it with some data
        $data->assign('user_id', $_SESSION['userid']);
        $data->assign('bar', 'BAZ');
        
        $this->dwooData = $data;
    
        $this->setDwooTemplate('./templates/all.tpl');
    }
    
    //Set standardtemplate
    public function setDwooTemplate($templatePath){
        //$this->dwooTemplate = new Dwoo_Template_File('./templates/all.tpl');
        
        if($this->functionname == 'ajax'){
            $this->dwooTemplate = new Dwoo_Template_File('./templates/ajax.tpl');
        }else{
            $this->dwooTemplate = new Dwoo_Template_File($templatePath);
        }
    }
    
    public function getOutput(){
        // Outputs the result ...
        $this->dwoo->output($this->dwooTemplate, $this->dwooData);
        // ... or get it to use it somewhere else
        $this->dwoo->get($this->dwooTemplate, $this->dwooData);
    }
    
    public function getUserdata(){
        $user_id = $_SESSION['userid'];
        $db = $this->getDb();
        
        $sql = "SELECT name, surname, email FROM users WHERE id = :user_id limit 1";
        
        $user = $db->getStatement($sql, array(0 => array('name' => 'user_id', 'value' => $user_id, 'param' => "PARAM_STR")));
        $user = $user[0];
        
        return $user;
    }


    static function getUser(){
        if(isset($_SESSION['userid'])){        
            return $_SESSION['userid'];
        }
        
        return FALSE;
    }
}
?>