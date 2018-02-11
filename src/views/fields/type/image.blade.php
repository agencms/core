@if(optional($field)['content'])
    <img src="{{ $field['content'] }}" alt="{{ optional($field)['alt'] }}">
@endif
