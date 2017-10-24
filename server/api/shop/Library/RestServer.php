<?php
define('br',"<br>");

class RestServer{
    
    use tConvertData;
    
    private $method;
    private $className;
    private $params;
    private $contentType;
    private $statusResponse;
    private $getParams;
    private $serverMethod;
    private $clientCode;

    public function __construct(){
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        //courses
        list($s, $a, $d, $db,$sv, $table, $path) = explode('/', $url, 7);
        //home
        //list($s, $a, $d, $db, $table, $path) = explode('/', $url, 6);
        
        $this->serverMethod = $_SERVER['REQUEST_METHOD'];
        $this->className = ucfirst($table);
        $this->getParams =  explode('/', $path);

        switch($this->serverMethod)
        {
        case 'GET':
            $this->params = explode('/', $path);
            if(count($this->params) == 1){
                if($this->params[0]==""){
                    $this->method = 'getAll'.ucfirst($table);
                }else{
                    $lastParam = $this->params[0];
                    if(strripos($lastParam,'.')!==false){
                        $firstPart = substr($lastParam, 0, strripos($lastParam,'.'));
                    }else{
                        $firstPart = $lastParam;
                    }
                    if($firstPart==""){
                           $this->method = 'getAll'.ucfirst($table); 
                    }else{
                        if(($id  = (int) $firstPart) > 0){
                                $this->method = 'getItem'.ucfirst($table);
                        }else if(strcasecmp ($table, 'book') == 0 ){
                            $this->method = 'getFullFilter'.ucfirst($table);
                        }else{
                            $this->clientCode = '0001';
                            $this->statusResponse = 400;
                        }
                    }
                }
            }else{
                $lastParam = $this->params[count($this->params)-1];
                if ($lastParam=="" || (strripos($lastParam,'.')!==false &&  substr($lastParam, 0, strripos($lastParam,'.'))=="") || strripos($lastParam,'.')===false) {
                    $this->method = 'getData'.ucfirst($table);
                }else if(strripos($lastParam,'.')!==false){
                    $this->params[count($this->params)-1] = substr($lastParam, 0, strripos($lastParam,'.'));
                    $this->method = 'getData'.ucfirst($table);
                }else{
                    $this->clientCode = '0001';
                    $this->statusResponse = 400;
                }
            }
            break;
        case 'POST':
            $this->params = $_POST;
            $this->method = 'post'.ucfirst($table);
            break;   
        case 'DELETE':
            $this->params =$this->getParams; 
            $this->method = 'delete'.ucfirst($table);
             break;
        case 'PUT':
            $this->params = file_get_contents("php://input");
            $this->params = json_decode($this->params);
            $this->method = 'put'.ucfirst($table);
            break;
        case 'OPTIONS':
            header("Access-Control-Allow-Origin:*");
            header("Access-Control-Allow-Methods:PUT, DELETE");
            header("Access-Control-Allow-Headers: Authorization,Content-Type");
           die();
            break;
        default:
            $this->statusResponse = 406;
            $this->clientCode = '0002';
        }
        //var_dump($this->className,$this->method, $this->params);die();
        if($this->statusResponse != 406 && $this->statusResponse != 400){
           $data =  $this->setMethod($this->className,$this->method, $this->params);
           
           $responseContent = ($this->setResponse($data) && $this->statusResponse == 200)? $this->setResponse($data) : null ;
           $this->getResponse($responseContent);
        }else{
            $this->getResponse();
        }
    }
    
    public function setMethod($class, $action, $param)
    {
        $controller = new $class();
        if (!method_exists($class, $action)){
            $this->clientCode = '0003';
            $this->statusResponse = 405;
            $this->getResponse();
            die();
        }else{
            $dataResponse = $controller->$action($param); 
            $this->statusResponse = $dataResponse['status'];
            if($this->statusResponse != 200)
                $this->clientCode = $dataResponse['clientCode'];
        }
        return  $dataResponse; 
    }
        
    private function setHeaders()
    {
        $clientCodeDescr = null;
        if ($this->statusResponse != 200)
        {
            $this->contentType = 'text/html';
            $clientCodeDescr = $this->getDescriptionClientCode($this->clientCode);
        }
        header("HTTP/1.1 ".$this->statusResponse,(!is_null($clientCodeDescr)? ("|".$clientCodeDescr) : ""));
        header("Content-Type:".$this->contentType);
    }
    
    private function setResponse($data){
        $lastParam = $this->getParams[count($this->getParams)-1];
        if(strripos($lastParam,'.')===false){
            $this->contentType = DEFAULT_CONTENT_TYPE;
        }
        else{
            $this->contentType = substr($lastParam, strripos($lastParam,'.')+1);
        }
        switch ($this->contentType)
        {
            case 'json':
                $this->contentType = 'application/json';
                return $this->convertDataJson($data);
                break;
            case 'xml':
                $this->contentType = 'text/xml';
                return $this->convertDataXml($data);
                break;
            case 'txt':
                $this->contentType = 'text/plain';
                $this->setHeaders();
                echo $this->convertDataTxt($data);
                die();
                break;
            case 'html':
                $this->contentType = 'text/html';
                $this->setHeaders();
                echo $this->convertDataHtml($data);
                die();
                break;
            default:
                $this->statusResponse = 501;
                $this->clientCode = '0005';
                $this->getResponse();
                die();
                break;
        }     
    }

    
    public function getResponse($responseContent = null){
        $this->setHeaders();
        if($this->statusResponse != 200){
            $error_str = $this->getDescriptionClientCode($this->clientCode);
            echo "ERROR: ".!(is_null($error_str)?$error_str:"");
            die();
        }
        else if(!is_null($responseContent)){
            echo $responseContent;
        }
        die();
    }
    
    public function getDescriptionClientCode($clientCode = null){
        $descriptionCode = [
            '0001'=>'incorrect query parameters',
            '0002'=>'no query type defined',
            '0003'=>'method does not exists in class',
            '0004'=>'method does not exists in class',
            '0005'=>'the return format is not defined',
            '0006'=>'pdo exception',
            '0007'=>'user is not created',
            '0008'=>'hesh is not valid',
            '0009'=>'user is not login',
            '0010'=>'param is not valid',
            '0011'=>'login is disabled',
            '0012'=>'error update data',
            '0013'=>'the method calling the class is not specified correctly'
        ];
        return($descriptionCode[$clientCode]);
    }

}
