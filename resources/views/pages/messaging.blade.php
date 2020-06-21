@extends('layouts.admin')
@section('content')
    <messaging-page email_request="{{$email}}" base_url="{{ url('/') }}"></messaging-page>
@endsection
