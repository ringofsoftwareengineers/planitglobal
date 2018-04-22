<?php
include_once '../config.php';
include_once '../classes/Moodle.php';
include '../classes/Explorer.php';
include '../classes/Trainee.php';
$explorer = new Explorer();
$trainee = new Trainee();
global $warning_message;
$warning_message = "";


if (isset($_POST["register_explorer"])) {

    try {
        include_once '../captcha.php';
        include '../explorer-api.php';

        if (!$resp) {
            //die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
            $warning_message = "CAPTCHA code error. Please try again";
        } else {

            $result = json_decode($resp);
            if (!$result->success) {
                $warning_message = "CAPTCHA code error. Please try again";
            } else {
                $explorer->user_email = $_POST["email"];
                $explorer->user_login = $_POST["login_name"];
                $explorer->first_name = $_POST["first_name"];
                $explorer->last_name = $_POST["last_name"];
                $explorer->user_password = $_POST["password"];              
                

                if (ctype_alnum($explorer->user_login) == 1) {

                    if (!$trainee->is_user_login_exists($trainee->user_login, $con)) {
                        
                        $return_value = register_explorer($explorer);
                        if($return_value[0]>0)
                        {
                           $moodle = new Moodle();
                        $fields = array(
                            'username' => $explorer->user_login,
                            'password' => $explorer->user_password,
                            'firstname' => $explorer->first_name,
                            'lastname' => $explorer->last_name,
                            'email' => $explorer->user_email,                   
                            'preferences' => array(array('type' => 'auth_forcepasswordchange', 'value' => false)) // This forces user to change password on first login.
                        );

                        $user = $moodle->createUser($fields);
                        if ($user){
                            //var_dump($user);          
                        // Normal result contains a 2-member array with new user id and username.
                            $trainee->moodle_user_id=$user["id"];
                            $trainee->register_trainee($con);
                            mail_to_explorer($explorer->first_name, $explorer->last_name, $explorer->user_email,$explorer->user_login );
                        header("location: explorer-success.php");
                        }
                        else{
                        //here newly registered user in planitglobal.co.uk database through API should be deleted.
                            $warning_message=$moodle->error;
                        } 
                        }else
                        {
                            $warning_message=$return_value[1];
                        }
                        
                        
                    } else {
                        $warning_message = "Login Name already exists";
                    }
                } else {
                    $alpha_numeric_login = false;
                    $warning_message = "Please enter alpha numeric Login Name";
                }
            }
        }
    } catch (Exception $ex) {
        
    }
}//end register user

function mail_to_explorer($first_name, $last_name, $email,$user_login )
{
    
$headers = "From: info@planitglobal.co.uk" . "\r\n" .
$headers .= "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


$to=$email;
$subject="Registeration Successfull in PLANit Global";
$message="<h1 style=\"text-align:center;\">Welcome to PLANiT Global</h1>";
$message.="<p>You have been registered with following data:</p>";
$message.="<strong>First Name: </strong> $first_name <br>";
$message.="<strong>Last Name: </strong> $last_name<br>";
$message.="<strong>Username: </strong> $user_login<br>";
$message.="<strong>Email: </strong> $email";
echo mail($to, $subject, $message,$headers);
}//end function


?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Planit Global Register</title>
<?php include './header-links.php'; ?>
        <style>
            label{color: white;}
            .alpha_numeric
            {
<?php if (!$alpha_numeric_login) { ?>
                    background-color: red;
                <?php } else {
                    ?>
                    background-color: white;
                <?php }
                ?>
            }
        </style>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <div class="container-fluid" style="background-image: url('../images/planit logo.jpg');background-size:cover;">

            <div class="row header-heading">
                <div class="col-md-3" style="height: 105px;">
                    <img src="../logo_navbar.png">
                </div>
                <div class="col-md-9" style="text-align: left;">
                    Welcome to <strong>PLANit GLOBAL</strong> Training
                </div>
            </div>
            <div class="row">
                    <div class="col-md-12"> 
                        <a href="login.php"  class="btn btn-default menu-button">Bussiness Login</a>
                        <a href="index.php" style="color:white;" class="btn btn-default menu-button">Register Bussiness</a>
                        <a href="http://18.218.207.117/moodle/login/index.php" style="color:white;" class="btn btn-default menu-button">Employee Login</a> 
                        
                    </div>
                <div class="col-md-12">
                    <H3 style="color: white; text-align: center; margin-top: 20px;">Already have an account? 
                        <a href="http://18.218.207.117/moodle/login/index.php" style="display: inline; float: none; margin-left: auto; margin-right: auto;">Login here</a> 
                        </H3>
                </div>    
                
                </div>
            <form method="post">
                <div class="row">
                    <div class="col-md-8 center-aligin" style="min-height: 460px;">
                        <h2 style="text-align: center; margin-top: 20px; margin-bottom: 20px; color: white;">Explorer Registration</h2>
                        <h4 id="warning" style="color: red; text-align: center;">
                            <?php
                            echo $warning_message;
                            ?>
                        </h4>
                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                <div class="form-group">

                                    <label for="trainee_first_name">Explorer First Name:</label>
                                    <input value="<?php echo $explorer->first_name; ?>" required="" type="text" name="first_name"  class="form-control" id="first_name">
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                <div class="form-group">

                                    <label for="trainee_last_name">Explorer Last Name:</label>
                                    <input value="<?php echo $explorer->last_name; ?>" required="" type="text" name="last_name"  class="form-control" id="last_name">
                                </div>
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                <div class="form-group">
                                    <label for="login_name">Login Name:</label>
                                    <input value="<?php echo $explorer->user_login; ?>"  required="" type="text" class="form-control alpha_numeric" name="login_name">
                                   
                                </div>
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input value="<?php echo $explorer->user_password; ?>" required="" type="password" name="password" class="form-control" id="password">
                                </div>
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input value="<?php echo $explorer->user_email; ?>" required="" type="email" name="email" class="form-control" id="email">
                                </div>
                            </div> 
                        </div>                                                       
                        <div class="row">
                            <div class="col-md-8 center-aligin">                               
                                <div class="g-recaptcha" data-sitekey="6LeSSkIUAAAAADUdxo2F0t2mYkuhUONg5i9apSe5"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                
                                <input id="register_trainee" style="background-color: green; color:white;margin-bottom: 20px;" type="submit" id="register" value="Register" name="register_explorer" class="btn btn-default right-align">
                            </div> 
                        </div>
                    </div>
                </div>           
            </form>



<?php include '../footer.php'; ?>
        </div>
    </body>
</html>
