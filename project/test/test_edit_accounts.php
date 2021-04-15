<?php require(__DIR__ . "/../partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
$accountId = -1;
if(isset($_GET["accountId"])) {
    $accountId = $_GET["accountId"];
}
if($accountId <= 0){
    flash("Invalid account!!!");
}
?>
<?php
if(isset($_POST["submit"]) && $accountId > 0){
    $account_number = $_POST["account_number"];
    $account_type = $_POST["account_type"];
    $balance = $_POST["balance"];
    $db = getDB();
    $query = "UPDATE Accounts set account_number=:account_number, account_type=:account_type, balance=:balance where id=:aid";
    $stmt = $db->prepare($query);
    $r = $stmt->execute([
            ":account_number"=> $account_number,
            ":account_type"=>$account_type,
            ":balance"=>$balance,
            ":aid"=>$accountId]);
        if($r){
            flash("Updated account successfully");
        }
        else{
            flash("Something went wrong: " . var_export($stmt->errorInfo(), true));
        }
}
?>
<?php
$result = [];
if($accountId >0){
    $query = "SELECT account_number,account_type,balance FROM Accounts WHERE id=:aid";
    $db = getDB();
    $stmt = $db->prepare($query);
    $r = $stmt->execute([":aid"=>$accountId]);
    if($r){
        //fetch
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    else{
        flash("Error looking up account: " . var_export($stmt->errorInfo(), true));
    }
}
?>
<?php if(isset($result)):?>
<form method="POST">
    <input name="account_number" placeholder="Account Number" value="<?php safer_echo($result["account_number"]);?>"/>
    <input name="account_type" type="text" placeholder="Account Type" value="<?php safer_echo($result["account_type"]);?>"/>
    <input name="balance" type="number" placeholder="Current Balance" min="0.00" value="<?php safer_echo($result["balance"]);?>"/>
    <input type="submit" name="submit" value="Edit"/>
</form>
<?php endif;?>
<?php require(__DIR__ . "/../partials/flash.php");?>