<?php
require_once ("vendor/autoload.php");
class AuthenticationModel extends Model
{  private Jwt $jwt;
    public function __construct()
    {
        parent::__construct();

    }
  
   
    private function validateCredentials(string $username, string $password): bool
    { 
        $connection = $this->connectionPool->getConnection();
$nume = $_POST['username'];
$pass = $_POST['password'];
$stm = $connection->prepare("SELECT * FROM users WHERE username=?");
$stm->bindValue(1, $username);
$res = $stm->execute();

$rows = $stm->fetchAll(PDO::FETCH_NUM);

if (password_verify($pass, $rows[0][2])) echo "Autentificat";
else echo "Neautorizat";

return password_verify($pass, $rows[0][2]);
    }


    private function createToken(string $username, string $password):bool
    {
        if (validateCredentials($username,$password))
{  $connection = $this->connectionPool->getConnection();
    $nume = $_POST['username'];
    $pass = $_POST['password'];
    $stm = $connection->prepare("SELECT * FROM users WHERE username=?");
    $stm->bindValue(1, $username);
    $res = $stm->execute();
    $rows = $stm->fetchAll(PDO::FETCH_NUM);
    
    $t0 = time();

    $key = 'secret';
    $payload = [
        'iss' => 'http://localhost',
        'aud' => 'http://infoiasi.ro',
        'userID' => $rows[0][0],
        'userName' => $rows[0][1],
        'iat' => $t0,
        'nbf' => $t0+10,
        'exp' => $t0+3600
    ];


    $token = $this->jwt->generateToken($payload,$key);
    
    echo $token;
} else {
   http_response_code(401);
}

    }

    
function getAuthorizationHeader():?string{
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}


/**
 * get access token from header
 * */
function getBearerToken():?string {
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}

function authenticated_request():void
{$jwt = getBearerToken();
    $key = getenv('secret');
 
    $decoded = $this->jwt->validateToken($jwt,$key);
    echo $decoded->userName;

}


    
}