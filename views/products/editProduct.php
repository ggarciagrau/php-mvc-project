<form method="POST" action="<?= htmlentities($_SERVER['PHP_SELF']); ?>">
    <fieldset>
        <div>
            <label for="id">Id: </label>
            <input type="text" name="idField" id="id" value="<?= !empty($params) ? $params->getId() : null; ?>">
        </div>
        <div>
            <label for="descriptionField">Description: </label>
            <input type="text" name="descriptionField" id="descriptionField" value="<?= !empty($params) ? $params->getDescription() : null; ?>">
        </div>
        <div>
            <label for="priceField">Price: </label>
            <input type="number" name="priceField" id="priceField" value="<?= !empty($params) ? $params->getPrice() : null; ?>">
        </div>
        <div>
            <label for="stockField">Stock: </label>
            <input type="number" name="stockField" id="stockField" value="<?= !empty($params) ? $params->getStock() : null; ?>">
        </div>
        <input type="hidden" name="id" value="<?= !empty($params) ? $params->getId() : null; ?>">
        <input type="hidden" name="entity" value="product">
        <input  class="button" type="reset">
        <button name="action" value="<?php switch ($_GET['action']) { 
            case 'edit': 
                echo 'modify'; 
                break; 
            case 'product/add': 
                echo 'addProduct'; 
                break; 
            default: 
                echo 'modify'; 
                break;
            }
            ?>" type="submit">Submit</button>
    </fieldset>
</form>