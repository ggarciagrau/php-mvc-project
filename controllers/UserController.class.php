<?php

namespace Controllers;

require_once 'controllers/BaseController.interface.php';
require_once 'repositories/RepositoryFactory.class.php';
require_once 'data-types/UserCollection.class.php';
require_once 'models/UserModel.class.php';
require_once 'models/BaseModel.interface.php';
require_once 'lib/CSVManager.class.php';

use lib;
use Repositories;
use Models;
use DataTypes;

class UserController implements BaseController {

    /** 
     * @var string DATATABLE. The route to DB table.
     */
    const DATATABLE = 'db/users.txt';

    /** 
     * @var string DATATABLE. The DB table columns.
     */
    const DATAHEADER = 'id;username;password;role;name;surname';

    /** login: Function that makes login.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {string} $username: string of the username.
     * @param {string} $username: string of the passwd.
     * @return {bool} true if the user exists and credentials are correct otherwise false.
     */
    public function login(string $username, string $passwd): bool {
        $userList = $this->getAll();
        foreach ($userList->getList() as $user) {
            if($username == $user->getUsername()) {
                $_SESSION['userSession']['username'] = $user->getUsername();
                $_SESSION['userSession']['role'] = $user->getRole();
                $_SESSION['userSession']['name'] = $user->getName();
                $_SESSION['userSession']['surname'] = $user->getSurname();
                return true;
            }
        }
        $_SESSION['errorLogin'] = "Invalid credentials";
        return false;     
    }

    /** logout: Function that makes logout.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {bool} true if the sessions exists and is deleted otherwise false.
     */
    public function logout(): bool {
        if(isset($_SESSION['userSession'])) {
            unset($_SESSION['userSession']);
            unset($_SESSION);
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"],$params["httponly"]);
            }
            session_destroy();
            return true;
        }
        return false;
    }

    /** modify: Function that modifies a user in the DB.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {array} $rawData: array of the new unprocessed data.
     * @return {int} 1 if is correct, 0 if it fails due an error, -1 if it fails due to permissions.
     */
    public function modify(array $rawData, $edit = false): int {
        if($_SESSION['userSession']['role'] == 'admin') {
            $filterTemplate = array(
                "idField" => FILTER_VALIDATE_INT,
                "usernameField" => FILTER_SANITIZE_STRING,
                "passwordField" => FILTER_SANITIZE_STRING,
                "role" => FILTER_SANITIZE_STRING,
                "nameField" => FILTER_SANITIZE_STRING,
                "surnameField" => FILTER_SANITIZE_STRING,
                "id" => FILTER_VALIDATE_INT,
            );

            $filteredData = filter_var_array($rawData, $filterTemplate);
            if($filteredData['idField'] &&
            $filteredData['usernameField'] &&
            $filteredData['passwordField'] &&
            $filteredData['role'] &&
            $filteredData['nameField'] &&
            $filteredData['surnameField'] &&
            $filteredData['id']) {
                $idUniqueness;
                $usernameUniqueness;
                $edit ? $idUniqueness = $edit : $idUniqueness = $idUniqueness = $this->verifyIdUniqueness($filteredData['idField']);
                $edit ? $usernameUniqueness = $edit : $usernameUniqueness = $idUniqueness = $this->verifyUsernameUniqueness($filteredData['usernameField']);
                if($idUniqueness && $usernameUniqueness) {
                    $userDAO = Repositories\RepositoryFactory::genRepository('user');
                    $user = $userDAO->getSpecific($filteredData['id']);
            
                    if($user->getId() == $filteredData['id']) {
                        $outputableData = $this->prepareModifyData($filteredData);
                        file_put_contents(self::DATATABLE, $outputableData);
                        return 1;
                    }
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
        $users = $this->getAll();
        for ($i=0; $i < count($users->getList()); $i++) { 
            if($users->getList()[$i]->getId() == $filteredData['id']) {
                $modifiedUser = new Models\UserModel();
                $modifiedUser->setData($filteredData['idField'], 
                $filteredData['usernameField'], 
                $filteredData['passwordField'],
                $filteredData['role'],
                $filteredData['nameField'],
                $filteredData['surnameField']);
                $outputableData .= $modifiedUser->returnData();
            } else {
                $outputableData .= $users->getList()[$i]->returnData();
            }
        }
        return $outputableData;
    }

    /** add: Function that adds an user to the DB.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {array} $rawNewUser: array of the new unprocessed data.
     * @return {int} 1 if is correct, 0 if it fails due an error, -1 if it fails due to permissions.
     */
    public function add(array $rawNewUser): int {
        if($_SESSION['userSession']['role'] == 'admin') {
            $filterTemplate = array(
                'idField' => FILTER_VALIDATE_INT,
                'usernameField' => FILTER_SANITIZE_STRING,
                'passwordField' => FILTER_SANITIZE_STRING,
                'role' => FILTER_SANITIZE_STRING,
                'nameField' => FILTER_SANITIZE_STRING,
                'surnameField' => FILTER_SANITIZE_STRING,
            );
            
            $filteredData = \filter_var_array($rawNewUser, $filterTemplate);
            if($filteredData['idField'] &&
            $filteredData['usernameField'] &&
            $filteredData['passwordField'] &&
            $filteredData['role'] &&
            $filteredData['nameField'] &&
            $filteredData['surnameField']) {
                $idUniqueness = $this->verifyIdUniqueness($filteredData['idField']);
                $usernameUniqueness = $this->verifyUsernameUniqueness($filteredData['usernameField']);
                if($idUniqueness && $usernameUniqueness) {
                    $newUser = $filteredData;
                    $newUser = PHP_EOL.\implode(';', $newUser);
                    $fileResource = $this->dbWrite();
                    lib\CSVManager::writeLine($fileResource, $newUser);
                    lib\CSVManager::closeFile($fileResource);
                    return 1;
                }
                return 2;
            }
            return 0;
        }
        return -1;
    }

    /** delete: Function that deletes an user of the DB.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {string} $id: unprocessed id.
     * @return {int} 1 if is correct, 0 if it fails due an error, -1 if it fails due to permissions.
     */
    public function delete(string $id): int {
        if($_SESSION['userSession']['role'] == 'admin') {
            $outputableData = self::DATAHEADER;
            $filteredData = \filter_var($id, FILTER_VALIDATE_INT);
    
            if($filteredData) {
                $targetUser = $this->getSpecific($filteredData);
                if($filteredData == $targetUser->getId()) {
                    $userList = $this->getAll();
                    for ($i=0; $i <= count($userList->getList()); $i++) {
                        if(isset($userList->getList()[$i])) {
                            if($userList->getList()[$i]->getId() != $filteredData) {
                                $outputableData .= $userList->getList()[$i]->returnData();
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

    /** dbWrite: Function that creates the DB connection.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {resource} DB connection stream.
     */
    public function dbWrite() {
        $dataTableExists = lib\CSVManager::checkIfFileExists(self::DATATABLE);
        if($dataTableExists) {
            $fileResource = lib\CSVManager::openFileWritePermission(self::DATATABLE);
            return $fileResource;
        }
    }

    /** getSpecific: Function that returns a specific user screening from id.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {int} $id: integer of the desired id.
     * @return {UserModel} new object UserModel object with his data otherwise an empty model with 9999 id.
     */
    public function getSpecific(int $id): Models\UserModel {
        $userDAO = Repositories\RepositoryFactory::genRepository('user');
        $user = $userDAO->getSpecific($id);
        return $user;
    }

    /** getAll: Function that returns all the registrered users.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {UserCollection} new object UserCollection containing all the users.
     */
    public function getAll(): DataTypes\UserCollection {
        $userDAO = Repositories\RepositoryFactory::genRepository('user');
        $userList = $userDAO->getAll();
        return $userList;
    }

    /** getLastId: Function that returns the last used user id.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {int} last used user id.
     */
    public function getLastId(): int {
        $userData = $this->getAll();
        $userList = $userData->getList();
        return $userList[count($userList)-1]->getId();
    }

    /** verifyIdUniqueness: Function that checks if the introduced id is unique in the DB.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {int} $id: user introduced id.
     * @return {bool} true if is unique otherwise false.
     */
    public function verifyIdUniqueness(int $id): bool {
        $uniquenessFlag = true;
        $allUserIds = $this->getAllIds();

        foreach ($allUserIds as $currId) {
            if($id == $currId) {
                $uniquenessFlag = false;
            }
        }

        return $uniquenessFlag;
    }

    /** verifyUsernameUniqueness: Function that checks if the introduced username is unique in the DB.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {string} $username: user introduced username.
     * @return {bool} true if is unique otherwise false.
     */
    public function verifyUsernameUniqueness(string $username): bool {
        $uniquenessFlag = true;
        $allUsernames = $this->getAllUsernames();

        foreach ($allUsernames as $currName) {
            if($username == $currName) {
                $uniquenessFlag = false;
            }
        }

        return $uniquenessFlag;
    }

    /** getAllIds: Function that returns all the user ids.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {array} array of ints.
     */
    public function getAllIds(): array {
        $userIds = array();
        $userData = $this->getAll();
        $userList = $userData->getList();
        

        foreach ($userList as $user) {
            \array_push($userIds, $user->getId());
        }
        return $userIds;
    }

    /** getAllUsernames: Function that returns all the user usernames.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {array} array of strings.
     */
    public function getAllUsernames(): array {
        $userIds = array();
        $userData = $this->getAll();
        $userList = $userData->getList();
        

        foreach ($userList as $user) {
            \array_push($userIds, $user->getUsername());
        }
        return $userIds;
    }
}