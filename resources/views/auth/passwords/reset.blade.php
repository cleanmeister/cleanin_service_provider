@extends('layouts.auth')

@section('content')
<passwordreset-page emailadd="{{ app('request')->input('email') }}" token="{{ $token }}" base_url="{{ url('/') }}"></passwordreset-page>
@endsection
