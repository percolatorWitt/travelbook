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
        $sql = "SELECT * FROM travel WHERE user_id = :id LIMIT 10";
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
        $user_id = Witt::getUser();
        
        //man f�gt Datum, von bis hinzu
        //gibt dem Urlaub einen Namen
        //gibt dem Urlaub einen Text und Bilder
        
        //man kann Tage 
        
        //add Location:
            //--> wo warst du wann?
                //schreib was dazu!!
        
        $postdata = $this->prepareTravelPostData();
        
        $sql = "INSERT INTO travel SET user_id = :user_id, name = :name, description = :description, locations = :locations";
        
        $travelId = $this->getInsert($sql, array(
                0 => array('name' => 'user_id', 'value' =>  $user_id, 'param' => "PARAM_INT"),
                1 => array('name' => 'name', 'value' =>  $postVar['name'], 'param' => "PARAM_STR"),
                2 => array('name' => 'description', 'value' =>  $postdata['description'], 'param' => "PARAM_STR"),
                3 => array('name' => 'locations', 'value' =>  $postdata['locations'], 'param' => "PARAM_STR"),
            
            ));
        
        $this->viewVariables = array('ajaxdata' => $travelId);
    }
    
    /**
     * 
     * @param int $travel_id
     */
    public function edit($travel_id){
        //prüfen ob $id int ist
        //prüfen ob Eintrag existiert
        //prüfen ob user-id Besitzer des Eintrages ist
        
        $user_id = Witt::getUser();

        $sql = "SELECT * FROM travel WHERE user_id = :user_id and travel_id = :travel_id LIMIT 1";
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
            'locations' => $newLocations,
            'startdate' => $this->prepareTravelDate($result[0]['startdate']),
            'enddate' => $this->prepareTravelDate($result[0]['enddate']),
        );
         
        
//echo "<pre>";
    
         //exit;
        //var_dump($result[0]['locations']);
         
       
    }
    //@todo Speichern nur, wenn Refferer Edit ist
    public function editajax($travel_id){
        $user_id = Witt::getUser();
        $postVar = $_POST;
        echo "editajax";
        
        $postdata = $this->prepareTravelPostData();
        
        $sql = "UPDATE travel SET name = :name, description = :description, locations = :locations, startdate = :startdate"
                . ", enddate = :enddate "
                . " WHERE travel_id = :travel_id AND user_id = :user_id";
        
        $travelId = $this->getInsert($sql, array(
                0 => array('name' => 'travel_id', 'value' =>  $travel_id, 'param' => "PARAM_INT"),
                1 => array('name' => 'user_id', 'value' =>  $user_id, 'param' => "PARAM_INT"),
                2 => array('name' => 'name', 'value' =>  $postVar['name'], 'param' => "PARAM_STR"),
                3 => array('name' => 'description', 'value' =>  $postdata['description'], 'param' => "PARAM_STR"),
                4 => array('name' => 'locations', 'value' =>  $postdata['locations'], 'param' => "PARAM_STR"),
                5 => array('name' => 'startdate', 'value' =>  $postdata['startdate'], 'param' => "PARAM_STR"),
                6 => array('name' => 'enddate', 'value' =>  $postdata['enddate'], 'param' => "PARAM_STR"),
            ));
        
            
            var_dump($_FILES);
            var_dump($_POST);
    }

    
    //nur Besitzer
    //nur nach Best�tigung
    public function delete($id){
        //DB-Eintrag löschen
        //alle Bilder löschen
    
    }
    
    /**
     * 
     * @return array
     */
    private function prepareTravelPostData(){
        $postVar = $_POST;
        
        $postData = array();
        
        /**
         * locatoins - begin
         */
        $postData['locations'] = array();
        
        if(!empty($postVar["places"])){
            foreach ($postVar["places"] as $locationId => $location){
                //var_dump($locationId);
               // var_dump($location);
                
                $locationArray = json_decode($location, TRUE);
                
                $postData['locations'][$locationId] = $locationArray[0];
            }
        }
        
        $postData['locations'] = json_encode($postData['locations']);
        /*
         * locations - end
         */
        
        if(isset($postVar['about'])){
            $descTemp = json_decode($postVar['about'], TRUE);
            
            if(isset($descTemp['ops'][0]['insert'])){
                $description = trim($descTemp['ops'][0]['insert']);
            }else{
                $postData['description'] = '';
            }   
        }else{
            $description = '';
        }
        
        //dates - yyyy-mm-dd
        $startDateTemp = preg_split('/\//', $postVar['startdate']);
        $postData['startdate'] = $startDateTemp[2].'-'.$startDateTemp[0].'-'.$startDateTemp[1];
                
        $endDateTemp = preg_split('/\//', $postVar['enddate']);
        $postData['enddate'] = $endDateTemp[2].'-'.$endDateTemp[0].'-'.$endDateTemp[1];
        
        return $postData;
    }
    
    /**
     * prepares date format from YYYY-MM-DD in MM/DD/YYYY
     */
    private function prepareTravelDate($date){
        if(empty($date)){
            return  "";
        }
        $dateTemp = preg_split('/-/', $date);
        $date = $dateTemp[1].'/'.$dateTemp[2].'/'.$dateTemp[0];
        
        return $date;
    }
    
    /**
     * progress for uploaded files
     * @todo Create folder for every user
     * @todo write in Database the naee and pathes of the pictures
     * @todo read exif-Data
     */
    private function uploadPictures(){
        if(isset($_FILES["FileInput"]) && $_FILES["FileInput"]["error"]== UPLOAD_ERR_OK){
            ############ Edit settings ##############
            $UploadDirectory	= '../../uploads/'; //specify upload directory ends with / (slash)
            ##########################################

            
            
            /*
            Note : You will run into errors or blank page if "memory_limit" or "upload_max_filesize" is set to low in "php.ini". 
            Open "php.ini" file, and search for "memory_limit" or "upload_max_filesize" limit 
            and set them adequately, also check "post_max_size".
            */

            //check if this is an ajax request
            if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
                    die();
            }


            //Is file size is less than allowed size.
            if ($_FILES["FileInput"]["size"] > 5242880) {
                    die("File size is too big!");
            }

            //allowed file type Server side check
            switch(strtolower($_FILES['FileInput']['type']))
                    {
                            //allowed file types
                case 'image/png': 
                            case 'image/gif': 
                            case 'image/jpeg': 
                            case 'image/pjpeg':
                            case 'text/plain':
                            case 'text/html': //html file
                            case 'application/x-zip-compressed':
                            case 'application/pdf':
                            case 'application/msword':
                            case 'application/vnd.ms-excel':
                            case 'video/mp4':
                                    break;
                            default:
                                    die('Unsupported File!'); //output error
            }

            $File_Name          = strtolower($_FILES['FileInput']['name']);
            $File_Ext           = substr($File_Name, strrpos($File_Name, '.')); //get file extention
            $Random_Number      = rand(0, 9999999999); //Random number to be added to name.
            $NewFileName 		= $Random_Number.$File_Ext; //new file name

            if(move_uploaded_file($_FILES['FileInput']['tmp_name'], $UploadDirectory.$NewFileName ))
               {
                    die('Success! File Uploaded.');
            }else{
                    die('error uploading File!');
            }

        } else {
                die('Something wrong with upload! Is "upload_max_filesize" set correctly?');
        }
    }
}
?>