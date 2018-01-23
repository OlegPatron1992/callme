$(document).ready(function () {
    $('#callme-form').on('submit', function (e) {
        e.preventDefault();

        $(this).find('small.text-danger').remove();

        var data = {
            name: $('#callme-form #callmeName').val().trim(),
            phone: $('#callme-form #callmePhone').val().trim(),
            email: $('#callme-form #callmeEmail').val().trim(),
            message: $('#callme-form #callmeMessage').val().trim()
        };
        var valid = true;

        if (data.name.length == 0) {
            $('#callme-form #callmeName').after('<small class="text-danger">Field is required</small>');
            valid = false;
        }

        if (data.phone.length == 0) {
            $('#callme-form #callmePhone').after('<small class="text-danger">Field is required</small>');
            valid = false;
        } else if (!/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im.test(data.phone)) {
            $('#callme-form #callmePhone').after('<small class="text-danger">Phone is incorrect</small>');
            valid = false;
        }

        if (data.email.length > 0 &&
            !/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(data.email)) {
            $('#callme-form #callmeEmail').after('<small class="text-danger">Email is incorrect</small>');
            valid = false;
        }

        if (valid) {
            $('#callme-submit').prop('disabled', true);

            $.ajax({
                type: 'POST',
                url: window.prestashop.urls.base_url + 'modules/callme/ajax.php',
                data: 'method=requestCall&data=' + JSON.stringify(data),
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        $('#callme-form').hide();
                        $('#callme-success').show();
                    } else {
                        $('#callme-form').hide();
                        $('#callme-failed').show();
                    }
                }, error: function () {
                    $('#callme-form').hide();
                    $('#callme-failed').show();
                }
            });
        }
    });
});