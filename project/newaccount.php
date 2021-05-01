<?php require(__DIR__ . "/../partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>
<?php
if(isset($_POST["save"]) && ($_POST["balance"]>=5)){
    $account = rand(00000000000, 999999999999);
    $account_type = $_POST["account_type"];
    $type = "Deposit";
    $src = "1";
    $balance = $_POST["balance"];
    $memo = "First deposit";
    $opened_date = date('Y-m-d H:i:s');
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
        $stmt = $db->prepare("SELECT balance FROM Accounts WHERE Accounts.id =:acct");
        $r = $stmt->execute([":acct" => $src]);
        $resultWorld = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$resultWorld){
            $e = $stmt->errorInfo();
            flash($e[2]);
        }
        $a2total = $resultWorld["balance"] + ($balance *-1);

        $stmt = $db->prepare("SELECT id FROM Accounts WHERE account_number=:account_number");
        $r = $stmt->execute([":account_number" => $account]);
        $resultAccId = $stmt->fetch(PDO::FETCH_ASSOC);
        $accId = $resultAccId["id"];


        $stmt = $db->prepare("INSERT INTO Transactions (act_src_id, act_dest_id, amount, action_type, memo, expected_total, created) VALUES(:p1a1, :p1a2, :p1amount, :type, :memo, :a1total, :created), (:p2a1, :p2a2, :p2amount, :type, :memo, :a2total, :created)");
        $r = $stmt->execute([
            ":p1a1" => $accId,
            ":p1a2" => $src,
            ":p1amount" => $balance,
            ":type" => $type,
            ":memo" => $memo,
            ":a1total" => $balance,
            ":created" => $opened_date,

            ":p2a1" => $src, // world account
            ":p2a2" => $accId,
            ":p2amount" => ($balance*-1),
            ":type" => $type,
            ":memo" => $memo,
            ":a2total" => $a2total,
            ":created" => $opened_date
        ]);
        if ($r) {
        }
        else {
            $e = $stmt->errorInfo();
            flash("Error creating transaction: " . var_export($e, true));
        }
        //Updated world account
        $stmt = $db->prepare("UPDATE Accounts set balance=:balance where id=:id");
        $r = $stmt->execute([
            ":balance" => $a2total,
            ":id" => $src
        ]);
        if($r){
        }
        else{
            $e = $stmt->errorInfo();
            flash("Error updating world account: " . var_export($e, true));
        }
        flash("Created your $account_type account successfuly!");
    }
    else{
        $e = $stmt->errorInfo();
        flash("Error creating your account");
    }
}
elseif (isset($_POST["save"]) &&$_POST["balance"]<5){
    flash("Please deposit at least $5 to open your account");
}
?>
    <form method="POST">
        <label>Account Type:</label>
        <select name="account_type">
            <option value="Checking">Checking</option>
            <option value="Savings">Savings</option>
        </select>
        <label>Initial Deposit:</label>
        <input type="number" min="0.00" step="0.01" name="balance"/>
        <input type="submit" name="save" value="Create"/>
    </form>

<?php require(__DIR__ . "/../partials/flash.php");?>