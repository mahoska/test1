<?php

class Fullinfoorder extends Controller
{
    public function postFullinfoorder($params)
    {
        // return ['status'=>200, 'data'=>$params];
        if(count($params) != 5){
            return ['status'=>400, 'clientCode'=>'0001'];
        }

        $params['count'] = (int)$params['count'];
        $params['order_id'] = (int)$params['order_id'];
        $params['book_id'] = (int)$params['book_id'];
        $params['discount_book'] = (double)$params['discount_book'];
        $params['book_price'] = (double)$params['book_price'];
        if(! $params['count']>0 ||  !$params['order_id']>0 || !$params['book_id']>0)
             return ['status'=>400, 'clientCode'=>'0001'];
        
        $model = new FullinfoorderModel();
        return $model->createFullinfoorder($params); 
    }
    
     public function getItemFullinfoorder($params)
    {
        if(count($params) != 1 || !(int)$params[0]>0){
            return ['status'=>400, 'clientCode'=>'0001'];
        }
        $model = new FullinfoorderModel();
        $data = $model->getFullInfoOrderById($params[0]);
        return $data;
    }

    
}

