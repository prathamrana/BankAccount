<?php require(__DIR__ . "/../partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
if(isset($_POST["submit"])){
    $account_number = $_POST["account_number"];
    $db = getDB();
    $query = "INSERT INTO Accounts(account_number, user_id) VALUES (:account_number,:aid)";
    $stmt = $db->prepare($query);
    $r = $stmt->execute([
        ":account_number"=>$account_number,
        ":aid"=>get_user_id()]);
    if($r){
        flash("Account created successfully");
    }
    else{
        flash("Something went wrong: " . var_export($stmt->errorInfo(), true));
    }
}
?>
    <form method="POST">
        <input name="Account Number" placeholder="Account Number"/>
        <input type="submit" name="submit" value="Create"/>
    </form>
<?php require(__DIR__ . "/../partials/flash.php");?>