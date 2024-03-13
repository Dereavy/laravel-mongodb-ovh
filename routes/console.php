<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Faker\Factory as Faker;

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


// Run test
Artisan::command('dev:test', function() {
    // try {
    //     $dbs = DB::connection('mongodb')->listCollections();
    //     $this->comment( json_encode($dbs));
    //     // DB::connection()->getMongoClient()->listDatabases();
    // } catch (\Exception $e) {
    //     //dd( $e->getMessage() );
    // }

    exec('php artisan config:cache');
    exec('php artisan cache:clear');
    $faker = Faker::create();
    // try {
    //     // connect to OVHcloud Public Cloud Databases for MongoDB (cluster in version 4.4, MongoDB PHP Extension in 1.8.1)
    //     $uri = env('OVH_MONGODB_URI');
    //     echo 'URI:'.$uri; 
    //     $m = new MongoDB\Driver\Manager($uri);
    //     // $m =  DB::connection('mongodb')->getPDO();
    //     echo "Connection to database successfull". PHP_EOL;
    //     // display the content of the driver, for diagnosis purpose
    //     var_dump($m);
    // }
    // catch (Throwable $e) {
    //     // catch throwables when the connection is not a success
    //     echo "Captured Throwable for connection : " . $e->getMessage() . PHP_EOL;
    // }
    try {
        $data =(object) [
            'name'=>$faker->name(),
            'latitude'=>$faker->latitude(),
            'longitude'=>$faker->longitude(),
        ];
        echo "Saving test data: ".$data->name.'|'.$data->latitude.'|'.$data->longitude. PHP_EOL;
        $mongotest = DB::connection('mongodb')->collection('test')->insert(
            [
                'name'             => $data->name,
                'latitude'         => $data->latitude,
                'longitude'        => $data->longitude,
            ]
        );
        var_dump($mongotest);
    }
    catch (Throwable $e) {
        echo "Captured Throwable for insert : ". PHP_EOL . $e->getMessage() . PHP_EOL;
    }
    $this->comment('Test finished!');
})->describe('Run Tests');