@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">System Setting</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.system.settings')}}">System Setting</a></li>
                        <li class="breadcrumb-item active" aria-current="page">System Setting</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')

@endsection

