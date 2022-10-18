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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
 




Route::middleware(['auth'])->group(function () {
Route::get('/dashboard',[ScopesController::class, 'index'])->name('dashboard');
Route::get('/scopes',[ScopesController::class, 'index'])->name('scopes');
Route::get('/scopes/new',[ScopesController::class, 'new'])->name('scopes-add-new-template');
Route::post('/scopes/create',[ScopesController::class, 'create'])->name('scopes-create');

Route::get('/scopes/{id}/edit',[ScopesController::class, 'edit'])->name('scopes-edit');


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


 
Route::post('/scan/{scope_id}/{type}',[ScansController::class, 'scope_scan'])->name('scope-scan');
Route::post('/scan/{scope_id}/{scope_entry_id}/{type}',[ScansController::class, 'scope_entry_scan'])->name('scope-entry-scan');
Route::post('/scan/{scope_id}/{scope_entry_id}/{$resource_id}/{type}',[ScansController::class, 'resource_scan'])->name('resource-scan');

Route::get('/findings/{scope_id}/{severity}/{type}',[FindingsController::class, 'scope_list'])->name('scope-findings');
Route::get('/findings/{scope_id}/{scope_entry_id}/{severity}/{type}',[FindingsController::class, 'scope_entry_list'])->name('scope-entry-findings');
 
Route::get('/jobs/{type}',[JobsController::class, 'monitor'])->name('view-jobs');





});
Route::get('/', function () {
    return view('main');
})->name('main');



Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'authentificate'])->name('authentificate');

Route::delete('logout', [AuthController::class, 'logout'])->name('logout');





