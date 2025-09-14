<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<div class="navigation">
    <ul>
        <li>
            <a href="admin_dashboard.php" class="<?= $currentPage == 'admin_dashboard.php' ? 'active' : '' ?>">
                <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                <span class="title">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="manage_users.php" class="<?= $currentPage == 'manage_users.php' ? 'active' : '' ?>">
                <span class="icon"><ion-icon name="people-outline"></ion-icon></span>
                <span class="title">Users</span>
            </a>
        </li>
        <li>
            <a href="manage_items.php" class="<?= $currentPage == 'manage_items.php' ? 'active' : '' ?>">
                <span class="icon"><ion-icon name="cafe-outline"></ion-icon></span>
                <span class="title">Shop</span>
            </a>
        </li>
        <li>
            <a href="manage_orders.php" class="<?= $currentPage == 'manage_orders.php' ? 'active' : '' ?>">
                <span class="icon"><ion-icon name="clipboard-outline"></ion-icon></span>
                <span class="title">Orders</span>
            </a>
        </li>
        <li>
            <a href="manage_store.php" class="<?= $currentPage == 'manage_store.php' ? 'active' : '' ?>">
                <span class="icon"><ion-icon name="storefront-outline"></ion-icon></span>
                <span class="title">Store</span>
            </a>
        </li>
        <li>
            <a href="manage_messages.php" class="<?= $currentPage == 'manage_messages.php' ? 'active' : '' ?>">
                <span class="icon"><ion-icon name="chatbubbles-outline"></ion-icon></span>
                <span class="title">Messages</span>
            </a>
        </li>
        <li>
            <a href="../index.php">
                <span class="icon"><ion-icon name="log-out-outline"></ion-icon></span>
                <span class="title">Sign Out</span>
            </a>
        </li>
    </ul>
</div>
