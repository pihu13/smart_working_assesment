@extends('admin::layouts.master')
@section('admin::content')
<div class="main-content">
    <div class="page-title col-sm-12">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Page Content</h1>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.category.list')}}">Category Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Category</li>
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
                                        <th> Category Name:</th>
                                        <td>{{$cat['name']}}</td>
                                    </tr>
                                    <tr>
                                        <th> Parent Category Name:</th>
                                        <td>
                                            <?php
                                            if (isset($cat->parentCatData) && !empty($cat->parentCatData)) {
                                                $carArr = array();
                                                foreach ($cat->parentCatData as $subcategory) {
                                                    $carArr[] = '<b>' . $subcategory->parentCat->name . '</b>';
                                                }
                                                if (!empty($carArr)) {
                                                    echo implode(', ', $carArr);
                                                } else {
                                                    echo "<b>N/A</b>";
                                                }
                                            } else {
                                                echo "<b>N/A</b>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Children Category Name:</th>
                                        <td>
                                            <?php
                                            if (isset($cat->subcategoryParent) && !empty($cat->subcategoryParent)) {
                                                $carArr = array();
                                                foreach ($cat->subcategoryParent as $subcategory) {
                                                    $carArr[] = '<b>' . $subcategory->childCat->name . '</b>';
                                                }
                                                if (!empty($carArr)) {
                                                    echo implode(', ', $carArr);
                                                } else {
                                                    echo "<b>N/A</b>";
                                                }
                                            } else {
                                                echo "<b>N/A</b>";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th> Category Image:</th>
                                        <td>
                                            <?php
                                            if (!empty($cat->cat_image)) {
                                                $img = $cat->cat_image;
                                            } else {
                                                $img = 'uploads/dummy.png';
                                            }
                                            ?>
                                            <img src="{{ asset('/storage/'.$img)}}" height="100" width="100" alt="img">
                                        </td>
                                    </tr>
                                    <?php
                                    if (@$cat->parentCatData && count($cat->parentCatData) > 0) {
                                        
                                    } else {
                                        ?>
                                        <tr>
                                            <th> Category Color:</th>
                                            <td>
                                                <span style="width: 16px;height: 16px;display: inline-block;background-color: {{ @$cat->color_code }};border-radius: 50%;vertical-align: middle;"></span>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                                    <tr>
                                        <th> Description:</th>
                                        <td>{{ (@$cat['description'])?@$cat['description']:"N/A" }}</td>
                                    </tr>
                                    <tr>
                                        <th> Status:</th>
                                        <td>
                                            <?php
                                            if ($cat['status'] == '1') {
                                                echo '<span class="badge badge-success">Active</span>';
                                            } else {
                                                echo '<span class="badge badge-danger">Inactive</span>';
                                            }
                                            ?>
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

