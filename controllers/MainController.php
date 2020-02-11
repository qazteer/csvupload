<?php

require_once "controllers/GlobalController.php";
require_once "models/Parser.php";

/**
 * Class MainController
 */
class MainController extends GlobalController
{
    /**
     * @return string
     */
    protected function getTitle()
    {
        return "Upload CSV";
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function getMiddle($data)
    {
        $arr["title"] = $this->getTitle();
        $arr["message"] = $data["message"] ?? "";
        $arr["response"] = $data["response"] ?? "";
        $arr["list_error"] = $data["list_error"] ?? "";
        $arr["list_response"] = $data["list_response"] ?? "";

        return $this->getTemplate($arr, "home");
    }

    /**
     * @return string|string[]
     */
    public function indexAction()
    {
        return $this->response(null, 200);
    }

    /**
     * @return string|string[]
     */
    public function parserAction()
    {
        $validation = new Parser();
        $data = $validation->validation();

        if (!empty($data["list_error"])) {
            $data["message"] = "error-message";
            $data["response"] = "Error Importing CSV Data";
            return $this->response($data, 500);
        }

        $data["message"] = "success-message";
        $data["response"] = "CSV Data Imported into the Database.";
        $data["list_response"] = "Inserted: " . $data["result_iu"]["inserts"] .
            ", Updated: " . $data["result_iu"]["updates"] .
            ", Deleted: " . $data["result_d"];
        return $this->response($data, 200);
    }


}