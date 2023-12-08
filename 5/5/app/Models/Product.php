<?php
namespace app\Models;
include "app/Config/DatabaseConfig.php";

use app\Config\DatabaseConfig;
use mysqli;

class Product extends DatabaseConfig {
    public $conn;
    public function __construct() {
        // connect ke database
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->database_name, $this->port);
        if($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // menampilkan data
    public function findAll() {
        $sql = "SELECT p.id, p.name AS product_name, d.name AS device_name FROM products p INNER JOIN device d ON (p.id_device = d.id)";
        $result = $this->conn->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $this->conn->close(); 
        return $data;
    }

    // menampilkan data dengan id
    public function findById($id) {
        $sql = "SELECT p.id, p.name AS product_name, d.name AS device_name FROM products p INNER JOIN device d ON (p.id_device = d.id) WHERE p.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $this->conn->close();
        $data = [];
        while($row = $result->fetch_assoc()) {
            $data = $row;
        }
    
        return $data;
    }

    // insert data
    public function create($data) {
        $productName = $data['name'];
        $deviceId = $data['id_device'];
        $query = "INSERT INTO products (name, id_device) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $productName, $deviceId);
        $stmt->execute();
        $this->conn->close();
    }

    // update data dengan id
    public function update($data, $id) {
        $productName = $data['name'];
        $deviceId = $data['id_device'];
        $query = "UPDATE products SET name = ?, id_device = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sii", $productName, $deviceId, $id);
        $stmt->execute();
        $this->conn->close();
    }

    // delete data dengan id
    public function destroy($id) {
        $query = "DELETE FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $this->conn->close();
    }
}
?>