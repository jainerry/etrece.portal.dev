$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let inputs = $('input[name=fName], input[name=lName], select[name=suffix], input[name=bdate]')
    let id = $('[name=id]');
    const isUpdate = id.length > 0;

    delayTrigger = 0;
    inputs.on('change', function () {
        clearTimeout(delayTrigger);
        delayTrigger = setTimeout(function () {
            data = new FormData();
            if (isUpdate) {
                data.append('id', id.val());
            }
            empty = [];
            $.each(inputs, function () {
                data.append($(this).attr('name'), $(this).val())

                if ($.trim($(this).val()) == "") {
                    if ($(this).attr('name') == "suffix" || $(this).attr('name') == "mName") {

                    } else {
                        empty.push($(this).val())
                    }

                }
            })
            if (empty.length > 0) {

            } else {
                $.ajax({
                    url: '/admin/citizen-profile/check-duplicate',
                    data: data,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    success: function (e) {
                        if (e.count > 0) {
                            swal({
                                title: "Entry record already exist!",
                                text: "Please provide new entry for this field",
                                icon: "error",
                                timer: 4000,
                                buttons: false,
                            });
                            $('form').trigger("reset");
                            if (isUpdate == false) {
                                $('.date input').datepicker('update', '');
                            }

                        }
                    }
                })
            }

            console.log('fire')
        }, 300)
    })
})