<?php

class FullinfoorderModel extends Model{

public function createFullinfoorder($params)
{
    //return ['status'=>200, 'data'=>$params];
    try{
        $sth = $this->pdo->prepare('INSERT INTO fullinfoorder (order_id, book_id, discount_book, count, book_price) '
                . 'VALUES (:order_id, :book_id, :discount_book, :count, :book_price)');
        $sth->execute($params);

        if($this->pdo->lastInsertId()>0)
             return ['status'=>200, 'data'=> 1];
         else 
             return ['status'=>500, 'clientCode'=>'0009'];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'clientCode'=>'0009'];
    }
}

public function getFullInfoOrderById($order_id){
    try{
        $sth = $this->pdo->prepare(
            'SELECT book_price, book_id , count, discount_book, book.name'
            . ' FROM fullinfoorder JOIN  book ON fullinfoorder.book_id = book.id'
            . ' WHERE order_id=:order_id');
        $sth->execute(['order_id' => $order_id]);
        $info = $this->getFetchAccoss($sth);
        return ['status'=>200, 'data'=> $info];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'clientCode'=>'0009'];
    }
}

}
