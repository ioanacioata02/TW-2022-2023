<?php


class ProblemsController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    public function processRequest(string $method, ?string $actions): void
    {


        $params = $actions ? $this->processAction($actions) : null;

        //if the id is set then this takes priority over anything else
        if (isset($params["id"])) {
            $this->processResourceRequest($method, intval($params["id"]));
            return;
        }

        $limit = $params["limit"] ?? 99999999;
        if (isset($params["sort"]) && $method=="GET") {
            echo $this->sortLimit($params["sort"], $limit);
            return;
        }
        elseif(isset($params["limit"])) {
            echo json_encode($this->model->getAll($limit));
            return;
        }
        $this->processCollectionRequest($method);
    }
    private function sortLimit($sort,$limit)
    {
        $sort=strtoupper($sort);
        switch ($sort)
        {
            CASE "POPULARITY":
                echo json_encode($this->model->sortLimit("nr_attempts", $limit, "DESC"));
                break;
            CASE "NAME":
                echo json_encode($this->model->sortLimit("name", $limit, "ASC"));
                break;
        }
    }
    private function processAction($actions)
    {

        $query = parse_url($actions, PHP_URL_QUERY);
        parse_str($query, $params);
        return $params;
    }

    private function processResourceRequest(string $method, int $id): void
    {
        switch ($method) {
            case "GET":
                echo json_encode($this->model->get($id));
                break;
            case "POST":
                http_response_code(400);
                echo json_encode(["message" => "For creating a problem use POST problems"]);
                break;
            case "PUT":
                if (Jwt::validateAuthorizationToken(2)) {
                    $data = (array)json_decode(file_get_contents("php://input"), true);
                    if (!$this->model->update($id, $data)) {
                        http_response_code(200);
                        echo json_encode(["id" => $id, "message" => "No entry found"]);
                    } else {
                        http_response_code(201);
                        echo json_encode(["id" => $id, "message" => "Problem updated"]);
                    }
                }

                break;
            case "DELETE":
                if (Jwt::validateAuthorizationToken(2)) {
                    $this->model->delete($id);
                    echo json_encode(["id" => $id, "message" => "The problem was successfully deleted"]);
                }
                break;
        }
    }

    private function processCollectionRequest(string $method): void
    {

        switch ($method) {
            case "GET":
                echo json_encode($this->model->getAll());
                break;
            case "POST":
                if (Jwt::validateAuthorizationToken(2)) {
                    $data = (array)json_decode(file_get_contents("php://input"), true);
                    $id = $this->model->create($data);
                    http_response_code(201);
                    echo json_encode(["id" => $id, "message" => "Problem created"]);
                }
                break;
            case "PUT":
                http_response_code(400);
                echo json_encode(["message" => "For updating a problem use PUT problems/?id=problem_id"]);
                break;
            case "DELETE":
                http_response_code(400);
                echo json_encode(["message" => "For deletion use DELETE problems/?id=problem_id"]);
                break;
        }
    }


}