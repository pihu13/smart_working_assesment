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
    <div class="card">
        <div class="card-body">
            <strong>System Settings</strong>
            <a href="{{route('admin.site.logo')}}">
                <span class="badge badge-primary float-right mr-2">Site Logo</span>
            </a>
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if ($message = Session::get('success'))
            <div class="alert alert-success" role="alert">
                <p>{{ $message }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <form action="{{route('admin.edit.system.setting.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <strong>SMTP Details</strong>
                @foreach($systemSettingData as $key => $value) 
                @if($value->setting_type == 'smtp')
                @if($value->status == 1) 
                <a onclick="" class="actions float-right" href="{{route('admin.option.status', $value->setting_type.'_0')}}">
                    <span class="badge badge-success">Active</span>
                </a> 
                @else 
                <a  onclick="" class="actions float-right" href="{{route('admin.option.status', $value->setting_type.'_1')}}">
                    <span class="badge badge-danger">Inactive</span>
                </a> 
                @endif
                @php break; @endphp
                @endif 
                @endforeach  
            </form>
        </div>
        <div class="card-body">
            <form action="{{route('admin.edit.system.setting.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <table id="" class="table table-responsive-lg table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Option Name</th>
                            <th>Option Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($systemSettingData as $key => $value)
                        @if($value->setting_type == "smtp")
                    <input type="hidden" name="id[]" value="{{$value->id}}">
                    <input type="hidden" name="setting_type[]" value="smtp">
                    <input type="hidden" name="slug[]" value="{{$value->option_slug}}">
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value->option_name}}</td>
                        <td><input type="text" value="{{$value->option_value}}" name="value[]" class="form-control"></td>
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

    <div class="card">
        <div class="card-header">
            <form action="{{route('admin.edit.system.setting.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <strong>Stripe Payment Gateway</strong>
                @foreach($systemSettingData as $key => $value) 
                @if($value->setting_type == 'stripe')
                @if($value->status == 1) 
                <a onclick="" class="actions float-right" href="{{route('admin.option.status', $value->setting_type.'_0')}}">
                    <span class="badge badge-success">Active</span>
                </a> 
                @else 
                <a  onclick="" class="actions float-right" href="{{route('admin.option.status', $value->setting_type.'_1')}}">
                    <span class="badge badge-danger">Inactive</span>
                </a> 
                @endif
                @php break; @endphp
                @endif 
                @endforeach  
            </form>
        </div>
        <div class="card-body">
            <form action="{{route('admin.edit.system.setting.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <table id="" class="table table-responsive-lg table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Option Name</th>
                            <th>Option Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($systemSettingData as $key => $value)
                        @if($value->setting_type == "stripe")
                    <input type="hidden" name="id[]" value="{{$value->id}}">
                    <input type="hidden" name="setting_type[]" value="stripe">
                    <input type="hidden" name="slug[]" value="{{$value->option_slug}}">
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value->option_name}}</td>
                        <td>
                            <?php if ($value->option_value == "Live" || $value->option_value == "Test") { ?>
                                <select name="value[]" class="form-control">
                                    <option value="Live" <?php echo ($value->option_value == "Live") ? 'selected="selected"' : ""; ?>>Live</option>
                                    <option value="Test" <?php echo ($value->option_value == "Test") ? 'selected="selected"' : ""; ?>>Test</option>
                                </select>
                            <?php } else { ?>
                                <input type="text" value="{{$value->option_value}}" name="value[]" class="form-control">
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

    <!-- Currency -->
    <div class="card">
        <div class="card-header">
            <strong>Default Currency</strong>
        </div>
        <div class="card-body">
            <form action="{{route('admin.edit.system.setting.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <table id="" class="table table-responsive-lg table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Option Name</th>
                            <th>Option Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($systemSettingData as $key => $value)
                        @if($value->setting_type == "currency")
                    <input type="hidden" name="id[]" value="{{$value->id}}">
                    <input type="hidden" name="setting_type[]" value="currency">
                    <input type="hidden" name="slug[]" value="{{$value->option_slug}}">
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value->option_name}}</td>
                        <td>
                            <select name="value[]" class="form-control">
<!--                                <option value="GBP" <?php //echo ($value->option_value == "GBP") ? 'selected="selected"' : "";           ?>>GBP</option>-->
                                <option value="USD" <?php echo ($value->option_value == "USD") ? 'selected="selected"' : ""; ?>>USD</option>
                            </select>
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

    <!-- Top -->
    <div class="card">
        <div class="card-header">
            <strong>Customer Support</strong>
        </div>
        <div class="card-body">
            <form action="{{route('admin.edit.system.setting.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <table id="" class="table table-responsive-lg table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Option Name</th>
                            <th>Option Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($systemSettingData as $key => $value)
                        @if($value->setting_type == "customersupport")
                    <input type="hidden" name="id[]" value="{{$value->id}}">
                    <input type="hidden" name="setting_type[]" value="customersupport">
                    <input type="hidden" name="slug[]" value="{{$value->option_slug}}">
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value->option_name}}</td>
                        <td>
                            <input type="text" id="" name="value[]" class="form-control" value="{{ $value->option_value }}">
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

    <!-- Google Service keys -->
    <div class="card">
        <div class="card-header">
            <strong>Google Service keys</strong>
        </div>
        <div class="card-body">
            <form action="{{route('admin.edit.system.setting.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <table id="" class="table table-responsive-lg table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Option Name</th>
                            <th>Option Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($systemSettingData as $key => $value)
                        @if($value->setting_type == "googleservicekeys")
                    <input type="hidden" name="id[]" value="{{$value->id}}">
                    <input type="hidden" name="setting_type[]" value="googleservicekeys">
                    <input type="hidden" name="slug[]" value="{{$value->option_slug}}">
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value->option_name}}</td>
                        <td>
                            <input type="text" name="value[]" id="" class="form-control" value="{{ $value->option_value }}">
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

    <!-- Product Discount -->
    <div class="card">
        <div class="card-header">
            <strong>Category Product Discount(%)</strong>
        </div>
        <div class="card-body">
            <form action="{{route('admin.edit.system.setting.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <table id="" class="table table-responsive-lg table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Option Name</th>
                            <th>Option Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($systemSettingData as $key => $value)
                        @if($value->setting_type == "productdiscounts")
                    <input type="hidden" name="id[]" value="{{$value->id}}">
                    <input type="hidden" name="setting_type[]" value="productdiscounts">
                    <input type="hidden" name="slug[]" value="{{$value->option_slug}}">
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value->option_name}}</td>
                        <td>
                            <input type="text" name="value[]" id="" class="form-control" value="{{ $value->option_value }}">
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

    <!-- Delivery Charge -->
    <div class="card">
        <div class="card-header">
            <strong>Delivery Charge($)</strong>
        </div>
        <div class="card-body">
            <form action="{{route('admin.edit.system.setting.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <table id="" class="table table-responsive-lg table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Option Name</th>
                            <th>Option Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($systemSettingData as $key => $value)
                        @if($value->setting_type == "deliverychargesection")
                    <input type="hidden" name="id[]" value="{{$value->id}}">
                    <input type="hidden" name="setting_type[]" value="deliverychargesection">
                    <input type="hidden" name="slug[]" value="{{$value->option_slug}}">
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value->option_name}}</td>
                        <td>
                            <input type="text" name="value[]" id="" class="form-control" value="{{ (@$value->option_value)?$value->option_value:0 }}">
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

    <!-- Product Tax -->
    <div class="card">
        <div class="card-header">
            <strong>Product Tax(%)</strong>
        </div>
        <div class="card-body">
            <form action="{{route('admin.edit.system.setting.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <table id="" class="table table-responsive-lg table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Option Name</th>
                            <th>Option Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($systemSettingData as $key => $value)
                        @if($value->setting_type == "producttaxsec")
                    <input type="hidden" name="id[]" value="{{$value->id}}">
                    <input type="hidden" name="setting_type[]" value="producttaxsec">
                    <input type="hidden" name="slug[]" value="{{$value->option_slug}}">
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value->option_name}}</td>
                        <td>
                            <input type="text" name="value[]" id="" class="form-control" value="{{ (@$value->option_value)?$value->option_value:0 }}">
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


    <div class="card">
        <div class="card-header">
            <strong>Social Media</strong>
        </div>
        <div class="card-body">
            <form action="{{route('admin.edit.system.setting.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <table id="" class="table table-responsive-lg table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Option Name</th>
                            <th>Option Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($systemSettingData as $key => $value)
                        @if($value->setting_type == "socialmediasection")
                    <input type="hidden" name="id[]" value="{{$value->id}}">
                    <input type="hidden" name="setting_type[]" value="socialmediasection">
                    <input type="hidden" name="slug[]" value="{{$value->option_slug}}">
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value->option_name}}</td>
                        <td>
                            <input type="text" value="{{$value->option_value}}" name="value[]" class="form-control">
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


    <div class="card">
        <div class="card-header">
            <strong>Expected Delivery Day</strong>
        </div>
        <div class="card-body">
            <form action="{{route('admin.edit.system.setting.post')}}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <table id="" class="table table-responsive-lg table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Option Name</th>
                            <th>Option Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($systemSettingData as $key => $value)
                        @if($value->setting_type == "productdeliverydaysec")
                    <input type="hidden" name="id[]" value="{{$value->id}}">
                    <input type="hidden" name="setting_type[]" value="socialmediasection">
                    <input type="hidden" name="slug[]" value="{{$value->option_slug}}">
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$value->option_name}}</td>
                        <td>
                            <select name="value[]" id="" class="form-control">
                                <?php
                                for ($i = 1; $i <= 10; $i++) {
                                    ?>
                                    <option value="{{ $i }}" <?php echo ($value->option_value == $i)?'selected="selected"':"" ?>>
                                        Order Date After {{ ($i > 1)?$i." Days":$i." Day" }}  
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
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