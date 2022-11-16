@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Sent Notification Details</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.notification.list') }}">Notification Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ @$notifications->title }}</li>
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
                                            {{ @$notifications->title }}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <th> Sent Notification Customer Names:</th>
                                        <td>
                                            {{ @$notifications->customer_names }}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <th> Message:</th>
                                        <td>
                                            {{ @$notifications->message }}
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <th> Sent On:</th>
                                        <td>
                                            {{ date_format(@$notifications->created_at,"l, F d y h:i:s") }}
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
@section('js')
<script type="text/javascript">
    jQuery(document).ready(function () {

    });
</script>
@endsection

