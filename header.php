<header>
    <h1>Welcome to Dating.AI</h1>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="lobby.php">Browse Personalities</a></li>
                <li><a href="signout.php">Sign Out</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>