<?php 

if(isset($_SESSION['userSession'])) {
    echo '<p class="correctText">Hello '.$_SESSION['userSession']['name'].' '.$_SESSION['userSession']['surname'].'</p>';
} else if(isset($_SESSION['errorLogin'])) {
    echo '<p class="errorText">'.$_SESSION['errorLogin'].'</p>';
    unset($_SESSION['errorLogin']);
} else if(isset($_GET['loginstatus'])) {
    switch ($_GET['loginstatus']) {
        case 'true':
            echo '<p class="correctText">Succesful logout</p>';
            header("Refresh:10; url=index.php");
            break;
        
        case 'false':
            echo '<p class="errorText">Invalid logout</p>';
            header("Refresh:10; url=index.php");
            break;
    }
} 
?>
<nav>
    <ul>
    <?php if(!isset($_SESSION['userSession'])): ?>
        <li><a href="index.php?action=login">LogIn</a></li>
    <?php endif; ?>
        <li><a href="index.php?action=product/listAll">The products</a></li>
        <?php if(isset($_SESSION['userSession']) && ($_SESSION['userSession']['role'] == 'staff' || $_SESSION['userSession']['role'] == 'admin')): ?>
            <li><a href="index.php?action=user/listAll">The users</a></li>
            <li><a href="index.php?action=logout">Logout</a></li>
            <!-- <li><a href="index.php?action=product/add">Add product form</a></li>
            <li><a href="index.php?action=product/modify">Modify product form</a></li>
            <li><a href="index.php?action=product/delete">Delete product form</a></li> -->
        <?php endif;?>
        <?php if(isset($_SESSION['userSession']) && $_SESSION['userSession']['role'] == 'admin'): ?>
            <!-- <li><a href="index.php?action=user/add">Create user form</a></li>
            <li><a href="index.php?action=user/modify">Modify user form</a></li>
            <li><a href="index.php?action=user/delete">Delete user form</a></li> -->
        <?php endif; ?>
    </ul>
</nav>
