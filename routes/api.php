<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/hypervisors', \Kregel\Supervisor\Http\Controllers\HypervisorsController::class);


Route::group(['prefix' => '/hypervisors/{hypervisor}',], function () {
    Route::get('machines', \Kregel\Supervisor\Http\Controllers\VirtualMachine::class.'@index');
    Route::resource('virtual-machines', \Kregel\Supervisor\Http\Controllers\VirtualMachine::class);

    Route::group(['prefix' => '/virtual-machines/{uuid}',], function () {
        Route::post('enable', \Kregel\Supervisor\Http\Controllers\VirtualMachine::class . '@enable');
        Route::post('pause', \Kregel\Supervisor\Http\Controllers\VirtualMachine::class . '@pause');
        Route::post('shutdown', \Kregel\Supervisor\Http\Controllers\VirtualMachine::class . '@shutdown');
        Route::post('destroy', \Kregel\Supervisor\Http\Controllers\VirtualMachine::class . '@destroy');
        Route::post('reboot', \Kregel\Supervisor\Http\Controllers\VirtualMachine::class . '@reboot');
        Route::post('force-stop', \Kregel\Supervisor\Http\Controllers\VirtualMachine::class . '@forceStop');
    });
});

Route::get('iso-files/{path}', \Kregel\Supervisor\Http\Controllers\Host::class.'@show');
Route::post('network', \Kregel\Supervisor\Http\Controllers\Host::class.'@network');
