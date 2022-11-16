<ul>
    @foreach($childs as $child)
    <li>
        {{{ $child->childCat->name }}} 
       <!--<a href="{{ route('admin.add.subcategory',[$child->childCat->id]) }}" class="add_sub" title="Add Sub Category">Add Child Category</a>-->
        <span class="add_sub">{{ Helper::category_product_count(@$child->childCat->id) }} Product</span>
        <div class="sub_cat_action action">
            <a class="icon-btn preview" href="{{ route('admin.view.category',[$child->childCat->slug]) }}">		
                <button type="button" itle="View" class="icon-btn preview"><i class="fal fa-eye"></i></button>
            </a>
            <a class="icon-btn edit" href="{{ route('admin.edit.category',[$child->childCat->slug]) }}">
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
        @include('admin::categories.recursive-category',['childs' => $child->childCat->children])
        @endif
    </li>
    @endforeach
</ul>
