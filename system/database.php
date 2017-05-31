<?php


class database{
    private $db;
    
    private $host = 'localhost';
    private $dbname = 'test';
    private $dbuser = 'root';
    private $dbpw = 'mcfly1';

    public function __construct(){
        
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