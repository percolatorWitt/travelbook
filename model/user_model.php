<?php echo "Model1";
class user_model{
    public function __construct(){
        echo "model";
    }
    
    public function __destruct(){
        
    }
    
    //Seite des Nutzers, 
    //sichtbarkeit: für andere ebenfalls sichtbar
    //Funktionsname wird nicht angezeigt
    public function index($id){
    
    
    }
    
    //Einstellungen des Nutzers
    //Sichtbarkeit: nur für den Nutzer
    public function settings(){
    
    }
}
?>