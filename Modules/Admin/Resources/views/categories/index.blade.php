@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3 m-0">Categories</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Category Manager</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success</strong> {{ $message }}
            <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        @endif
        @if ($message = Session::get('errors_catch'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Success</strong> {{ $message }}
            <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        @endif
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">Category List
                <a href="{{route('admin.add.category')}}">
                    <span class="badge badge-primary float-right">Add Category</span>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 mb-4">
                <div class="box bg-white">
                    <div class="box-row">
                        <div class="box-content">
                            <table id="dataTable" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="display:none;"></th>
                                        <th scope="col" class="sr-no">#</th>
                                        <th scope="col">Category Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; @endphp
                                    @foreach($categories as $categorie)

                                    <tr>
                                        <td style="display:none;"></td>
                                        <th scope="row" class="sr-no"> {{$i++}}</th>
                                        <td>
                                            {{ $categorie->name }} 

                                            <?php
                                            if (@$categorie->color_code && !empty($categorie->color_code)) {
                                                ?>
                                                <span style="width: 16px;height: 16px;display: inline-block;background-color: {{ @$categorie->color_code }};border-radius: 50%;vertical-align: middle;"></span>
                                                <?php
                                            }
                                            ?>
                                            <a href="{{ route('admin.add.subcategory',[$categorie->id]) }}" class="add_sub" title="Add Sub Category">Add Child Category</a>
                                            <span class="add_sub">{{ Helper::category_product_count($categorie->id) }} Products</span>
                                            <div class="sub_cat_action action">
                                                <a class="icon-btn preview" href="{{ route('admin.view.category',[$categorie->slug]) }}">		
                                                    <button type="button" itle="View" class="icon-btn preview"><i class="fal fa-eye"></i></button>
                                                </a>
                                                <a class="icon-btn edit" href="{{ route('admin.edit.category',[$categorie->slug]) }}">
                                                    <button type="button" itle="Edit" class="icon-btn edit"><i class="fal fa-edit"></i></button>
                                                </a>
                                                <a href="{{ route('admin.delete.category', [$categorie->slug])}}" onclick="return confirm('Are you sure you want to delete this page?');"  class="actions" data-id="{{ $categorie->slug }}" data-token="{{ csrf_token() }}">
                                                    <button title="Delete" type="button" class="icon-btn delete"><i class="fal fa-times"></i></button>
                                                </a>
                                            </div>
                                            <div class="active_btn_cls text-right">
                                                @if($categorie->status == 1) 
                                                <a onclick="" class="actions" href="{{ route('admin.category.status', $categorie->slug.'_0_'.$categorie->id) }}">
                                                    <span class="badge badge-success">Active</span>
                                                </a> 
                                                @else 
                                                <a  onclick="" href="{{ route('admin.category.status', $categorie->slug.'_1_'.$categorie->id) }}" class="actions">
                                                    <span class="badge badge-danger">Inactive</span>
                                                </a> 
                                                @endif 
                                            </div>
                                            <hr>
                                            <ul class="rev_cat_cls">
                                                <?php foreach ($categorie->children as $child) { ?>
                                                    <li>
                                                        {{ $child->childCat->name }} 
                                                        <!--<a href="{{ route('admin.add.subcategory',$child->childCat->id) }}" class="add_sub" title="Add Sub Category">Add Child Category</a>-->
                                                        <span class="add_sub">{{ Helper::category_product_count(@$child->childCat->id) }} Products</span>
                                                        <div class="sub_cat_action action">
                                                            <a class="icon-btn preview" href="{{ route('admin.view.category',$child->childCat->slug) }}">		
                                                                <button type="button" itle="View" class="icon-btn preview"><i class="fal fa-eye"></i></button>
                                                            </a>
                                                            <a class="icon-btn edit" href="{{ route('admin.edit.category',$child->childCat->slug) }}">
                                                                <button type="button" itle="Edit" class="icon-btn edit"><i class="fal fa-edit"></i></button>
                                                            </a>
                                                            <a href="{{ route('admin.delete.category', [$child->childCat->slug])}}" onclick="return confirm('Are you sure you want to delete this page?');"  class="actions" data-id="{{ $child->childCat->slug }}" data-token="{{ csrf_token() }}">
                                                                <button title="Delete" type="button" class="icon-btn delete"><i class="fal fa-times"></i></button>
                                                            </a>

                                                        </div>

                                                        <div class="active_btn_cls text-right">
                                                            @if($child->childCat->status == 1) 
                                                            <a onclick="" class="actions" href="{{ route('admin.category.status', $child->childCat->slug.'_0_'.$child->childCat->id) }}">
                                                                <span class="badge badge-success">Active</span>
                                                            </a> 
                                                            @else 
                                                            <a  onclick="" href="{{ route('admin.category.status', $child->childCat->slug.'_1_'.$child->childCat->id) }}" class="actions">
                                                                <span class="badge badge-danger">Inactive</span>
                                                            </a> 
                                                            @endif
                                                        </div>
                                                        <hr>
                                                        @if(count($child->childCat->children))
                                                        @include('admin::categories.recursive-category',['childs' => @$child->childCat->children])
                                                        @endif
                                                    </li>
                                                <?php } ?>
                                            </ul>
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
    </div>
</div>
@endsection
@section('js')
<style type="text/css">
    .rev_cat_cls {
        padding: 10px 0 0 10px;
        margin: 0;
        list-style: none;
    }
    .rev_cat_cls li {
        padding: 10px 0 0;
    }
    .rev_cat_cls li:before {
        display: inline-block;
        font-family: "Font Awesome 5 Pro";
        content: "\f105";
    }
    .rev_cat_cls ul {
        padding: 10px 0 0 15px;
        margin: 0;
        list-style: none;
    }
    .add_sub {
        display: inline-block;
        padding: 1px 5px;
        background: #28a745;
        font-weight: 700;
        font-size: 9px;
        margin-left: 10px;
        color: #ffffff;
        border-radius: 3px;
    }
    .add_sub:hover {
        color: #ffffff;
    }
    .sub_cat_action {
        display: inline-block;
        float: right;
    }
    .sub_cat_action a, .sub_cat_action a .icon-btn {
        width: 20px;
        height: 20px;
        line-height: 10px;
        display: inline-block;
        border: none;
        padding: 0;
        border-radius: 100%;
        background-color: #6c757d;
        border: 1px solid #6c757d;
        color: #fff;
        font-size: 12px;
    }
    .sub_cat_action a.edit, .sub_cat_action a .icon-btn.edit {
        background-color: #f47521;
        border-color: #f47521;
    }
    .sub_cat_action a.actions, .sub_cat_action a .icon-btn.delete {
        background-color: #ff1a30;
        border-color: #ff1a30;
    }
    hr {
        margin: 5px 0 0;
    }
</style>
<script type="text/javascript">
    jQuery(function () {
        jQuery("#dataTable").DataTable({
            'columnDefs': [{
                    'targets': [1],
                    'orderable': false
                }],
            stateSave: true
        });
    });
</script>
@endsection

