<?php

/**
 * Class GlobalController
 */
abstract class GlobalController
{
    protected $method = '';
    protected $action = '';

    /**
     * ApiController constructor.
     */
    public function __construct() {

        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @param $arr
     * @param $name_tpl
     * @return string|string[]
     */
    protected function getTemplate($arr, $name_tpl)
    {
        if (empty($arr) || empty($name_tpl)) {
            return file_get_contents("views/home.tpl");
        }

        $text = file_get_contents("views/" . $name_tpl . ".tpl");
        $search = array();
        $replace = array();
        $i=0;

        foreach($arr as $key => $value){
            $search[$i] = "%$key%";
            $replace[$i] = $value;
            $i++;
        }
        return str_replace($search, $replace, $text);
    }

    public function run() {

        $this->action = $this->getAction();

        if (method_exists($this, $this->action)) {
            return $this->{$this->action}();
        } else {
            throw new RuntimeException('Invalid Method', 405);
        }
    }

    /**
     * @return string|null
     */
    protected function getAction()
    {
        $method = $this->method;
        switch ($method) {
            case 'GET':
                return 'indexAction';
                break;
            case 'POST':
                return 'parserAction';
                break;
            default:
                return null;
        }
    }

    /**
     * @param array|object $data
     * @param int $status
     * @return string|string[]
     */
    protected function response($data = null, $status = 500) {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
        $arr["title"] = $this->getTitle();
        $arr["content"] = $this->getMiddle($data);
        return $this->getTemplate($arr, "main");
    }

    /**
     * @param int $code
     * @return string
     */
    private function requestStatus($code) {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code]) ? $status[$code] : $status[500];
    }

    abstract protected function getTitle();

    abstract protected function getMiddle($data);

    abstract protected function indexAction();

    abstract protected function parserAction();
}