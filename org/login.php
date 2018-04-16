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

if (isset($_POST["btnLogin"])) {

    try {
        $organization->password = $_POST["txtPassword"];
        $organization->user_login = $_POST["txtLogin"];

        if (ctype_alnum($organization->user_login) == 1) {

            if ($organization->is_user_login_exists($organization->user_login, $con)) {

                if ($organization->org_login($con, $organization->user_login, $organization->password)) {
                    header("location: home.php");
                }
            } else {
                $warning_message = "Incorrect User Name or Password";
            }
        } else {
            $alpha_numeric_login = false;
            $warning_message = "Please enter alpha numeric Login Name";
        }
    } catch (Exception $ex) {
        
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Planit Global Login</title>
        <?php include './header-links.php'; ?>
        <style>
            ::placeholder {
                color: red;
                opacity: 1; /* Firefox */
            }

            :-ms-input-placeholder { /* Internet Explorer 10-11 */
                color: red;
            }

            ::-ms-input-placeholder { /* Microsoft Edge */
                color: red;
            }
        </style>
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

            <!-- header -->
 <form  method="post">
            <div class="row">
            <div class="col-md-6">                    
            <a href="index.php" style="color:white;" class="btn btn-default menu-button">Register Organization</a>                     
            </div>
            <div class="col-md-6" style="padding-top: 9px;">

            <input type="text" name="txtLogin" style="float: left; width: 200px; height: 50px; background-color: purple; color: white; margin-right: 10px;" placeholder="username" class="form-control" ID="txtLogin" >
            <input type="password" name="txtPassword" style="float: left; width: 200px; height: 50px; background-color: purple; color: white;" placeholder="password" class="form-control complete-width" ID="txtLogin" >
            <input name="btnLogin" type="submit" style="height: 50px;float:right; background-color:green; color:white; width:200px; font-weight: bold;" ID="btnLogin" class="btn btn-default right-align" value="Organization Login" />
            </div>
            </div>

           
            <div class="row" style="margin-top: 50px; min-height: 460px;">
           
            <?php
            if (strlen($warning_message) > 0) {
                ?>
                <label ID="lblInCorrect"  style="color: red;margin-left: auto;margin-right: auto; float: none;"><?php echo $warning_message; ?></label>
                <?php
            }
            ?>
            </div>
            </form>

            <?php include '../footer.php'; ?>
            </div>
            </body>
            </html>
