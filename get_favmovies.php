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

 
 
    // if decode succeed, show user details
 
        // decode jwt
 

        $movies->idArray = $data->idArray;
        $movies->filters = $data->filters;
        $movies->userName = $data->userName;

    
    // update the user record
    if($stmt = $movies->getfavMovies()){
        // regenerate jwt will be here
        // we need to re-generate jwt because user details might be different        
        // set response code
        $movies_arr = NULL;
        $num = $stmt->rowCount();
        if($num > 0){
            $movies_arr = array();
            $movies_arr["records"] = array();
            $movies_arr["count"] = $num;
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $movies_item = array(
                    "id" => $id,
                    "Title" => $title,
                    "Year" => $year,
                    "imdbId" => $imdbId,
                    "Type" => $type,
                    "object" => json_decode($object),
                    "Poster" => $img,
                    "userName" => $userName,
                    "archived" => $archived,
                    "created" => $created,
                    "modified" => $modified
                );

                array_push($movies_arr["records"], $movies_item);
            }
        }




        http_response_code(200);
        
        // response in json format
        echo json_encode($movies_arr);
    }
    
    // message if unable to update user
    else{
        // set response code
        http_response_code(401);
    
        // show error message
        echo json_encode(array("message" => "Unable to get data."));
    }


?>