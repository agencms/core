{{--  Render a heading repeater field  --}}
@foreach($fields as $field)
    <h2>{{ $field['content'] }}</h2>
@endforeach
