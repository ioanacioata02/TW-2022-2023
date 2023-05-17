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
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $id = $this->model->create($data);
                echo json_encode(["message"=>"Problem created",
                "id"=>$id]
                );
                break;
        }
    }

}