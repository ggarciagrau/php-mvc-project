<?php

namespace lib;

class CSVManager {

    /** checkIfFileExists: Function that checks if the specified file exists.
     * @author gerardgg
     * @version 1.0
     * @date 06/10/2020
     * @param {string} $filename: string of the filename.
     * @return {bool} $verifiedData: true if the file is found, otherwise false.
     */
    public static function checkIfFileExists(string $filename): bool {
        if(\is_file($filename)) {
            return true;
        } else {
            return false;
        }
    }

    /** openFileReadPermision: Function that opens a specified file with read permission.
     * @author gerardgg
     * @version 1.0
     * @date 06/10/2020 
     * @param {string} string of filename
     * @return {resource} $openedFile: resource of a file if the file is readeable otherwise false.
     */
    public static function openFileReadPermision($filename) {
        if(\is_readable($filename)) {
            $openedFile = \fopen($filename, 'r');
            return $openedFile;
        } else {
            return false;
        }
    }

    /** openFileWritePermission: Function that opens a specified file with write permission.
     * @author gerardgg
     * @version 1.0
     * @date 07/10/2020 
     * @param {string} string of filename
     * @return {resource} $openedFile: resource of a file if the file is writeable otherwise false.
     */
    public static function openFileWritePermission($filename) {
        if(\is_writeable($filename)) {
            $openedFile = \fopen($filename, 'a');
            return $openedFile;
        } else {
            return false;
        }
    }

    /** getFileLinesToArray: Function that reads a file line by line till end of file.
     * @author gerardgg
     * @version 1.0
     * @date 06/10/2020
     * @param {resource} $fileResource: file opened resource.
     * @return {array} $fileLines: array with file lines.
     */
    public static function getFileLinesToArray($fileResource): array {
        $fileContent = array();
        while(!feof($fileResource)) {
            $filePermisisions = \stream_get_meta_data($fileResource)['mode'];
            if($filePermisisions == 'r') {
                while(!\feof($fileResource)) {
                    $currentLine = \fgets($fileResource);
                    \array_push($fileContent, \trim($currentLine));
                }
            }
            return $fileContent;
        }
    }

    /** writeLine: Function that writes a line in a file.
     * @author gerardgg
     * @version 1.0
     * @date 07/10/2020
     * @param {resource} $fileResource: file opened resource.
     * @param {string} $stringToWrite: desired string to write.
     * @param {bool} true if writed correctly otherwise false.
     */
    public static function writeLine($fileResource, $stringToWrite): bool {
        if(\is_resource($fileResource)) {
            \fwrite($fileResource, $stringToWrite);
            return true;
        }
        return false;
    }


    /** closeFile: Function that closes a file resource.
     * @author gerardgg
     * @version 1.0
     * @date 06/10/2020 
     * @param {resource} file resource
     */
    public static function closeFile($resource) {
        if(\is_resource($resource)) {
            fclose($resource);
        }
    }

}