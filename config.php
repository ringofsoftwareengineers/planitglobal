<?php

$host = "localhost";
$user = "root";
$password="";
$database = "planit";




session_start();

$con = new mysqli($host, $user, $password, $database);

//////////////Organization Configuration////////////////

$org_types = array();
$org_types["1"] = "Small";
$org_types["2"] = "Large";
$org_types["-1"] = "Sponsor";

$org_type_value["Small"] = "1";
$org_type_value["Large"] = "2";
$org_type_value["Sponsor"] = "-1";

$free_enrollments["Small"] = 2; // number of free trainees registrations allowed for a small organization
$free_enrollments["Large"] = 20;
$free_enrollments["Sponsor"] = -1; // no limit




