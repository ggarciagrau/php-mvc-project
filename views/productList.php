<?php if(substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/', 0)) == '/listAll'): ?>
    <h2>Product list</h2>
        <?php if(isset($_SESSION['userSession']) && ($_SESSION['userSession']['role'] == 'admin' || $_SESSION['userSession']['role'] == 'staff')):?>
            <div class="one-rem-margin">
                <form method="GET" class="inline-block one-rem-margin">
                    <input type="hidden" name="entity" value="product">
                    <button name="action" value="product/add" type="submit">Add product</button>
                </form>
                <form method="GET" class="inline-block one-rem-margin">
                    <input type="hidden" name="entity" value="product">
                    <fieldset>
                        <legend>Search a specific product by id</legend>
                        <input type="hidden" name="entity" value="product">
                        <input type="text" name="idField" id="userId" class="low-l-margin" placeholder="target id">
                        <button name="action" value="search" type="submit">Search</button>
                    </fieldset>
                </form>
            </div>
            <?php if(isset($_SESSION['editForm'])):?>
                <?= gzuncompress($_SESSION['editForm']); ?>
                <?php unset($_SESSION['editForm']); ?>
            <?php endif; ?>
        <?php endif; ?>
<?php foreach ($params->getList() as $productLoaded): ?>
<div class="one-rem-margin">
    <table>
    <thead>
    <tr>
        <th>Id<br></th>
        <th>Description</th>
        <th>Price</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?= \ucfirst($productLoaded->getId()) ?></td>
        <td><?= \ucfirst($productLoaded->getDescription()) ?></td>
        <td><?= $productLoaded->getStock() ?></td>
    </tr>
    <?php if(isset($_SESSION['userSession']) && $_SESSION['userSession']['role'] = 'admin'):?>
    <tr>
        <td colspan="3">
            <?php echo <<<EOT
            <form method="GET" class="inline-block">
                <input type="hidden" name="id" value="{$productLoaded->getId()}">
                <input type="hidden" name="entity" value="product">
                <button name="action" value="edit" type="submit">Edit product</button>
            </form>
            EOT; ?>
            <?php echo <<<EOT
            <form method="GET" class="inline-block">
                <input type="hidden" name="id" value="{$productLoaded->getId()}">
                <input type="hidden" name="entity" value="product">
                <button name="action" value="delete" type="submit">Delete product</button>
            </form>
            EOT; ?>
        </td>
    </tr>
    <?php endif; ?>
    </tbody>
    </table>
</div>
<?php endforeach; ?>
<?php else: ?>
    <?php header("Location: ../index.php?badUrl=true"); ?>
<?php endif; ?>