@if (!empty($machineries))
	@foreach ($machineries as $machinery)
        {{ $machinery->ARPNo }}
	@endforeach
@endif