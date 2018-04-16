<?php
include_once './config.php';
include_once './classes/Trainee.php';
$organization = new Trainee();

if($organization->is_trainee_login())
{
    header("location: home");
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Planit Global</title>
        <?php include './header-links.php'; ?>
    </head>
    <body>
        <div class="container-fluid" style="background-image: url('images/planit logo.jpg');background-size:cover;">
            <?php
            include './front-page-header.php';
            ?>
            <div class="row">
                <div class="col-md-12" style="min-height: 460px;">
                    
                </div>
                
            </div>
            <?php include_once './footer.php'; ?>

        </div>
    </body>
</html>

