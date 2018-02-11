{{--  Defaults to simply render each field with its relevant view  --}}
@foreach($fields as $field)
    @field($field)
@endforeach
