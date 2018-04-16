<?php
// this class handles all functions for Organizatiion.
class Organization {

    public $id = -1;
    public $org_title;
    public $user_login;
    public $org_address;
    public $org_email;
    public $org_phone;
    public $password;
    public $time_at_save;
    public $is_active = 0;
    public $user_id; // id of activating user
    public $org_type; // -1 : Sponsor, 1:Small, 2: Large
    public $free_trainees = 2; //e.g for small organization number of free trainees is 2.

    //
    public function register_organization($con) {

        $stmt = $con->prepare("INSERT INTO rse_organization (org_title, user_login,"
                . " org_address,org_email,org_phone,password,org_type,free_trainees )VALUES( ?,?, ?, ?, ?, ?,?,?)");

        $title = $this->org_title;
        $login = $this->user_login;
        $address = $this->org_address;
        $email = $this->org_email;
        $phone = $this->org_phone;
        $password = $this->password;
        $type = $this->org_type;
        $free_trainees = $this->free_trainees;
        $stmt->bind_param("sssssssi", $title, $login, $address, $email, $phone, $password, $type, $free_trainees);
        $stmt->execute();
        //$last_id = $con->insert_id;
        $id = mysqli_insert_id($con);
        $this->id = $id;
    }
//end function

//to veryfy that newly entered login name is unique
    public function is_user_login_exists($user_login, $con) {

        $login = $user_login;
        $stmt = $con->prepare("SELECT * FROM rse_organization WHERE user_login=?");
        $stmt->bind_param("s", $login);
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

    // to get list of all organizations
    public function get_organizations($con) {
        $sql = "SELECT * FROM rse_organization where is_active='1'";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $organizations = array();
        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {
                $organization = new Organization();
                $organization->user_login = $row["user_login"];
                $organization->org_address = $row["org_address"];
                $organization->id = $row["id"];
                $organization->org_email = $row["org_email"];
                $organization->org_title = $row["org_title"];
                $organization->org_phone = $row["org_phone"];
                $organization->is_active = $row["is_active"];
                $organization->time_at_save = $row["time_at_save"];
                $organization->password = $row["password"];
                $organization->user_id = $row["user_id"];
                $organization->org_type = $row["org_type"];
                $organization->free_trainees = $row["free_trainees"];
                $organizations[] = $organization;
            }//end while
        }//end if-statement
        return $organizations;
    }
//end function
//
    public function get_organization_by_login($con, $login) {
        $sql = "SELECT * FROM rse_organization where user_login=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $result = $stmt->get_result();
        $organization = new Organization();
        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $organization->user_login = $row["user_login"];
            $organization->org_address = $row["org_address"];
            $organization->id = $row["id"];
            $organization->org_email = $row["org_email"];
            $organization->org_title = $row["org_title"];
            $organization->org_phone = $row["org_phone"];
            $organization->is_active = $row["is_active"];
            $organization->time_at_save = $row["time_at_save"];
            $organization->password = $row["password"];
            $organization->user_id = $row["user_id"];
            $organization->org_type = $row["org_type"];
            $organization->free_trainees = $row["free_trainees"];
        }
        return $organization;
    }
//end function
//
    public function get_organization_by_id($con, $org_id) {
        $sql = "SELECT * FROM rse_organization where id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $org_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $organization = new Organization();
        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $organization->user_login = $row["user_login"];
            $organization->org_address = $row["org_address"];
            $organization->id = $row["id"];
            $organization->org_email = $row["org_email"];
            $organization->org_title = $row["org_title"];
            $organization->org_phone = $row["org_phone"];
            $organization->is_active = $row["is_active"];
            $organization->time_at_save = $row["time_at_save"];
            $organization->password = $row["password"];
            $organization->user_id = $row["user_id"];
            $organization->org_type = $row["org_type"];
            $organization->free_trainees = $row["free_trainees"];
        }
        return $organization;
    }
//end function

    
    // org_lgin function perfoms login operation if username / login_name and password are correct.
    //It saves user info in SESSION
    function org_login($con, $login_name, $password) {
        global $warning_message;
        $wrong = false;
        $organization = new Organization();
        $organization->user_login = $login_name;
        $organization = $organization->get_organization_by_login($con, $login_name);
        if ($organization->id == -1) {
            $wrong = true;
        } elseif (strcmp($password, $organization->password) !== 0) {
            $wrong = true;
        } else {

            $_SESSION["org_user_login"] = $organization->user_login;
            $_SESSION["org_id"] = $organization->id;
            $_SESSION["org_title"] = $organization->org_title;
            $_SESSION["org_type"] = $organization->org_type;

            return true;
        }

        if ($wrong) {
            $warning_message = "Incorrect User Name or Password";
        }

        return false;
    }
//end function

    //verifying that if the user is already loggedin or not
    function is_org_user_loggedin() {
        if (isset($_SESSION["org_user_login"])) {
            return true;
        }
        return false;
    }
//end function
    
    
    
    //getting number of trainees who are enrolled as free
    // this functiion is obsolete and not being used now
    function get_no_free_enrollments($con, $org_id) {
        $sql = "SELECT cout(id)as free_enrollments FROM rse_course_enroll"
                . " where org_id='$org_id' and enrollment_type='free'";
        $result = $con->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $free_enrollments = $row["free_enrollments"];
            return $free_enrollments;
        } else {
            return 0;
        }
    }
}
//end  one minute