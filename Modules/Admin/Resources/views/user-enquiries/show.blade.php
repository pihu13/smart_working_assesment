@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Inquiry Details</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.user.enquiries')}}">Inquiries Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Inquiry Details</li>
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
                                        <th> Full Name:</th>
                                        <td>
                                            {{ @$userEnquiry->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Email Address:</th>
                                        <td>
                                            {{ @$userEnquiry->email }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Contact Number:</th>
                                        <td>
                                            <a href="tel:{{ @$userEnquiry->country_std_code.@$userEnquiry->phone_number }}">
                                                {{ @$userEnquiry->country_std_code.@$userEnquiry->phone_number }}
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th> Cancer Type:</th>
                                        <td>
                                            {{ @$userEnquiry->cancer_type }}
                                        </td>
                                    </tr>
                                  
                                    <tr>
                                        <th> State Name:</th>
                                        <td>
                                            {{ @$userEnquiry->state }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> City Code:</th>
                                        <td>
                                            {{ @$userEnquiry->city }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th> Zipcode:</th>
                                        <td>
                                            {!! @$userEnquiry->zipcode !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Address:</th>
                                        <td>
                                            {!! @$userEnquiry->address !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Submitted On:</th>
                                        <td>
                                            {{ date_format(@$userEnquiry->created_at,"d M Y") }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                  
                      

                        
                       
                       


                    </div>
                </div>
            </div>
            <?php 
                          if(isset($userEnquiry['EnquiryImages'])){
                          $getimges = explode("|",$userEnquiry['EnquiryImages']['enqiery_img']);

                          
                       foreach($getimges as  $val){ ?>

                     
                <img src="{{ url('/images/'.$val)  }}" width:100px;/>

                     <?php  }


                          }
                         ?>
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

