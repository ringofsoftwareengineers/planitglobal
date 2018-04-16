<?php
include_once '../config.php';
include_once '../classes/Trainee.php';
$organization = new Trainee();

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

if(isset($_POST["register_user"]))
{
    
    $organization->user_name=$_POST["name"];
    $organization->user_login=$_POST["login_name"];
    $organization->user_email=$_POST["email"];
    $organization->user_mobile_no=$_POST["mobile"];
    $organization->user_password=$_POST["password"];
    
    
    if($organization->is_user_login_exists($organization->user_login, $con))
    {
        echo '-1';
        
    }else
    {
        $organization->register_trainee($con);
        echo '0'; 
    }
      
}//end register user

