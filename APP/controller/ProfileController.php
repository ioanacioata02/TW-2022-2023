<?php

class ProfileController extends Controller{

    public function __construct(){
        parent::__construct();
    }

    public function processRequest(string $method, ?string $actions): void{
        if($method === "OPTIONS"){
            http_response_code(200);
            return;
        }
        

        if(!isset($actions)){
            
            $this->processSimpleRequest($method);
            return;
        }

        if($actions === "change-pass"){
            $this->processChangePass($method);
            return;
        }
        
        if($actions === "upload-img"){
            $this->processImg($method);
            return;
        }

        else if(strpos($actions, "history") === 0){

            $query = substr($actions, 8);
            parse_str($query, $params);

            if(!isset($params['page'])){
                http_response_code(404);
                echo json_encode(["message" => "Route not found"]);
                return;
            }
            $data = [];
            $data['page'] = $this->processNr($params['page']);
            if(isset($params['id'])){
                $data['id']= $this->processNr($params['id']);
                if($data['id' ] === null)
                    return;
                    
                $this->processHistory($method, $data);
            }
            else
                $this->processOwnHistory($method, $data);
            return;
        }

        $id = $this->processNr($actions);

        if($id === null)
            return;

        // otherwise id is set
        $this->processIdRequest($method, $id);

    }

    private function processNr($actions): ?int{

        $pattern = '/^\d+$/';
        if(preg_match($pattern, $actions, $matches) === 1 && count($matches) === 1){
            return intval($matches[0]);
        }
        
        http_response_code(404);
        echo json_encode(["message" => "Route not found"]);
        
        return null;
    }

    private function processIdRequest(string $method, int $id): void{
        
        switch ($method) {

            case "GET":
                $row = $this->model->get($id);
                if(!empty($row)){
                    http_response_code(200);
                    echo json_encode($row);
                }
                else{
                    http_response_code(404);
                    echo json_encode(["id" => $id, "message" => "User not found"]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

    private function processSimpleRequest($method): void{

        if(!Jwt::validateAuthorizationToken("secret")){
            return;
        }

        switch ($method) {

            case "GET":
                $row = $this->model->get(Jwt::getIdFromToken());
                if(!empty($row)){
                    http_response_code(200);
                    echo json_encode($row);
                }
                else{
                    http_response_code(404);
                    echo json_encode(["id" => Jwt::getIdFromToken(), "message" => "User not found"]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

    private function processOwnHistory(string $method, array $data): void{
        if(!Jwt::validateAuthorizationToken("secret")){
            return;
        }

        switch ($method) {

            case "GET":
                $data['id'] = Jwt::getIdFromToken();
                if(!$this->model->userExists($data['id'])){
                    http_response_code(404);
                    echo json_encode(["id" => $data['id'], "message" => "User not found"]);
                }
                else{
                    http_response_code(200);
                    echo json_encode(["submits"=>$this->model->getOwnSubmits($data), "nrOfSubmits" => $this->model->getNrOfSubmits($data['id'])]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

    private function processHistory(string $method, array $data): void{

        switch ($method) {

            case "GET":
                if(!$this->model->userExists($data['id'])){
                    http_response_code(404);
                    echo json_encode(["id" => $data['id'], "message" => "User not found"]);
                }
                else{
                    http_response_code(200);
                    echo json_encode(["submits"=>$this->model->getSubmits($data), "nrOfSubmits" => $this->model->getNrOfSubmits($data['id'])]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

    private function processImg($method): void{
        if(!Jwt::validateAuthorizationToken("secret")){
            return;
        }

        switch ($method) {

            case "PATCH":
                $data = (array)json_decode(file_get_contents("php://input"), true);
                if(self::checkData($data, ["image"])){

                    $data["id"] = Jwt::getIdFromToken();
                    $data["image"]=htmlspecialchars($data["image"]);
                    
                    if($this->model->changeImg($data)){
                        http_response_code(200);
                        echo json_encode(["message" => "Image changed"]);
                    }
                }
                else{
                    http_response_code(400);
                    echo json_encode(["message" => "Bad request"]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

    private function processChangePass($method): void{
        if(!Jwt::validateAuthorizationToken("secret")){
            return;
        }
        
        switch ($method) {

            case "PATCH":
                $data = (array)json_decode(file_get_contents("php://input"), true);

                if(self::checkData($data, ["password"])){

                    $data["id"] = Jwt::getIdFromToken();
                    $data["password"]=htmlspecialchars($data["password"]);
                    
                    if($this->model->changePass($data)){
                        http_response_code(200);
                        echo json_encode(["message" => "Password changed"]);
                    }
                }
                else{
                    http_response_code(400);
                    echo json_encode(["message" => "Bad request"]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

    private static function checkData(array $data, array $requiredKeys): bool{
        if($data === null){
            return false;
        }

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                $differentKeys[] = $key;
            }
            if($data[$key] === null)
                return false;
        }

        if (count($requiredKeys) !== count($data) || !empty($differentKeys))
            return false;

        return true;
    }
}