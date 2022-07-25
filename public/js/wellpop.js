function handleFromToDateInitilization(from, to) {

    $("."+from).datepicker({
        autoclose: true,
        format: "mm-dd-yyyy",
        endDate: '-0d'
    });

    $("."+to).datepicker({
        autoclose: true,
        format: "mm-dd-yyyy",
        startDate: '-0d'
    });

    $("."+from).datepicker().on('changeDate', function (selected) {
        console.log(selected);
        var startDate = new Date(selected.date.valueOf());
        $("."+to).datepicker('setStartDate', startDate);
    }).on('clearDate', function (selected) {
        $("."+to).datepicker('setStartDate', '-0d');
    });

    $("."+to).datepicker().on('changeDate', function (selected) {
        if(selected.hasOwnProperty('date')) {
            var endDate = new Date(selected.date.valueOf());
            $("."+from).datepicker('setEndDate', endDate);
        }
    }).on('clearDate', function (selected) {
        $("."+from).datepicker('setEndDate', '-0d');
    });
}


function handleMessages(data,elementId,fromParent = false){
    if(data.hasOwnProperty('status') && data.hasOwnProperty('message')) {
        if(fromParent) {
            var alert = $('#'+elementId).parent().find('.alert');
        } else {
            var alert = $('#'+elementId).find('.alert');
        }

        alert.removeClass('alert-success').removeClass('alert-danger');
        alert.removeClass('hide');
        alert.css('display', 'block');
        if(data.status === 'error') {
            alert.addClass('alert-danger');
        } else {
            alert.addClass('alert-'+data.status);
        }

        alert.find('.alert-message').text(data.message);
    }
}

function getURLParameter(url, name) {
    return (RegExp(name + '=' + '(.+?)(&|$)').exec(url)||[,null])[1];
}


function handleWellPopInitialization()
{
    $('.alert').on('close.bs.alert', function (event) {
        event.preventDefault();
        $(this).css('display','none');
    });
}

handleWellPopInitialization();

