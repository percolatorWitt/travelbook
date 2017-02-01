<?php
class travel_controller extends database{
    public $viewVariables = array();
    
    public function __construct($function, $param){
        $this->$function($param);
        
    }
    
    public function __destruct(){
        
    }
    
    //Reise�bersicht
    //sichtbarkeit: f�r andere ebenfalls sichtbar
    //Funktionsname wird nicht angezeigt
    public function index($id){
        //Text zur Reise, allgemeine Bilder zur Reise
        $sql = "SELECT * FROM travel WHERE user_id = :id";
        $result = $this->getStatement($sql, array(0 => array('name' => 'id', 'value' => $id, 'param' => "PARAM_INT")));
        
        
        $this->viewVariables = array('bar' => 'tset');
        #$this->viewVariables = array('bar' => 'tset', 'template' => 'test.tpl');
    }
    
    public function user($id){
        //Text zur Reise, allgemeine Bilder zur Reise
        $sql = "SELECT * FROM travel WHERE user_id = :id";
        $result = $this->getStatement($sql, array(0 => array('name' => 'id', 'value' => $id, 'param' => "PARAM_INT")));
        
        $travels = array();
        
        foreach($result as $row){
            $travels[$row['travel_id']] = array(
                'travel_id' => $row['travel_id'],
                'user_id' => $row['travel_id'],
                'name' => $row['name'],
                'description' => $row['description'],
                'locations' => $row['location'],
            );
        }
        
        $this->viewVariables = array('travels' => $travels);
    }
    
    /**
     * @todo secure redirect
     */
    public function add(){
  
        
    }
    
    /**
     * perform the add
     * @todo add 500-code for error during save
     * @todo UserId einfügen!! oder wat?
     */
    public function addajax(){
        $postVar = $_POST;
        
        //man f�gt Datum, von bis hinzu
        //gibt dem Urlaub einen Namen
        //gibt dem Urlaub einen Text und Bilder
        
        //man kann Tage 
        
        //add Location:
            //--> wo warst du wann?
                //schreib was dazu!!
        
        /**
         * locatoins - begin
         */
        $locations = array();
        if(!empty($postVar["places"])){
            foreach ($postVar["places"] as $locationId => $location){
                //var_dump($locationId);
               // var_dump($location);
                
                $locationArray = json_decode($location, TRUE);
                
                $locations[$locationId] = $locationArray[0];
            }
        }
        
        $locations = json_encode($locations);
        /*
         * locations - end
         */
        
        if(isset($postVar['about'])){
            $descTemp = json_decode($postVar['about'], TRUE);
            
            if(isset($descTemp['ops'][0]['insert'])){
                $description = trim($descTemp['ops'][0]['insert']);
            }else{
                $description = '';
            }   
        }else{
            $description = '';
        }
        
        $sql = "INSERT INTO travel SET user_id = :user_id, name = :name, description = :description, locations = :locations";
        
        $travelId = $this->getInsert($sql, array(
                0 => array('name' => 'user_id', 'value' =>  1, 'param' => "PARAM_INT"),
                1 => array('name' => 'name', 'value' =>  $postVar['name'], 'param' => "PARAM_STR"),
                2 => array('name' => 'description', 'value' =>  $description, 'param' => "PARAM_STR"),
                3 => array('name' => 'locations', 'value' =>  $locations, 'param' => "PARAM_STR"),
            
            ));
        
        $this->viewVariables = array('ajaxdata' => $travelId);
    }
    
    //nur Besitzer
    public function edit($travel_id){
        //prüfen ob $id int ist
        //prüfen ob Eintrag existiert
        //prüfen ob user-id Besitzer des Eintrages ist
        
        $user_id = Witt::getUser();

        $sql = "SELECT * FROM travel WHERE user_id = :user_id and travel_id = :travel_id";
        //provozierte Fehler --> abfangen
            //$sql = "SELECT * FROM travels WHERE user_id = :user_id";
        $result = $this->getStatement($sql, array(
                0 => array('name' => 'user_id', 'value' => $user_id, 'param' => "PARAM_INT"),
                1 => array('name' => 'travel_id', 'value' => $travel_id, 'param' => "PARAM_INT")
            ));
        
        $newLocations = array();
        if( ($result[0]['locations'] != "0") && (!empty($result[0]['locations'])) ){
            $locations = json_decode($result[0]['locations'], TRUE);
            
            foreach($locations as $key => $location){
                $newLocations[] = array(
                        "lat" => $location["lat"], 
                        "lon" => $location["lon"], 
                        "text" => $location["text"],
                        "id" => $key
                );
            }
        }
        
        $this->viewVariables = array(
            'travel_id' => $result[0]['travel_id'],
            'user_id' => $result[0]['user_id'],
            'name' => $result[0]['name'],
            'description' => $result[0]['description'],
            'locations' => $newLocations
        );
         
        
//echo "<pre>";
    
         //exit;
        //var_dump($result[0]['locations']);
         
       
    }
    
    public function editajax($travel_id){
            
    }

    
    //nur Besitzer
    //nur nach Best�tigung
    public function delete($id){
    
    }
}
?>