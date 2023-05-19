<?php

class ProposedController extends Controller{

    public function __construct(){
        parent::__construct();
    }

    public function processRequest(string $method, ?string $actions): void{

        $params = $actions ? $this->processAction($actions) : null;

        if($actions === null){
            $this->processSimpleRequest($method);
        }

        /*
        if(!Jwt::validateAuthorizationToken(2))
            return;*/

        if (isset($params["id"]))
            $this->processResourceRequest($method, intval($params["id"]));

        else if(isset($params['page']) && isset($params["limit"]))
            echo json_encode($this->model->getPage($params));
            
        return;
    }

    private function processAction($actions){
        $pattern = '/^\d+$/';
        if(preg_match($pattern, $actions, $matches) === 1 && count($matches) === 1){
            $params['id'] = $matches[0];
            return $params;
        }

        $query = parse_url($actions, PHP_URL_QUERY);
        parse_str($query, $params);

        if(isset($params['page']) && isset($params['limit']))
            return $params;
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
                echo json_encode(["message" => "Method not allowed. Use POST proposed"]);
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
        }
    }

    private function processSimpleRequest($method): void{

        switch ($method) {
            case "POST":
                /*
                if(!Jwt::validateAuthorizationToken(1)){
                    return;
                }*/
                $data = (array)json_decode(file_get_contents("php://input"), true);
                $id = $this->model->create($data);
                if($id > 0){
                    http_response_code(200);
                    echo json_encode(["id" => $id, "message" => "Problem created"]);
                }

                break;

            default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
            break;
        }
    }
}