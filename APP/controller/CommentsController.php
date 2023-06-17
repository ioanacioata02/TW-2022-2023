<?php


class CommentsController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }
    public function processRequest(string $method, ?string $actions): void
    {

        $params = $actions ? $this->processAction($actions) : null;

        if($method== "OPTIONS"){
            // Setează codul de stare 200 și antetele CORS pentru cererile OPTIONS
            http_response_code(200);
            return;}
        if (isset($params["id"])) {

            $this->processResourceRequest($method, intval($params["id"]));
            return;

        }
        else http_response_code(400);
        echo json_encode(["message" => "Problem id unknown"]);

    }

    protected function processAction($actions)
    {

        $query = parse_url($actions, PHP_URL_QUERY);
        parse_str($query, $params);
        return $params;
    }

    private function processResourceRequest(string $method, int $id): void
    {
        $userId = Jwt:: getIdFromToken();
        switch ($method) {
            case "GET":
                echo json_encode($this->model->get($id));
                break;
            case "POST":
                $this->handlePost($id,$userId);
                break;
            default:
                http_response_code(405);
                echo json_encode(["message" => "Method not allowed"]);
                break;
        }
    }

    private function handlePost(int $id, int $userId)
    {
        $payload = Utils::getBody();
        $title = htmlspecialchars($this->fieldExists($payload, "title"));
        $text = htmlspecialchars($this->fieldExists($payload, "comment_txt"));
        $grade=  htmlspecialchars($this->fieldExists($payload, "grade"));

        $success = $this->model->addComment($id,$userId,$title,$text, $grade);
        if ($success) {
            http_response_code(200);
            echo json_encode(["message" => "Comment added successfully"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Failed to add comment"]);
        }


    }





}