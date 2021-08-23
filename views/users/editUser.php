<form action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" method="POST">
    <fieldset>
        <div>
            <label for="id">Id: </label>
            <input type="text" name="idField" id="id" value="<?= !empty($params) ? $params->getId() : null; ?>">
        </div>
        <div>
            <label for="username">Username: </label>
            <input type="text" name="usernameField" id="username" value="<?= !empty($params) ? $params->getUsername() : null; ?>">
        </div>
        <div>
            <label for="password">Password: </label>
            <input type="password" name="passwordField" id="password" value="<?= !empty($params) ? $params->getPasswd() : null; ?>">
        </div>
        <div>
            <label for="role">Role: </label>
            <select name="role" id="role" value="<?= !empty($params) ? $params->getRole() : null; ?>">
                <?php $roles = array('admin', 'staff'); ?>
                <?php foreach ($roles as $role): ?>
                    <?php if(!empty($params) && $role != $params->getRole()): ?>
                        <?= '<option value="'.$role.'">'.ucfirst($role).'</option>' ?>
                    <?php else: ?>
                        <?= '<option value="'.$role.'" selected>'.ucfirst($role).'</option>' ?>
                    <?php endif ?>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label for="name">Name: </label>
            <input type="text" name="nameField" id="name" value="<?= !empty($params) ? $params->getName() : null; ?>">
        </div>
        <div>
            <label for="surname">Surname: </label>
            <input type="text" name="surnameField" id="surname" value="<?= !empty($params) ? $params->getSurname() : null; ?>">
        </div>
        <input type="hidden" name="id" value="<?= !empty($params) ? $params->getId() : null; ?>">
        <input type="hidden" name="entity" value="user">
        <input  class="button" type="reset">
        <button name="action" value="<?php switch ($_GET['action']) { 
            case 'edit': 
                echo 'modify'; 
                break; 
            case 'user/add': 
                echo 'addUser'; 
                break; 
            default: 
                echo 'modify'; 
                break;
            }
            ?>" type="submit">Submit</button>
    </fieldset>
</form>