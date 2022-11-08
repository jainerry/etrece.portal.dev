@if (!empty($lands))
	@foreach ($lands as $land)
        {{ $land->ARPNo }}
	@endforeach
@endif