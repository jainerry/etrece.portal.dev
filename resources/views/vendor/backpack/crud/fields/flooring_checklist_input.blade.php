{{-- flooring_checklist_input --}}
@php
  $key_attribute = (new $field['model'])->getKeyName();
  $field['attribute'] = $field['attribute'] ?? (new $field['model'])->identifiableAttribute();
  $field['number_of_columns'] = $field['number_of_columns'] ?? 3;

  // calculate the checklist options
  if (!isset($field['options'])) {
      $field['options'] = $field['model']::all()->pluck($field['attribute'], $key_attribute)->toArray();
  } else {
      $field['options'] = call_user_func($field['options'], $field['model']::query());
  }

  // calculate the value of the hidden input
  $field['value'] = old_empty_or_null($field['name'], []) ??  $field['value'] ?? $field['default'] ?? [];
  if(!empty($field['value'])) {
      if (is_a($field['value'], \Illuminate\Support\Collection::class)) {
          $field['value'] = ($field['value'])->pluck($key_attribute)->toArray();
      } elseif (is_string($field['value'])){
        $field['value'] = json_decode($field['value']);
      }
  }

  // define the init-function on the wrapper
  $field['wrapper']['data-init-function'] =  $field['wrapper']['data-init-function'] ?? 'bpFieldInitChecklist';
@endphp

<div data-init-function="bpFieldInitChecklist" class="form-group col-sm-12" element="div" bp-field-wrapper="true" bp-field-name="flooring" bp-field-type="flooring_checklist_input" data-initialized="true">
	<label>Flooring</label>

	<div class="row">
		<div class="col-sm-4">
			<div class="checkbox">
				<label class="font-weight-normal"></label>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="checkbox">
				<label class="font-weight-normal">1st Floor</label>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="checkbox">
				<label class="font-weight-normal">2nd Floor</label>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="checkbox">
				<label class="font-weight-normal">3rd Floor</label>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="checkbox">
				<label class="font-weight-normal">4th Floor</label>
			</div>
		</div>
	</div>

	<input type="hidden" value="[]" name="flooring_floor1">
	<input type="hidden" value="[]" name="flooring_floor2">
	<input type="hidden" value="[]" name="flooring_floor3">
	<input type="hidden" value="[]" name="flooring_floor4">

	@foreach ($field['options'] as $key => $option)

		<div class="row flooringOptionId_{{ $key }}" id="flooringOptionId_{{ $key }}">
			<div class="col-sm-4">
				<div class="checkbox">
					<label class="font-weight-normal">{{ $option }}</label>
				</div>
			</div>
			<div class="col-sm-2 flooringCheckbox" data-for-floor="1">
				<div class="checkbox">
					<label class="font-weight-normal"><input type="radio" name="floor_1" value="{{ $key }}"></label>
				</div>
			</div>
			<div class="col-sm-2 flooringCheckbox" data-for-floor="2">
				<div class="checkbox">
					<label class="font-weight-normal"><input type="radio" name="floor_2" value="{{ $key }}"></label>
				</div>
			</div>
			<div class="col-sm-2 flooringCheckbox" data-for-floor="3">
				<div class="checkbox">
					<label class="font-weight-normal"><input type="radio" name="floor_3" value="{{ $key }}"></label>
				</div>
			</div>
			<div class="col-sm-2 flooringCheckbox" data-for-floor="4">
				<div class="checkbox">
					<label class="font-weight-normal"><input type="radio" name="floor_4" value="{{ $key }}"></label>
				</div>
			</div>
		</div>

	@endforeach

	<div class="row">
		<div class="col-sm-4">
		</div>
		<div class="col-sm-2">
			<div class="checkbox">
				<p class="help-block"><i>Please specify</i></p>
				<input type="text" class="form-control" name="flooring_floor1_other" id="flooring_floor1_other" value="" />
			</div>
		</div>
		<div class="col-sm-2">
			<div class="checkbox">
			<p class="help-block"><i>Please specify</i></p>
				<input type="text" class="form-control" name="flooring_floor2_other" id="flooring_floor2_other" value="" />
			</div>
		</div>
		<div class="col-sm-2">
			<div class="checkbox">
				<p class="help-block"><i>Please specify</i></p>
				<input type="text" class="form-control" name="flooring_floor3_other" id="flooring_floor3_other" value="" />
			</div>
		</div>
		<div class="col-sm-2">
			<div class="checkbox">
				<p class="help-block"><i>Please specify</i></p>
				<input type="text" class="form-control" name="flooring_floor4_other" id="flooring_floor4_other" value="" />
			</div>
		</div>
	</div>


</div>