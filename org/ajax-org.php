<?php
include_once '../config.php';
include_once '../classes/Organization.php';
$organization = new Organization();
//an AJAX request will be send here to verify that login_name of newly registering org does not exist and can be assigned or otherwis
if(isset($_POST["verify_login"]))
{
    $organization->user_login=$_POST["verify_login"];
    if($organization->is_user_login_exists($organization->user_login, $con))
    {
        echo '1';
    }  else {
        echo '0';
    }
}//end verify login

