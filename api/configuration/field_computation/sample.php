<?php
header("Access-Control-Allow-Origin: *");

$getfile = "https"."://"."uat-php-dws.chookstogo.com.ph"."/19987file/?file=";
    require "../bootstrap.php";
    $newUser = new User();
    $token = new Tokens();

$id = '';
if (isset($_GET['id'])) {
     $id = $_GET['id'];
}
     $decision = "";
     $question = $entityManager->find(Question::class,$id);
                if($question){
                   $selectanswer = json_decode($question->answer(), true);

                    if($selectanswer[0]['choices'][0]['value']!=""){
                    $result = 0;
                    $operator = "";

                    foreach ($selectanswer as $index => $value) {
                        $count = count($value['choices']);
                        for ($i = 0; $i < $count; $i++) {
                           if (isset($value['choices'][$i]['value'])) {
                            preg_match('/([\d.]+)\s*([<>])\s*([\d.]+)/', $value['choices'][$i]['value'], $matches);
                             if (count($matches) != 4) {
                                throw new Exception("Invalid comparison expression: $expression");
                             }
                           }

                            $question = $entityManager->find(Question::class,$matches[1]);
                            $selectanswer = json_decode($question->answer(), true);
                            $value1 = (float) $question->final_answer();
                            $operator = $matches[2];
                            $value2 = (float) $matches[3];


try {
    if ($value1<$value2){
        $decision  = ['value'=>$value1,'color'=>$value['decision'][$i]['value']];
    }
} catch (Exception $e) {}

                         }
                      }
                    }
                 }
                    echo header('HTTP/1.1 200 OK');
                    echo json_encode($decision);