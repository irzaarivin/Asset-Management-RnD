@if($entry->status == 'pending')
    <a href="{{ url($crud->route.'/update?status=borrowed&id='.$entry->getKey()) }}" class="btn btn-sm btn-link text-capitalize"><i class="las la-check"></i> Izinkan</a>
@endif
