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

Route::get('/hypervisors', \Kregel\Homestead\Http\Controllers\HypervisorsController::class);


Route::group(['prefix' => '/hypervisors/{hypervisor}',], function () {
    Route::get('machines', \Kregel\Homestead\Http\Controllers\VirtualMachine::class.'@index');
    Route::resource('virtual-machines', \Kregel\Homestead\Http\Controllers\VirtualMachine::class);

    Route::group(['prefix' => '/virtual-machines/{uuid}',], function () {
        Route::post('enable', \Kregel\Homestead\Http\Controllers\VirtualMachine::class . '@enable');
        Route::post('pause', \Kregel\Homestead\Http\Controllers\VirtualMachine::class . '@pause');
        Route::post('shutdown', \Kregel\Homestead\Http\Controllers\VirtualMachine::class . '@shutdown');
        Route::post('destroy', \Kregel\Homestead\Http\Controllers\VirtualMachine::class . '@destroy');
        Route::post('reboot', \Kregel\Homestead\Http\Controllers\VirtualMachine::class . '@reboot');
        Route::post('force-stop', \Kregel\Homestead\Http\Controllers\VirtualMachine::class . '@forceStop');
    });
});

Route::get('iso-files/{path}', \Kregel\Homestead\Http\Controllers\Host::class.'@show');
Route::post('network', \Kregel\Homestead\Http\Controllers\Host::class.'@network');
