<?php
// Database credentials
require_once "config.php";

class Database
{
    private $conn;
    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    /**
     * Get the database connection.
     *
     * @return mysqli The database connection.
     */
    public function getConnection()
    {
        return $this->conn;
    }
    /**
     * Execute a database query.
     *
     * @param string $query The SQL query.
     * @param array $params The query parameters.
     * @return array|bool The result of the query as an associative array, or false on failure.
     */
    public function executeQuery($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Query preparation failed: " . $this->conn->error);
        }
        if (!empty($params)) {
            $types = str_repeat("s", count($params));
            $stmt->bind_param($types, ...$params);
        }
        if (!$stmt->execute()) {
            die("Query execution failed: " . $stmt->error);
        }
        $result = $stmt->get_result();
        if (!$result) {
            die("Query execution failed: " . $this->conn->error);
        }
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close(); // Close the statement
        return $data;
    }
    /**
     * Execute a non-query database statement.
     *
     * @param string $query The SQL query.
     * @param array $params The query parameters.
     * @return bool True if the statement executed successfully, false otherwise.
     */
    public function executeNonQuery($query, $params = [])
    {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Query preparation failed: " . $this->conn->error);
        }
        if (!empty($params)) {
            $types = str_repeat("s", count($params));
            $stmt->bind_param($types, ...$params);
        }
        if (!$stmt->execute()) {
            die("Query execution failed: " . $stmt->error);
        }
        $stmt->close(); // Close the statement
        return true;
    }
    /**
     * Check if the database connection is successful.
     *
     * @return bool True if the connection is successful, false otherwise.
     */
    public function isConnected()
    {
        return $this->conn->ping();
    }
    /**
     * Close the database connection.
     */
    public function closeConnection()
    {
        $this->conn->close();
    }
}
// Create a new instance of the Database class
$database = new Database();
// Get the database connection
$conn = $database->getConnection();
