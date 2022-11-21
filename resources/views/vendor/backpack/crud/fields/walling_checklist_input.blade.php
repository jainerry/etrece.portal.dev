{{-- walling_checklist_input --}}
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

<div data-init-function="bpFieldInitChecklist" class="form-group col-sm-12" element="div" bp-field-wrapper="true" bp-field-name="walling" bp-field-type="walling_checklist_input" data-initialized="true">
	<label>Walling</label>

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

	@foreach ($field['options'] as $key => $option)

		<div class="row wallingOptionItem">
			<div class="col-sm-4">
				<div class="checkbox">
					<label class="font-weight-normal">{{ $option }}</label>
				</div>
			</div>
			<div class="col-sm-2 wallingCheckbox_1">
				<div class="checkbox">
					<label class="font-weight-normal"><input type="radio" name="floor1_walling_radio" value="{{ $key }}" data-name="{{ $option }}"></label>
				</div>
			</div>
			<div class="col-sm-2 wallingCheckbox_2">
				<div class="checkbox">
					<label class="font-weight-normal"><input type="radio" name="floor2_walling_radio" value="{{ $key }}" data-name="{{ $option }}"></label>
				</div>
			</div>
			<div class="col-sm-2 wallingCheckbox_3">
				<div class="checkbox">
					<label class="font-weight-normal"><input type="radio" name="floor3_walling_radio" value="{{ $key }}" data-name="{{ $option }}"></label>
				</div>
			</div>
			<div class="col-sm-2 wallingCheckbox_4">
				<div class="checkbox">
					<label class="font-weight-normal"><input type="radio" name="floor4_walling_radio" value="{{ $key }}" data-name="{{ $option }}"></label>
				</div>
			</div>
		</div>

	@endforeach

	<div class="row wallingOtherWrapper">
		<div class="col-sm-4">
		</div>
		<div class="col-sm-2 wallingOther wallingOther_1 ">
			<div class="checkbox wallingOther_1_wrapper hidden">
				<p class="help-block"><i>Please specify</i></p>
				<input type="text" class="form-control" name="floor1_otherWalling_temp" value="" />
			</div>
		</div>
		<div class="col-sm-2 wallingOther wallingOther_2 ">
			<div class="checkbox wallingOther_2_wrapper hidden">
			<p class="help-block"><i>Please specify</i></p>
				<input type="text" class="form-control" name="floor2_otherWalling_temp" value="" />
			</div>
		</div>
		<div class="col-sm-2 wallingOther wallingOther_3 ">
			<div class="checkbox wallingOther_3_wrapper hidden">
				<p class="help-block"><i>Please specify</i></p>
				<input type="text" class="form-control" name="floor3_otherWalling_temp" value="" />
			</div>
		</div>
		<div class="col-sm-2 wallingOther wallingOther_4 ">
			<div class="checkbox wallingOther_4_wrapper hidden">
				<p class="help-block"><i>Please specify</i></p>
				<input type="text" class="form-control" name="floor4_otherWalling_temp" value="" />
			</div>
		</div>
	</div>


</div>