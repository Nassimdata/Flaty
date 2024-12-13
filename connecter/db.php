<?php

/*数据库连接文件 */

class DB
{

    static private $pdo;
    static private $config = [
        'db_host' => '127.0.0.1',
        'db_port' => '3306',
        'db_name' => 'flaty',
        'db_user' => 'root',
        'db_pass' => '123456',
    ];

    private function __construct() {}

    public static function pdo()
    {
        if (!empty(self::$pdo)) {
            return self::$pdo;
        }
        self::$pdo = self::connect();
        return self::$pdo;
    }

    private static function connect()
    {
        
        $host = self::$config['db_host'];
        $port =  self::$config['db_port'];
        $db_name = self::$config['db_name'];
        $username = self::$config['db_user'];
        $password = self::$config['db_pass'];

        $conn = null;
        try {
            $dsn = "mysql:host=$host;port=$port;dbname=$db_name";
            $conn = new PDO($dsn, $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            throw $exception;
        }

        return $conn;
    }

    // Insert data
    public static function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $values = ':' . implode(', :', array_keys($data));

        $query = "INSERT INTO $table ($columns) VALUES ($values)";
 
        $stmt = self::pdo()->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    // Update data
    public static  function update($table, $data, $condition)
    {
        $set = '';
        foreach ($data as $key => $value) {
            $set .= "$key = :$key, ";
        }
        $set = rtrim($set, ', ');

        $query = "UPDATE $table SET $set WHERE $condition";
        $stmt = self::pdo()->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }

    // Delete data
    public static  function delete($table, $condition)
    {
        $query = "DELETE FROM $table WHERE $condition";
        $stmt = self::pdo()->prepare($query);
        return $stmt->execute();
    }

    // Select data
    public static  function select($table, $columns = '*', $condition = '', $order = '', $limit = '')
    {
        $query = "SELECT $columns FROM $table";
        if (!empty($condition)) {
            $query .= " WHERE $condition";
        }
        if (!empty($order)) {
            $query .= " ORDER BY $order";
        }
        if (!empty($limit)) {
            $query .= " LIMIT $limit";
        }
        $stmt = self::pdo()->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function lastInsertId(){
        return self::pdo()->lastInsertId();
    }
}
