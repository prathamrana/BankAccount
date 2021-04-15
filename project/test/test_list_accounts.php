<?php require(__DIR__ . "/../partials/nav.php"); ?>
<?php
if (!has_role("Admin")) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You don't have permission to access this page");
    die(header("Location: login.php"));
}
?>

<?php
$result = [];
if(isset($_POST["account_number"])){
    $account_number = $_POST["account_number"];
    $isValid = true;
    if(empty(trim($account_number))){
        flash("You must provide a search criteria");
        $isValid = false;
    }
    if($isValid) {
        $db = getDB();
        $query = "SELECT id, account_number from Accounts WHERE account_number LIKE :account_number";
        $stmt = $db->prepare($query);
        $r = $stmt->execute([":account_number" => "%$account_number%"]);

        if ($r) {
            //fetch
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            flash("There was a problem fetching the results: " . var_export($stmt->errorInfo(), true));
        }
    }
}
?>
<form method = "Post">
<label for = "an"> Account Number</label>
<input type = "text" name ="account_number" id ="an"/>
    <input type = "submit" value = "Search"/>
</form>

<table>
    <thead>
    <th> Account ID</th>
    <th> Account Number</th>
    <th>Actions</th>
    </thead>
    <tbody>
    <?php if(isset($result) && count($result) >0):?>
        <?php foreach($result as $r):?>
            <tr>
                <td><?php echo $r["id"];?></td>
                <td><?php echo $r["account_number"];?></td>
                <td>
                    <a type ="button" href="test_edit_accounts.php?accountId=<?php echo $r['id'];?>"Edit</a>
                    <a type ="button" href="test_view_accounts.php?accountId=<?php echo $r['id'];?>"View</a>
                </td>
            </tr>
        <? endforeach;?>
    <?php else:?>
        <tr>
            <td colspan="100%"
                No Results
        </tr>
    <?php endif;?>
    </tbody>
</table>
<?php require(__DIR__ . "/../partials/flash.php");?>