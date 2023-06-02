<?php

class PromoteController extends Controller{

    public function __construct(){
        parent::__construct();
    }

    public function processRequest(string $method, ?string $actions): void{

        if(!isset($actions)){
            $this->notAllowed();
        }
        else{
            $params = $this->processAction($actions);
        }
        
        if(!Jwt::validateAuthorizationToken(2))
            return;

        if (isset($params["status"]))
            $this->processResourceRequest($method, intval($params["status"]));

        return;
    }

    private function processAction($actions){
        
        if($actions === '1' || $actions === '2'){
            $params['status'] = intval($actions);
            return $params;
        }

        http_response_code(404);
        echo json_encode(["message" => "Route not found"]);

        return null;
    }

    private function processResourceRequest(string $method, int $status): void{
        switch ($method) {

            case "PATCH":
                
                $data = (array)json_decode(file_get_contents("php://input"), true);
                if(self::checkData($data)){
                    $email = $data["email"];
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        http_response_code(400);
                        echo json_encode(["message" => "Invalid email"]);
                        break;
                    }
                    if($this->model->promote($status, $email)){
                        http_response_code(200);
                        echo json_encode(["status" => $status, "email" => $email, "message" => "User promoted"]);
                    }
                }
                break;

            default:
                $this->notAllowed();
                break;
        }
    }

    private function notAllowed():void{
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
    }

    private static function checkData(array $data): bool{
        $requiredKeys = ["email"];
        $differentKeys = array_diff(array_keys($data), $requiredKeys);

        if (!empty($differentKeys)) {
            http_response_code(400);
            echo json_encode(["message" => "Bad request"]);
            return false;
        }

        return true;
    }
}