<?php

class Order extends Controller
{
    public function getAllOrder($params = false)
    {
        
    }
    
    public function getItemOrder($params)
    {
        if(count($params) != 1 || !(int)$params[0]>0){
            return ['status'=>400, 'clientCode'=>'0001'];
        }
        $model = new OrderModel();
        $data = $model->getOrdersByClient($params[0]);
        return $data;
    }
    
    public function postOrder($params)
    {
        if(count($params) != 4){
            return ['status'=>400, 'clientCode'=>'0001'];
        }

        $params['status_id'] = 1;
        $params['client_id'] = (int)$params['client_id'];
        $params['payment_id'] = (int)$params['payment_id'];
        $params['discount_client'] = (float)$params['discount_client'];
        $params['total_price'] = (double)$params['total_price'];
        if(!$params['client_id']>0 ||  !$params['payment_id']>0)
             return ['status'=>400, 'clientCode'=>'0001'];
        
        $model = new OrderModel();
        return $model->createOrder($params); 
        
    }
    

    public function putOrder($params)
    {

    }
}

