@guest

@else
@if(Auth::user())

<li class="nav-item"> 
    <a class="nav-link" href="{{ route('service.index') }}">Services</a>
</li>
<li class="nav-item">
    <a href="{{ url('/') }}/schedules/{{Auth::user()->id}}" class="nav-link">Book Schedules</a>
</li>
@endif
@endguest