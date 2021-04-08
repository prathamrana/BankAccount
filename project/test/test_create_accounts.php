<?php require(__DIR__ . "/../partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

    form method="POST">
    <input name="Account Number" placeholder="Account Number"/>
    <input type="submit" name="submit" value="Create"/>

<?php

if(isset($_POST["submit"])){
    $account_number = $_POST["account_number"];
    $db = getDB();
    $query = "INSERT INTO Accounts(account_number, user_id) Values (:account_number,:uid)";
    $stmt = $db->prepare($query);
    $r = $stmt->execute([
        ":account_number" => $account_number,
        ":uid" => get_user_id()]);
    if($r){
        flash("Account created successfully");
    }
    else{
        flash("Something went wrong: " . var_export($stmt->errorInfo(), true));;
    }
}
?>
<?php require(__DIR__ . "/../partials/flash.php");