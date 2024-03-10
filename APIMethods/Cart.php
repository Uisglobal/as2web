<?php
class Cart
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $stmt = $this->conn->query("SELECT * FROM cart");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$data['user_id'], $data['product_id'], $data['quantity']]);
        return $this->conn->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE cart SET user_id = ?, product_id = ?, quantity = ? WHERE cart_id = ?");
        $stmt->execute([$data['user_id'], $data['product_id'], $data['quantity'], $id]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM cart WHERE cart_id = ?");
        $stmt->execute([$id]);
    }
}
?>