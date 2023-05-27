<?php

class AuthenticationController extends Controller
{   private Jwt $jwt;
    private AuthenticationModel $am;
    public function __construct()
    {
        parent::__construct();
        $this->jwt = new Jwt();
        $this->am=new AuthenticationModel();
    }

    private function processSimpleRequest($method): void
{
    switch ($method) {
        case "POST":
            $requestData = json_decode(file_get_contents('php://input'), true);//request data-ul primit e in format json
            $username = $requestData['username'] ?? '';
            $password = $requestData['password'] ?? '';
            $key = 'secret';
            if ($this->am->validateCredentials($username, $password)) {
                $token = $this->jwt->generateToken($payload,$key);
                echo json_encode(["token" => $token]);
            } else {
                http_response_code(401);
                echo json_encode(["message" => "Invalid credentials"]);
            }

            break;

        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
            break;
    }
}





   

}
