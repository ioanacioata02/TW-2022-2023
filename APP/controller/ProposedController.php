<?php

class ProposedController extends Controller{

    public function __construct(){
        parent::__construct();
    }

    public function processRequest(string $method, ?string $actions): void{
        
        if(!isset($actions)){
            $this->processSimpleRequest($method);
            return;
        }

        // with parameters
        // admin status
        if(!Jwt::validateAuthorizationToken(2))
            return;

        $params = $this->processAction($actions);
        if($params === null) // params are not the expected ones
            return;

        if (isset($params["id"]))
            $this->processResourceRequest($method, intval($params["id"]));

        else if(!is_numeric($params['page']) || $params['page'] <= 0){
            http_response_code(400);
            echo json_encode(["message" => "Page must be a natural number"]);
        }
        else if(!is_numeric($params['limit']) || $params['limit'] <= 0){
            http_response_code(400);
            echo json_encode(["message" => "Limit must be a natural number"]);
        }
        else
            $this->processViewAllRequest($method, $params); 
            
    }

    private function processAction($actions): ?array{

        $pattern = '/^\d+$/';
        if(preg_match($pattern, $actions, $matches) === 1 && count($matches) === 1){
            $params['id'] = $matches[0];
            return $params;
        }
        
        $query = substr($actions, 1);
        parse_str($query, $params);

        if(isset($params['page']) && isset($params['limit']))
            return $params;

        http_response_code(404);
        echo json_encode(["message" => "Route not found"]);
        
        return null;
    }

    private function processViewAllRequest(string $method, array $params): void{

        switch ($method){
            case "GET":
                echo json_encode(["problems"=>$this->model->getPage($params), "nrOfProblems" => $this->model->getNrOfProblems()]);
                break;
            
            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed with this parameters"]);
                break;
        }
    }
    

    private function processResourceRequest(string $method, int $id): void{
        
        switch ($method) {
            case "OPTIONS":
                http_response_code(200);
                break;

            case "GET":
                $row = $this->model->get($id);
                if(!empty($row)){
                    http_response_code(200);
                    echo json_encode($row);
                }
                else{
                    http_response_code(404);
                    echo json_encode(["id" => $id, "message" => "Proposed problem not found"]);
                }
                break;

            case "POST":
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed. Use POST proposed without id"]);
                break;

            case "PUT":
                if ($this->model->accept($id)) {
                    http_response_code(201);
                    echo json_encode(["id" => $id, "message" => "Problem accepted"]);
                }

                break;

            case "DELETE":
                if($this->model->reject($id)){
                    http_response_code(200);
                    echo json_encode(["id" => $id, "message" => "Problem rejected"]);
                }
                break;

            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

    private function processSimpleRequest($method): void{
        // at least teacher
        if(!Jwt::validateAuthorizationToken(1)){
            return;
        }

        switch ($method) {
            case "OPTIONS":
                http_response_code(200);
                break;


            case "POST":
                $data = (array)json_decode(file_get_contents("php://input"), true);
                $data["id_author"] = self::getIdFromToken();

                if(self::checkData($data)){
                    $id = $this->model->create($data);
                    if($id > 0){
                        http_response_code(200);
                        echo json_encode(["id" => $id, "message" => "Problem created"]);
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

    private static function checkData(array $data): bool{
        if($data === null){
            return false;
        }

        $requiredKeys = ["name", "description", "tags", "tests", "id_author"];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                $differentKeys[] = $key;
            }
        }

        if (count($requiredKeys) !== count($data) || !empty($differentKeys))
            return false;

        
        if(!is_numeric($data['id_author']))
            return false;

        //tags
        if(empty($data['tags'])){
            return false;
        }
        
        //tests
        if(empty($data['tests'])){
            return false;
        }

        #var_dump($data['tests']);
        $requiredKeys = ["input", "output"];
        foreach ($data['tests'] as $test) {
            if (count($requiredKeys) !== count($test)){
                return false;
            }
            foreach($requiredKeys as $key){
                if(!array_key_exists($key, $test))
                    return false;
            }
        }

        return true;
    }
}