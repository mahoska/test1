<?php

class AuthorModel extends Model
{

    public function getAllAuthors()
    {
        try{
            $sth = $this->pdo->query("SELECT id, name, surname FROM author"); 
            $collection = $this->getFetchAccoss($sth);
            return ['status'=>200, 'data'=>$collection];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        }
    }

  
       public function isAuthorExists($params){
        try{
            $sth = $this->pdo->prepare("SELECT COUNT(*) FROM author WHERE name = :name AND surname=:surname"); 
            $sth->execute($params);
            $res = $sth->fetch(\PDO::FETCH_NUM);
            if($res[0]>0) return false;
            return true;
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        }
    }

}
