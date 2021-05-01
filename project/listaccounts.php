<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
if (!is_logged_in()) {
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>
<?php
$email = "";
$results = [];
if (isset($_SESSION["user"])) {
    $email = $_SESSION["user"]["email"];
}
if (!empty($email)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT acc.account_number, acc.account_type, acc.balance FROM Accounts as acc JOIN Users on acc.user_id = Users.id WHERE Users.email = :email LIMIT 5");
    $r = $stmt->execute([":email" => "$email"]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem trying to view your account.");
    }
}

?>
    <div class="results">
        <?php if (count($results) > 0): ?>
            <div class="list-group">
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item">
                        <div>
                            <div>Account Number:</div>
                            <div><?php safer_echo($r["account_number"]); ?></div>
                        </div>
                        <div>
                            <div>Account Type:</div>
                            <div><?php safer_echo($r["account_type"]); ?></div>
                        </div>
                        <div>
                            <div>Balance:</div>
                            <div><?php safer_echo($r["balance"]); ?></div>
                        </div>
                        <div>
                            <a type="button" href="listtransactions.php?account_number=<?php safer_echo($r['account_number']); ?>">Transactions</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No accounts found!</p>
        <?php endif; ?>
    </div>

<?php require(__DIR__ . "/partials/flash.php");