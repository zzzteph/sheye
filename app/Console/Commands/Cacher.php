<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Response;
use App\Models\Queue;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use App\Models\Service;
use App\Models\User;
class Cacher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create cache for scopes and scope_entries';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }




    public function handle()
    {
		$scopes=Scope::all();
		$cache_timeout=env('CACHE_TIMEOUT',120);
		
		foreach($scopes as $scope)
		{
			Cache::remember('scope_'.$scope->id."_resources", $cache_timeout, function () use ($scope){
				return $scope->resources()->count();
			});
			
			Cache::remember('scope_'.$scope->id."_scope_entries", $cache_timeout, function () use ($scope){
				$scope->scope_entries()->count();
			});
			Cache::remember('scope_'.$scope->id."_screenshots", $cache_timeout, function ()use ($scope) {
				return $scope->screenshots()->count();
			});
			Cache::remember('scope_'.$scope->id."_responses", $cache_timeout, function () use ($scope){
				return  $scope->responses()->count();
			});
			Cache::remember('scope_'.$scope->id."_progress", 120, function () use ($scope){
				return $scope->queues()->where('status','!=', 'done')->count();
			});
			
			Cache::remember('scope_'.$scope->id."_critical_findings", $cache_timeout, function () use ($scope){
				return $scope->outputs()->where('severity','critical')->count();
			});
			
			Cache::remember('scope_'.$scope->id."_high_findings", $cache_timeout, function ()use ($scope) {
				return $scope->outputs()->where('severity','high')->count();
			});
			
			Cache::remember('scope_'.$scope->id."_medium_findings", $cache_timeout, function () use ($scope){
				return $scope->outputs()->where('severity','medium')->count();
			});
			
			Cache::remember('scope_'.$scope->id."_low_findings", $cache_timeout, function ()use ($scope) {
				return $scope->outputs()->where('severity','!=','critical')->where('severity','!=','high')->where('severity','!=','medium')->count();
			});


			
			foreach($scope->scope_entries as $scope_entry)
			{
			Cache::remember('scope_entry_'.$scope_entry->id."_resources", $cache_timeout, function () use ($scope_entry){
				return $scope_entry->resources()->count();
			});
			
			Cache::remember('scope_entry_'.$scope_entry->id."_screenshots", $cache_timeout, function () use ($scope_entry) {
				return $scope_entry->screenshots()->count();
			});
			Cache::remember('scope_entry_'.$scope_entry->id."_responses", $cache_timeout, function () use ($scope_entry) {
				return  $scope_entry->responses()->count();
			});
			Cache::remember('scope_entry_'.$scope_entry->id."_progress", 120, function () use ($scope_entry) {
				return $scope_entry->queues()->where('status','!=', 'done')->count();
			});
			
			Cache::remember('scope_entry_'.$scope_entry->id."_critical_findings", $cache_timeout, function () use ($scope_entry) {
				return $scope_entry->outputs()->where('severity','critical')->count();
			});
			
			Cache::remember('scope_entry_'.$scope_entry->id."_high_findings", $cache_timeout, function () use ($scope_entry) {
				return $scope_entry->outputs()->where('severity','high')->count();
			});
			
			Cache::remember('scope_entry_'.$scope_entry->id."_medium_findings", $cache_timeout, function () use ($scope_entry) {
				return $scope_entry->outputs()->where('severity','medium')->count();
			});
			
			Cache::remember('scope_entry_'.$scope_entry->id."_low_findings", $cache_timeout, function () use ($scope_entry) {
				return $scope_entry->outputs()->where('severity','!=','critical')->where('severity','!=','high')->where('severity','!=','medium')->count();
			});
			}
			
			
			
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		

    }
}
