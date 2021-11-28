<?php
/**
 * Created by PhpStorm
 * User: Dasun Dissanayake
 * Date: 2021-11-27
 * Time: 6:30 PM
 */

include_once 'Request.php';
include_once 'Router.php';
try {
    $router = new Router(new Request);

    $router->get('/', function () {
        return apiResponse("success", "API Home", null);
    });

    $router->get('/weekly-retention-data', function ($request) {

        $result = [];
        $requestData = $request->getBody();
        $startDate = $requestData["start_date"] ?? null;
        $endDate = $requestData["end_date"] ?? null;

        if ($startDate != null && $endDate != null) {
            try {
                $csvData = readCSV();
            } catch (Error $e) {
                return apiResponse("error", $e->getMessage(), null, 500);
            }

            for ($date = strtotime($startDate); $date <= strtotime($endDate); $date = strtotime("+1 week", $date)) {
                $nextWeek = strtotime("+1 week", $date);
                $filteredData = dataFilterByDateRange($csvData, $date, $nextWeek);
                $filteredTotalDataCount = count($filteredData);
                $onBoardingSteps = ON_BOARDING_STEPS;
                $dataSet = [];
                foreach ($onBoardingSteps as $step) {
                    $currentStepPassedData = array_filter($filteredData, function ($value) use ($step) {
                        return $value["onboarding_perentage"] >= $step;
                    });
                    $percentage = intval(round((count($currentStepPassedData) / $filteredTotalDataCount) * 100));
                    $dataSet[] = [intval($step), $percentage];
                }
                $result[] = ["name" => date("Y-m-d", $date), "data" => $dataSet];
            }
        }

        return apiResponse("success", "Data Set", $result);
    });

} catch (Error $e) {
    return apiResponse("error", $e->getMessage(), null, 500);
}