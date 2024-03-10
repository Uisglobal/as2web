<?php
class Comment
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getComments()
    {
        $stmt = $this->conn->query("SELECT * FROM comments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // public function add($data)
    // {
    //     $stmt = $this->conn->prepare("INSERT INTO comments (product_id, user_id, rating, text) VALUES (?, ?, ?, ?)");
    //     $stmt->execute([$data['product_id'], $data['user_id'], $data['rating'], $data['text']]);
    //     return $this->conn->lastInsertId();
    // }

    public function add($data)
{
    try {
        $stmt = $this->conn->prepare("INSERT INTO comments (product_id, user_id, rating, text) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['product_id'], $data['user_id'], $data['rating'], $data['text']]);
        return $this->conn->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error adding comment: " . $e->getMessage());
        return false;
    }
}
    
    public function update($id, $data)
    {
        $stmt = $this->conn->prepare("UPDATE comments SET product_id = ?, user_id = ?, rating = ?, text = ? WHERE comment_id = ?");
        $stmt->execute([$data['product_id'], $data['user_id'], $data['rating'], $data['text'], $id]);
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM comments WHERE comment_id = ?");
        $stmt->execute([$id]);
    }
}
?>
