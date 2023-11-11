<header class="site-header">
    <nav class="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">Dating.AI</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <?php if (isset($_SESSION["user_id"])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="lobby.php">Browse Personalities</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="chatroom.php">Chatroom</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="billing.php">Billing</a>
                        </li>
                        <?php 
                        if (currentUserIsAdmin() == true) {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="./BACKEND/ADMIN/admin_dashboard.php">Admin Dashboard</a>
                            </li>
                            <?php
                        } 
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="signout.php">Sign Out</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
