<?php

namespace DataTypes;

require_once 'models/BaseModel.interface.php';

use models\BaseModel;

class ModelCollection {

    /** 
     * @var string $modelType. The type of the collection.
     */
    private string $modelType;

    /** 
     * @var array $list. Array with object instances.
     */
    private array $list;

    /** __construct: Function that instances the collection and sets the type.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {string} $dataTypeName: desired collection type name.
     */
    public function __construct(string $dataTypeName) {
        $this->modelType = $dataTypeName;
        $this->list = array();
    }

    /** add: Function that adds an element to the collection.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {BaseModel} $elem: Instance of BaseModel to store in the collection.
     * @return {bool} true if the object is saved correctly otherwise false.
     */
    public function add(BaseModel $elem): bool {
        $className = \get_class($elem);
        if($this->modelType == \substr($className, \strrpos($className, '\\')+1)) {
            \array_push($this->list, $elem);
            return true;
        } else {
            return false;
        }
    }

    /** delete: Function that deletes an element of the collection.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {int} $index: Index to delete.
     * @return {bool} true if the object is saved correctly otherwise false.
     */
    public function delete(int $index): bool {
        if(\array_key_exists($index, $this->list)) {
            \array_splice($this->list, $index, 1);
            return true;
        } else {
            return false;
        }
    }

    /** getList: Function that returns the array list.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {array} array property.
     */
    public function getList(): array {
        return $this->list;
    }

    /** getElem: Function that returns specific element given a specific index.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {int} $index: Index to return.
     * @return {BaseModel} otherwise false
     */
    public function getElem(int $index) {
        if(isset($this->list[$index])) {
            return $this->list[$index];
        }
        return false;
    }

    /** setElem: Function that overwrites an specific element given a specific index.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {int} $index: Index to set.
     * @param {BaseModel} $elem: New element.
     * @return {BaseModel} otherwise false
     */
    public function setElem(int $index, BaseModel $elem): bool {
        $className = \get_class($elem);
        if(isset($this->list[$index]) && $this->modelType == \substr($className, \strrpos($className, '\\')+1)) {
            $this->list[$index] = $elem;
            return true;
        }
        return false;
    }
}