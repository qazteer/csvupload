<?php

require_once "config/config.php";

class DataBase
{

    private static $db = null;
    private $config;
    private $mysqli;

    /**
     * DataBase constructor.
     */
    private function  __construct()
    {
        $this->config = new Config();
        $this->mysqli = new mysqli($this->config->host, $this->config->user, $this->config->pass, $this->config->dbName);
        $this->mysqli->query("SET NAME 'utf8'");
    }

    /**
     * @return DataBase
     */
    public static function getDB()
    {
        return empty(self::$db) ? self::$db = new DataBase() : self::$db;
    }

    /**
     * @param $table_name
     * @param $fields
     * @param $values
     * @param $on
     * @return bool|mysqli_result
     */
    public function insertUpdate($table_name, $fields, $values, $on)
    {
        $query = "INSERT INTO `".$table_name."` (";

        foreach ($fields as $field) {
            $query .= "`".$field."`,";
        }
        $query = substr($query, 0, -1);

        $query .=") VALUES ";

        foreach ($values as $value) {
            $query .= $value .",";
        }
        $query = substr($query, 0, -1);

        $query .=" ON DUPLICATE KEY UPDATE ";
        foreach ($on as $field => $value) {
            $query .= "".$field." = ".$value.",";
        }
        $query = substr($query, 0, -1);

        $this->mysqli->query($query);

        $total_rows_affected = $this->mysqli->affected_rows;

        list($rec, $dupes, $warns) = sscanf($this->mysqli->info, "Records: %d Duplicates: %d Warnings: %d");
        $result["inserts"] = $total_rows_affected - ($dupes * 2);
        $result["updates"] = ($total_rows_affected - $result["inserts"])/2;

        return $result;
    }

    /**
     * @param string $table_name
     * @param array $ids
     * @return bool|mysqli_result
     */
    public function removeRecord($table_name, $ids)
    {
        $query = "DELETE FROM `".$table_name."` WHERE `uid` NOT IN (" . implode(",", $ids) . ")";
        $this->mysqli->query($query);

        return $this->mysqli->affected_rows;//print_r($total_rows_affected);exit;
    }

    public function __destruct()
    {
        if($this->mysqli) $this->mysqli->close();
    }
}