<?php

class AuthenticationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    private function processSimpleRequest($method): void{

        switch ($method) {
            case "POST":
                /*
                if(!Jwt::validateAuthorizationToken(1)){
                    return;
                }*/
              

                break;

            default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
            break;
        }
    }

    public function login(string $username, string $password): void
    {
        // Validate the username and password
        if ($this->validateCredentials($username, $password)) {
            // Generate and return an authentication token
            $token = $this->generateToken($username);
            echo json_encode(["token" => $token]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Invalid credentials"]);
        }
    }

    public function logout(): void
    {
        // Invalidate the current authentication token
        // ...
        echo json_encode(["message" => "Logged out successfully"]);
    }

   

    private function generateToken(string $username): string
    {
        // Replace this with your actual token generation logic
        // For example, you might use a JWT (JSON Web Token) library to generate a token
        // Here's a simple example that generates a random token
        $token = bin2hex(random_bytes(16));

        return $token;
    }
}
