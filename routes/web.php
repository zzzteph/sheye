<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\ScansController;
use App\Http\Controllers\ScopesController;
use App\Http\Controllers\ScopeEntryController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\QueuesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommandsController;
use App\Http\Controllers\FindingsController;
use App\Http\Controllers\ScannersController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\ServicesController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
 




Route::middleware(['auth'])->group(function () {
Route::get('/dashboard',[ScopesController::class, 'index'])->name('dashboard');

Route::get('/scopes',[ScopesController::class, 'index'])->name('scopes');
Route::get('/scopes/new',[ScopesController::class, 'new'])->name('scopes-add-new-template');
Route::post('/scopes/create',[ScopesController::class, 'create'])->name('scopes-create');


Route::put('/scopes/{id}/edit',[ScopesController::class, 'update'])->name('scopes-update');

Route::put('/scopes/{scope_id}/bulk',[ScopesController::class, 'bulk_update'])->name('scope-entry-bulk-update');
Route::put('/scopes/{scope_id}/{id}',[ScopeEntryController::class, 'update'])->name('scope-entry-update');

Route::delete('/scopes/{id}',[ScopesController::class, 'delete'])->name('scopes-delete');


Route::get('/scopes/{scope_id}/entries',[ScopeEntryController::class, 'list'])->name('scope-entry-list');

Route::delete('/scopes/{scope_id}/{id}',[ScopeEntryController::class, 'delete'])->name('scope-entry-delete');




//QUEUES
Route::get('/scopes/{scope_id}/queues/{type?}',[QueuesController::class, 'scope_queues_list'])->name('scopes-queues-list');
Route::get('/queues',[QueuesController::class, 'list'])->name('queues-list');
Route::delete('/queues/{queue_id}',[QueuesController::class, 'delete'])->name('queues-delete');


Route::get('/scopes/{scope_id}/{scope_entry_id}/screenshots',[ResponseController::class, 'scope_entry_screenshots'])->name('scope_entry-screenshots-list');


Route::get('/scopes/{id}/resources',[ResourceController::class, 'scope_resources_list'])->name('resources-list');
Route::get('/scopes/{scope_id}/entries/{id}',[ResourceController::class, 'scope_entries_resources_list'])->name('scope_entries-resources-list');
Route::get('/scopes/{scope_id}/{scope_entry_id}/{resource_id}',[ResourceController::class, 'view'])->name('resources-view');

Route::post('/search/scope/{scope_id}',[SearchController::class, 'scope_entries_search'])->name('scopes-search');
Route::post('/search/entry/{scope_entry_id}',[SearchController::class, 'resources_search'])->name('resources-search');

Route::get('/tools/{type}',[CommandsController::class, 'list'])->name('commands-list');
Route::post('/tools',[CommandsController::class, 'insert'])->name('commands-create');
Route::get('/reports/{id}',[CommandsController::class, 'view'])->name('commands-view');
Route::delete('/reports/{id}',[CommandsController::class, 'delete'])->name('commands-delete');




Route::get('/schedule',[ScheduleController::class, 'list'])->name('schedule-list');
Route::post('/schedule',[ScheduleController::class, 'insert'])->name('schedule-insert');
Route::delete('/schedule/{schedule_id}',[ScheduleController::class, 'delete'])->name('schedule-delete');

Route::get('/scopes/{scope_id}/screenshots',[ResponseController::class, 'scope_screenshots'])->name('scope-screenshots-list');


 
Route::post('/scan/{scope_id}',[ScansController::class, 'scope_scan_launch'])->name('scope-scan-launch');
Route::delete('/scan/{scope_id}',[ScansController::class, 'scope_scan_stop'])->name('scope-scan-stop');


Route::get('/findings/{scope_id}/{severity}',[FindingsController::class, 'scope_list'])->name('scope-findings');
Route::get('/findings/{scope_id}/{scope_entry_id}/{severity}',[FindingsController::class, 'scope_entry_list'])->name('scope-entry-findings');
 
Route::get('/jobs/{type}',[JobsController::class, 'monitor'])->name('view-jobs');


//Scanners management
Route::get('/scanners/types',[ScannersController::class, 'types'])->name('scanners-types');
Route::get('/scanners/list',[ScannersController::class, 'list'])->name('scanners-list');
Route::get('/scanners/new',[ScannersController::class, 'new'])->name('scanners-new');
Route::post('/scanners',[ScannersController::class, 'create'])->name('scanners-create');
Route::get('/scanners/{id}',[ScannersController::class, 'view'])->name('scanners-view');
Route::put('/scanners/{id}',[ScannersController::class, 'update'])->name('scanners-update');
Route::delete('/scanners/{id}',[ScannersController::class, 'delete'])->name('scanners-delete');
  



Route::get('/templates/new',[TemplateController::class, 'new'])->name('templates-new');
Route::get('/templates/list',[TemplateController::class, 'list'])->name('templates-list');
Route::post('/templates/launch',[TemplateController::class, 'launch'])->name('templates-launch');

Route::post('/templates',[TemplateController::class, 'create'])->name('templates-create');
Route::get('/templates/{id}',[TemplateController::class, 'view'])->name('templates-view');
Route::put('/templates/{id}',[TemplateController::class, 'update'])->name('templates-update');
Route::delete('/templates/{id}',[TemplateController::class, 'delete'])->name('templates-delete');




Route::get('/services/{id}',[ServicesController::class, 'list'])->name('services-list');





});
Route::get('/', function () {
    return view('main');
})->name('main');



Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authentificate'])->name('authentificate');

Route::delete('logout', [AuthController::class, 'logout'])->name('logout');





