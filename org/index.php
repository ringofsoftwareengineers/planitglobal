<?php
include_once '../config.php';
include_once '../classes/Organization.php';
$organization = new Organization();
global $warning_message;
global $alpha_numeric_login;
$alpha_numeric_login = true;
$warning_message = "";
if ($organization->is_org_user_loggedin()) {
    header("location: home.php");
}
if (isset($_POST["org_register"])) {
    try{
        include_once '../captcha.php';
    }  catch (Exception $ex)
    {
        
    }
    
    if (!$resp) {
        //die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
         $warning_message = "CAPTCHA code error. Please try again";
    }  else {        
        try {
            $result = json_decode($resp);
    if (!$result->success) {
        $warning_message = "CAPTCHA code error. Please try again";
    } else {
        $organization->org_address = $_POST["address"];
        $organization->org_email = $_POST["email"];
        $organization->org_phone = $_POST["phone"];
        $organization->org_title = $_POST["org_title"];
        $organization->password = $_POST["password"];
        $organization->user_login = $_POST["login_name"];
        $organization->org_type = $_POST["org_type"];
        $type = $org_types[$organization->org_type];
        $organization->free_trainees = $free_enrollments[$type];

        if (ctype_alnum($organization->user_login) == 1) {

            if (!$organization->is_user_login_exists($organization->user_login, $con)) {
                $organization->register_organization($con);
                $organization->org_login($con, $organization->user_login, $organization->password);
                header("location: index.php");
            } else {
                
            }
        } else {
            $alpha_numeric_login = false;
            $warning_message = "Please enter alpha numeric Login Name";
        }
    }
        
        } catch (Exception $ex) {
            
        }//end try-catch
    }//end checking !$resp i.e no response from google url.
    
}//end register user
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Planit Global Register</title>
<?php include './header-links.php'; ?>
        <style>
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
                <div class="col-md-9" style="text-align: left; color: white;">
                    Welcome to <strong>PLANit GLOBAL</strong> Training
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">                    
                    <a href="login.php"  class="btn btn-default menu-button">Organization Login</a>                                       
                </div>
            </div>

            <form method="post">
                <div class="row" style="min-height: 460px;">
                    <div class="col-md-8 center-aligin">
                        <h4 id="warning" style="color: red; text-align: center;">
<?php
echo $warning_message;
?>
                        </h4>
                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                <div class="form-group">

                                    <label style="color:white;" for="org_title">Organization Title:</label>
                                    <input value="<?php echo $organization->org_title; ?>" required="" type="text" name="org_title"  class="form-control" id="org_title">
                                </div>
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                <div class="form-group">

                                    <label style="color:white;" for="org_title">Organization Type:</label>
                                    <select class="form-control" name="org_type">
<?php
foreach ($org_types as $type) {
    if ($type == $organization->org_type) {
        ?>
                                                <option selected="" value="<?php echo $org_type_value[$type]; ?>"><?php echo $type; ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value="<?php echo $org_type_value[$type]; ?>"><?php echo $type; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                <div class="form-group">
                                    <label style="color:white;" for="login_name">Login Name:</label>
                                    <input autocomplete="false" autofocus="false" value="<?php echo $organization->user_login; ?>"  required="" type="text" class="form-control alpha_numeric" name="login_name" id="login_name_org">
                                    <span id="warning_login_name" style="color: red;"></span>
                                </div>
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                <div class="form-group">
                                    <label style="color:white;" for="password">Password:</label>
                                    <input autocomplete="false" value="<?php echo $organization->password; ?>" required="" type="password" name="password" class="form-control" id="password">
                                </div>
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                <div class="form-group">
                                    <label style="color:white;" for="email">Email:</label>
                                    <input value="<?php echo $organization->org_email; ?>" required="" type="email" name="email" class="form-control" id="email">
                                </div>
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                <div class="form-group">
                                    <label style="color:white;" for="phone">Phone #:</label>
                                    <input required="" value="<?php echo $organization->org_phone; ?>" type="text" name="phone" class="form-control" id="phone">
                                </div>
                            </div> 
                        </div> 

                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                <div class="form-group">
                                    <label style="color:white;" for="address">Address:</label>
                                    <input required="" value="<?php echo $organization->org_address; ?>" type="text" name="address" class="form-control" id="address">
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
                                <input onclick="return validate_submit_form_data()" style="background-color: green; color: white; margin-bottom: 10px;" id="org_register" type="submit" id="register" value="Register" name="org_register" class="btn btn-default right-align">
                            </div> 
                        </div>
                    </div>
                </div> 



            </form>
<?php include '../footer.php'; ?>
        </div>

    </body>
</html>
