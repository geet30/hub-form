$(".Icd_class").chosen({ width:'100%' });

function icdCodeDynamic(){
   //setup before functions
    var typingTimer;                //timer identifier
    var xhr = {abort: function () {  }};  //time in ms (2 seconds)
    var selectID = 'icdCode';    //Hold select id
    var selectData = [];           // data for unique id array

    $('#' + selectID + '_chosen .chosen-choices input').autocomplete({
        minLength:1,
        source: function( request, response ) {
          $('#' + selectID + '_chosen .no-results').hide();
           var inputData = $('#' + selectID + '_chosen .chosen-choices input').val();
            $.ajax({
          url:"/icdcode_list",
          data: {data: inputData,selected_code : $('#' + selectID).val()},
          type:'POST',
          dataType: "json",
          beforeSend: function(){
            // Change No Result Match to Getting Data beforesend
         //   $('#' + selectID + '_chosen .no-results').html('Getting Data = "'+$('#' + selectID + '_chosen .chosen-choices input').val()+'"');

          },
          success: function( data ) { 

            $('#' + selectID ).find('option').not(':selected').remove();
            
            $.map( data.html, function( item ) {
              if($.inArray(item.id,selectData) == -1){
                $('#' + selectID ).append('<option value="'+item.id+'" data-id = "'+item.id+'">' + item.code + '- '+ item.name+ '</option>');
              }
            });
            $('#' + selectID ).trigger("chosen:updated");
            $('.chosen-search-input' ).val(inputData);
          }
        });
        }
    });

  // Chosen event listen on input change eg: after select data / deselect this function will be trigger
    $('#' + selectID ).on('change', function() {
      // get select jquery object
      var domArray = $('#' + selectID ).find('option:selected');
      // empty array data
      selectData = [];
      for (var i = 0, length = domArray.length; i < length; i++ ){
        // Push unique data to array (for matching purpose)
        selectData.push( $(domArray[i]).data('id') );

      }
      // Replace select <option> to only selected option
      $('#' + selectID ).html(domArray);

      // Update chosen again after replace selected <option>
      $('#' + selectID ).trigger("chosen:updated");

      });

}
