<?php
include_once '../config.php';
include_once '../classes/Trainee.php';
include_once '../classes/Organization.php';
include_once '../classes/Transaction.php';
include_once '../classes/Fee.php';
include_once '../classes/Moodle.php';
$fee = new Fee();
$fee->get_fee($con);
$trainee = new Trainee();
$organization = new Organization();
$transaction = new Transaction();
global $warning_message;
global $alpha_numeric_login;
$alpha_numeric_login = true;
$warning_message = "";
if (!$organization->is_org_user_loggedin()) {
    header("location: login.php");
}
$org_id = $_SESSION["org_id"];
$trainees = $trainee->get_all_trainees_by_org_id($con, $org_id);
$org = $organization->get_organization_by_id($con, $org_id);
$no_of_paind_trainees = $transaction->get_no_of_paid_trainees($con, $org_id);

if (isset($_POST["register_trainee"])) {

    try {
        include_once '../captcha.php';

        if (!$resp) {
            //die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
            $warning_message = "CAPTCHA code error. Please try again";
        } else {

            $result = json_decode($resp);
            if (!$result->success) {
                $warning_message = "CAPTCHA code error. Please try again";
            } else {
                $trainee->user_email = $_POST["email"];
                $trainee->user_login = $_POST["login_name"];
                $trainee->first_name = $_POST["first_name"];
                $trainee->last_name = $_POST["last_name"];
                $trainee->user_password = $_POST["password"];
                $trainee->user_mobile_no = $_POST["phone"];
                $trainee->org_id = $org_id;

                if (ctype_alnum($trainee->user_login) == 1) {

                    if (!$trainee->is_user_login_exists($trainee->user_login, $con)) {
                        $moodle = new Moodle();
                        $fields = array(
                            'username' => $trainee->user_login,
                            'password' => $trainee->user_password,
                            'firstname' => $trainee->first_name,
                            'lastname' => $trainee->last_name,
                            'email' => $trainee->user_email,
                            //    'city' => 'Hope, BC',
                            //    'country' => 'CA',
                            'customfields' => array(
                                array('type' => 'organization', 'value' => $org->org_title)),
                            'preferences' => array(array('type' => 'auth_forcepasswordchange', 'value' => false)) // This forces user to change password on first login.
                        );

                        $user = $moodle->createUser($fields);
                        if ($user){
                            //var_dump($user);          // Normal result contains a 2-member array with new user id and username.
                            $trainee->moodle_user_id=$user["id"];
                            $trainee->register_trainee($con);
                        header("location: register-trainee.php");
                        }
                        else{
                            //var_dump($moodle->error); // Error.
                            $warning_message=$moodle->error;
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
        <div class="container-fluid">
<?php
include './org-account-header.php';
?>
            <div class="row">
                <div class="col-md-12" style="min-height: 460px;">


<?php
$remaining = 0;
$free_trainees = $org->free_trainees;
$paid_trainees = $no_of_paind_trainees;
$registered_trainees = count($trainees);
if ($registered_trainees < ($free_trainees + $paid_trainees)) {
    $remaining = ($free_trainees + $paid_trainees) - $registered_trainees;
}
?>

                    <div class="row">
                        <div class="col-md-6 center-aligin">

                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top" >
                                <table class="center-aligin">
                                    <tr>
                                        <td>								
                                            <input type="hidden" name="cmd" value="_s-xclick">
                                            <input type="hidden" name="hosted_button_id" value="2FLPXTRJ6ZV5E">
                                            <input type="hidden" name="custom" value="<?php echo $_SESSION["org_id"]; ?>">
                                            <input type="number" class="form-control" min="1"  name="quantity" value="1">
                                        </td>
                                        <td>
                                            <input style="cursor: pointer;" type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
                                            <img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
                                        </td>
                                    </tr>

                                </table>

                            </form>

                            <table class="center-aligin">
                                <thead>
                                    <tr>
                                        <th style="text-align: center; border: #CCC thin solid; padding: 5px;">
                                            Free Trainees
                                        </th>
                                        <th style="text-align: center; border: #CCC thin solid;padding: 5px;">
                                            Paid Trainees
                                        </th>
                                        <th style="text-align: center; border: #CCC thin solid;padding: 5px;">
                                            Registered Trainees
                                        </th>
                                        <th style="text-align: center; border: #CCC thin solid;padding: 5px;">
                                            Remaining
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align: center; border: #CCC thin solid;"><?php
                    if ($org->org_type < 0) {
                        echo 'Not applicable';
                    } else {
                        echo $free_trainees;
                    }
?></td>
                                        <td style="text-align: center; border: #CCC thin solid;"><?php
                                            if ($org->org_type < 0) {
                                                echo 'Not applicable';
                                            } else {
                                                echo $paid_trainees;
                                            }
                                            ?></td>
                                        <td style="text-align: center; border: #CCC thin solid;"><?php echo $registered_trainees; ?></td>
                                        <td style="text-align: center; border: #CCC thin solid;"><?php
                                            if ($org->org_type < 0) {
                                                echo 'Not applicable';
                                            } else {
                                                echo $remaining;
                                            }
                                            ?></td>
                                    </tr>
                                </tbody>
                            </table>







                        </div>
                    </div>

<?php
if ($remaining > 0 || $org->org_type < 0) {
    ?>


                        <form method="post">
                            <div class="row">
                                <div class="col-md-8 center-aligin">
                                    <h2 style="text-align: center; margin-top: 20px; margin-bottom: 20px;">Trainee Registration</h2>
                                    <h4 id="warning" style="color: red; text-align: center;">
    <?php
    echo $warning_message;
    ?>
                                    </h4>
                                    <div class="row">
                                        <div class="col-md-8 center-aligin">
                                            <div class="form-group">

                                                <label for="trainee_first_name">Trainee First Name:</label>
                                                <input value="<?php echo $trainee->first_name; ?>" required="" type="text" name="first_name"  class="form-control" id="trainee_name">
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 center-aligin">
                                            <div class="form-group">

                                                <label for="trainee_last_name">Trainee Last Name:</label>
                                                <input value="<?php echo $trainee->last_name; ?>" required="" type="text" name="last_name"  class="form-control" id="trainee_name">
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 center-aligin">
                                            <div class="form-group">
                                                <label for="login_name">Login Name:</label>
                                                <input value="<?php echo $trainee->user_login; ?>"  required="" type="text" class="form-control alpha_numeric" name="login_name" id="login_name">
                                                <span id="warning_login_name" style="color: red;"></span>
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 center-aligin">
                                            <div class="form-group">
                                                <label for="password">Password:</label>
                                                <input value="<?php echo $trainee->user_password; ?>" required="" type="password" name="password" class="form-control" id="password">
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 center-aligin">
                                            <div class="form-group">
                                                <label for="email">Email:</label>
                                                <input value="<?php echo $trainee->user_email; ?>" required="" type="email" name="email" class="form-control" id="email">
                                            </div>
                                        </div> 
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 center-aligin">
                                            <div class="form-group">
                                                <label for="phone">Mobile #:</label>
                                                <input required="" value="<?php echo $trainee->user_mobile_no; ?>" type="text" name="phone" class="form-control" id="phone">
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
                                            <input onclick="return validate_trainee_submit_form_data()" id="register_trainee" style="background-color: green; color:white;" type="submit" id="register" value="Register" name="register_trainee" class="btn btn-default right-align">
                                        </div> 
                                    </div>
                                </div>
                            </div>           
                        </form>

<?php } ?>

                    <div class="row">
                        <div class="col-md-8 center-aligin" style="margin-top: 30px; margin-bottom: 30px;">
<?php
if (count($trainees) > 0) {
    ?>
                                <table id="trainees_table" style="margin-top: 30px; margin-bottom: 30px;">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th><th>Name</th><th>Login Name</th><th>Email</th><th>Mobile</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <?php
    $counter = 1;
    foreach ($trainees as $org_trainee) {
        ?>
                                            <tr>
                                                <td><?php echo $counter; ?></td>
                                                <td><?php echo $org_trainee->first_name; ?></td>
                                                <td><?php echo $org_trainee->user_login; ?></td>
                                                <td><?php echo $org_trainee->user_email; ?></td>
                                                <td><?php echo $org_trainee->user_mobile_no; ?></td>
                                            </tr>
        <?php
        $counter++;
    }
    ?>
                                    </tbody>

                                </table>
                                <script>
                                    $(document).ready(function () {
                                        $('#trainees_table').DataTable();
                                    });
                                </script>

    <?php
}
?>  
                        </div>
                    </div>

                </div>
            </div>        

<?php include '../footer.php'; ?>

        </div>


    </body>
</html>
