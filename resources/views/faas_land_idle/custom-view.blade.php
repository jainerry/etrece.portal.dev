@if (!empty($idleLands))
	@foreach ($idleLands as $idleLand)
        {{ $idleLand->ARPNo }}
	@endforeach
@endif