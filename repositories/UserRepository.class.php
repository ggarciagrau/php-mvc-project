<?php

namespace Repositories;

require_once 'repositories/BaseRepository.interface.php';
require_once 'models/UserModel.class.php';
require_once 'models/BaseModel.interface.php';
require_once 'data-types/CollectionFactory.class.php';
require_once 'data-types/UserCollection.class.php';
require_once 'lib/CSVManager.class.php';

use lib;
use Models;
use DataTypes;

class UserRepository implements BaseRepository {

    /**
     * @var string DATATABLE. The route to the DB data.
     */
    const DATATABLE = 'db/users.txt'; 

    /** getSpecific: Function that returns a specific user screening from id.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {int} $id: integer of the desired id.
     * @return {UserModel} new object UserModel object with his data otherwise an empty model with 9999 id.
     */
    public function getSpecific(int $id): Models\UserModel {
        $dataTableExists = lib\CSVManager::checkIfFileExists(self::DATATABLE);
        if($dataTableExists) {
            $dataTableResource = lib\CSVManager::openFileReadPermision(self::DATATABLE);
            if($dataTableResource) {
                $resultSet = lib\CSVManager::getFileLinesToArray($dataTableResource);
                lib\CSVManager::closeFile($dataTableResource);
                \array_shift($resultSet);
                foreach ($resultSet as $currRawUser) {
                    $currUser = explode(';', $currRawUser);
                    if(intval($currUser[0]) == $id) {
                        $newUser = new Models\UserModel();
                        $newUser->setData(intval($currUser[0]), $currUser[1], $currUser[2], $currUser[3], $currUser[4], $currUser[5]);
                        return $newUser;
                    }
                }
                $err = new Models\UserModel();
                $err->setId(9999);
                return $err;
            }
        }
    }

    /** getAll: Function that returns all the registrered users.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {UserCollection} new object UserCollection containing all the users.
     */
    public function getAll(): DataTypes\UserCollection {
        $dataTableExists = lib\CSVManager::checkIfFileExists(self::DATATABLE);
        if($dataTableExists) {
            $dataTableResource = lib\CSVManager::openFileReadPermision(self::DATATABLE);
            if($dataTableResource) {
                $userList = DataTypes\CollectionFactory::genDataCollection('user');
                $resultSet = lib\CSVManager::getFileLinesToArray($dataTableResource);
                lib\CSVManager::closeFile($dataTableResource);
                \array_shift($resultSet);
                for ($i=0; $i < \count($resultSet); $i++) { 
                    $currRawUser = \explode(';', $resultSet[$i]);
                    $newUser = new Models\UserModel();
                    $newUser->setData(intval($currRawUser[0]), $currRawUser[1], $currRawUser[2], $currRawUser[3], $currRawUser[4], $currRawUser[5]);
                    $userList->add($newUser);
                }
                return $userList;
            }
        }
    }
}