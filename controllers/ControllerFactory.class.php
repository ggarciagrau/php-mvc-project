<?php

namespace Controllers;

require_once 'controllers/BaseController.interface.php';
require_once 'controllers/UserController.class.php';
require_once 'controllers/ProductController.class.php';

abstract class ControllerFactory {

    /** genController: Function that returns an controller object.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {string} $baseType: string with the classname to instantiate the desired object.
     * @return {BaseController} new object inherited of BaseController class
     */
    public static function genController(string $baseType): BaseController {
        switch ($baseType) {
            case 'product':
                return new ProductController();
                break;
            case 'user':
                return new UserController();
                break;
        }
    }
}