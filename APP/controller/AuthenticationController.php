<?php

class AuthenticationController extends Controller
{
<<<<<<< Updated upstream
    public function __construct()
=======
    public function __consstruct()
>>>>>>> Stashed changes
    {
        parent::__construct();
    }

<<<<<<< Updated upstream
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
=======
    public function processRequest(string $method, ?string $action):void
    {
        if($method!="POST")
        {
            Utils::notAllowed();
            return;
        }
        $this->handlePost();

    }

    private function handlePost()
    {


        if (Jwt::validateAuthorizationToken("secret")) {
            echo json_encode(["JWT"=> $token= getallheaders()["Authorization"], "message" => "Success"]);
            return;
        }
    
        $payload = Utils::getBody();
        if (isset($payload["email"]) && isset($payload["password"])) {
            $result = $this->testUser($payload["email"], $payload["password"]);
            echo json_encode($result);
            return;
        }
        Utils::throwError(401, ["message"=>"Fail"]);
    }
   public function testUser($email, $password)
   {
       $email=htmlspecialchars($email);
       $password=htmlspecialchars($password);
       if(filter_var($email, FILTER_VALIDATE_EMAIL))
       {
           $email=filter_var($email, FILTER_SANITIZE_EMAIL);
           $data = $this->model->checkData($email, $password);
          
           if($data==null)
           {    http_response_code(401);
                return ["JWT"=> "","message"=>"failed"];
                
           }
           return [
               "JWT"=>Jwt::generateToken([
                   "id"=>$data["id"],
                   "username"=>$data["username"],
                   "status"=>$data["status"],
               ], 30*24),
               "message"=>"success"
           ];
       }
       else
       {
           Utils::throwError(400, ["message"=>"The email is invalid"]);
           exit(0);
       }
   }
}
>>>>>>> Stashed changes
