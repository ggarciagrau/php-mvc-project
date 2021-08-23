<?php

namespace Models;

require_once 'models/BaseModel.interface.php';

class UserModel implements BaseModel {

    /**
     * @var int $id. The id of the user.
     */
    private int $id;

     /**
     * @var string $username. The username of the user.
     */
    private string $username;

    /**
     * @var string $passwd. The passwd of the user.
     */
    private string $passwd;

    /**
     * @var string $role. The role of the user.
     */
    private string $role;

    /**
     * @var string $username. The name of the user.
     */
    private string $name;

    /**
     * @var string $username. The surname of the user.
     */
    private string $surname;

    /** setData: Function that sets all the class properties.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {int} $newId: integer of the desired id.
     * @param {string} $newUsername: string of the desired username.
     * @param {string} $newRole: string of the desired role.
     * @param {string} $newName: string of the desired name.
     * @param {string} $newSurname: string of the desired surname.
     */
    public function setData(int $newId, string $newUsername, string $newPasswd, string $newRole, string $newName, string $newSurname) {
        $this->id = $newId;
        $this->username = $newUsername;
        $this->role = $newRole;
        $this->passwd = $newPasswd;
        $this->name = $newName;
        $this->surname = $newSurname;
    }

    /** returnData: Function that returns a specific user as a CSV string.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {string} Class properties as a CSV string.
     */
    public function returnData(): string {
        return PHP_EOL."{$this->id};{$this->username};{$this->passwd};{$this->role};{$this->name};{$this->surname}";
    }

    // Getters & setters
    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPasswd() {
        return $this->passwd;
    }

    public function getRole() {
        return $this->role;
    }

    public function getName() {
        return $this->name;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setId(int $newId) {
        $this->id = $newId;
    }

    public function setUsername(string $newUsername) {
        $this->username = $newUsername;
    }

    public function setPasswd(string $newPasswd) {
        $this->passwd = $newPasswd;
    }
    
    public function setRole(string $newRole) {
        $this->role = $newRole;
    }

    public function setName(string $newName) {
        $this->name = $newName;
    }

    public function setSurname(string $newSurname) {
        $this->surname = $newSurname;
    }
}
