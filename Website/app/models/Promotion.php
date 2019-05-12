<?php

class Promotion extends Model {
    
    public function __construct() {
        $table = "promotion";
        parent::__construct($table);
        $this->_softDelete = true;
    }

    public function Search($params=[]) {
        $results = [];
        $today = currentDate();

        if($params["promoter"] == "on") {
            $this->query("SELECT * FROM promotion WHERE  pr_username = ? AND state = ? AND end_date > ? ORDER BY start_date DESC" , [$params['search'],"Approved",$today]);
        } elseif ($params['catagory'] == "on") {
            $this->query("SELECT * FROM promotion WHERE  catagory = ? AND state = ? AND end_date > ? ORDER BY start_date DESC" , [$params['search'],"Approved",$today]);
        } else {
            $this->query("SELECT * FROM promotion WHERE (catagory = ? OR pr_username = ? OR title = ?) AND state = ? AND end_date > ? ORDER BY start_date DESC", [$params['search'],$params['search'],$params['search'],"Approved",$today]);
        }
        $resultsQuery = $this->_db->results();
        foreach($resultsQuery as $result) {
            $obj = new Promotion($this->_table);
            $obj->populateObjData($result);
            $results[] =$obj;
        }

        return $results;
    }

    public function getPromoByCatagory($catagory) {
        $today = currentDate();
        $results = $this->find(['conditions' => ['catagory = ?' ,'end_date > ?','state = ?'],'bind' => [$catagory,$today,'Approved'],'order' => "start_date DESC"]);
        return $results;
    }

    public function comfirmPromotions($promotion) {
        $this->update($promotion->id,array(
            'state' => 'Approved'
        ));
    }

    public function validatePromo() {

    }

    public function registerPromo() {

    }

    

    // public static function readPromotionFromDB($promoID){
	// 	$dbh=new Dbh();
	// 	$conn = $dbh->connect();
	// 	$sql = $conn->prepare("SELECT * from confirmed_promotion WHERE promo_id = ?");
				
	// 	$sql->bind_param("s", $promoID);
	// 	$sql->execute();
	// 	$results = $sql->get_result();
	// 	if($row = $results->fetch_array(MYSQLI_ASSOC)){

	// 		//$promoID,$category,$title,$description,$image,$link,$state,$startDate,$endDate,$location,$pr_username,$ad_username
    //         return new Promotion($row["promo_id"],$row["category"],$row["title"],$row["description"],$row["image_path"],$row["link"],$row["state"],$row["start_date"],$row["end_date"],$row["location"],$row['pr_username'],$row["ad_username"]);
	// 	}

	// 	else{
	// 		return null;
	// 	}	
	// }


}