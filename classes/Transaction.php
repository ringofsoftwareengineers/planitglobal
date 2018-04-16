<?php

//transaction of paypal to save transactiion data
class Transaction {
   
    public $id;
    public $transaction_id="-1";
    public $org_id=-1;
    public $no_of_trainees=-1;
    public $fee_per_trainee=1.1;
    public $is_completed=0;
    public $discount;
    public $user_id;
    public $time_at_save;
    public $tr_amount;




    public function get_no_of_paid_trainees($con,$org_id)
    {
        $sql="SELECT COALESCE(SUM(no_of_trainees),0)as trainees FROM rse_transactions WHERE org_id='$org_id' AND is_completed=1";
         $result = $con->query($sql);         
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["trainees"];
        }
        return 0;
    }//end function
    
    
    // saving trnsaction data in database after completion of transaction
    
     public function save_transaction(mysqli $con) {
        $stmt = $con->prepare("INSERT INTO rse_transactions (transaction_id, org_id, no_of_trainees, fee_per_trainee"
                . ",is_completed,tr_amount) VALUES ( ?, ?, ?, ?, ?,?)");
        
//         $sql = "INSERT INTO test(values1)values('Error ::: $con->error')";
//          $con->query($sql);
       
        $transaction_id = $this->transaction_id;
        $org_id = $this->org_id;
        $trainees = $this->no_of_trainees;
        $fee = $this->fee_per_trainee;
        $is_ompleted = 1;  
        $amount=  $this->tr_amount;
        $stmt->bind_param("siidid", $transaction_id, $org_id, $trainees, $fee, $is_ompleted,$amount);
        $stmt->execute();
        //$last_id = $con->insert_id;
        $id = mysqli_insert_id($con);
        $this->id = $id;
        //die($con->error);
        // ok 
    }
//end function
    
}//end class

