$(document).ready(function () {
    $('#notifications').show();
    $('.pre_loader').hide();
    var BU_table = $('#notifications').DataTable({
        sPaginationType: 'full_numbers',
        paging: true,
        ordering: false,
        "autoWidth": false,
        //searching: false,
        aoColumnDefs: [
            // {bSortable: false, aTargets: [8]},
            //{type: 'title-string', targets: 2 }
        ],
        order: [],
        // scrollX : true,
        dom: 'Blfrtip',
        buttons: [],
    });


    $('#notifications_filter').css('display', 'none');

    $(".filterhead").each(function (i) {
        $('.filter', this).on('keyup change', function () {
            if (BU_table.column(i).search() !== this.value) {
                BU_table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });
    });
});
$('body').on('click', '.reset', function () {
    $('.filter').val('');
    $('input[type="search"]').val('');
    $('.filter').trigger('change');
});
$(function () {
    var today = new Date();
    $("#fiscalYear").datepicker({
        dateFormat: "yy-mm-dd",
        endDate: "today",
        maxDate: today,
        showOtherMonths: true,
        selectOtherMonths: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        // gotoCurrent: true,
    });
    $(".date_picker").datepicker({
        dateFormat: "yy-mm-dd",
        endDate: "today",
        maxDate: today,
        showOtherMonths: true,
        selectOtherMonths: true,
        autoclose: true,
        changeMonth: true,
        changeYear: true,
    });
});

/*Save as completed forms*/
$(document).ready(function () {
    $(".archive").click(function (event) {
        event.preventDefault();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let id = $(this).attr('data-id');
        $.ajax({
            url: '/admin/archive',
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                id: id
            },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                location.reload(true);
            }
        });
    });
    //restore completed forms
    $(".restore").click(function (event) {
        event.preventDefault();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let id = $(this).attr('data-id');
        $.ajax({
            url: '/admin/restore',
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                id: id
            },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                location.reload(true);
            }
        });
    });

    //edit completed forms
    $(".update_answer").click(function (event) {
        event.preventDefault();
        let type = '';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let id = $(this).attr('data-id');
        type = $(this).attr('data-type');
        let answer = $(this).parent().find(".answer").val();
        let notifications = $("#form_id").val();
        var arr = [];
        $(this).parent().find(".answer:checkbox:checked").each(function () {
            arr.push($(this).val());
        });
        //for multiple select
        if (type == 7) {
            $(this).parent().find(".answer :selected").each(function () {
                arr.push($(this).val());
            });
        }

        //for yes no 
        if (type == 5) {
            answer = $(this).parent().find(".answer:selected").val();
        }

        $.ajax({
            url: '/admin/update_answer',
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                id: id,
                answer: answer,
                arr: arr
            },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                location.reload(true);
            }
        });
    });
    //save admin comments
    $(".save_comments").click(function (event) {
        event.preventDefault();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        let id = $(this).attr('data-id');
        let type = $(this).attr('data-type');
        let question = $(this).attr('data-question');
        let answer = $(this).attr('data-answer');
        let section = $(this).attr('data-section');
        let template_id = $(this).attr('data-template');
        let comment = $(this).parent().find(".comment").val();
        let form_id = $("#form_id").val();
        $.ajax({
            url: '/admin/save_comments',
            type: 'POST',
            data: {
                _token: CSRF_TOKEN,
                id: id,
                type: type,
                comment: comment,
                question_id: question,
                answer_id: answer,
                form_id: form_id,
                template_id: template_id,
                section_id: section,
            },
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
                location.reload(true);
            }
        });
    });

});
