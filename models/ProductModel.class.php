<?php

namespace Models;

require_once 'models/BaseModel.interface.php';

class ProductModel implements BaseModel {

    /** 
     * @var int $id. The id of the product.
     */
    private int $id;

    /** 
     * @var string $id. The id of the product.
     */
    private string $description;

    /** 
     * @var int $price. The price of the product.
     */
    private int $price;

    /** 
     * @var int $stock. The stock of the product.
     */
    private int $stock;

    // Methods
    /** setData: Function that sets all the class properties.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @param {int} $newId: integer of the desired id.
     * @param {string} $newDescription: string of the desired description.
     * @param {int} $newPrice: string of the desired price.
     * @param {int} $newStock: string of the desired stock.
     */
    public function setData(int $newId, string $newDescription, int $newPrice, int $newStock) {
        $this->id = $newId;
        $this->description = $newDescription;
        $this->price = $newPrice;
        $this->stock = $newStock;
    }

    /** returnData: Function that returns a specific user as a CSV string.
     * @author gerardgg
     * @version 1.0
     * @date 14/02/2021
     * @return {string} Class properties as a CSV string.
     */
    public function returnData(): string {
        return PHP_EOL."{$this->id};{$this->description};{$this->price};{$this->stock}";
    }

    // Getters & setters
    public function getId(): int {
        return $this->id;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPrice(): int {
        return $this->price;
    }

    public function getStock(): int {
        return $this->stock;
    }

    public function setId(int $newId): void {
        $this->id = $newId;
    }

    public function setDescription(string $newDescription): void {
        $this->description = $newDescription;
    }

    public function setPrice(int $newPrice): void {
        $this->price = $newPrice;
    }

    public function setStock(int $newStock): void {
        $this->stock = $newStock;
    }
}