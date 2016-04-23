<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

include '../vendor/autoload.php';

$app = new \Slim\App;

include 'connection.php';

$app->post('/address', function (Request $req, Response $response) {
    $PostVars = $req->getParsedBody();
    $name = $PostVars['name'];
    $email = $PostVars['email'];
    $address_line_1 = $PostVars['address_line_1'];
    $address_line_2 = $PostVars['address_line_2'];
    $mobile = $PostVars['mobile'];
    $city = $PostVars['city'];
    $state = $PostVars['state'];
    $pin = $PostVars['pin'];

    try 
    {
        $db = getDB();
        $q1 = "INSERT into address(name,email,address_line_1,address_line_2,mobile,city,state,pin)
         values('$name','$email','$address_line_1','$address_line_2','$mobile','$city','$state',$pin)";
        $ins = $db->prepare($q1);
        $ins->execute();
        echo json_encode(array("status" => "successful insertion"));
        $db = null;
 
    } catch(PDOException $e) {
        echo json_encode(array("error" => $e->getMessage()));
    }
 
});

$app->get( '/address', function ( Request $req, Response $response) {
	try{
		$db = getDB();
		$q2 = "SELECT * from address";
		$giv = $db->prepare( $q2 );
		$giv->execute();
		$data = [];
		while($A = $giv->fetch(PDO::FETCH_ASSOC )) {
			$data[] = $A;
		}
		
		echo json_encode($data);
		
	} catch(PDOException $e) {
        echo json_encode(array("error" => $e->getMessage()));
    }

});

$app->get('/address/{id}', function (Request $req, Response $response, $args) {
	$id = (int)$args['id'];
    try {
        $db = getDB();
        $q3 = "SELECT * FROM address WHERE id = ".$id."";
        $getb = $db->prepare( $q3 );
 
        $getb->bindParam(':id', $id, PDO::PARAM_INT);
        $getb->execute();
 
        $A = $getb->fetch(PDO::FETCH_OBJ);
 
        if($A) {
           echo json_encode($A);
            $db = null;
        } else {
            throw new PDOException('No records found.');
        }
 
    } catch(PDOException $e) {
        echo json_encode(array("error" =>$e->getMessage()));
    }
});


 
 
$app->run();