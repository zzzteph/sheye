@include('include.header')
<h1 class="title">Dashboard</h1>
{{ $scopes->links() }}
@foreach ($scopes as $scope)
@if(($loop->iteration-1)%3==0 && !$loop->first)
</div>
<div class="columns is-multiline">
   @endif
   @if($loop->first)
   <div class="columns is-multiline">
      @endif
      <div class="column is-4-desktop">
         <div class="box">
            <h2 class="mb-2 is-size-3 is-size-4-mobile has-text-weight-bold">  <a href="{{route('scope-entry-list',['scope_id' => $scope->id])}}">{{ $scope->name }}</a></h2>
            <span><small class="has-text-grey-dark">{{ $scope->created_at }}</small></span>
            <table class="table is-fullwidth has-text-centered">
               <thead>
                  <th><span class="icon"><i class="fas fa-exclamation-triangle"></i></span></th>
                  <th><span class="icon"><i class="fas fa-exclamation-circle"></i></span></th>
                  <th><span class="icon"><i class="fas fa-exclamation"></i></span></th>
                  <th><span class="icon"><i class="fas fa-info"></i></span></th>
               </thead>
               <tbody>
                  <tr>
                     <td>
                        <a href="{{route('scope-findings',['scope_id' => $scope->id,'severity'=>'critical'])}}"> {{ $scope->critical_findings_count }}</a>
                     </td>
                     <td>
                        <a href="{{route('scope-findings',['scope_id' => $scope->id,'severity'=>'high'])}}"> {{ $scope->high_findings_count }}</a>
                     </td>
                     <td>
                        <a href="{{route('scope-findings',['scope_id' => $scope->id,'severity'=>'medium'])}}"> {{ $scope->medium_findings_count }}</a>
                     </td>
                     <td>
                        <a href="{{route('scope-findings',['scope_id' => $scope->id,'severity'=>'low'])}}"> {{ $scope->low_findings_count }}</a>
                     </td>
                  </tr>
               </tbody>
            </table>
            <table class="table is-fullwidth ">
               <tbody>
			   
			                     <tr>
                     <td>Entries</td>
                     <td><a href="{{route('scope-entry-list',['scope_id' => $scope->id])}}">{{ $scope->scope_entries_count }}</a></td>
                     <td></td>
                  </tr>
			   
                  <tr>
                     <td>Resources</td>
                     <td><a href="{{route('resources-list',['id' => $scope->id])}}">{{ $scope->resources_count }}</a></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Screenshots</td>
                     <td><a href="{{route('scope-screenshots-list',['scope_id' => $scope->id])}}">{{ $scope->screenshots_count }}</a></td>
                     <td></td>
                  </tr>
                  @if($scope->progress!==FALSE && $scope->progress!==100 && $scope->progress!==0)
                  <tr>
                     <td>Tasks</td>
                     <td><a href="{{route('scopes-queues-list',['scope_id' => $scope->id])}}">{{$scope->progress}}</a>
                     </td>
                     <td>
                        <form method="POST" action="{{route('scope-scan-stop',['scope_id' => $scope->id])}}">
                           @method('delete')
                           @csrf
                           <div class="control">
                              <button class="button is-black is-small">
                              <span class="icon is-small">
                              <i class="fas fa-trash-alt"></i>
                              </span>
                              </button>
                           </div>
                        </form>
                     </td>
                  </tr>
                  @else
                  <tr>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                     <td>&nbsp;</td>
                  </tr>
                  @endif
               </tbody>
            </table>
            <form method="POST" action="{{route('templates-launch')}}">
               
			   <div class="field has-addons ">
                  @csrf
					<input type="hidden" name="scope" value="{{$scope->id}}">
                  <div class="control is-expanded">
                     <div class="select is-expanded is-fullwidth is-small">
                        <select name="template" >
                           <option value="" disabled selected></option>
                           @foreach ($templates as $template)
                           <option value="{{$template->id}}">{{$template->name}}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="control">
                     <button class="button is-black is-small">
                     <span class="icon is-small">
                     <i class="fas fa-play"></i>
                     </span>
                     </button>
                  </div>
               </div>
            </form>
         </div>
      </div>
      @if($loop->last)
   </div>
   @endif 
   @endforeach
   </tbody>
   </table>
</div>
{{ $scopes->links() }}
@include('include.footer')