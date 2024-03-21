require('parsleyjs');

export function init() {
    // Parsley extra validators
    (function ($) {
        'use strict';

        window.Parsley
            .addValidator('filemaxsize', {
                requirementType: 'string',
                validateString: function (value, requirement, parsleyInstance) {

                    let file = parsleyInstance.$element[0].files;
                    let maxBytes = requirement * 1024;

                    if (file.length == 0) {
                        return true;
                    }

                    return file.length === 1 && file[0].size <= maxBytes;

                },
                messages: {
                    en: 'File size cannot exceed  %s KB'
                }
            })
            .addValidator('filemimetypes', {
                requirementType: 'string',
                validateString: function (value, requirement, parsleyInstance) {

                    let file = parsleyInstance.$element[0].files;

                    if (file.length == 0) {
                        return true;
                    }

                    let allowedMimeTypes = requirement.replace(/\s/g, "").split(',');

                    return allowedMimeTypes.indexOf(file[0].type) !== -1;
                },
                messages: {
                    en: 'File type is not allowed'
                }
            })
            .addValidator('imagemindimensions', {
                requirementType: 'string',
                validateString: function (value, requirement, parsleyInstance) {

                    let file = parsleyInstance.$element[0].files[0];
                    let [width, height] = requirement.split('x');
                    let image = new Image();
                    let deferred = $.Deferred();

                    image.src = window.URL.createObjectURL(file);
                    image.onload = function() {
                        if (image.width >= width && image.height >= height) {
                            deferred.resolve();
                        }
                        else {
                            deferred.reject();
                        }
                    };

                    return deferred.promise();
                },
                messages: {
                    en: 'Image dimensions have to be at least %spx'
                }
            })
            .addValidator('mindate', {
                requirementType: 'string',
                validateString: function (value, requirement, parsleyInstance) {
                    const timestamp = Date.parse(value),
                        minDate = Date.parse(requirement);

                    return isNaN(timestamp) ? false : timestamp > minDate;
                },
                messages: {
                    en: 'This date should be greater than %s'
                }
            })
            .addValidator('urlstrict', {
                requirementType: 'string',
                validateString: function (value, requirement, parsleyInstance) {
                    const regExp = /^(https?|s?ftp|git):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i;

                    return '' !== value ? regExp.test(value) : false;
                },
                messages: {
                    en: 'This value should be a valid url, including https://'
                }
        });

    }(jQuery));

    // init parsley with different error/success classes
    $('.js-parsley').parsley({
        errorClass: 'is-invalid',
        successClass: 'is-valid',
        classHandler: function(ParsleyField) {
            return ParsleyField.$element.parents('.form-group');
        },
        errorsContainer: function(ParsleyField) {
            return ParsleyField.$element.parents('.form-group');
        },
        errorsWrapper: '<div class="invalid-feedback"></div>',
        errorTemplate: '<div></div>'
    });
}
