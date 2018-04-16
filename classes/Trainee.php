<?php

//Handles operation for trainee in domain of Planit APP and not Moodle

$orange = new Trainee();

// above statement creates a new object of class Trainee that is stored into a 
// variable $orange and this variable is used to access functions of class Trainee. 
// Trainee(); is a constructor which is used to initilie newely created object 
// and it can be parametrizez or not(default). Its parameters are used to 
// to pass values to the original constructor of class Trainee and from their they are passed to the instance variables of class Traineee.

class Trainee {

    public $id = -1;
    public $user_login;
    public $first_name;
    public $last_name;
    public $user_email;
    public $user_mobile_no;
    public $user_password;
    public $time_at_save;
    public $is_active = 0;
    public $org_id;
    public $moodle_user_id;
    public $user_id; // id of activating user
    
    public $course_code;
    public $course_title;
    
    //save data of trainee in local database
    // this function is called after creation of trainee login in moodle
    public function register_trainee(mysqli $con) {
        $stmt = $con->prepare("INSERT INTO rse_trainee (user_login, first_name,last_name, user_email, user_mobile_no"
                . ", user_password,org_id,moodle_user_id) VALUES (?,?,?,?,?,?,?,?)");

        $user_login = $this->user_login; // this points to current object whose functions is being called.
        $first_name = $this->first_name;
        $last_name= $this->last_name;
        $user_email = $this->user_email;
        $user_mobile_no = $this->user_mobile_no;
        $user_password = $this->user_password;
        $org_id = $this->org_id;
        $moodle_user_id= $this->moodle_user_id;
        $stmt->bind_param("ssssssii", $user_login, $first_name,$last_name, $user_email, $user_mobile_no, $user_password, $org_id,$moodle_user_id);


        $stmt->execute();
        //$last_id = $con->insert_id;
        $id = mysqli_insert_id($con);
        $this->id = $id;
    }
//end function
    //is trainee logged in. This was used in app and now it is obsolete
   // now trainee is loggedin in Moodle.
    public function is_trainee_login()
    {
        if(isset($_SESSION["trainee_login"]))
        {
            return true;
        }
        return false;
    }//end function
    public function enroll_trainee($con, $course_id, $trainee_id, $org_id) {
        $sql = "insert into rse_course_enroll(course_id,trainee_id,org_id)"
                . "values('$course_id','$trainee_id','$org_id')";
        $con->query($sql);
    }
    public function is_trainee_already_enrolled($con, $course_id, $trainee_id, $org_id) {
        $sql = "SELECT * FROM rse_course_enroll"
                . " where org_id='$org_id' and trainee_id='$trainee_id' and course_id='$course_id'";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {

            return true;
        } else {
            return false;
        }
    }
//end function
    public function is_user_login_exists($user_login, $con) {
        $stmt = $con->prepare("SELECT * FROM rse_trainee WHERE user_login=?");
        $stmt->bind_param("s", $login);
        $login = $user_login;
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_all();
        if (mysqli_num_rows($res) > 0) {
            return true;
        } else {
            return false;
        }
    }
//end function

    
    
    public function get_all_trainees($con) {
        $sql = "SELECT * FROM rse_trainee order by id desc";
        $result = $con->query($sql);
        $trainees = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $trainee = new Trainee();
                $trainee->user_login = $row["user_login"];
                $trainee->first_name = $row["first_name"];
                $trainee->id = $row["id"];
                $trainee->user_email = $row["user_email"];
                $trainee->is_active = $row["is_active"];
                $trainee->time_at_save = $row["time_at_save"];
                $trainee->user_password = $row["user_password"];
                $trainee->user_id = $row["user_id"];
                $trainee->org_id = $row["org_id"];
                $trainees[] = $trainee;
            }
        }
        return $trainees;
    }
//end function

    public function get_all_trainees_by_org_id($con, $org_id) {
        $sql = "SELECT * FROM rse_trainee where org_id='$org_id' order by id desc";
        $result = $con->query($sql);
        $trainees = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $trainee = new Trainee();
                $trainee->user_login = $row["user_login"];
                $trainee->first_name = $row["first_name"];
                $trainee->last_name = $row["last_name"];
                $trainee->id = $row["id"];
                $trainee->user_email = $row["user_email"];
                $trainee->is_active = $row["is_active"];
                $trainee->time_at_save = $row["time_at_save"];
                $trainee->user_password = $row["user_password"];
                $trainee->user_id = $row["user_id"];
                $trainee->org_id = $row["org_id"];
                $trainee->user_mobile_no=$row["user_mobile_no"];
                $trainees[] = $trainee;
            }
        }
        return $trainees;
    }
//end function 

    public function get_all_course_enrolled_trainees_by_org_id_course_id($con,$org_id,$course_id)
    {
        $sql = "SELECT * FROM (SELECT DISTINCT trainee_id ".
                " FROM rse_course_enroll WHERE org_id='$org_id' AND course_id='$course_id')co_ids ".                
                " JOIN rse_trainee rt on co_ids.trainee_id=rt.id";
        
        $result = $con->query($sql);
        $trainees = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $trainee = new Trainee();
                $trainee->user_login = $row["user_login"];
                $trainee->first_name = $row["first_name"];
                $trainee->id = $row["id"];
                $trainee->user_email = $row["user_email"];
                $trainee->is_active = $row["is_active"];
                $trainee->time_at_save = $row["time_at_save"];
                $trainee->user_password = $row["user_password"];
                $trainee->user_id = $row["user_id"];
                $trainee->org_id = $row["org_id"];
                $trainee->user_mobile_no=$row["user_mobile_no"];
                $trainees[] = $trainee;
            }
        }
        return $trainees;
    }//end function

    
    public function get_all_enrolled_trainees_by_org_id($con,$org_id)
    {
        $sql = "SELECT * FROM (SELECT DISTINCT course_id, trainee_id ".
                " FROM rse_course_enroll WHERE org_id='$org_id')co_ids ".
                " JOIN rse_course rc on co_ids.course_id=rc.id ".
                " JOIN rse_trainee rt on co_ids.trainee_id=rt.id";
        
        $result = $con->query($sql);
        $trainees = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $trainee = new Trainee();
                $trainee->user_login = $row["user_login"];
                $trainee->first_name = $row["first_name"];
                $trainee->id = $row["id"];
                $trainee->course_code=$row["course_code"];
                $trainee->course_title=$row["course_title"];
                $trainees[] = $trainee;
            }
        }
        return $trainees;
    }//end function

     public function get_all__trainees_by_org_id($con,$org_id) {
        $sql = "SELECT * FROM rse_trainee where is_active > 0 and org_id='$org_id' order by id desc";
        $result = $con->query($sql);
        $trainees = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $trainee = new Trainee();
                $trainee->user_login = $row["user_login"];
                $trainee->first_name = $row["first_name"];
                $trainee->user_mobile_no=$row["user_mobile_no"];
                $trainee->id = $row["id"];
                $trainee->user_email = $row["user_email"];
                $trainee->is_active = $row["is_active"];
                $trainee->time_at_save = $row["time_at_save"];
                $trainee->user_password = $row["user_password"];
                $trainee->user_id = $row["user_id"];
                $trainee->org_id = $row["org_id"];
                $trainees[] = $trainee;
            }
        }
        return $trainees;
    }
//end function

    public function get_all_active_trainees($con) {
        $sql = "SELECT * FROM rse_trainee where is_active > 0 order by id desc";
        $result = $con->query($sql);
        $trainees = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $trainee = new Trainee();
                $trainee->user_login = $row["user_login"];
                $trainee->first_name = $row["first_name"];
                $trainee->id = $row["id"];
                $trainee->user_email = $row["user_email"];
                $trainee->is_active = $row["is_active"];
                $trainee->time_at_save = $row["time_at_save"];
                $trainee->user_password = $row["user_password"];
                $trainee->user_id = $row["user_id"];
                $trainee->org_id = $row["org_id"];
                $trainees[] = $trainee;
            }
        }
        return $trainees;
    }
//end function

    public function get_all_inactive_trainees($con) {
        $sql = "SELECT * FROM rse_trainee where is_active ==0 order by id desc";
        $result = $con->query($sql);
        $trainees = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $trainee = new Trainee();
                $trainee->user_login = $row["user_login"];
                $trainee->first_name = $row["first_name"];
                $trainee->id = $row["id"];
                $trainee->user_email = $row["user_email"];
                $trainee->is_active = $row["is_active"];
                $trainee->time_at_save = $row["time_at_save"];
                $trainee->user_password = $row["user_password"];
                $trainee->user_id = $row["user_id"];
                $trainee->org_id = $row["org_id"];
                $trainees[] = $trainee;
            }
        }
        return $trainees;
    }
//end function

    public function get_trainee_by_id($con, $id) {
        $sql = "SELECT * FROM rse_trainee where id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $trainee = new Trainee();
        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $trainee->user_login = $row["user_login"];
            $trainee->first_name = $row["first_name"];
            $trainee->id = $row["id"];
            $trainee->user_email = $row["user_email"];
            $trainee->is_active = $row["is_active"];
            $trainee->time_at_save = $row["time_at_save"];
            $trainee->user_password = $row["user_password"];
            $trainee->user_id = $row["user_id"];
            $trainee->org_id = $row["org_id"];
        }
        return $trainee;
    }
//end function

    public function get_trainee_by_login($con) {

        $sql = "SELECT * FROM rse_trainee where user_login=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $login);
        $login = $this->user_login;
        //$result = $con->query($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $trainee = new Trainee();
        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $trainee->user_login = $row["user_login"];
            $trainee->first_name = $row["first_name"];
            $trainee->id = $row["id"];
            $trainee->user_email = $row["user_email"];
            $trainee->is_active = $row["is_active"];
            $trainee->time_at_save = $row["time_at_save"];
            $trainee->user_password = $row["user_password"];
            $trainee->user_id = $row["user_id"];
            $trainee->org_id=$row["org_id"];
        }

        return $trainee;
    }
//end function
}
//end class