<?php


class ProblemsController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }
    public function processRequest(string $method, ?string $id):void
    {
        if($id)
        {
            $this->processResourceRequest($method, $id);
        }
        else
        {
            $this->processCollectionRequest($method);
        }
    }

    private function processCollectionRequest(string $method): void
    {
        switch ($method)
        {
            case "GET":
                echo json_encode($this->model->getAll());
                break;
            case "POST":
               $this->handlePostRequest();
               break;
        }
    }
    private function handlePostRequest():void
    {
        $data = (array) json_decode(file_get_contents("php://input"), true);
        $id = $this->model->create($data);
        if(Jwt::validateAuthorizationToken(2))
            echo json_encode(["message"=>"Problem created", "id"=>$id]);

    }

}