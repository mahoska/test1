<?php

class Author extends Controller
{
    public function getAllAuthor($params = false)
    {
        $model = new AuthorModel();
        $data = $model->getAllAuthors();
        return $data;
    }
    
    public function getItemBAuthor($params = false)
    {
        
    }
    
    public function postAuthor($params)
    {
        if(count($params) != 2){
            return ['status'=>400, 'clientCode'=>'0001'];
        }
        $model = new AuthorModel();
        $isAuthor = $model->isAuthorExists($params);
        if($isAuthor)
            return $model->insert($params);
        else 
            return ['status'=>200, 'err'=>true, 'data'=>'this author already exists'];
    }
    
    

    public function putAuthor($params)
    {

    }
}

