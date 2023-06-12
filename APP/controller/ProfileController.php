<?php

class ProfileController extends Controller{

    public function __construct(){
        parent::__construct();
    }

    public function processRequest(string $method, ?string $actions): void{
        
        if(!JWT::validateAuthorizationToken(getenv("secret"))){
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
        

        $id = $this->processId($actions);
        if($id === null)
            return;

        // otherwise id is set
        $this->processIdRequest($method, $id);

    }

    private function processId($actions): ?int{

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

        switch ($method) {

            case "GET":
                $row = $this->model->get(self::getIdFromToken());
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

    private function processImg($method): void{
        switch ($method) {
            case "OPTIONS":
                http_response_code(200);
                break;

            case "PATCH":
                $data = (array)json_decode(file_get_contents("php://input"), true);
                if(self::checkData($data, ["image"])){

                    $data["id"] = self::getIdFromToken();
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
                //http_response_code(200);
                //echo json_encode(["image"=>$data["image"]]);
                break;

            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

    private function processChangePass($method): void{
        switch ($method) {
            case "OPTIONS":
                http_response_code(200);
                break;

            case "PATCH":
                $data = (array)json_decode(file_get_contents("php://input"), true);

                if(self::checkData($data, ["password"])){

                    $data["id"] = self::getIdFromToken();
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

    private static function getIdFromToken(): int{
        $token= getallheaders()["Authorization"];
        $tokenParts = explode('.', $token);
        $decodedPayload = json_decode(base64_decode($tokenParts[1]), true);
        return $decodedPayload["id"];
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