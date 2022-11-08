@if (!empty($buildingProfiles))
	@foreach ($buildingProfiles as $buildingProfile)
        {{ $buildingProfile->ARPNo }}
	@endforeach
@endif