<?php
include_once '../config.php';
include_once '../classes/Organization.php';
$organization = new Organization();
if(!$organization->is_org_user_loggedin())
{
    header("location: login.php");
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
        <div class="container-fluid">
            <?php
            include './org-account-header.php';
            ?>
            <div style="min-height: 460px;">
                
            </div>
<?php include '../footer.php'; ?>
        </div>
    </body>
</html>

