<?php

class ProposedController extends Controller{

    public function __construct(){
        parent::__construct();
    }

    public function processRequest(string $method, ?string $actions): void{

        if(!isset($actions)){
            $this->processSimpleRequest($method);
        }
        else{
            $params = $this->processAction($actions);
        }

        if(!Jwt::validateAuthorizationToken(2))
            return;

        if (isset($params["id"]))
            $this->processResourceRequest($method, intval($params["id"]));

        else if(isset($params['page']) && isset($params["limit"])){
            if(!filter_var($params['page'], FILTER_VALIDATE_INT) || $params['page'] <= 0){
                http_response_code(400);
                echo json_encode(["message" => "Page must be a natural number"]);
            }
            else if(!filter_var($params['limit'], FILTER_VALIDATE_INT) || $params['limit'] <= 0){
                http_response_code(400);
                echo json_encode(["message" => "Limit must be a natural number"]);
            }
            else
                echo json_encode($this->model->getPage($params));
        }
            
        return;
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

    private function processResourceRequest(string $method, int $id): void{
        
        switch ($method) {
            case "GET":
                $row = $this->model->get($id);
                if(isset($row)){
                    if(!empty($row)){
                        http_response_code(200);
                        echo json_encode($row);
                    }
                    else{
                        http_response_code(404);
                        echo json_encode(["id" => $id, "message" => "Proposed problem not found"]);
                    }
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

        switch ($method) {
            case "POST":
                if(!Jwt::validateAuthorizationToken(1)){
                    return;
                }
                $data = (array)json_decode(file_get_contents("php://input"), true);

                if(self::checkData($data)){
                    $id = $this->model->create($data);
                    if($id > 0){
                        http_response_code(200);
                        echo json_encode(["id" => $id, "message" => "Problem created"]);
                    }
                }

                break;

            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

    private static function checkData(array $data): bool{
        if($data === null){
            http_response_code(400);
            echo json_encode(["message" => "Bad request"]);
            return false;
        }

        $requiredKeys = ["name", "description", "tags", "tests", "id_author"];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                $differentKeys[] = $key;
            }
        }

        if (count($requiredKeys) !== count($data) ||
        !empty($differentKeys) || 
        !filter_var($data['name'], FILTER_SANITIZE_SPECIAL_CHARS) || 
        !filter_var($data['description'], FILTER_SANITIZE_SPECIAL_CHARS) || 
        !filter_var($data['id_author'], FILTER_VALIDATE_INT)
        ) {
            http_response_code(400);
            echo json_encode(["message" => "Bad request"]);
            return false;
        }
        //tags
        if(empty($data['tags'])){
            {
                http_response_code(400);
                echo json_encode(["message" => "Bad request"]);
                return false;
            }
        }
        #var_dump($data['tags']);
        foreach ($data['tags'] as $tag) {
            if (!filter_var($tag, FILTER_SANITIZE_SPECIAL_CHARS)) {
                http_response_code(400);
                echo json_encode(["message" => "Bad request"]);
                return false;
            }
        }
        if(empty($data['tests'])){
            {
                http_response_code(400);
                echo json_encode(["message" => "Bad request"]);
                return false;
            }
        }
        #var_dump($data['tests']);
        $requiredKeys = ["input", "output"];
        foreach ($data['tests'] as $test) {
            if (count($requiredKeys) !== count($test)){
                http_response_code(400);
                echo json_encode(["message" => "Bad request"]);
                return false;
            }

        }
        return true;
    }
}