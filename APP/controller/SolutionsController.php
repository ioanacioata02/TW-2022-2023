<?php

class SolutionsController extends Controller
{
    public function __consstruct()
    {
        parent::__construct();
    }

    public function processRequest(string $method, ?string $actions)
    {
        $params = $actions ? $this->processAction($actions) : null;
        if(Jwt::validateAuthorizationToken("secret")) {
            if(!isset($params["id"]))
                $this->badRequest();

            switch ($method) {

                case "POST":
                    $this->handlePost($params["id"]);
                    break;
                case "GET":
                    $this->handleGet($params["id"]);
                    break;
            }
        }

    }
    private function handleGet($id_problem)
    {
        $result = $this->model->getSolutions($id_problem, Jwt::getPayload()["id"]);
        echo json_encode($result);
    }

    private function handlePost($id_problem)
    {
        $body= Utils::getBody();
        if(isset($body["solution"]) )
        {
            $result = $this->model->create($id_problem, $body["solution"], Jwt::getPayload()["id"]);
            http_response_code(201);
            echo json_encode($result);
            return;
        }
        else
        {
            $this->badRequest();
        }
    }
    private function badRequest()
    {
        http_response_code(401);
        throw new Exception("Something went wrong");

    }
}