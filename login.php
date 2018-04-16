<?php
include_once './config.php';
include_once './classes/Trainee.php';
global $warning_message;
$warning_message = "";
$organization = new Trainee();

if ($organization->is_trainee_login()) {
    header("location: home");
}

if (isset($_POST["btnLogin"])) {

    try {

        if (user_login($con, $organization)) {
            //go head
        }
    } catch (Exception $ex) {
        
    }
}
function user_login($con, Trainee $trainee) {
    global $warning_message;
    $login_name = $_POST["txtLogin"];
    $password = $_POST["txtPassword"];
    $wrong = false;
    if (ctype_alnum($login_name) == 1) {
        $trainee->user_login = $login_name;
        $trainee = $trainee->get_trainee_by_login($con);
        if ($trainee->id == -1) {
            $wrong = true;
            //die("no user name exiss");
        } elseif (strcmp($password, $trainee->user_password) !== 0) {
            $wrong = true;
        } else {
            $_SESSION["user_login"] = $trainee->user_login;
            $_SESSION["user_name"] = $trainee->user_name;
            $_SESSION["user_id"] = $trainee->id;
            $_SESSION["trainee_login"] = 1;
            $_SESSION["org_id"] = $trainee->org_id;
            header("location: ./home");
        }

        if ($wrong) {
            $warning_message = "Incorrect User Name or Password";
        }
    } else {
        $warning_message = "Please enter alpha numeric login name";
    }

    if (!$wrong) {
        return true;
    }

    return false;
}
//end function
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Planit Global Login</title>
        <?php include './header-links.php'; ?>
    </head>
    <body>
        <div class="container-fluid" style="background-image: url('images/planit logo.jpg');background-size:cover;">

            <div class="row header-heading">
                <div class="col-md-3" style="height: 105px;">
                    <img src="./logo_navbar.png">
                </div>
                <div class="col-md-9" style="text-align: left;">
                    Welcome to <strong>PLANit GLOBAL</strong> Training
                </div>
            </div>
            <form  method="post">
                <div class="row">
                    <div class="col-md-6">
                        <a href="org/login.php" class="btn btn-default menu-button">Organization Login</a>

                    </div>
                    <div class="col-md-6" style="padding-top: 9px;">

                        <input type="text" name="txtLogin" style="float: left; width: 200px; height: 50px; background-color: purple; color: white; margin-right: 10px;" placeholder="username" class="form-control" ID="txtLogin" >
                        <input type="password" name="txtPassword" style="float: left; width: 200px; height: 50px; background-color: purple; color: white;" placeholder="password" class="form-control complete-width" ID="txtLogin" >
                        <input name="btnLogin" type="submit" style="height: 50px;float:right; background-color:green; color:white; width:200px; font-weight: bold;" ID="btnLogin" class="btn btn-default right-align" value="Trainee Login" />
                    </div>
                </div>


                <div class="row" style="min-height: 460px;">
                    <?php
                    if (strlen($warning_message) > 0) {
                        ?>
                        <label ID="lblInCorrect"  style="color: red;margin-left: auto;margin-right: auto; float: none;"><?php echo $warning_message; ?></label>
                        <?php
                    }
                    ?>
                    <!--                                <div class="col-md-5 center-aligin">
                                                        <h2 style="text-align: center; color: white;">Trainee Login</h2>
                                                        <div class="row" style="margin-top: 50px; margin-bottom: 5px;">
                                                            <div class="col-md-3">
                                                                <label style="color:white;"><strong>Username:</strong></label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <input type="text" name="txtLogin" class="form-control complete-width" ID="txtLogin" >
                                                               
                                                            </div>
                                                        </div>
                                
                                                        <div class="row" style="margin-bottom: 5px;">
                                                            <div class="col-md-3">
                                                                 <label style="color:white;"><strong>Password:</strong></label>
                                                                 
                                                            </div>
                                                            <div class="col-md-9">
                                                                <input type="password" name="txtPassword" class="form-control complete-width" ID="txtPassword" >                                
                                                            </div>
                                                        </div>
                                
                                                        <div class="row top-row-margin">                            
                                                            <div class="col-md-12">
                                                                <input name="btnLogin" type="submit" style="float:right; background-color:blue; color:white; width:120px;" ID="btnLogin" class="btn btn-default right-align" value="Login" />
                                                            </div>
                                                        </div>
                    <?php
                    if (strlen($warning_message) > 0) {
                        ?>
                                                                     <label ID="lblInCorrect"  style="color: red;"><?php // echo $warning_message;   ?></label>
                        <?php
                    }
                    ?>
                                                       
                                                    </div>-->
                </div>
            </form>


            <?php include './footer.php'; ?>
        </div>
    </body>
</html>
