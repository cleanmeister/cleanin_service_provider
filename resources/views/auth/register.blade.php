@extends('layouts.auth')
@section('content')
@isset($serviceProvider)
    <registersp-page base_url="{{ url('/') }}"></registersp-page>
@else
    <register-page base_url="{{ url('/') }}"></register-page>
@endisset

@endsection
