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
                    echo json_encode(["userId" => $userId, "message" => "No classes were found"]);
                }
                break;
            case "POST":
                $this->handlePost($userId);
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
                case "POST":
                    $requestData = json_decode(file_get_contents('php://input'), true);
                    if (isset($requestData['userId']) ) 
                        $param1 = $requestData['userId'];
                    $success = $this->model->addMember($id, $param1);
                    if ($success) {
                        http_response_code(200);
                        echo json_encode(["message" => "Member added to the class successfully"]);
                    } else {
                        http_response_code(400);
                        echo json_encode(["message" => "Failed to add member to the class"]);
                    }
                
                    break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

    private function handlePost(int $id)
    {
        $payload = Utils::getBody();
        $className = htmlspecialchars($this->fieldExists($payload, "className"));
        
           $success = $this->model->createClass($className,$id);
           if ($success) {
            http_response_code(200);
            echo json_encode(["message" => "Class added successfully"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Failed to add class"]);
        }
    
           
        }


    

}
