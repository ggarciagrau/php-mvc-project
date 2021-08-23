<?php if(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/', 0)) == '/index.php?action=login'): ?>
<form action="index.php" method="POST">
    <div>
        <label for="usernameField">Usernamme:</label>
        <input type="text" name="usernameField" id="usernameField">    
    </div>
    <div>
        <label for="passwordField">Password:</label>
        <input type="password" name="passwordField" id="passwordField">    
    </div>
    <button name="action" value="doLogin" type="submit">Submit</button>
</form>
<?php else: ?>
    <?php header("Location: ../index.php?badUrl=true"); ?>
<?php endif; ?>