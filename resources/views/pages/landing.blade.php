@extends('layouts.home')
@section('content')
	@guest
		<landing base_url="{{ url('/') }}" isguest="true"></landing>
	@else
		<landing base_url="{{ url('/') }}" user_email="{{ Auth::user()->email }}" logout_action="{{ route('logout') }}" logout="{{ __('Logout') }}" csrf="{{ csrf_token() }}"></landing>
	@endguest
@endsection