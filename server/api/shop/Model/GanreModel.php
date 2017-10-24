<?php

class GanreModel extends Model
{
    public function getAllGanres()
    {
        try{
            $sth = $this->pdo->query("SELECT id, name FROM ganre"); 
            $collection = $this->getFetchAccoss($sth);
            return ['status'=>200, 'data'=>$collection];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        }
    }

    public function isGanreExists($params){
        try{
            $sth = $this->pdo->prepare("SELECT COUNT(*) FROM ganre WHERE name = :name"); 
            $sth->execute($params);
            $res = $sth->fetch(\PDO::FETCH_NUM);
            if($res[0]>0) return false;
            return true;
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        }
    }

    public function getfullInfoGanre($params){
         try{
             $sth = $this->pdo->prepare("SELECT id, name FROM ganre WHERE id = :id");
            $sth->execute($params);
            $info = $sth->fetch(\PDO::FETCH_ASSOC);
            return ['status'=>200, 'data'=>$info];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        } 
    }
}
