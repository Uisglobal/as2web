<?php
class ProductData
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // get products list
    public function getProductList()
    {
        $stmt = $this->conn->query("SELECT * FROM products"); // products query
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // add products list
    public function addProduct($data)
{
    try {
        $stmt = $this->conn->prepare("INSERT INTO products (pricing, description, image, shipping_cost) VALUES (?, ?, ?, ?)"); // insert query 
        $stmt->execute([$data['pricing'], $data['description'], $data['image'], $data['shipping_cost']]);
        return $this->conn->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error inserting product: " . $e->getMessage()); // error handling
        return false;
    }
}

    // update product list
    public function updateProduct($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE products SET pricing = ?, description = ?, image = ?, shipping_cost = ?  WHERE product_id = ?"); //update query
        $stmt->execute([$data['pricing'], $data['description'], $data['image'], $data['shipping_cost'], $id]);
    }

    // delete product list
    public function deleteProduct($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM products WHERE product_id = ?"); // delete query
        $stmt->execute([intval($id)]);
    }
}