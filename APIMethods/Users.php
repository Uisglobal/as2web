<?php

class UserData
{
    //connection
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // get all user data 
    public function getUserData()
    {
        $stmt = $this->conn->query("SELECT * FROM users"); // get user data query
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // add a user
    public function addUser($data)
    {
        $stmt = $this->conn->prepare("INSERT INTO users (email, password, username, purchase_history, shipping_address) VALUES (?, ?, ?, ?, ?)");  // user add query
        $stmt->execute([$data['email'], $data['password'], $data['username'], $data['purchase_history'], $data['shipping_address']]);
        return $this->conn->lastInsertId();
    }

    // update user
    public function updateUser($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE users SET email = ?, password = ?, username = ?, purchase_history = ?, shipping_address = ? WHERE user_id = ?"); //update user query
        $stmt->execute([$data['email'], $data['password'], $data['username'], $data['purchase_history'], $data['shipping_address'], $id]);
    }

    // deleat user
    public function deleteUser($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = ?"); // deleat user query
        $stmt->execute([intval($id)]);
    }
}

?>
