<?php

class RegisterController extends Controller
{
    public function __consstruct()
    {
        parent::__construct();
    }


    public function processRequest(string $method, ?string $action):void
    {   if($method== "OPTIONS"){
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
        $payload = Utils::getBody();
        $username = htmlspecialchars($this->fieldExists($payload, "username"));
        $lastName = htmlspecialchars($this->fieldExists($payload, "lastName"));
        $fistName = htmlspecialchars($this->fieldExists($payload, "firstName"));
        $password = htmlspecialchars($this->fieldExists($payload, "password"));
        $email=htmlspecialchars($this->fieldExists($payload, "email"));
        $type =strtoupper(htmlspecialchars($this->fieldExists($payload, "type")));
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $email=filter_var($email, FILTER_SANITIZE_EMAIL);
        }
        else
        {
            Utils::throwError(400, ["message"=>"The email is invalid"]);
            exit(0);
        }
        //0=>student
        //1=>teacher
        if($type=="0" || $type == "1")
        {
           $id = $this->model->register($lastName, $fistName, $username, $password, $email, $type);
           echo json_encode( ["JWT"=> Jwt::generateToken([
               "id"=>$id,
               "username"=>$username,
               "status"=>$type,
               ],"secret", 30*24)]);
        }


    }
}