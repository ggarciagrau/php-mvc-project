<?php

namespace Controllers;

require_once 'controllers/BaseController.interface.php';
require_once 'repositories/ProductRepository.class.php';
require_once 'data-types/ProductCollection.class.php';
require_once 'models/ProductModel.class.php';
require_once 'lib/CSVManager.class.php';

use lib;
use Repositories;
use DataTypes;
use Models;

class ProductController implements BaseController {

    /** 
     * @var string DATATABLE. The route to DB table.
     */
    const DATATABLE = 'db/products.txt';
    
    /** 
     * @var string DATATABLE. The DB table columns.
     */
    const DATAHEADER = 'id;description;price;stock';

    /** getSpecific: Function that returns a specific product screening from id.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {int} $id: integer of the desired id.
     * @return {ProductModel} new object ProductModel object with his data otherwise an empty model with 9999 id.
     */
    public function getSpecific(int $id): Models\ProductModel {
        $productDAO = Repositories\RepositoryFactory::genRepository('product');
        $product = $productDAO->getSpecific($id);
        return $product;
    }

    /** getAll: Function that returns all the registrered products.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {ProductCollection} new object ProductCollection containing all the products.
     */
    public function getAll(): DataTypes\ProductCollection {
        $productDAO = new Repositories\ProductRepository();
        return $productDAO->getAll();
    }

    /** modify: Function that modifies a product in the DB.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {array} $rawData: array of the new unprocessed data.
     * @return {int} 1 if is correct, 0 if it fails due an error, -1 if it fails due to permissions.
     */
    public function modify(array $rawData, bool $edit = false): int {
        if($_SESSION['userSession']['role'] == 'admin' || $_SESSION['userSession']['role'] == 'staff') {
        $filterTemplate = array(
            'idField' => FILTER_VALIDATE_INT,
            'descriptionField' => FILTER_SANITIZE_STRING,
            'priceField' => FILTER_VALIDATE_INT,
            'stockField' => FILTER_VALIDATE_INT,
            'id' => FILTER_VALIDATE_INT
        );

        $filteredData = filter_var_array($rawData, $filterTemplate);
        if($filteredData['idField'] &&
        $filteredData['descriptionField'] &&
        $filteredData['priceField'] &&
        $filteredData['stockField'] &&
        $filteredData['id']) {
            $idUniqueness;
            $edit ? $idUniqueness = $edit : $idUniqueness = $idUniqueness = $this->verifyIdUniqueness($filteredData['idField']);
            if($idUniqueness) {
                $productDAO = Repositories\RepositoryFactory::genRepository('product');
                $outputableData = $this->prepareModifyData($filteredData);
                $fileResource = $this->dbWrite();
                file_put_contents(self::DATATABLE, $outputableData);
                return 1;
            }
            return 2;
        }
        return 0;
        }
        return -1;
    }

    /** prepareModifyData: Function that formats the data in an outputable CSV format.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {array} $filteredData: array of the new data.
     * @return {string} $outputableData: data with correct format.
     */
    public function prepareModifyData(array $filteredData): string {
        $outputableData = self::DATAHEADER;
        $productList = $this->getAll();
        for ($i=0; $i < count($productList->getList()); $i++) { 
            if($productList->getList()[$i]->getId() == $filteredData['id']) {
                $modifiedProduct = new Models\ProductModel();
                $modifiedProduct->setData($filteredData['idField'], 
                $filteredData['descriptionField'], 
                $filteredData['priceField'],
                $filteredData['stockField']);
                $outputableData .= $modifiedProduct->returnData();
            } else {
                $outputableData .= $productList->getList()[$i]->returnData();
            }
        }
        return $outputableData;
    }

    /** delete: Function that deletes a product of the DB.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {string} $id: unprocessed id.
     * @return {int} 1 if is correct, 0 if it fails due an error, -1 if it fails due to permissions.
     */
    public function delete(string $id): int {
        if($_SESSION['userSession']['role'] == 'admin' || $_SESSION['userSession']['role'] == 'staff') {
            $outputableData = self::DATAHEADER;
            $filteredData = \filter_var($id, FILTER_VALIDATE_INT);

            if($filteredData) {
                $targetProduct = $this->getSpecific($filteredData);
                if($filteredData == $targetProduct->getId()) {
                    $productList = $this->getAll();
                    for ($i=0; $i <= count($productList->getList()); $i++) {
                        if(isset($productList->getList()[$i])) {
                            if($productList->getList()[$i]->getId() != $filteredData) {
                                $outputableData .= $productList->getList()[$i]->returnData();
                            }
                        }
                    }
                    file_put_contents(self::DATATABLE, $outputableData);
                    return 1;
                }
            }
            return 0;
        }
        return -1;
    }

    /** add: Function that adds a product to the DB.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {array} $rawNewProduct: array of the new unprocessed data.
     * @return {int} 1 if is correct, 0 if it fails due an error, -1 if it fails due to permissions.
     */
    public function add(array $rawNewProduct): int {
        echo 'aqio';
        if($_SESSION['userSession']['role'] == 'admin' || $_SESSION['userSession']['role'] == 'staff') {
            $filterTemplate = array(
                'idField' => FILTER_VALIDATE_INT,
                'descriptionField' => FILTER_SANITIZE_STRING,
                'priceField' => FILTER_VALIDATE_INT,
                'stockField' => FILTER_VALIDATE_INT
            );

            $filteredData = \filter_var_array($rawNewProduct, $filterTemplate);

            if($filteredData['idField'] &&
            $filteredData['descriptionField'] &&
            $filteredData['priceField'] &&
            $filteredData['stockField']) {
                $idUniqueness = $this->verifyIdUniqueness($filteredData['idField']);
                if($idUniqueness) {
                    $newProduct = $filteredData;
                    $newProduct = PHP_EOL.\implode(';', $newProduct);
                    $fileResource = $this->dbWrite();
                    lib\CSVManager::writeLine($fileResource, $newProduct);
                    lib\CSVManager::closeFile($fileResource);
                    return 1;
                }
                return 2;
            }
            return 0;
        }
        return -1;
    }

    /** getLastId: Function that returns the last used product id.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {int} last used product id.
     */
    public function getLastId(): int {
        $productData = $this->getAll();
        $productList = $productData->getList();
        return $productList[count($productList)-1]->getId();
    }


    /** dbWrite: Function that creates the DB connection.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {resource} DB connection stream.
     */
    public function dbWrite() {
        $dataTableExists = lib\CSVManager::checkIfFileExists(self::DATATABLE);
        if($dataTableExists) {
            echo 'existe';
            $fileResource = lib\CSVManager::openFileWritePermission(self::DATATABLE);
            return $fileResource;
        }
    }

    /** verifyIdUniqueness: Function that checks if the introduced id is unique in the DB.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {int} $id: product introduced id.
     * @return {bool} true if is unique otherwise false.
     */
    public function verifyIdUniqueness(int $id): bool {
        $uniquenessFlag = true;
        $allProductsIds = $this->getAllIds();

        foreach ($allProductsIds as $currId) {
            if($id == $currId) {
                $uniquenessFlag = false;
            }
        }

        return $uniquenessFlag;
    }


    /** getAllIds: Function that returns all the product ids.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {array} array of ints.
     */
    public function getAllIds(): array {
        $productIds = array();
        $productData = $this->getAll();
        $productList = $productData->getList();
        

        foreach ($productList as $product) {
            \array_push($productIds, $product->getId());
        }
        return $productIds;
    }

    
}