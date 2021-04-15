<?php require(__DIR__ . "/../partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
if(isset($_POST["save"])){
    //TODO add proper validation/checks
    $account = $_POST["account_number"];
    $account_type = $_POST["account_type"];
    $balance = $_POST["balance"];
    $opened_date = date('Y-m-d H:i:s');//calc
    $user = get_user_id();
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO Accounts (account_number, account_type, balance, opened_date, user_id) VALUES(:account_number, :account_type, :balance, :opened_date, :user)");
    $r = $stmt->execute([
        ":account_number"=>$account,
        ":account_type"=>$account_type,
        ":balance"=>$balance,
        ":opened_date"=>$opened_date,
        ":user"=>$user
    ]);
    if($r){
        flash("Created successfully with id: " . $db->lastInsertId());
    }
    else{
        $e = $stmt->errorInfo();
        flash("Error creating: " . var_export($e, true));
    }
}
?>
    <form method="POST">
        <label>Account Number</label>
        <input type="number"  name="account_number" />
        <label>Account Type</label>
        <select name="account_type">
            <option value="checking">Checking</option>
            <option value="saving">Savings</option>
        </select>
        <label>Balance</label>
        <input type="number" min="0.00"  name="balance"/>
        <input type="submit" name="save" value="Create"/>
    </form>

<?php require(__DIR__ . "/../partials/flash.php");?>