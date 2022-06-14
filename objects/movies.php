<?php
// 'user' object
class FavoriteMovies{
 
    // database connection and table name
    private $conn;
    private $table_name = "favmovies";
 
    // object properties
    public $id;
    public $title;
    public $year;
    public $imdbId;
    public $type;
    public $object;
    public $img;
    public $userName;
    public $archived;
    public $idArray;
    public $filters;


 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
// create() method will be here
// create new user record
function create(){
 
    // insert query
    $query = "INSERT INTO " . $this->table_name . "
            SET
                title = :title,
                year = :year,
                imdbId = :imdbId,
                userName = :userName,
                type = :type,
                img = :img,
                object = :object";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->title=htmlspecialchars(strip_tags($this->title));
    $this->year=htmlspecialchars(strip_tags($this->year));
    $this->imdbId=htmlspecialchars(strip_tags($this->imdbId));
    $this->userName=htmlspecialchars(strip_tags($this->userName));
    $this->type=htmlspecialchars(strip_tags($this->type));
    $this->img=htmlspecialchars(strip_tags($this->img));
    $this->object= $this->object ? json_encode($this->object) : NULL;



 
    // bind the values
    $stmt->bindParam(':title', $this->title);
    $stmt->bindParam(':year', $this->year);
    $stmt->bindParam(':imdbId', $this->imdbId);
    $stmt->bindParam(':img', $this->img);
    $stmt->bindParam(':type', $this->type);
    $stmt->bindParam(':userName', $this->userName);
    $stmt->bindParam(':object', $this->object);

 
    // execute the query, also check if query was successful
    if($stmt->execute()){
        return true;
    }else{
        $stmt->debugDumpParams();
        return false;
    }
}
 



    public function delete(){

        $query = "DELETE FROM " . $this->table_name . "
                WHERE id = :id AND userName = :userName";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userName', $this->userName);
        $stmt->bindParam(':id', $this->id);


        
        if($stmt->execute()){
            return true;
        }
        $stmt->debugDumpParams();
    
        return false;
    }

    public function getfavMovies(){
        $idArray = $this->idArray;
        $filters = $this->filters;
        $userName = $this->userName;
        $query = "SELECT * FROM " . $this->table_name;
        if(isset($idArray) && $idArray[0] != 0 && !$userName){
           $query .= " WHERE ";

        //    $query .= "bridge_id = ". $idArray[0];

        foreach ($idArray as $key => $id) {
            $andOr = $key != 0 ? " OR " : "";
            $query .= $andOr . "id = " . $id;
        }

        //    for ($i=0; $i < $idArray.length; $i++) { 
        //        $paring = "bridge_id = ".$idArray[$i];
        //        $query.= $paring;
        //    } 
        }else
        
        if(isset($filters) && $filters != [] && !$userName){
            $query .= " WHERE ";
            foreach ($filters as $key => $filter) {
                $andOr = $key != 0 ? " OR " : "";
                $query .= $andOr . $filter->tulp." LIKE '".$filter->tekst."'";
            }
        }

        if(isset($userName)){
            $query .= " WHERE userName LIKE '" . $userName . "' AND archived = 0"; 
        }
       

        $stmt = $this->conn->prepare($query);
        if($stmt->execute()){
            return $stmt;
        }
        $stmt->debugDumpParams();
    
        return false;
    }

}