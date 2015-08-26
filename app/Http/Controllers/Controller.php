<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public static function get_last_query() {
        $queries = DB::getQueryLog();
        $sql = end($queries);

        if( ! empty($sql['bindings']))
        {
            $pdo = DB::getPdo();
            foreach($sql['bindings'] as $binding)
            {
                $sql['query'] =
                    preg_replace('/\?/', $pdo->quote($binding),
                        $sql['query'], 1);
            }
        }

        return $sql['query'];
    }
}
