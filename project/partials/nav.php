<link rel="stylesheet" href="static/css/styles.css">
<?php
//we'll be including this on most/all pages so it's a good place to include anything else we want on those pages
require_once(__DIR__ . "/../lib/helpers.php");
?>
<nav>
    <ul class="nav">
        <li><a href="home.php">Home</a></li>
        <?php if (!is_logged_in()): ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>

        <?php if (has_role("Admin")): ?>
            <li><a href="test_create_accounts.php">Create Account</a></li>
            <li><a href="test_list_accounts.php">List Accounts</a></li>
            <li><a href="test_create_transactions.php">Create Transaction</a></li>
            <li><a href="test_list_transactions.php">List Transactions</a></li>
        <?php endif; ?>

        <?php if (is_logged_in()): ?>
        <li><a href="newaccount.php">Open an Account</a></li>
        <li><a href="listaccounts.php">View Accounts</a></li>
        <li><a href="myaccttransfer.php">Account Transfer</a></li>
        <li><a href="transfer.php">External Transfer</a></li>
        <li><a href="loan.php">Loan</a></li>
            <li><a href="close_account.php">Close Account</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
        <?php endif; ?>

    </ul>
</nav>