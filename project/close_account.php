<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
    if (!is_logged_in()) {
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
    }
?>
<?php

    $user = get_user_id();
    $db = getDB();


    $stmt = $db->prepare("SELECT id, account_number, account_type, balance FROM Accounts WHERE user_id = :id");
    $stmt->execute([':id' => $user]);
    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_POST["save"])) {
    //TODO add proper validation/checks
    $id = $_POST["account"];

    $stmt = $db->prepare('SELECT balance, account_number FROM Accounts WHERE id = :q');
    $stmt->execute([ ":q" => $id ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    if($account["balance"] != 0) {
    flash("Balance has to be $0 before closing.");
    }

    $user = get_user_id();
    $stmt = $db->prepare("UPDATE Accounts SET active = 0 WHERE id = :id");
    $r = $stmt->execute([ ":id" => $id ]);
    if ($r) {
    flash("Account ".$account["account_number"]." successfully closed.");
    }
    else {
    flash("Error closing account!");
    }
    }
    ?>

    <form method="POST">
        <div class="form-group">
            <label for="account_dest">Which Account Would You Like to Close?</label>
            <select class="form-control" id="account" name="account">
                <?php foreach ($accounts as $r): ?>
                    <option value="<?php safer_echo($r["id"]); ?>">
                        <?php safer_echo($r["account_number"]); ?> | <?php safer_echo($r["account_type"]); ?> | <?php safer_echo($r["balance"]); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" name="save" value="close" class="btn btn-primary">Close</button>
    </form>

<?php require(__DIR__ . "/partials/flash.php");