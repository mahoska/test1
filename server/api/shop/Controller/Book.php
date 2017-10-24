<?php 

class Book extends Controller
{
    public function getAllBook($params = false)
    {
        $model = new BookModel();
        $data = $model->getAllBooks();
        return $data;
    }
    
    public function getItemBook($param)
    {
        $model = new BookModel();
        $data = $model->getfullInfoBook($param[0]);
        return $data;
    }
    
    public function getDataBook($params)
    {
        
        $keysFilterParams = [];
        $i=0;
        $KEYS_FILTER_PARAMS=[
            'ganre', 
            'author'
        ];
        if(count($params)!= count($KEYS_FILTER_PARAMS)){
            return ['status'=>400, 'clientCode'=>'0001'];
        }
        //return ['status'=>200, 'data'=>$params];
        foreach ($params as &$value) {
            if($value == "-") $value=0;
            else{
                if (!($value = (int)$value)>0){
                    return ['status'=>400, 'clientCode'=>'0010'];
                }
                else{
                    $model = new BookModel();
                    $action = 'filterBooksBy'.ucfirst($KEYS_FILTER_PARAMS[$i]);
                    $data = $model->$action($value);
                    return $data;
                }
            }
            $keysFilterParams[$KEYS_FILTER_PARAMS[$i++]] = $value;
        }
        
        
    }
    
    public function getFullFilterBook($param)
    {
        //return ['status'=>200, 'data'=>$param];
        if(count($param) != 1){
            return ['status'=>400, 'clientCode'=>'0001'];
        }
        $model = new BookModel();
        $data = $model->fullFilterBooks($param[0]);
        return $data;
    }

    public function postBook($params  = false){}

    public function putBook($params  = false){}

    public function deleteBook($params  = false){}

}
