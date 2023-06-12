<?php

class AuthenticationController extends Controller
{
    public function __consstruct()
    { 
        
        parent::__construct();
    }

    public function processRequest(string $method, ?string $action):void
     {
       if($method== "OPTIONS"){
            // Setează codul de stare 200 și antetele CORS pentru cererile OPTIONS
            http_response_code(200);
            return;}
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