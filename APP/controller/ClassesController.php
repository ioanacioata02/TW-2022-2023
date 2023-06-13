<?php

class ClassesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
    }

    public function processRequest(string $method, ?string $actions): void
    {

        if($method === "OPTIONS"){
            http_response_code(200);
            return;
        } 



        $userId = Jwt:: getIdFromToken();

        if ($userId !== null) {
            if(!isset($actions)){
            
                $this->processSimpleRequest($method,$userId);
                return;
            }
            $id = $this->processId($actions);
            if($id === null)
                return;
          $this->processIdRequest($method, $id,$userId);
    
        }


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



    private function processSimpleRequest(string $method, int $userId): void
    {
        
        switch ($method) {
            case "GET":
                $rows = $this->model->getForUser($userId);
                $nr = $this->model->getNrOfClasses($userId);
                if (!empty($rows)) {
                    http_response_code(200);
                    echo json_encode(["classes"=>$rows, "nrOfClasses" => $nr]);
                } else {
                    http_response_code(404);
                    echo json_encode(["userId" => $id, "message" => "No classes were found"]);
                }
                break;
            case "POST":
                // Handle creating a new class for the user
                // ...
                break;
            case "PUT":
                // Handle updating a class for the user
                // ...
                break;
            case "DELETE":
                // Handle deleting a class for the user
                // ...
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

    private function processIdRequest(string $method, int $id,int $userId): void{
        
        switch ($method) {

            case "GET":
                $row = $this->model->get($id, $userId);
                if (!empty($row)) {
                    http_response_code(200);
                    echo json_encode($row);
                } else {
                    http_response_code(404);
                    echo json_encode(["id" => $id, "message" => "No classes were found"]);
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

}
