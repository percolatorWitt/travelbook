<?php


class database{
    private $db;
    
    private $host = '';
    private $dbname = '';
    private $dbuser = '';
    private $dbpw = '';

    public function __construct(){
        $database = include('system/configuration.php');
        
        if(!is_array($database)){
            echo "no Configuration found3.";
            exit;
        }
        if(empty($database['database'])){
            echo "no Configuration for database found.";
            exit;
        }
        
        $this->host = $database['database']['host'];
        $this->dbname = $database['database']['dbname'];
        $this->dbuser = $database['database']['dbuser'];
        $this->dbpw = $database['database']['dbpw'];
    }
    
    private function setDb(){
        try {
            $pdo = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname, $this->dbuser, $this->dbpw);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->db = $pdo;
        } catch (PDOException $e) {
            echo _('Verbindung fehlgeschlagen: ') . $e->getMessage();
        }
        
    }
    
    public function getDb(){
        if(!isset($this->db)){
            $this->setDb();
        }
        
        return $this->db;
    }
    
    //@todo Fehlerbehandlung
    /**
     * 
     * @param string $sql
     * @param array $data
     * @return integer
     */
    public function getStatement($sql, $data){
        $this->getDb();
        
        $statement = $this->db->prepare($sql);
        
        if (!$statement) {
            echo "errorInfo:";
            print_r($this->db->errorInfo());
        }
        
        foreach($data as $row){
            //$statement->bindParam(':id', $data, PDO::PARAM_INT);
            
            //int, str, bool
            if($row['param'] == 'PARAM_INT'){
                $statement->bindParam(':'.$row['name'], $row['value'], PDO::PARAM_INT);
            }elseif($row['param'] == 'PARAM_BOOL'){
                $statement->bindParam(':'.$row['name'], $row['value'], PDO::PARAM_BOOL);
            }else{
                $statement->bindParam(':'.$row['name'], $row['value'], PDO::PARAM_STR);
            }
        }
        
        
        try {
            $statement->execute();
            $return = $statement->fetchAll();
            
            $statement->closeCursor();
            
        } catch (PDOException $e) {
            echo _('Fehler bei der Abfrage: ') . $e->getMessage();
            echo "\nPDO::errorCode(): ", $this->db->errorCode();
            echo $statement;
            echo $sql;
            $statement->closeCursor();
            
            $return = FALSE;
        }
        
        return $return;
    }
    
    //@todo Fehlerbehandlung
    /**
     * 
     * @param string $sql
     * @param array $data
     * @return integer
     */
    public function getInsert($sql, $data){
        $this->getDb();
        
        $statement = $this->db->prepare($sql);
        
        if (!$statement) {
            echo "errorInfo:";
            print_r($this->db->errorInfo());
        }
        
        foreach($data as $row){
            //$statement->bindParam(':id', $data, PDO::PARAM_INT);
            
            //int, str, bool
            if($row['param'] == 'PARAM_INT'){
                $statement->bindParam(':'.$row['name'], $row['value'], PDO::PARAM_INT);
            }elseif($row['param'] == 'PARAM_BOOL'){
                $statement->bindParam(':'.$row['name'], $row['value'], PDO::PARAM_BOOL);
            }else{
                $statement->bindParam(':'.$row['name'], $row['value'], PDO::PARAM_STR);
            }
        }
        
        $statement->execute();
        $statement->closeCursor();
        
        $lastId = $this->db->lastInsertId();
        
        return $lastId;
    }
}
?>