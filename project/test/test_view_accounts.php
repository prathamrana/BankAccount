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
$result = [];
if($accountId >0){
    $query = "SELECT account_number,account_type,balance FROM Accounts WHERE id=:aid";
    $db = getDB();
    $stmt = $db->prepare($query);
    $r = $stmt->execute([":aid"=>$accountId]);
    if($r){
        //fetch
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$result){
            $result =[];
        }
    }
    else{
        flash("Error looking up account: " . var_export($stmt->errorInfo(), true));
    }
}
?>
<?php if(isset($result) && count($result) >0):?>
<table style="width= 100%">
    <thead>
    <th>Account Number</th>
    <th>Account Type</th>
    <th>Account Balance</th>
    </thead>
    <tbody style = "text-align: center">
    <tr>
        <?php foreach($result as $cell):?>
            <td><?php echo $cell;?></td>
        <? endforeach;?>
    </tr>
    </tbody>
</table>
<?php else:?>
<p>Invalid account selection, or missing query parameter</p>
<?php endif;?>
<?php require(__DIR__ . "/../partials/flash.php");?>