<?php

class Auth extends Controller
{
    
    public function getDataAuth($params)
    {
        if(count($params) != 2){
            return ['status'=>400, 'clientCode'=>'0001'];
        }
        $model = new AuthModel();
        $data = $model->isLogin(['login'=>$params[1],'status'=>$params[0]]);
        if($data['disable']==1){
            return ['status'=>200, 'err'=>true, 'data'=>'login disabled'];
        }
        $time_life = $data['time_life'];
        $time_now =  time();

        if($time_life > $time_now){
            return ['status'=>200, 'data'=>$data['id']];
        }
        
        return ['status'=>400, 'clientCode'=>'0008'];
    }
    
    public function postAuth($params)
    {
        if(count($params) != 6){
            return ['status'=>400, 'clientCode'=>'0001'];
        }
          
        $model = new AuthModel();
        $isUser = $model->isLoginExists($params['login']);
        if(!$isUser)
            return ['status'=>200, 'err'=>true, 'data'=>'login already exists'];
        
        $params['status'] = $this->setHash();
        $params['time_life'] =  time()+LIFE_ACTIVE_LOGIN;//forexample 1507554709
        $pass = md5($params['password']);
        $str= 'passsolt';
        $str = md5($str);
        $pass_db = md5($params['password'].$str);
        $params['password'] = $pass_db;
       //return ['status'=>200, 'data'=>$params];
        return $model->createUser($params);
    }
    
     public function putAuth($params)
    {
        $pass = md5($params->password);
        $str= 'passsolt';
        $str = md5($str);
        $pass_db = md5($params->password.$str);
        $fparams['password'] = $pass_db;
        $fparams['login'] = $params->login;
         
        $model = new AuthModel();
        $isUser = $model->isUser($fparams);
        if(!$isUser)
            return ['status'=>200, 'err'=>true, 'data'=>'user not exists'];

        $fparams['status'] = $this->setHash(); 
        $fparams['time_life'] =  time()+LIFE_ACTIVE_LOGIN;
        $data =  $model->setLogin($fparams);
        if($data['status']==200){
            $client_id = $model->getClientId(['login'=>$fparams['login'],'password'=>$fparams['password']]);
            return ['status'=>200, 'data'=>['status'=>$data['data'],'client_id'=>$client_id]];
        }else{
            return $data;
        }
    }

    private function setHash(){
        $model = new AuthModel();
        $countUsers = $model->countUser();
        //generate rand string
        $randStr = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $rand = '';
        for($i=0; $i<5; $i++) {
            $key = rand(0, strlen($randStr)-1);
            $rand .= $randStr[$key];
        }
        
        return md5($rand.$countUsers);
    }

     public function getItemAuth($params)
    {
        if(count($params) != 1 || !((int)$params[0]>0)){
            return ['status'=>400, 'clientCode'=>'001'];
        }
        $model = new AuthModel();
        return $model->getInfoClient($params[0]);
    }
}
