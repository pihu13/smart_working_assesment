<?php
$catArrOld = (@$catArr && count($catArr) > 0) ? $catArr : old('category_id');
?>
@foreach($childs as $child)
<option value="{{$child->childCat->id}}" <?php echo (@in_array($child->childCat->id, $catArrOld)) ? 'selected="selected"' : ""; ?>>
    --{{ $child->childCat->name }} 
</option>
@if(count($child->childCat->children))
@include('admin::products.recursive-category',['childs' => $child->childCat->children])
@endif
@endforeach

