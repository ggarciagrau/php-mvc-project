<?php

namespace Repositories;

require_once 'repositories/BaseRepository.interface.php';
require_once 'models/ProductModel.class.php';
require_once 'data-types/CollectionFactory.class.php';
require_once 'data-types/ProductCollection.class.php';
require_once 'lib/CSVManager.class.php';

use lib;
use Models;
use DataTypes;

class ProductRepository implements BaseRepository {

    /**
     * @var string DATATABLE. The route to the DB data.
     */
    const DATATABLE = 'db/products.txt';

    /** getSpecific: Function that returns a specific product screening from id.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {int} $id: integer of the desired id.
     * @return {ProductModel} new object ProductModel object with his data otherwise an empty model with 9999 id.
     */
    public function getSpecific(int $id): Models\ProductModel {
        $dataTableExists = lib\CSVManager::checkIfFileExists(self::DATATABLE);
        if($dataTableExists) {
            $dataTableResource = lib\CSVManager::openFileReadPermision(self::DATATABLE);
            if($dataTableResource) {
                $resultSet = lib\CSVManager::getFileLinesToArray($dataTableResource);
                lib\CSVManager::closeFile($dataTableResource);
                \array_shift($resultSet);
                foreach ($resultSet as $currRawProduct) {
                    $currProduct = explode(';', $currRawProduct);
                    if(intval($currProduct[0]) == $id) {
                        $newProduct = new Models\ProductModel();
                        $newProduct->setData(intval($currProduct[0]), $currProduct[1], intval($currProduct[2]), intval($currProduct[3]));
                        return $newProduct;
                    }
                }
                $err = new Models\ProductModel();
                $err->setId(9999);
                return $err;
            }
        }
    }

    /** getAll: Function that returns all the registrered products.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {ProductCollection} new object ProductCollection containing all the users.
     */
    public function getAll(): DataTypes\ProductCollection {
        $dataTableExists = lib\CSVManager::checkIfFileExists(self::DATATABLE);
        if($dataTableExists) {
            $dataTableResource = lib\CSVManager::openFileReadPermision(self::DATATABLE);
            if($dataTableResource) {
                $productList = DataTypes\CollectionFactory::genDataCollection('product');
                $resultSet = lib\CSVManager::getFileLinesToArray($dataTableResource);
                lib\CSVManager::closeFile($dataTableResource);
                \array_shift($resultSet);
                for ($i=0; $i < \count($resultSet); $i++) { 
                    $currRawProduct = \explode(';', $resultSet[$i]);
                    $newProduct = new Models\ProductModel();
                    $newProduct->setData(intval($currRawProduct[0]), $currRawProduct[1], intval($currRawProduct[2]), intval($currRawProduct[3]));
                    $productList->add($newProduct);
                }
                return $productList;
            }
        }
    }
    
}