
@extends('layouts.admin')
@section('content')

    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Cleaner Profile
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-12">
                                <img src="" alt="profile image" width="100%" height="200px">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label for="" class="form-label text-capitalize">
                                    firstname
                                </label>
                            </div>
                            <div class="col-auto">
                                {{ $user->profile->firstname }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label for="" class="form-label text-capitalize">
                                    middlename
                                </label>
                            </div>
                            <div class="col-auto">
                                {{ $user->profile->middlename }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label for="" class="form-label text-capitalize">
                                    lastname
                                </label>
                            </div>
                            <div class="col-auto">
                                {{ $user->profile->lastname }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label for="" class="form-label text-capitalize">
                                    address
                                </label>
                            </div>
                            <div class="col-auto">
                                {{ $user->profile->address }}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-3 text-right">
                                <label for="" class="form-label text-capitalize">
                                    mobile number
                                </label>
                            </div>
                            <div class="col-auto">
                                {{ $user->profile->mobile_nmber }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  
    </div>

@endsection
