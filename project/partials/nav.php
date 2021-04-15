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
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                   data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    Admin
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="nav-link" href="<?php echo getURL("test/test_create_accounts.php"); ?>">Create
                        Account</a>
                    <a class="nav-link" href="<?php echo getURL("test/test_list_accounts.php"); ?>">View
                        Accounts</a>
                    <a class="nav-link" href="<?php echo getURL("test/test_create_transactions.php"); ?>">Create
                        Transaction</a>
                    <a class="nav-link" href="<?php echo getURL("test/test_list_transactions.php"); ?>">View
                        Transactions</a>
                </div>
            </li>
        <?php endif; ?>

        <?php if (is_logged_in()): ?>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php endif; ?>
    </ul>
</nav>