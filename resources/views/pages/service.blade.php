
@extends('layouts.admin')
@section('content')

    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-auto">
                                <h4>{{ $service->name }}</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                            <h6>{{ $service->desc }}</h6>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                            <h6>{{ $service->rate }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-hover table-striped">
                            <thead>
                                <tr>

                                    <th>Cleaner</th>
                                    <th>Address</th>
                                    <th>Mobile Number</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cleaners as $cleaner)
                                <tr>
                                    <td>{{ $cleaner->lastname }}, {{ $cleaner->firstname }} {{ $cleaner->middlename }}</td>
                                    <td>{{ $cleaner->address }}</td>
                                    <td>{{ $cleaner->mobile_number }}</td>
                                    <td class="text-right">
                                    <a class="btn btn-sm btn-primary" href="{{ url('/') }}/service/cleaner/{{ $cleaner->id }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
  
    </div>

@endsection
