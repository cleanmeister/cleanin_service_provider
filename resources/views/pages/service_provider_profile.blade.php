
@extends('layouts.admin')
@section('content')
    <service-provider-profile declinedrequest="{{ $DeclinedRequest }}" base_url="{{ url('/') }}"></service-provider-profile>
@endsection
