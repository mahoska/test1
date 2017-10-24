<?php

class Payment extends Controller
{
    public function getAllPayment($params = false)
    {
        $model = new PaymentModel();
        $data = $model->getAllPayment();
        return $data;
    }
    
    public function getItemGanre($params = false)
    {
        
    }
    
    public function postGanre($params)
    {

    }
    

    public function putGanre($params)
    {

    }
}

