<?php
/**
 * Created by PhpStorm
 * User: Dasun Dissanayake
 * Date: 2021-11-27
 * Time: 7:21 PM
 */

/**
 * Read the CSV File and return an array
 * @return array
 */
function readCSV(): array
{
    $csvFile = file(CSV_PATH);
    $data = [];
    if (count($csvFile) > 0) {
        $titleArray = explode(";", str_replace("\n", "", $csvFile[0]));
        for ($index = 1; $index < count($csvFile); $index++) {
            $rowData = explode(";", str_replace("\n", "", $csvFile[$index]));
            $data[] = [
                $titleArray[0] => intval($rowData[0]),
                $titleArray[1] => strtotime($rowData[1]),
                $titleArray[2] => $rowData[2],
                $titleArray[3] => $rowData[3],
                $titleArray[4] => $rowData[4]
            ];
        }
    }

    return $data;
}

/**
 * Data filter by date range
 * @param $data
 * @param $min
 * @param $max
 * @return array
 */
function dataFilterByDateRange($data, $min, $max): array
{
    return array_filter($data, function ($row) use ($min, $max) {
        return $row["created_at"] >= $min && $row["created_at"] < $max;
    });
}

/**
 * @param string $status
 * @param string $message
 * @param array|null $data
 * @param int $code
 * @return false|string
 */
function apiResponse(string $status = "success", string $message = "Done", array $data = null, int $code = 200): string
{
    $result = [
        "status" => $status,
        "message" => $message,
        "data" => $data
    ];
    http_response_code($code);
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json; charset=utf-8');
    return json_encode($result);
}

if (!function_exists('dd')) {
    /**
     * Dump and Die for testing
     */
    function dd()
    {
        echo "<pre>";
        foreach (func_get_args() as $x) {
            var_dump($x);
        }
        echo "</pre>";
        die;
    }
}
