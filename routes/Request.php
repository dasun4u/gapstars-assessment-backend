<?php
/**
 * Created by PhpStorm
 * User: Dasun Dissanayake
 * Date: 2021-11-27
 * Time: 6:37 PM
 */

include_once 'IRequest.php';

class Request implements IRequest
{
    function __construct()
    {
        $this->bootstrapSelf();
    }

    private function bootstrapSelf()
    {
        foreach ($_SERVER as $key => $value) {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    /**
     * Change to camel case
     * @param $string
     * @return array|string|string[]
     */
    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);

        foreach ($matches[0] as $match) {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }

        return $result;
    }

    /**
     * Get Request Body
     * @return array|void
     */
    public function getBody()
    {
        if ($this->requestMethod === "GET") {
            $body = [];
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            return $body;
        }


        if ($this->requestMethod == "POST") {
            $body = [];
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            return $body;
        }
    }
}