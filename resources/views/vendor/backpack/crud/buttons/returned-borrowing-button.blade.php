@if($entry->status == 'borrowed')
    <a href="{{ url($crud->route.'/update?status=returned&id='.$entry->getKey()) }}" class="btn btn-sm btn-link text-capitalize"><i class="las la-undo"></i> Dikembalikan</a>
@endif
