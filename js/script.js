"use strict";

$(document).ready(function () {
    $('[data-get-bp-template-button]').on('click', function () {
        const bpId = $('[data-get-bp-template-input]')[0].value;

        if ($.isNumeric(bpId) && bpId > 0 && Math.floor(bpId) === Number(bpId)) {
            BX.ajax.runAction('qsoft:bizproc_migration.api.bizproctemplate.getbptemplate', {
                data: {
                    id: bpId,
                }
            }).then(function (e) {
                let a = document.createElement("a");
                a.href = 'data:application/octet-stream;base64,' + e.data;
                a.download = "bp_" + bpId + ".php";
                a.click();
            }, function (e) {
                let errorText = '';
                for (let i = 0; i < e.errors.length; i++) {
                    errorText += e.errors[i].message + ' ';
                }
                alert(errorText);
            })
        } else {
            alert('Введите цифровое значение id бизнес-процесса >0')
        }
    });
});
