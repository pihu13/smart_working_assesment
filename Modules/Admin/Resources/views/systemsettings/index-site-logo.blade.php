@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">System Settings</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">System Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Site Logo -->
    <div class="card">
        @if ($message = Session::get('success'))
        <div class="alert alert-success" role="alert">
            <p>{{ $message }}</p>
        </div>
        @endif
        <div class="card-header">
            <strong>Site Logo</strong>
            <a href="{{route('admin.system.settings')}}">
                <span class="badge badge-primary float-right mr-2">System Settings</span>
            </a>
        </div>
        <div class="card-body">
            <form action="{{route('admin.edit.site.logo')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <table id="" class="table table-responsive-lg table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Option Name</th>
                            <th>Option Value</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($systemSettingData as $key => $value)
                        @if($value->setting_type == "sitelogo")
                    <input type="hidden" name="id[]" value="{{$value->id}}">
                    <input type="hidden" name="setting_type[]" value="sitelogo">
                    <input type="hidden" name="slug[]" value="{{$value->option_slug}}">
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value->option_name}}</td>
                        <td>
                            <input type="file" value="" name="value[]" class="form-control">
                        </td>
                        <?php
                        if (!empty($value->option_value)) {
                            $img = $value->option_value;
                        } else {
                            if ($value->option_slug == "header-logo") {
                                $img = 'uploads/logo.svg';
                            } else {
                                $img = 'uploads/footer-img.png';
                            }
                        }
                        ?>
                        <td>
                            <?php
                            if ($value->option_slug == "favicon-icon" || $value->option_slug == "footer-logo") {
                                ?>
                                <img src="{{ asset('/storage/'.$img)}}" height="25" width="25" alt="img">
                            <?php } else if ($value->option_slug == "norton-secured-icon") { ?>
                                <img src="{{ asset('/storage/'.$img)}}" height="48" width="115" alt="img">
                            <?php } else { ?>
                                <img src="{{ asset('/storage/'.$img)}}" height="100" width="150" alt="img">
                            <?php } ?>
                        </td>
                    </tr>

                    @php $i++; @endphp
                    @endif 
                    @endforeach  
                    </tbody>
                </table>
                <div class="card-footer">
                    <button class="btn btn-sm btn-primary" type="submit">Update</button>
                </div> 
            </form>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>
    CKEDITOR.replace('email_content');
</script>
@endsection