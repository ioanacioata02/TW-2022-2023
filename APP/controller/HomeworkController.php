<?php

class HomeworkController extends Controller
{
    protected $dispatchTable = [
        'students' => 'processStudentsAction',
        'problems' => 'processProblemsAction',
        'solutions' => 'processSolutionsAction',
        'submit' => 'processSubmitAction'
    ];
    private $params;
    public function __construct()
    {
        parent::__construct();
    }

    public function processRequest(string $method, ?string $actions)
    {
        $this->params = $actions ? $this->processAction($actions) : null;
        if ($this->params) {
            foreach ($this->dispatchTable as $key => $action) {
                if (isset($this->params[$key])) {
                    if (method_exists($this, $action)) {
                        if ($method == 'GET' || $method == 'POST') {
                            $this->$action($this->params[$key], $method);
                        } else {
                            $this->respondMethodNotAllowed();
                        }
                    }
                }
            }
        } else {
            $this->processNoAction($method);
        }
    }

    private function processStudentsAction($id, $method)
    {
        if ($method == 'GET') {
            if (Jwt::validateAuthorizationToken("secret", 1)) {
                $result = $this->model->getStudentsFromHomework($id);
                echo json_encode($result);
            } else {
                $this->respondUnauthorized();
            }
        }
    }

    private function processProblemsAction($id, $method)
    {
        if ($method == 'GET') {
            if (Jwt::validateAuthorizationToken("secret")) {
                $result = $this->model->getProblems($id, Jwt::getPayload());
                echo json_encode($result);
            } else {
                $this->respondUnauthorized();
            }
        }
    }

    private function processSolutionsAction($id, $method)
    {


        if ($method == 'GET') {

                if (isset($this->params["student"])) {
                if (Jwt::validateAuthorizationToken("secret", 1)) {

                    $result = $this->model->getStudentProblems($id, $this->params["id_student"]);
                    echo json_encode($result);
                }
            } else {
                $this->respondBadRequest();
            }
        }
        else
        {
            $this->respondMethodNotAllowed();
        }
    }

    private function processSubmitAction($id, $method)
    {
        if ($method == 'POST') {
            if (Jwt::validateAuthorizationToken("secret")) {
                $body = Utils::getBody();
                if (isset($body["solution"]) && isset($body["id_problem"])) {
                    $result = $this->model->postSolution($body["solution"], $body["id_problem"], $id, Jwt::getPayload()["id"]);
                    echo json_encode($result);
                } else {
                    $this->respondBadRequest();
                }
            } else {
                $this->respondUnauthorized();
            }
        }
    }

    private function processNoAction($method)
    {
        switch ($method)
        {
            case "POST":
                if(Jwt::validateAuthorizationToken("secret", 1)) {
                    $data = Utils::getBody();
                    $response  = $this->model->createHomework($data);
                    echo json_encode($response);
                }
                break;
            case "GET":

                if(Jwt::validateAuthorizationToken("secret")) {

                    $response =  $this->model->getHomeworks(Jwt::getPayload());
                    echo json_encode($response);
                }
                break;
        }
    }



    private function respondBadRequest()
    {
        http_response_code(400);
        echo json_encode(["message" => "Bad request"]);
    }

    private function respondMethodNotAllowed()
    {
        http_response_code(400);
        echo json_encode(["message" => "Method not allowed"]);
    }

    private function respondUnauthorized()
    {
        http_response_code(401);
    }
}