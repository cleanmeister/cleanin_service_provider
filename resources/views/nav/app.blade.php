@guest

@else
@if(Auth::user()->role_id == 1)
<li class="nav-item">
    <a class="nav-link" href="">Services</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="">Companies</a>
</li>

<li class="nav-item">
    <a class="nav-link" href="">Cleaners</a>
</li>

<li class="nav-item">
    <a class="nav-link" href="">Cleaner Schedules</a>
</li>

<li class="nav-item">
    <a class="nav-link" href="">Clients</a>
</li>

<li class="nav-item">
    <a class="nav-link" href="">Cleaner Schedule Client</a>
</li>
@endif
@if(Auth::user()->role_id == 2)
<li class="nav-item">
    <a class="nav-link" href="">Services</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="">Cleaners</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="">Cleaner Schedules</a>
</li>
@endif
@if(Auth::user()->role_id == 3)
<li class="nav-item">
    <a class="nav-link" href="">Services</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="">Cleaner Schedules</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="">Cleaner Schedule Client</a>
</li>
@endif
@if(Auth::user()->role_id == 4)
<li class="nav-item">
    <a class="nav-link" href="{{ url('/') }}/clienthome">Book Schedule</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ url('/') }}/services">Services</a>
</li>
@endif
@endguest