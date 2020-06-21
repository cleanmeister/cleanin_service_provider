@guest

@else
@if(Auth::user())
<li class="nav-item"> 
    <a class="nav-link" href="{{ route('service.index') }}">Services</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('cleaner.index') }}">Cleaners</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('cleaner_schedule.index') }}">Cleaner Schedules</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('cleaner_schedule_client.index') }}">Cleaner Schedule Client</a>
</li>
@endif
@endguest