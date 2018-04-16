<?php

// this class is obsolete. I created it When I was handling fee from APP. Now I am handling fee from inside paypal.
ْْْْْْْ
class Fee {

    public $id;
    public $org_type;
    public $fee_per_trainee;
    public $user_id = -1;
    public $is_deleted;
    public $time_at_save;

    public function save_fee(mysqli $con) {
        $this->delete_fee($con);
        $stmt = $con->prepare("INSERT INTO rse_trainee_fee (fee_per_trainee, user_id)"
                . "VALUES( ?,?)");

        $fee = $this->fee_per_trainee;
        $user_id = $this->user_id;

        $stmt->bind_param("di", $fee, $user_id);
        $stmt->execute();
        //$last_id = $con->insert_id;
        $id = mysqli_insert_id($con);
        $this->id = $id;
    }
//end function

    public function delete_fee(mysqli $con) {

        $stmt = $con->prepare("update rse_trainee_fee set is_deleted=?");

        $is_deleted = 1;
        $stmt->bind_param("i", $is_deleted);
        $stmt->execute();
    }
//end function

    public function get_fee(mysqli $con) {
        $sql = "SELECT * FROM rse_trainee_fee where is_deleted=?";
        $stmt = $con->prepare($sql);
        $is_deleted = 0;
        $stmt->bind_param("i", $is_deleted);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $this->id = $row["id"];
            $this->fee_per_trainee = $row["fee_per_trainee"];
            $this->time_at_save = $row["time_at_save"];
            $this->user_id = $row["user_id"];
        }//end if-statement        
    }
//end function
}
//end class