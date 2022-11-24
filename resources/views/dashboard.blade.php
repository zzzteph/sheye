@include('include.header')
<h1 class="title">Dashboard</h1>
{{ $scopes->links() }}
@foreach ($scopes as $scope)
@if($loop->iteration%4==0 && !$loop->first)
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
                        <a href="{{route('scope-findings',['scope_id' => $scope->id,'severity'=>'critical','type'=>'nuclei'])}}"> {{ $scope->outputs()->where('severity','critical')->count() }}</a>
                     </td>
                     <td>
                        <a href="{{route('scope-findings',['scope_id' => $scope->id,'severity'=>'high','type'=>'nuclei'])}}"> {{ $scope->outputs()->where('severity','high')->count() }}</a>
                     </td>
                     <td>
                        <a href="{{route('scope-findings',['scope_id' => $scope->id,'severity'=>'medium','type'=>'nuclei'])}}"> {{ $scope->outputs()->where('severity','medium')->count() }}</a>
                     </td>
                     <td>
                        <a href="{{route('scope-findings',['scope_id' => $scope->id,'severity'=>'medium','type'=>'nuclei'])}}"> {{ $scope->outputs()->where('severity','medium')->where('severity','high')->where('severity','critical')->count() }}</a>
                     </td>
                  </tr>
               </tbody>
            </table>
            <table class="table is-fullwidth ">
               <tbody>
			   
			                     <tr>
                     <td>Entries</td>
                     <td><a href="{{route('scope-entry-list',['scope_id' => $scope->id])}}">{{ $scope->scope_entries()->count() }}</a></td>
                     <td></td>
                  </tr>
			   
                  <tr>
                     <td>Resources</td>
                     <td><a href="{{route('resources-list',['id' => $scope->id])}}">{{ $scope->resources()->count() }}</a></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Screenshots</td>
                     <td><a href="{{route('scope-screenshots-list',['scope_id' => $scope->id])}}">{{ $scope->screenshots_count }}</a></td>
                     <td></td>
                  </tr>
                  <tr>
                     <td>Services</td>
                     <td><a href="{{route('services-list',['id' => $scope->id])}}">{{ $scope->services()->count() }}</a></td>
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
                     <td>Tasks</td>
                     <td></td>
                     <td></td>
                  </tr>
                  @endif
               </tbody>
            </table>
            <form method="POST" action="{{route('scope-scan-launch',['scope_id' => $scope->id])}}">
               <div class="field has-addons ">
                  @csrf
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