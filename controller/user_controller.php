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

        $sql = "SELECT * FROM users WHERE user_id = :user_id";
        $result = $this->getStatement($sql, array(0 => array('name' => 'user_id', 'value' => $userId, 'param' => "PARAM_INT")));
        
        $this->viewVariables = array(
                'email' => $result[0]['email'],
                'emailmd5' => md5($result[0]['email']),
                'nickname' => $result[0]['nickname'],
                'first_name' => $result[0]['first_name'],
                'surname' => $result[0]['surname'],
                'displayname' => $result[0]['displayname']
        );
        
        //check if result is right
        
        /*if($result[0]['id'] == $userId){
            
           $this->setUser($result);
           
        }else{
            echo "FEHLER/Hack";
        }*/
       
        
    }
        
    //get posts vor update
    public function settingsajax(){
        $userId = Witt::getUserId();
        $postVar = $_POST;
        
        $userSettings = array();
        
        //get data
        $sql = "SELECT * FROM users WHERE user_id = :user_id";
        $result = $this->getStatement($sql, array(0 => array('name' => 'user_id', 'value' => $userId, 'param' => "PARAM_INT")));
        
        //check if result is right
        if($result[0]['user_id'] == $userId){
            $this->setUser($postVar, $userId);
             
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
    
    private function setUser($data, $userId){
            
        $sql = "UPDATE users SET first_name = :first_name, surname = :surname, " . 
                "nickname = :nickname ".
                " WHERE user_id = :user_id";

        $status = $this->getStatement($sql, array(
            0 => array('name' => 'first_name', 'value' =>  $data['first_name'], 'param' => "PARAM_STR"),
            1 => array('name' => 'surname', 'value' =>  $data['surname'], 'param' => "PARAM_STR"),
            2 => array('name' => 'nickname', 'value' =>  $data['nickname'], 'param' => "PARAM_STR"),
            3 => array('name' => 'user_id', 'value' =>  $userId, 'param' => "PARAM_INT")
        ));
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