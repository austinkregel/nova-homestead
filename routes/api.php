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
Route::resource('virtual-machines', \Kregel\Homestead\Http\Controllers\VirtualMachine::class);

Route::get('/hypervisors/{hypervisor}/machines', \Kregel\Homestead\Http\Controllers\HypervisorsController::class.'@machines');
Route::post('virtual-machines/{uuid}/enable', \Kregel\Homestead\Http\Controllers\VirtualMachine::class.'@enable');
Route::post('virtual-machines/{uuid}/pause', \Kregel\Homestead\Http\Controllers\VirtualMachine::class.'@pause');
Route::post('virtual-machines/{uuid}/shutdown', \Kregel\Homestead\Http\Controllers\VirtualMachine::class.'@shutdown');
Route::post('virtual-machines/{uuid}/destroy', \Kregel\Homestead\Http\Controllers\VirtualMachine::class.'@destroy');
Route::post('virtual-machines/{uuid}/reboot', \Kregel\Homestead\Http\Controllers\VirtualMachine::class.'@reboot');
Route::post('virtual-machines/{uuid}/force-stop', \Kregel\Homestead\Http\Controllers\VirtualMachine::class.'@forceStop');

Route::get('iso-files/{path}', \Kregel\Homestead\Http\Controllers\Host::class.'@show');
Route::post('network', \Kregel\Homestead\Http\Controllers\Host::class.'@network');
