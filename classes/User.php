<?php

//not needed now obsolete. 

class User {

    public $id = -1;
    public $user_login;
    public $user_name;
    public $user_email;
    public $user_mobile_no;
    public $user_password;
    public $time_at_save;
    public $is_active = 0;
    public $user_id=-1; // admin_id who added this this user

    
    public function login_in_user()
    {
        $_SESSION["user_id"]=  $this->id;
        $_SESSION["USER_login"]=  "yes";
        $_SESSION["user_name"]=  $this->user_name;
        $_SESSION["user_login"]=  $this->user_login;
        
    }//end function

    public function validate_user_page_access()
    {
        
        if(isset($_SESSION["USER_login"]))
        {
            return true;
        }
        return false;
        
    }//end function
    
    public function register_user($con) {
        $stmt = $con->prepare("INSERT INTO rse_user (user_login, user_name, user_email, user_mobile_no"
                . ", user_password,user_id) VALUES ( ?, ?, ?, ?, ?,?)");
        $stmt->bind_param("sssssi", $user_login, $user_name, $user_email, $user_mobile_no, $user_password, $user_id);

        $user_login = $this->user_login;
        $user_name = $this->user_name;
        $user_email = $this->user_email;
        $user_mobile_no = $this->user_mobile_no;
        $user_password = $this->user_password;
        $user_id = $this->user_id;
        $stmt->execute();
        //$last_id = $con->insert_id;
        $id = mysqli_insert_id($con);
        $this->id = $id;
    }
//end function


    public function is_user_login_exists($user_login, $con) {
        $stmt = $con->prepare("SELECT * FROM rse_user WHERE user_login=?");
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
   
    public function get_all_users($con) {
        $sql = "SELECT * FROM rse_user order by id desc";
        $result = $con->query($sql);
        $users = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new User();
                $user->user_login = $row["user_login"];
                $user->user_name = $row["user_name"];
                $user->id = $row["id"];
                $user->user_email = $row["user_email"];
                $user->is_active = $row["is_active"];
                $user->time_at_save = $row["time_at_save"];
                $user->user_password = $row["user_password"];
                $user->user_id = $row["user_id"];
                $user->user_mobile_no=$row["user_mobile_no"];
                $users[] = $user;
            }
        }
        return $users;
    }
//end function
    
    public function get_all_active_users($con) {
        $sql = "SELECT * FROM rse_user where is_active>0 order by id desc";
        $result = $con->query($sql);
        $users = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new User();
                $user->user_login = $row["user_login"];
                $user->user_name = $row["user_name"];
                $user->id = $row["id"];
                $user->user_email = $row["user_email"];
                $user->is_active = $row["is_active"];
                $user->time_at_save = $row["time_at_save"];
                $user->user_password = $row["user_password"];
                $user->user_id = $row["user_id"];
                $users[] = $user;
            }
        }
        return $users;
    }
//end function
    
    
     public function get_all_inactive_users($con) {
        $sql = "SELECT * FROM rse_user where is_active==0 order by id desc";
        $result = $con->query($sql);
        $users = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $user = new User();
                $user->user_login = $row["user_login"];
                $user->user_name = $row["user_name"];
                $user->id = $row["id"];
                $user->user_email = $row["user_email"];
                $user->is_active = $row["is_active"];
                $user->time_at_save = $row["time_at_save"];
                $user->user_password = $row["user_password"];
                $user->user_id = $row["user_id"];
                $users[] = $user;
            }
        }
        return $users;
    }
//end function
  
    public function delete_user_by_id($con,$id)
    {
        $sql = "DELETE FROM rse_user where id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);     
        $stmt->execute();
    }//end function


    public function get_user_by_id($con, $id) {
        $sql = "SELECT * FROM rse_user where id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);     
        $stmt->execute();
        $result = $stmt->get_result();
        $user = new User();
        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $user->user_login = $row["user_login"];
            $user->user_name = $row["user_name"];
            $user->id = $row["id"];
            $user->user_email = $row["user_email"];
            $user->is_active = $row["is_active"];
            $user->time_at_save = $row["time_at_save"];
            $user->user_password = $row["user_password"];
            $user->user_id = $row["user_id"];
        }
        return $user;
    }
//end function
    
    public function get_user_by_login($con) {

        $sql = "SELECT * FROM rse_user where user_login=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $login);
        $login = $this->user_login;
        
        //$result = $con->query($sql);
        $stmt->execute();        
        $result = $stmt->get_result();        
        $user = new User();
        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $user->user_login = $row["user_login"];
            $user->user_name = $row["user_name"];
            $user->id = $row["id"];
            $user->user_email = $row["user_email"];
            $user->is_active = $row["is_active"];
            $user->time_at_save = $row["time_at_save"];
            $user->user_password = $row["user_password"];
            $user->user_id = $row["user_id"];
        }

        return $user;
    }
//end function
    
}
//end class