<?php require(__DIR__ . "/../partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>
<?php
$query = "";
$results = [];
if (isset($_POST["query"])) {
    $query = $_POST["query"];
}
if (isset($_POST["search"]) && !empty($query)) {
    $db = getDB();
    $stmt = $db->prepare("SELECT Users.username, acc.account_number, tr.id, tr.amount, tr.action_type, tr.memo, tr.expected_total FROM `Transactions` as tr JOIN Accounts as acc on tr.act_src_id = acc.id JOIN Users on acc.user_id = Users.id WHERE Users.username like :q LIMIT 10");
    $r = $stmt->execute([":q" => "%$query%"]);
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results " . var_export($stmt->errorInfo(), true));
    }
}
?>
    <form method="POST">
        <input name="query" placeholder="Search" value="<?php safer_echo($query); ?>"/>
        <input type="submit" value="Search" name="search"/>
    </form>
    <div class="results">
        <?php if (count($results) > 0): ?>
            <div class="list-group">
                <?php foreach ($results as $r): ?>
                    <div class="list-group-item">
                        <div>
                            <div>Username:</div>
                            <div><?php safer_echo($r["username"]); ?></div>
                        </div>
                        <div>
                            <div>Account Number:</div>
                            <div><?php safer_echo($r["account_number"]); ?></div>
                        </div>
                        <div>
                            <div>Type:</div>
                            <div><?php safer_echo($r["action_type"]); ?></div>
                        </div>
                        <div>
                            <div>Amount:</div>
                            <div><?php safer_echo($r["amount"]); ?></div>
                        </div>
                        <div>
                            <div>Memo:</div>
                            <div><?php safer_echo($r["memo"]); ?></div>
                        </div>
                        <div>
                            <div>Account Total:</div>
                            <div><?php safer_echo($r["expected_total"]); ?></div>
                        </div>
                        <div>
                            <a type="button" href="test_edit_transactions.php?id=<?php safer_echo($r['id']); ?>">Edit</a>
                            <a type="button" href="test_view_transactions.php?id=<?php safer_echo($r['id']); ?>">View</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No results</p>
        <?php endif; ?>
    </div>

<?php require(__DIR__ . "/../partials/flash.php");?>