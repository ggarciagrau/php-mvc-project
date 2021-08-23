<?php

namespace Repositories;

require_once 'repositories/ProductRepository.class.php';
require_once 'repositories/UserRepository.class.php';

abstract class RepositoryFactory {

    /** genRepository: Function that returns an repository object.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {string} $baseType: string with the classname to instantiate the desired object.
     * @return {BaseRepository} new object inherited of BaseRepository class
     */
    public static function genRepository(string $baseType): BaseRepository {
        switch ($baseType) {
            case 'product':
                return new ProductRepository();
                break;
            case 'user':
                return new UserRepository();
                break;
        }
    }
}