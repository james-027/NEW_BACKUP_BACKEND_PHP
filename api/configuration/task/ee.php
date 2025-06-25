<?php
$requestID = null;
if (isset($uri[2])) {
    $requestID = (int) $uri[2];
}
$bearer_token = '';
if (!empty($_SERVER['HTTP_AUTHORIZATION'])){
    $auth_header = $_SERVER['HTTP_AUTHORIZATION'];
    if(strpos($auth_header,'Bearer')=== 0){
        $bearer_token = substr($auth_header,7);
    }
}
$file =  $getfile;
$getUserID = $userID;
$requestMethod = $_SERVER["REQUEST_METHOD"];
$controller = new Controller($requestMethod, $requestID,$bearer_token,$getUserID,$file);
$controller->processRequest();
class Controller
{
    private $requestMethod;
    private $requestID;
    private $bearer_token;
    private $getUserID;
    private $file;
    public function __construct($requestMethod, $requestID,$bearer_token,$getUserID,$file)
    {
        $this->requestMethod = $requestMethod;
        $this->requestID = $requestID;
        $this->bearer_token = $bearer_token;
        $this->getUserID = $getUserID;
        $this->file = $file;
    }
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'POST':
                $response = $this->post();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
       private function post()
    {
        require "bootstrap.php";
        $newUser = new User();
        $token = new Tokens();
        $result = $token->getValidation($this->bearer_token);
        $input = (array) json_decode(file_get_contents('php://input'), true);
      if($result == true){
        $task = $entityManager->find(Page::class, $input['page_id']);
        $validation_list= [];
        foreach($task->getTaskvalidation() as $validation){
            $created_by = $entityManager->find(User::class,$validation->getCreatedby());
            $user_type = $entityManager->find(Mirrorposition::class,$validation->getUsertype());
            $validation_list[] = [
                "id"=>$validation->getId(),
                "created_by" =>$created_by->getFirstname() . " " . $created_by->getLastname(),
                "user_type" =>$user_type->getDescription(),
                "valid"=>$validation->getValid()
            ];
        }
        echo header("HTTP/1.1 200 OK");
        echo json_encode($validation_list);



        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($validation_list);
        return $response;
}else{
                echo header('Content-Type: application/json; charset=utf-8');
                $response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
                $response['body'] = json_encode(['Message'=> "Invalid Token"]);
}
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode(["Message" => "Method not allowed"]);
        return $response;
    }
}
