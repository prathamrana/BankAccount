<?php require_once(__DIR__ . "/partials/nav.php");
if (!is_logged_in()) {
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}?>
<?php
$user = get_user_id();
$results = [];
$db = getDB();
$stmt = $db->prepare("SELECT id, account_number, account_type, balance from Accounts WHERE user_id like :id");
$r = $stmt->execute([":id" => $user]);
if ($r) {
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
else {
    flash("There was a problem fetching the results");
}
?>
<?php
if(isset($_POST["save"])){
    $bal = $_POST["balance"];
    if ($bal < 500){
        flash("Enter balance greater than $500");
    }else{
        $accNum =  rand(100000000000, 999999999999);
        $dest_acc = $_POST["dest_acc"];
        $opened = date('Y-m-d H:i:s');//calc
        $updated = $opened;
        $stmt = $db->prepare("INSERT INTO Accounts (account_number, account_type, opened_date, last_updated, user_id, balance) VALUES(:accnum, :acctype, :opened, :updated, :id, :balance)");
        $r = $stmt->execute([
            ":accnum"=>$accNum,
            ":acctype"=>"Loan",
            ":opened"=>$opened,
            ":updated"=>$updated,
            ":user"=>$user,
            ":balance"=>$bal,
        ]);
        if($r){
            var_dump($r);

            $stmt = $db->prepare("SELECT id FROM Accounts WHERE account_number =:num");
            $r = $stmt->execute([":num" => $dest_acc]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                $e = $stmt->errorInfo();
                flash($e[2]);
            }else{

                flash("Your new account has been created successfully!");
            }
        }
        else{
            $e = $stmt->errorInfo();
            flash("Error creating: " . var_export($e, true));
        }
    }
}
?>
    <form method="POST">
        <label> Account <label>
                <select name="dest_acc" required>
                    <?php foreach ($results as $account): ?>
                        <option value="<?php safer_echo($account["account_number"]); ?>">
                            <?php safer_echo($account["account_number"]); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>Balance, minimum is $500</label>
                <input type="number" min="500" name="balance"/>
                <input type="submit" name="save" value="Get Loan"/>
    </form>

<?php require(__DIR__ . "/partials/flash.php"); ?>