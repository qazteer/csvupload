<?php

require_once "config/config.php";
require_once "db/database.php";

/**
 * Class User
 */
class Parser
{
    private $config;
    private $db;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->config = new Config();
        $this->db = DataBase::getDB();
    }

    /**
     * @return array
     */
    public function validation()
    {
        $error = [];
        $data["list_error"] = "";

        if (isset($_POST["import"])) {

            $fileName = $_FILES["file"]["tmp_name"];

            if ($_FILES["file"]["size"] > 0) {

                $file = fopen($fileName, "r");

                $ids = [];
                $fields = ['uid', 'firstName', 'lastName', 'birthDay', 'dateChange', 'description'];
                $values = [];
                $on = [
                    'dateChange' => "VALUES(dateChange)"
                ];

                while (($column = fgetcsv($file, 10000, ",")) !== false) {

                    $str_values = "('" .
                        $column[0] . "', '" .
                        addslashes($column[1]) . "', '" .
                        addslashes($column[2]) . "', '" .
                        $column[3] . "', '" .
                        $column[4] . "', '" .
                        addslashes($column[5]) . "' )";

                    array_push($values, $str_values);
                    array_push($ids, $column[0]);
                }

                $result_iu = $this->db->insertUpdate('users', $fields, $values, $on);
                $result_d = $this->db->removeRecord("users", $ids);

                if (empty($result_iu)) {
                    $error[] = "Problem in Importing CSV Data";

                }
            }
        }

        $data["list_error"] = !empty($error) ? implode("<br>", $error) : "";
        $data["result_iu"] = !empty($result_iu) ? $result_iu : "";
        $data["result_d"] = !empty($result_d) ? $result_d : 0;

        return $data;
    }

}
