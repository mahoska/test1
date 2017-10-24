<?php

class AuthModel extends Model
{
    public function isLoginExists($login){
         try{
            $sth = $this->pdo->prepare('SELECT  COUNT(*) FROM client WHERE login=:login');
            $sth->execute(['login' => $login]);    
            $res = $sth->fetch(\PDO::FETCH_NUM);
            if($res[0]>0) return false;
            return true;
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        } 
    }
    
    public function createUser($params)
    {
       try{
        $sth = $this->pdo->prepare('INSERT INTO client( name, surname, login, password, phone, email, status, time_life) '
                . 'VALUES ( :name, :surname, :login, :password, :phone, :email, :status, :time_life)');
        $sth->execute($params);
        if($this->pdo->lastInsertId()>0)
            return ['status'=>200, 'data'=>$params['status']];
         else 
            return ['status'=>500, 'clientCode'=>'0007'];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        } 
    } 
    
    public function countUser(){
        try{
            $sth = $this->pdo->query("SELECT COUNT(*) FROM client"); 
            $res = $sth->fetch(\PDO::FETCH_NUM);
            return $res[0];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        } 
    }
    
    public function isLogin($params){
        try{
            $sth = $this->pdo->prepare('SELECT  time_life, id, disable  FROM  client WHERE status=:status AND login=:login');
            $sth->execute($params);    
            return  $sth->fetch(\PDO::FETCH_ASSOC);
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        } 
    }
    
    public function isUser($params){
        try{
            $sth = $this->pdo->prepare('SELECT  COUNT(*)   FROM  client WHERE login=:login AND password=:password');
            $sth->execute($params);    
            $res = $sth->fetch(\PDO::FETCH_NUM);
            if($res[0]>0) return true;
            return false;
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        } 
    }
      
    public function setLogin($params)
    {
        try{
            $sth = $this->pdo->prepare("UPDATE client "
                    . "SET status = :status, time_life = :time_life "
                    . " WHERE login = :login AND password =:password AND disable=0");
            $sth->execute($params);
            $count =  $sth->rowCount();
            if($count>0)
                return ['status'=>200, 'data'=>$params['status']];
             else 
                return ['status'=>500, 'clientCode'=>'0009'];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        }
    }
    
   public function getClientId($params){
         try{
            $sth = $this->pdo->prepare('SELECT id FROM client WHERE login=:login and password=:password ');
            $sth->execute($params);    
            $res = $sth->fetch(\PDO::FETCH_NUM);
            return $res[0];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        } 
   }
   
    public function getInfoClient($client_id){
        try{
            $sth = $this->pdo->prepare('SELECT id, login, password, discount, email, name, surname, phone, disable'
                     . ' FROM client WHERE id = :id LIMIT 1');
            $sth->execute(['id'=>$client_id]);    
            $info = $this->getFetchAccoss($sth);
            return ['status'=>200, 'data'=>$info[0]];
         }catch(PDOException $err){
             file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
             return ['status'=>500, 'clientCode'=>'0006'];
         } 
    }
    
   
    }
    




