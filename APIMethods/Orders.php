<?php
class Orders
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // get all orders
    public function getOrders()
    {
        $stmt = $this->conn->query("SELECT * FROM `orders`"); //get order details query
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //add order
    public function addOrder($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO `orders` (user_id, product_id, quantity, total_price, order_date) VALUES (?, ?, ?, ?, ?)"); // insert order details query
        $stmt->execute([$data['user_id'], $data['product_id'], $data['quantity'], $data['total_price'], $data['order_date']]);
        return $this->conn->lastInsertId();
    }

    //update order 
    public function updateOrder($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE `orders` SET user_id = ?, product_id = ?, quantity = ?, total_price = ?, order_date = ? WHERE orderr_id = ?"); // update query
        $stmt->execute([$data['user_id'], $data['product_id'], $data['quantity'], $data['total_price'], $data['order_date'], $id]);
    }

    //delete order
    public function deleteOrder($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM `orders` WHERE order_id = ?"); // delete query
        $stmt->execute([$id]);
    }
}
?>
