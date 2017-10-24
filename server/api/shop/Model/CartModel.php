<?php

class CartModel extends Model
{

public function createCart($params)
{
    try{
        $sth = $this->pdo->prepare('INSERT INTO cart (book_id, client_id, count) '
                . 'VALUES (:book_id, :client_id, :count)');
        $sth->execute($params);
        if($this->pdo->lastInsertId()>0)
            return ['status'=>200, 'data'=>1];
         else 
            return ['status'=>500, 'clientCode'=>'0006'];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'clientCode'=>'0006'];
    }
}

public function getCountBook($params){
     try{
        $sth = $this->pdo->prepare("SELECT count  FROM cart WHERE client_id = :client_id AND book_id = :book_id");
        $sth->execute($params);
        $result = $sth->fetch(\PDO::FETCH_NUM);
        return $result[0];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'clientCode'=>'0006'];
    }
}

public function getCountCartByClient($client_id)
{
    try{
        $sth = $this->pdo->prepare("SELECT SUM(count)as count_cart FROM cart WHERE client_id = :client_id");
        $sth->execute(['client_id' => $client_id]);
        $result = $sth->fetch(\PDO::FETCH_NUM);
        return ['status'=>200, 'data'=>$result[0]];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'clientCode'=>'0006'];
    }
}

public function getCartByClient($client_id)
{
    try{
        $sth = $this->pdo->prepare(
                "SELECT book.id, book.name, book.price, book.discount as book_discount, count "
                . "from book JOIN (cart JOIN client ON cart.client_id = client.id )"
                . " ON book.id = cart.book_id WHERE client.id = :client_id");
        $sth->execute(['client_id' => $client_id]);
        $collection =  $this->getFetchAccoss($sth);
        return ['status'=>200, 'data'=>$collection];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'clientCode'=>'0006'];
    }
}

public function updateCart($params)
{
     try{
        $sth = $this->pdo->prepare("UPDATE cart SET `count` = :count WHERE client_id = :client_id AND book_id=:book_id");
        $sth->execute($params);
        $count =  $sth->rowCount();
        if($count>0)
            return ['status'=>200, 'data'=>1];
         else 
            return ['status'=>500, 'clientCode'=>'0012'];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'clientCode'=>'0006'];
    }
}

public function deleteCartItem($params){
    try{
        $sth = $this->pdo->prepare("DELETE FROM cart WHERE  book_id=:book_id AND client_id=:client_id");
        $sth->execute(['book_id'=>$params[0],'client_id'=>$params[1]]);
        $count =  $sth->rowCount();
        if($count>0)
            return ['status'=>200, 'data'=>1];
         else 
            return ['status'=>500, 'clientCode'=>'0012'];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'clientCode'=>'0006'];
    }
}

public function deleteCart($params){
    try{
        $sth = $this->pdo->prepare("DELETE FROM cart WHERE  client_id=:client_id");
        $sth->execute(['client_id'=>$params[0]]);
        $count =  $sth->rowCount();
        if($count>0)
            return ['status'=>200, 'data'=>1];
         else 
            return ['status'=>500, 'clientCode'=>'0012'];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'clientCode'=>'0006'];
    }
}

}
