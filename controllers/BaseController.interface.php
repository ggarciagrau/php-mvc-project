<?php

namespace Controllers;

require_once 'models/BaseModel.interface.php';
require_once 'data-types/ModelCollection.class.php';

use Models;
use DataTypes;

interface BaseController {

    /** getSpecific: Function that returns a specific BaseModel screening from id.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {int} $id: integer of the desired id.
     * @return {BaseModel} new object BaseModel.
     */
    public function getSpecific(int $id): Models\BaseModel;

     /** getAll: Function that returns all the registrered BaseModels.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {ModelCollection} new object ModelCollection containing all the BaseModels.
     */
    public function getAll(): DataTypes\ModelCollection;

    /** modify: Function that modifies a BaseModel in the DB.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {array} $rawData: array of the new unprocessed data.
     * @return {int} 1 if is correct, 0 if it fails due an error, -1 if it fails due to permissions.
     */
    public function modify(array $rawData): int;

    /** prepareModifyData: Function that formats the data in an outputable CSV format.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {array} $filteredData: array of the new data.
     * @return {string} $outputableData: data with correct format.
     */
    public function prepareModifyData(array $filteredData): string;

    /** delete: Function that deletes an BaseModel of the DB.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {string} $id: unprocessed id.
     * @return {int} 1 if is correct, 0 if it fails due an error, -1 if it fails due to permissions.
     */
    public function delete(string $id): int;

    /** add: Function that adds an BaseModel to the DB.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {array} $newData: array of the new unprocessed data.
     * @return {int} 1 if is correct, 0 if it fails due an error, -1 if it fails due to permissions.
     */
    public function add(array $newData): int;

    /** getLastId: Function that returns the last used BaseModel id.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {int} last used BaseModel id.
     */
    public function getLastId(): int;
}