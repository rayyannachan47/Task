@extends('Layout.admin')
@section('content')
@include('User.navbar')

@php
    $imagePath = 'public/images/' . $getDetails[0]->email . '/' . $getDetails[0]->image;
@endphp

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>{{ $getDetails[0]->name }}'s Profile</h3>
                </div>
                <div class="card-body">
                    <div class="text-center">                        

                        @if(File::exists($imagePath))
                            <img src="{{ asset($imagePath) }}" alt="User Image" class="img-thumbnail" width="150">
                        @else
                            <img src="{{ asset('public/asset/img/Sample_User_Icon.png') }}" alt="Default User Image" class="img-thumbnail" width="150">
                        @endif
                        
                    </div>
                    <ul class="list-group list-group-flush mt-3">
                        <li class="list-group-item"><strong>Name:</strong> {{ $getDetails[0]->name }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $getDetails[0]->email }}</li>
                        <li class="list-group-item"><strong>Date of Birth:</strong> {{ $getDetails[0]->dob }}</li>
                        <li class="list-group-item"><strong>Contact:</strong> {{ $getDetails[0]->contact }}</li>
                        <li class="list-group-item"><strong>Address:</strong> {{ $getDetails[0]->address }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>