<?php if(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/', 0)) == '/listAll'): ?>
    <h2>User list</h2>
        <?php if(isset($_SESSION['userSession']) && $_SESSION['userSession']['role'] = 'admin'):?>
            <div class="one-rem-margin">
            <form method="GET" class="inline-block one-rem-margin">
                <input type="hidden" name="entity" value="user">
                <button name="action" value="user/add" type="submit">Add user</button>
            </form>
            <form method="GET" class="inline-block one-rem-margin">
                <input type="hidden" name="entity" value="product">
                    <fieldset>
                        <legend>Search a specific user by id</legend>
                        <input type="hidden" name="entity" value="user">
                        <input type="text" name="idField" id="productId" class="low-l-margin" placeholder="target id">
                        <button name="action" value="search" type="submit">Search</button>
                    </fieldset>
                </form>
            </div>
            <?php if(isset($_SESSION['editForm'])):?>
                <?= gzuncompress($_SESSION['editForm']); ?>
                <?php unset($_SESSION['editForm']); ?>
            <?php endif; ?>
        <?php endif; ?>
<?php foreach ($params->getList() as $userLoaded): ?>
<div class="one-rem-margin">
    <table>
    <thead>
    <tr>
        <th>Id<br></th>
        <th>Username</th>
        <th>Role</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?= \ucfirst($userLoaded->getId()) ?></td>
        <td><?= $userLoaded->getUsername() ?></td>
        <td><?= \ucfirst($userLoaded->getRole()) ?></td>
    </tr>
    <?php if(isset($_SESSION['userSession']) && $_SESSION['userSession']['role'] = 'admin'):?>
        <?php echo <<<EOT
        <tr>
            <td colspan="3">
                <form method="GET" class="inline-block">
                    <input type="hidden" name="id" value="{$userLoaded->getId()}">
                    <input type="hidden" name="entity" value="user">
                    <button name="action" value="editUser" type="submit">Edit user</button>
                </form>
                <form method="GET" class="inline-block">
                    <input type="hidden" name="id" value="{$userLoaded->getId()}">
                    <input type="hidden" name="entity" value="user">
                    <button name="action" value="delete" type="submit">Delete user</button>
                </form>
            </td>
        </tr>
        EOT; ?>
     <?php endif; ?>
    </tbody>
    </table>
</div>
<?php endforeach; ?>
<?php else: ?>
    <?php header("Location: ../index.php?badUrl=true"); ?>
<?php endif; ?>