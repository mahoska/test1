<?php

class OrderModel extends Model{

public function createOrder($params)
{
    //return ['status'=>200, 'data'=>$params];
    try{
        $sth = $this->pdo->prepare('INSERT INTO order_book (client_id, payment_id, discount_client, status_id, total_price) '
                . 'VALUES (:client_id, :payment_id, :discount_client, :status_id, :total_price)');
        $sth->execute($params);
        $order_id = $this->pdo->lastInsertId();
        if( $order_id>0)
             return ['status'=>200, 'data'=> $order_id];
         else 
             return ['status'=>500, 'clientCode'=>'0009'];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'clientCode'=>'0009'];
    }
}


public function getOrdersByClient($client_id)
{
    try{
        $sth = $this->pdo->prepare(
                'SELECT order_book.id, payment.name as payment, statusorder.name as status, discount_client, date_order_create, total_price'
                . ' FROM payment JOIN (order_book JOIN statusorder ON statusorder.id = order_book.status_id) '
                . 'ON payment.id = order_book.payment_id '
                . 'WHERE client_id=:client_id');
        $sth->execute(['client_id' => $client_id]);
        $collection = $this->getFetchAccoss($sth);
        return ['status'=>200, 'data'=> $collection];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'clientCode'=>'0009'];
    }
 
}

/*
public function updatetOrder($params)
{
     try{
        $sth = $this->pdo->prepare("UPDATE `orders_rest` SET `status` = 0 WHERE id = :id_order AND id_user=:id_user");
        $sth->execute($params);
        $count =  $sth->rowCount();
        if($count>0)
            return ['status'=>200, 'data'=>['order_update']];
         else 
             return ['status'=>500, 'data'=>['error update']];
    }catch(PDOException $err){
        file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
        return ['status'=>500, 'data'=>[]];
    }
}
 
 */

}
