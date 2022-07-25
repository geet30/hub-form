$.ajaxSetup({
    type:"POST",
    headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
    beforeSend:function(){
        $('.loader_div').waitMe();
    },
    complete:function(){
        $('.loader_div').waitMe('hide');
    },
    error:function(error){
    }
});