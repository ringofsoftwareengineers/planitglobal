<?php
include '../config.php';
include '../classes/Transaction.php';
include '../classes/Fee.php';

if(!isset($_POST["txn_id"]))
{
    header("location: home.php");
    exit();
}

$Fee = new Fee();
$Fee->get_fee($con);
$transaction_id=$_POST["txn_id"];
$org_id=$_POST["custom"];
$quantity =$_POST["quantity"];
$amount=$_POST["mc_gross"];
$fee=$Fee->fee_per_trainee;

//         $sql = "INSERT INTO test(values1)values('Fee:::$fee --- tx_id:::$transaction_id  --- org_id:::$org_id"
//                 . " --- quantiry:::$quantity --- amount:::$amount')";
//          $con->query($sql);

$transaction=new Transaction();
$transaction->fee_per_trainee=$fee;
$transaction->is_completed=1;
$transaction->no_of_trainees=$quantity;
$transaction->org_id=$org_id;
$transaction->tr_amount=$amount;
$transaction->transaction_id=$transaction_id;

$transaction->save_transaction($con);









//$get="";
//foreach ($_POST as $key=>$value)
//{
//     $get.= " -- Key::".$key." -- Value::".$value;
//     $sql = "INSERT INTO test(values1)values('POST_Key ::: $key Value::: $value')";
//$con->query($sql);
//}


