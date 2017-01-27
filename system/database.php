<?php
include_once('./configuration.php');

class database{
    private $db;
    
    private $host = '';
    private $dbname = '';
    private $dbuser = '';
    private $dbpw = '';

    public function __construct(){
        
        
        $this->host = $config['host'];
        $this->dbname = $config['dbname'];
        $this->dbuser = $config['dbuser'];
        $this->dbpw = $config['dbpw'];
        
        $this->setDb();
    }
    
    private function setDb(){
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', 'mcfly1');
            //$pdo = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname, $this->dbuser, $this->dbpw);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->db = $pdo;
        } catch (PDOException $e) {
            echo _('Verbindung fehlgeschlagen: ') . $e->getMessage();
        }
        
    }
    
    public function getDb(){
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
        $this->setDb();
        
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
        } catch (PDOException $e) {
            echo _('Fehler bei der Abfrage: ') . $e->getMessage();
            echo "\nPDO::errorCode(): ", $this->db->errorCode();
            
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
        $this->setDb();
        
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
        $lastId = $this->db->lastInsertId();
        
        return $lastId;
    }
}
?>