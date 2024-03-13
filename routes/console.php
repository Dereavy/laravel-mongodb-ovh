<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Models\Building;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('logs:clear', function() {
    exec('rm -f ' . storage_path('logs/*.log'));
    exec('rm -f ' . base_path('*.log'));
    $this->comment('Logs have been cleared!');
})->describe('Clear log files');

Artisan::command('logs:backup', function() {
    // exec('mv ' . storage_path('logs/laravel.log').' '.storage_path('logs/backup_'.Carbon\Carbon::now()->timestamp.'.log'));
    exec('mv ' . storage_path('logs/laravel.log').' '.storage_path('logs/backup.log'));
    $this->comment('Logs have been backed up!');
})->describe('Clear log files');

// Delete old backup(s) and backup existing laravel.log 
Artisan::command('logs:refresh', function() {
    Log::info('Refreshing logs : '.Carbon\Carbon::now()->toDateTimeString());
    exec('rm -f ' . storage_path('logs/backup_*.log'));
    exec('mv ' . storage_path('logs/laravel.log').' '.storage_path('logs/backup_'.Carbon\Carbon::now()->timestamp.'.log'));
    exec('rm -f ' . storage_path('logs/laravel.log'));
    exec('touch ' . storage_path('logs/laravel.log'));
    Log::channel('activity')->info('Logs refreshed : '.Carbon\Carbon::now()->toDateTimeString());
    $this->comment('Logs have been refreshed!');
})->describe('Clear log files');


// Run test
Artisan::command('dev:test', function() {
    try {
        // connect to OVHcloud Public Cloud Databases for MongoDB (cluster in version 4.4, MongoDB PHP Extension in 1.8.1)
        $m = new MongoDB\Driver\Manager(env('OVH_MONGODB_URI'));
        echo "Connection to database successfull". PHP_EOL;
        // display the content of the driver, for diagnosis purpose
        var_dump($m);
    }
    catch (Throwable $e) {
        // catch throwables when the connection is not a success
        echo "Captured Throwable for connection : " . $e->getMessage() . PHP_EOL;
    }
    try {
        $building = new Object{
            'name'=>fake()->name;
            'city'=>fake()->city;
        }
        echo "Testing for: ".$building->name. PHP_EOL;
        $mongotest = DB::connection('mongodb')->collection('test')->insert(
            [
                'name'             => $building->name,
                'city'             => $building->city,
            ]
        );
        var_dump($mongotest);

    }
    catch (Throwable $e) {
        echo "Captured Throwable for insert : ". PHP_EOL . $e->getMessage() . PHP_EOL;
    }
    $this->comment('Test finished!');
})->describe('Run Tests');