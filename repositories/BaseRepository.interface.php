<?php

namespace Repositories;

require_once 'models/BaseModel.interface.php';
require_once 'data-types/ModelCollection.class.php';

use Models\BaseModel;
use DataTypes\ModelCollection;

interface BaseRepository {

    /** getSpecific: Function that returns a specific entity screening from id.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {int} $id: integer of the desired id.
     * @return {ProductModel} new object BaseModel object with his data otherwise an empty model with 9999 id.
     */
    public function getSpecific(int $id): BaseModel;

    /** getAll: Function that returns all the registrered instances of an entity.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {ModelCollection} new object ModelCollection containing all the instances of an entity.
     */
    public function getAll(): ModelCollection;
}