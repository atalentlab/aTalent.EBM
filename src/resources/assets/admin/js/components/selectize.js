module.exports.init = function($elem) {
    if (!$elem.length) return;
    if ($elem[0].selectize) return;

    let options = {};

    // only show remove button when we have a multiple select dropdown
    if ($elem.prop('multiple')) {
        options['plugins'] = ['remove_button'];
    }

    // allow the creation of new select options by typing
    if ($elem.data('allow-create')) {
        options['create'] = true;
        options['createOnBlur'] = true;
    }

    if ($elem.data('max-items')) {
        options['maxItems'] = $elem.data('max-items');
    }

    if ($elem.data('remote')) {
        options['valueField'] = 'id';
        options['labelField'] = 'name';
        options['searchField'] = 'name';
        options['load'] = function(query, callback) {
            if (query.length < 2) return callback();

            $.ajax({
                url: $elem.data('remote-url'),
                type: "POST",
                dataType: "JSON",
                data: {
                    q: query
                },
                error: function () {
                    callback();
                },
                success: function (res) {
                    callback(res.data);
                },
            });
        }
    }

    $elem.selectize(options);

    //parsley message need to be passed right after selectized
    if($elem.data('parsley-error-message')) {
        $elem.closest('.form-group').find('input[type=select-one]').attr('data-parsley-error-message' , $elem.data('parsley-error-message'));
    }
};
