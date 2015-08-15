<?php

namespace App\Services;

use DB;

class AverageConsumptionService
{
    public static function getAverageConsumption($vehicleId)
    {
        $averageConsumptionQuery =
            "SELECT F1.litres / (F1.mileage - COALESCE(( " .
            "       SELECT F2.mileage " .
            "       FROM   ta_fillups F2 " .
            "       WHERE  F2.fillup_date < F1.fillup_date " .
            "       AND    F2.vehicle_id = F1.vehicle_id " .
            "       ORDER BY F2.fillup_date DESC " .
            "       LIMIT 1), F1.mileage)) * 100 AS consumption " .
            "FROM  ta_fillups F1 " .
            "WHERE F1.vehicle_id = :vehicleId " .
            "ORDER BY F1.fillup_date DESC LIMIT 1;";

        $result = DB::select($averageConsumptionQuery, ['vehicleId' => $vehicleId]);

        if (count($result) > 0) {
            return $result[0]->consumption;
        }
        else {
            return 0;
        }
    }

    public static function getFillupConsumption($fillupId)
    {
        $fillupConsumptionQuery =
            "SELECT F1.litres / (F1.mileage - COALESCE(( " .
            "           SELECT F2.mileage " .
            "           FROM   ta_fillups F2 " .
            "           WHERE  F2.fillup_date < F1.fillup_date " .
            "           AND    F2.vehicle_id = F1.vehicle_id " .
            "           ORDER BY F2.fillup_date DESC " .
            "           LIMIT 1), F1.mileage)) * 100 AS consumption " .
            "FROM  ta_fillups F1 " .
            "WHERE F1.id = :fillupId " .
            "ORDER BY F1.fillup_date DESC LIMIT 1; ";

        $result = DB::select($fillupConsumptionQuery, ['fillupId' => $fillupId]);

        if (count($result) > 0) {
            return $result[0]->consumption;
        }
        else {
            return 0;
        }
    }
}