@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Email Template</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Email Template</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
    <div class="alert alert-success" role="alert">
        <p>{{ $message }}</p>
    </div>
    @endif

    @if ($message = Session::get('errors'))
    <div class="alert alert-danger" role="alert">
        <p>{{ $message }}</p>
    </div>
    @endif

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12 mb-4 mt-3" >
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <table id="dataTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="display:none;"></th>
                                        <th scope="col" class="sr-no">#</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col" class="action">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach ($emails as $key => $val)
                                    <tr>
                                        <td style="display:none;"></td>
                                        <th scope="row" class="sr-no">{{ $i }}</th>
                                        <td>{{ $val->title }}</td>
                                        <td>{{ $val->subject }}</td>
                                        <td class="action" style="float: left;">
                                            <a class="icon-btn edit" href="{{ route('admin.edit.email',$val->slug) }}">
                                                <button type="button" itle="Edit" class="icon-btn edit"><i class="fal fa-edit"></i></button>
                                            </a>
                                        </td>
                                    </tr>
                                    @php $i++; @endphp
                                    @endforeach 
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
@section('js')
<script type="text/javascript">
    jQuery(function () {
        jQuery("#dataTable").DataTable({
            'columnDefs': [{
                    'targets': [1,4],
                    'orderable': false
                }],
            stateSave: true
        });
    });
</script>
@endsection