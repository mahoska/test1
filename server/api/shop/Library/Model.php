<?php

abstract class Model{
    protected $pdo;

    public function __construct()
    {
        try{
            $this->pdo = DbConnection::getInstance()->getLink(); 
        }catch(PDOException $err){}
    }
    public function __destruct()
    {
        $this->pdo = null;
    }
    
    public function getFetchAccoss($sth)
    {
        $collection = [];
        while($res = $sth->fetch(\PDO::FETCH_ASSOC)){
        $collection[] = $res;
        }
         return $collection;
    }
    
       public function insert($params){
        try{
            $tempFields = array_keys($params);
            $fieldStr = implode(", ", $tempFields);
            foreach ($tempFields as &$value) {
                $value = ":".$value;
            }
            $valueStr = implode(", ", $tempFields);
            $className = strtolower(get_class($this));
            $pos = strpos($className,'model');
            if($pos!= -1){
                $tableName = substr($className, 0, strlen($className)-5 );
                $sth = $this->pdo->prepare("INSERT into ".$tableName." ($fieldStr) VALUES ($valueStr)"); 
                $sth->execute($params);
                if($this->pdo->lastInsertId()>0)
                    return ['status'=>200, 'data'=>1];
                else 
                return ['status'=>500, 'clientCode'=>'0006'];
            }
            return ['status'=>500, 'clientCode'=>'0013'];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        }
    }
}

