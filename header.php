
<script>
//  var headerHeight = document.querySelector('header').offsetHeight;
//console.log(headerHeight);
</script>

<header class="site-header">
    <nav class="navbar">
        <!-- Brand Logo -->
        <a href="/" class="navbar-brand">Dating.AI</a>

        <!-- Navigation Links -->
        <div class="navbar-nav">
            <a href="lobby.php" class="nav-link">Character Selection</a>
            <!-- <a href="custom.php" class="nav-link">Character Creator</a> -->
            <a href="chatroom.php" class="nav-link">Chatroom</a>
        </div>

        <!-- Profile Dropdown -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="profile-dropdown">
                <button class="dropbtn">Profile</button>
                <div class="dropdown-content">
                    <a href="profile.php">My Profile</a>
                    <a href="settings.php">Settings</a>
                    <a href="billing.php">Billing</a>
                    <a href="signout.php">Sign Out</a>
                </div>
            </div>
        <?php else: ?>
          <div class="profile-dropdown">
                <button class="dropbtn">Login</button>
                <div class="dropdown-content">
                    <a href="login.php">Login</a>
                    <a href="signup.php">Sign Up</a>
                    <a href="support.php">Customer Suppport</a>
                    <a href="contact_us.php">Contact Us</a>
                </div>
            </div>
        <?php endif; ?>
    </nav>
</header>

<script>
// JavaScript for handling the dropdown
document.addEventListener('DOMContentLoaded', function () {
  var dropbtn = document.querySelector('.dropbtn');
  var dropdownContent = document.querySelector('.dropdown-content');

  dropbtn.addEventListener('click', function () {
    dropdownContent.classList.toggle('show');
  });

  // Close the dropdown if the user clicks outside of it
  window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
        var openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }
  }
});
</script>
