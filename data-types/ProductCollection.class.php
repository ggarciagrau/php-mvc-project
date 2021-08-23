<?php

namespace DataTypes;

require_once 'data-types/ModelCollection.class.php';

class ProductCollection extends ModelCollection {

    /** __construct: Function that instances the collection and sets the type.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     */
    public function __construct() {
        parent::__construct('ProductModel');
    }

}