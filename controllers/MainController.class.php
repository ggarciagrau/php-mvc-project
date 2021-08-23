<?php

namespace Controllers;

require_once 'lib/ViewLoader.php';
require_once 'controllers/ControllerFactory.class.php';
require_once 'controllers/ProductController.class.php';
require_once 'controllers/UserController.class.php';

use Controllers;
use Utils\ViewLoader;
/**
 * Main controller for store application.
 *
 * @author ProvenSoft
 */
class MainController {
    /**
     * @var Model $model. The model to provide data services. 
     */
    //private Model $model;
    /**
     * @var ViewLoader $view. The loader to forward views. 
     */
    private ViewLoader $view;
    /**
     * @var string $action. The action requested by client. 
     */

    public function __construct() {
        //instantiate the view loader.
        $this->view = new ViewLoader();
    }

    /**
     * processes requests made by client.
     */
    public function processRequest() {
        $requestMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        switch ($requestMethod) {
            case 'GET':
            case 'get':
                $this->processGet();
                break;
            case 'POST':
            case 'post':
                $this->processPost();
                break;
            default:
                $this->processError();
                break;
        }
    }
    
    /**
     * processes get request made by client.
     */
    private function processGet() {
        $this->action = "";
        if (filter_has_var(INPUT_GET, 'action')) {
            $this->action = filter_input(INPUT_GET, 'action'); 
        }
        switch ($this->action) {
            case 'login':  //login page.
                $this->doLogInPage();
                break;
            case 'logout':
                $this->logOut();
                break;
            case 'product/listAll':
                $this->doProductListPage();
                break;
            case 'user/listAll':
                $this->doUserListPage();
                break;
            case 'editUser':
            case 'edit':
                $this->doEditPage();
                break;
            case 'user/add':
            case 'product/add':
                $this->doAddPage();
                break;
            case 'delete':
                $this->deleteEntity();
                break;
            case 'search':
                $this->search();
                break;
        }
    }

    private function processPost() {
        $this->action = "";
        if (filter_has_var(INPUT_POST, 'action')) {
            $this->action = filter_input(INPUT_POST, 'action'); 
        }
        switch ($this->action) {
            case 'doLogin':  //do login.
                $this->doLogin();
                break;
            case 'modify':
                $this->saveChanges();
                break;
            case 'addUser':
            case 'addProduct':
                $this->add();
                break;
        }
    }

    // GET METHODS
    /** search: Function that searchs an specific entity and displays it in the shorthand edit menu.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     */
    public function search() {
        $filterTemplate = array("idField" => FILTER_VALIDATE_INT,
        "entity" => FILTER_SANITIZE_STRING);
        $rawData = $_GET;
        $filteredData = filter_var_array($rawData, $filterTemplate);
        if($filteredData['idField']) {
            $entityController = Controllers\ControllerFactory::genController($filteredData['entity']);
            $targetEntity = $entityController->getSpecific($filteredData['idField']);
            if($filteredData['idField'] == $targetEntity->getId()) {
                ob_start();
                $this->view->show("{$filteredData['entity']}s/edit".ucfirst($filteredData['entity']).".php", $targetEntity);
                $editForm = gzcompress(ob_get_clean());
                $_SESSION['editForm'] = $editForm;
                header("Location: index.php?action={$filteredData['entity']}/listAll");                
            } else {
                //header("Location: index.php?notFound&entity={$filteredData['entity']}");
            }
        }
        //header("Location: index.php?fillTheFields");
    }

    /** doLogInPage: Function that renders login page.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     */
    public function doLogInPage() {
        if(!isset($_SESSION['userSession'])) {
            $this->view->show("login.php");
        } else {
            header("Location: index.php?loggedstatus=true");
        }
    }

    /** doProductListPage: Function that renders product list page.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     */
    public function doProductListPage() {
        $productController = new Controllers\ProductController();
        $productList = $productController->getAll();
        $this->view->show('productList.php', $productList);
    }

    /** doUserListPage: Function that renders user list page.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     */
    public function doUserListPage() {
        if(isset($_SESSION['userSession']['role']) && ($_SESSION['userSession']['role'] == 'admin' || $_SESSION['userSession']['role'] == 'staff')) {
            $userController = new Controllers\UserController();
            $userList = $userController->getAll();
            $this->view->show('userList.php', $userList);
        } else {
            header("Location: index.php?invalidPerms=true");
        }
    }

    /** logOut: Function that logouts and destroys session.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     */
    public function logOut() {
        $userController = new Controllers\UserController();
        $result = $userController->logout();
        if($result) {
            header("Location: index.php?loginstatus=true");
        } else {
            header("Location: index.php?loginstatus=false");
        }
    }

    /** doEditPage: Function that renders edit page.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     */
    public function doEditPage() {
        $filterTemplate = array(
            "id" => FILTER_VALIDATE_INT,
            "entity" => FILTER_SANITIZE_STRING,
        );

        $filteredData = filter_input_array(INPUT_GET, $filterTemplate);
        if($filteredData['id'] && $filteredData['entity']) {
            $entityController = Controllers\ControllerFactory::genController($filteredData['entity']);
            $targetEntity = $entityController->getSpecific($_GET['id']);
            if($targetEntity->getId() == $_GET['id']) {
                $this->view->show($filteredData['entity'].'s/edit'.ucfirst($filteredData['entity']).'.php', $targetEntity);
            } else {
                header("Location: index.php?notFound&entity={$filteredData['entity']}");
            }
        }
    }

    /** doAddPage: Function that renders add page.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     */
    public function doAddPage() {
        $filterTemplate = array(
            "entity" => FILTER_SANITIZE_STRING,
        );
        $filteredData = filter_input_array(INPUT_GET, $filterTemplate);

        if($filteredData['entity']) {
            $this->view->show($filteredData['entity'].'s/edit'.ucfirst($filteredData['entity']).'.php');
        }

    }

    /** saveChanges: Function that saves changes of modified entity.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     */
    public function saveChanges() {
        $filterTemplate = array(
            "id" => FILTER_VALIDATE_INT,
            "entity" => FILTER_SANITIZE_STRING,
        );

        $rawData = $_POST;

        $filteredData = filter_var_array($rawData, $filterTemplate);
        if($filteredData['id'] && $filteredData['entity']) {
            \array_pop($rawData);
            \array_pop($rawData);
            $entityController = Controllers\ControllerFactory::genController($filteredData['entity']);
            $result = $entityController->modify($rawData, true);
            if($result != -1) {
                header("Location: index.php?modify={$result}&entity={$filteredData['entity']}");
            } else {
                header("Location: index.php?invalidPerms");
            }
        }
    }


    /** deleteEntity: Function that deletes a specific entity.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     */
    public function deleteEntity() {
        $filterTemplate = array(
            "id" => FILTER_VALIDATE_INT,
            "entity" => FILTER_SANITIZE_STRING,
        );

        $rawData = $_GET;

        $filteredData = filter_var_array($rawData, $filterTemplate);

        if($filteredData['id'] && $filteredData['entity']) {
            \array_pop($rawData);
            \array_pop($rawData);
            $entityController = Controllers\ControllerFactory::genController($filteredData['entity']);
            $result = $entityController->delete($rawData['id']);
            if($result != -1) {
                header("Location: index.php?delete={$result}&entity={$filteredData['entity']}");
            } else {
                header("Location: index.php?invalidPerms");
            }   
        }
    }
    // POST METHODS
    /** doLogin: Function that handle login process.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     */
    public function doLogin() {
        $filterTemplate = array(
            "usernameField" => FILTER_SANITIZE_STRING,
            "passwordField" => FILTER_SANITIZE_STRING,
        );

        $filteredData = filter_input_array(INPUT_POST, $filterTemplate);
        $userController = new Controllers\UserController();
        $result = $userController->login($filteredData['usernameField'], $filteredData['passwordField']);
        header("Refresh:0");
    }

    /** add: Function that handle adding new entity process.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     */
    public function add() {
        $rawData = $_POST;
        $filterTemplate = array(
            'entity' => FILTER_SANITIZE_STRING,
            'action' => FILTER_SANITIZE_STRING
        );

        $filteredData = filter_var_array($rawData, $filterTemplate);
        \array_pop($rawData);
        \array_pop($rawData);

        if($filteredData['entity'] && $filteredData['action']) {
            $entityController = Controllers\ControllerFactory::genController($filteredData['entity']);
            $result = $entityController->add($rawData);
            if($result != -1) {
                header("Location: index.php?add={$result}&entity={$filteredData['entity']}");
            } else {
                header("Location: index.php?invalidPerms");
            }
        } else {
            header("Location: index.php?fillTheFields");
        }
    }
}