"use strict";

jQuery(document).ready(function() {
    $(document).foundation();

    var entityid = $('#federations').data('entityid');

    $('input').change(function(ev) {
        var protocol       = $('input[name="protocol"]:checked').val();

        var params = {
            target: entityid,
        };

        var classref = [];
        $('#classref input').each(function() {
            if ($(this).is(':checked')) {
                classref.push($(this).val());
            }
        });
        if (classref) {
            params['authnContextClassRef'] = classref.join(' ');
        }

        var comparison = $('input[name="comparison"]:checked').val();
        if (comparison) {
            params['authnContextComparison'] = comparison;
        }

        var forceauthn = $('input[name="forceauthn"]').is(':checked');
        if (forceauthn) {
            params['forceAuthn'] = 'true';
        }

        $('#federations a').each(function() {
            var url = `/Shibboleth.sso/Login?${jQuery.param(params)}`;
            $(this).attr('href', url);
        });
    });

});
