<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Response;
use Illuminate\Support\Collection;
class SearchController extends Controller
{


	
	public function scope_entries_search(Request $request,$scope_id)
	{
				$validated = $request->validate([
			'term' => 'required|max:64|min:4'
		]);
		
		
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		$responses=$scope->responses()
		 ->where(function($query) use ($request) {
                $query->whereFullText('responses.title',$request->input('term'))->orWhereFullText('responses.source', $request->input('term'));
            })->limit(50)->get();

		return view('search.scope',['responses'=>$responses,'scope'=>$scope]);

	}
	
	
		public function resources_search(Request $request,$scope_entry_id)
	{
					$validated = $request->validate([
			'term' => 'required|max:64|min:4'
		]);
		
		$scope_entry=ScopeEntry::where('id',$scope_entry_id)->firstOrFail();
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_entry->scope_id)->firstOrFail();
		$responses=$scope_entry->responses()
		 ->where(function($query) use ($request) {
                $query->whereFullText('responses.title', $request->input('term'))->orWhereFullText('responses.source', $request->input('term'));
            })->limit(50)->get();

		return view('search.scope_entry',['responses'=>$responses,'scope_entry'=>$scope_entry,'scope'=>$scope]);

	}


}