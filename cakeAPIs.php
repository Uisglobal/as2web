<?php
require 'connection/conn.php';
require 'APIMethods/Products.php';
require 'APIMethods/Users.php';
require 'APIMethods/Cart.php';  
require 'APIMethods/PublicComments.php';
require 'APIMethods/Orders.php';


// database connection
$db = new Database();
$conn = $db->getConnection();


//product table crud
$product = new ProductData($conn);


// get api for products
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_all_products') {
    header('Content-Type: application/json');
    echo json_encode($product->getProductList());
}

// add api for products
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'insert_product') {
    $data = json_decode(file_get_contents("php://input"), true);

    $required_fields = ['description', 'image', 'pricing', 'shipping_cost'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required field: $field"));
            exit;
        }
    }

    $id = $product->addProduct($data);

    if ($id !== false) {
        http_response_code(201);
        echo json_encode(array("id" => $id, "message" => "Product added successfully."));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Failed to add product."));
    }
}

//update_product update api for products
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'update_product') {
    $json_data = file_get_contents("php://input");
    if ($json_data === false || !($data = json_decode($json_data, true)) || !isset($data['id'], $data['description'], $data['image'], $data['price'], $data['shipping_cost'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Invalid or incomplete JSON data."));
        exit;
    }

    try {
        $product->updateProduct($data['id'], $data);
        http_response_code(200);
        echo json_encode(array("message" => "Product updated."));
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("message" => "Failed to update product: " . $e->getMessage()));
    }
}

//delete_product delete product api
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action']) && $_GET['action'] === 'delete_product') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Product ID is required."));
        exit;
    }

    $product_id = $_GET['id'];
    $product->deleteProduct($product_id);
    echo json_encode(array("message" => "Product is deleted."));
}

//user crud operations

$user = new UserData($conn);

// get user api
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_all_user') {
    header('Content-Type: application/json');
    echo json_encode($user->getUserData());
}

//insert user api
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'insert_user') {
    $data = json_decode(file_get_contents("php://input"), true);

    $required_fields = ['email', 'password', 'username', 'purchase_history', 'shipping_address'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required field: $field"));
            exit;
        }
    }

    $id = $user->addUser($data);

    if ($id !== false) {
        http_response_code(201);
        echo json_encode(array("id" => $id, "message" => "User added successfully."));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Failed to add user."));
    }
}

//update user api
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'update_user') {
    $json_data = file_get_contents("php://input");
    if ($json_data === false || !($data = json_decode($json_data, true)) || !isset($data['id'], $data['email'], $data['password'], $data['username'], $data['purchase_history'], $data['shipping_address'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Invalid or incomplete JSON data."));
        exit;
    }

    try {
        $user->updateUser($data['id'], $data);
        http_response_code(200);
        echo json_encode(array("message" => "User updated."));
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("message" => "Failed to update user: " . $e->getMessage()));
    }
}

//delete user api
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action']) && $_GET['action'] === 'delete_user') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "insert user id"));
        exit;
    }

    $user_id = $_GET['id'];
    $user->deleteUser($user_id);
    echo json_encode(array("message" => "user is deleted."));
}

// Cart crud operations

$cart = new Cart($conn);

//get cart api
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_cart_data') {
    header('Content-Type: application/json');
    echo json_encode($cart->getAll());
}

//add cart api
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'insert_cart') {
    $data = json_decode(file_get_contents("php://input"), true);

    $required_fields = ['user_id', 'product_id', 'quantity'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required field: $field"));
            exit;
        }
    }

    $id = $cart->add($data);

    if ($id !== false) {
        http_response_code(201);
        echo json_encode(array("id" => $id, "message" => "Cart item added successfully."));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Failed to add cart item."));
    }
}

//update cart api
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'update_cart') {
    $json_data = file_get_contents("php://input");
    if ($json_data === false || !($data = json_decode($json_data, true)) || !isset($data['cart_id'], $data['user_id'], $data['product_id'], $data['quantity'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Invalid or incomplete JSON data."));
        exit;
    }

    try {
        $cart->update($data['cart_id'], $data);
        http_response_code(200);
        echo json_encode(array("message" => "Cart item updated."));
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("message" => "Failed to update cart item: " . $e->getMessage()));
    }
}

//delete cart api
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action']) && $_GET['action'] === 'delete_cart') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Cart item ID is required."));
        exit;
    }

    $cart_id = $_GET['id'];
    $cart->delete($cart_id);
    echo json_encode(array("message" => "Cart item is deleted."));
}

// Comments crud operations
$comment = new Comment($conn);

//get comments api
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_comment') {
    header('Content-Type: application/json');
    echo json_encode($comment->getComments());
}

//add comments api
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'insert_comment') {
    $data = json_decode(file_get_contents("php://input"), true);

    $required_fields = ['product_id', 'user_id', 'rating', 'text'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required field: $field"));
            exit;
        }
    }

    $id = $comment->add($data); 

    if ($id !== false) {
        http_response_code(201);
        echo json_encode(array("id" => $id, "message" => "Comment added successfully."));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Failed to add comment."));
    }
}

//update comments api
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'update_comment') {
    $json_data = file_get_contents("php://input");
    if ($json_data === false || !($data = json_decode($json_data, true)) || !isset($data['id'], $data['product_id'], $data['user_id'], $data['rating'], $data['text'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Invalid or incomplete JSON data."));
        exit;
    }

    try {
        $comment->update($data['id'], $data);
        http_response_code(200);
        echo json_encode(array("message" => "Comment updated."));
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(array("message" => "Failed to update comment: " . $e->getMessage()));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action']) && $_GET['action'] === 'delete_comment') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Comment ID is required."));
        exit;
    }

    $comment_id = $_GET['id'];
    $comment->delete($comment_id);
    echo json_encode(array("message" => "Comment is deleted."));
}

// Orders crud operations


$orders = new Orders($conn);

//get orders api
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'get_order') {
    header('Content-Type: application/json');
    echo json_encode($orders->getOrders());
}

//insert order api
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_order') {
    $data = json_decode(file_get_contents("php://input"), true);

    $required_fields = ['user_id', 'product_id', 'quantity', 'total_price', 'order_date'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field])) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required field: $field"));
            exit;
        }
    }

    $id = $orders->addOrder($data);

    if ($id !== false) {
        http_response_code(201);
        echo json_encode(array("id" => $id, "message" => "Order added successfully."));
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Failed to add orders."));
    }
}

//update order api
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['action']) && $_GET['action'] === 'update_order') {
    $json_data = file_get_contents("php://input");
    if ($json_data === false || !($data = json_decode($json_data, true)) || !isset($data['order_id'], $data['user_id'], $data['product_id'], $data['quantity'], $data['total_price'], $data['order_date'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Invalid or incomplete JSON data."));
        exit;
    }

    try {
        $orders->updateOrder($data['order_id'], $data);
        http_response_code(200);
        echo json_encode(array("message" => "Order updated."));
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(   array("message" => "Failed to update orders: " . $e->getMessage()));
    }
}

//delete order api
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['action']) && $_GET['action'] === 'delete_order') {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array("message" => "Order ID is required."));
        exit;
    }

    $order_id = $_GET['id'];
    $orders->deleteOrder($order_id);
    echo json_encode(array("message" => "Order is deleted."));
}

?>
