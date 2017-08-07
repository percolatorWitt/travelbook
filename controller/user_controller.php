<?php
//localisation https://www.transifex.com/blog/2012/php-hello-l10n/
    //http://www.lug-wr.de/tmp/alt/archiv/Internationalisierung_von_Webseiten_mit_PHP_und_gettext.pdf
    //po-Editor: https://poedit.net/
class user_controller extends database{
    public $viewVariables = array();

    public function __construct($function, $param){
        if(!empty($param)){
            $this->$function($param);
        }else{
            $this->$function();
        }
       
    }
    
    public function __destruct(){
        
    }
    
    //Seite des Nutzers, 
    //sichtbarkeit: f�r andere ebenfalls sichtbar
    //Funktionsname wird nicht angezeigt
    //@todo informa about traveler: mount of travels, km, countries, last travel
    public function index($id){
        $sql = "SELECT * FROM users WHERE id = :id";
        $result = $this->getStatement($sql, array(0 => array('name' => 'id', 'value' => $id, 'param' => "PARAM_INT")));
        //var_dump($result);
        
        $this->viewVariables = array('bar' => 'tset');
        #$this->viewVariables = array('bar' => 'tset', 'template' => 'test.tpl');
    }
    
    //Einstellungen des Nutzers
    //Sichtbarkeit: nur f�r den Nutzer
    //Passwort nur aendern, wenn neues eingegeben wurde und validate gleich ist
    public function settings(){
        $userId = Witt::getUserId();

        $sql = "SELECT * FROM users WHERE id = :id";
        $result = $this->getStatement($sql, array(0 => array('name' => 'id', 'value' => $userId, 'param' => "PARAM_INT")));
        //check if result is right
        if($result[0]['id'] == $userId){
            
            $this->viewVariables = array(
                'email' => $result[0]['email'],
                'emailmd5' => md5($result[0]['email']),
                'nickname' => $result[0]['nickname'],
                'first_name' => $result[0]['first_name'],
                'surname' => $result[0]['surname'],
                'displayname' => $result[0]['displayname']
            );
        }else{
            echo "FEHLER/Hack";
        }
       
        
    }
        
    //get posts vor update
    public function settingsajax(){
        $userId = Witt::getUserId();
        $postVar = $_POST;
        
        $userSettings = array();
        
        //get data
        $sql = "SELECT * FROM users WHERE id = :id";
        $result = $this->getStatement($sql, array(0 => array('name' => 'id', 'value' => $userId, 'param' => "PARAM_INT")));
        
        //check if result is right
        if($result[0]['id'] == $user){
            
            $userSettings = array(
                    'email' => $result[0]['email'],
                    'emailmd5' => md5($result[0]['email']),
                    'nickname' => $result[0]['nickname'],
                    'first_name' => $result[0]['first_name'],
                    'surname' => $result[0]['surname'],
                    'displayname' => $result[0]['displayname']
                    );
            
            
            $sql = "INSERT INTO user SET name = :name, surname = :surname" . 
                    " WHERE user_id = :user_id";
        
            $travelId = $this->getStatement($sql, array(
                0 => array('name' => 'name', 'value' =>  $postdata['name'], 'param' => "PARAM_STR"),
                1 => array('name' => 'surname', 'value' =>  $postdata['surname'], 'param' => "PARAM_STR"),
                2 => array('name' => 'user_id', 'value' =>  $userId, 'param' => "PARAM_INT"),
            ));
        
             
        }else{
            echo "FEHLER/Hack"; exit;
        }

        if(isset($postVar['setgrvatar'])){
            if($postVar['setgrvatar'] == 1){

                $ajaxdata['gravatarData'] = $this->getGravatar($userSettings['email']);
            }
        }
        
        $this->viewVariables = array( 'ajaxdata' => json_encode($ajaxdata) );
        
    }
    
    
    
    
    //@todo andere HTTP-Methode verwenden, diese verwendet Standardtimeout
    private function getGravatar($email){
        $gravatarId = md5(strtolower(trim($email)));
        $gravatarId = "205e460b479e2e5b48aec07710c08d50";
        //andere HTTP-Methode verwenden
        $str = file_get_contents( 'https://www.gravatar.com/'.$gravatarId.'.php' );
        
        if($str != FALSE){
            //var_dump($str);
        }
        
        
        $profile = unserialize( $str );
        if ( is_array( $profile ) && isset( $profile['entry'] ) ){
            
            $gravatarData['displayName'] = $profile['entry'][0]['displayName'];
            $gravatarData['thumbnailUrl'] = str_replace('http:', 'https:', $profile['entry'][0]['thumbnailUrl']);
            
            return $gravatarData;
        }
        
        return FALSE;
    }
    
    //show all travels of user
    //@todo limit (paging)
    public function travels(){
        $userId = Witt::getUserId();

        $sql = "SELECT * FROM travel WHERE user_id = :user_id";
        //provozierte Fehler --> abfangen
            //$sql = "SELECT * FROM travels WHERE user_id = :user_id";
        $result = $this->getStatement($sql, array(0 => array('name' => 'user_id', 'value' => $userId, 'param' => "PARAM_INT")));
        $travels = array();
         foreach($result as $row){
            $travels[$row['travel_id']] = array(
                'travel_id' => $row['travel_id'],
                'user_id' => $userId,
                'name' => $row['name'],
                'description' => $row['description'],
                'locations' => $row['location'],
                'startdate' => $row['startdate'],
                'enddate' => $row['enddate'],
            );
        }
        $this->viewVariables = array('travels' => $travels);
        
    }
    
    public function logout(){
        session_destroy();
        header('Location: /');
    }
}
?>