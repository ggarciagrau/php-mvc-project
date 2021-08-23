<?php

namespace DataTypes;

require_once 'data-types/ModelCollection.class.php';
require_once 'data-types/ProductCollection.class.php';
require_once 'data-types/UserCollection.class.php';

abstract class CollectionFactory {

    /** genDataCollection: Function that that returns a collection object.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {string} $baseType: string of the desired type collection.
     * @return {ModelCollection} empty specicic collection instance.
     */
    public static function genDataCollection(string $baseType): ModelCollection {
        switch ($baseType) {
            case 'product':
                return new ProductCollection();
                break;
            case 'user':
                return new UserCollection();
                break;
        }
    }
}