<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS' ) {
    die ('OK');
}
 
// required to encode json web token
include_once 'config/core.php';
 
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/movies.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$movies = new FavoriteMovies($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// get jwt
 
// if jwt is not empty
 
    // if decode succeed, show user details
 
    // set user property values
    $movies->id = $data->id;
    $movies->userName = $data->userName;
    
    // update the user record
    if($movies->delete()){
        // user Delete successful
        
        // set response code
        http_response_code(200);
        
        // response in json format
        echo json_encode(
                array(
                    "message" => "Fav movie was deleted.",
                    "id" => $movies->id
                )
            );
    }
    
    // message if unable to update user
    else{
        // set response code
        http_response_code(401);
    
        // show error message
        echo json_encode(array("message" => "Unable to delete movie."));
    }
 
// if decode fails, it means jwt is invalid


?>