<?php

class StatsController extends Controller{

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

        http_response_code(404);
        echo json_encode(["message" => "Route not found"]);
            
    }
    

    private function processSimpleRequest($method): void{
        if(!Jwt::validateAuthorizationToken("secret", 2)){
            return;
        }

        switch ($method) {

            case "GET":
                $data = $this->model->get();
                http_response_code(200);
                echo json_encode($data);
                break;

            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }
}