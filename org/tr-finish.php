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
            <div style="min-height: 460px;padding-top: 30px;">
                <h4>
                    Thank you for your payment. Your transaction has been completed and a receipt for your purchase has been
                    emailed to you. You may log in to your account at www.paypal.com to view details of this transaction
                </h4> 
            </div>
<?php include '../footer.php'; ?>
        </div>
    </body>
</html>

