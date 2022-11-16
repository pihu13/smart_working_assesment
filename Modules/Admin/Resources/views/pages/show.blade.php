@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>CMS Page Details</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.pages.list')}}">CMS Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$page->title}}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <table id="dataTable" class="table table-striped table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th> Title:</th>
                                        <td>
                                            {{ $page->title }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Description:</th>
                                        <td>
                                            {!! @$page->description !!}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th> Created:</th>
                                        <td>
                                            {{ date_format($page->created_at,"d M Y")}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Status:</th>
                                        <td>
                                            @if($page['status'] == 1) 
                                            <span class="badge badge-success">Active</span>
                                            @else 
                                            <span class="badge badge-danger">Inactive</span>
                                            @endif 
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

