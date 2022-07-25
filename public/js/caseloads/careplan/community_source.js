/*careplan community source script starts here*/
$(document).on('click', '.careplan-community-source-sidebar-eye, .careplan-community-source-main-table-eye', function(e){
    e.preventDefault();
    let sourceId = $(this).closest('tr').attr('data-id');
    $.ajax({
        url: careplan_get_community_source_details,
        type: 'get',
        data: {
            'id': sourceId,
        },
        success: function(response){
            $('span#view-careplan-community-service-code').text(response.code);
            $('span#view-careplan-community-service-website').text((response.website) ? response.website : '-');
            $('span#view-careplan-community-service-category').text(response.category.name);
            $('span#view-careplan-community-service-contact').text((response.contact_person) ? response.contact_person : '-');
            $('span#view-careplan-community-service-resource-name').text((response.name) ? response.name : '-');
            $('span#view-careplan-community-service-address-1').text((response.address_line1) ? response.address_line1 : '-');
            $('span#view-careplan-community-service-state').text((response.state) ? response.state.full_name : '-');
            $('span#view-careplan-community-service-phone-1').text((response.phone_1) ? maskPhoneNumber(response.phone_1) : '-');
            $('span#view-careplan-community-service-zip').text((response.zip) ? response.zip : '-');
            $('span#view-careplan-community-service-city').text((response.city) ? response.city : '-');
            $('span#view-careplan-community-service-description').text((response.description) ? response.description : '-');
            $('#view-careplan-community-source').modal('show');
        },
        error: function(xhr, status, error){
            checkHttpStatus(xhr);
        }
    });
});

$(document).on('change', '#careplan-community-source-category', function(){
    filterCommunitySources();
});
$(document).on('keyup', '#careplan-community-resource-search-bar', function(){
    filterCommunitySources();
});

$(document).on('click', '#save-careplan-community-source-btn', function(e){
    e.preventDefault();
    $.ajax({
        url: careplan_save_community_source,
        type: 'post',
        data: $('#careplan-community-resource-form').serialize(),
        success: function(response){
            scrollTop();
            $('#careplan-community-source-main-table').html(response.html);
            $(".inner-slide").addClass("slideOutRight");
            setTimeout(function () {
                $("#careplan-community-resource-sidebar").removeClass("show-overlay");
                $("body").removeClass("hideout");
                $(".inner-slide").removeClass("slideOutRight");
            }, 1000);
            applpyEllipses('careplan-community-source-main-table', 4, 'no');
            if(response.status == "danger")
            {
              $('.careplan-community-source-error').removeClass('hide alert-success').addClass('alert-danger').show();
              $(".careplan-community-source-error .alert-message").html('');
              $(".careplan-community-source-error .alert-message").html(response.message);
            }
            else{
              $('.careplan-community-source-error').removeClass('hide alert-danger').addClass('alert-success').show();
              $(".careplan-community-source-error .alert-message").html('');
              $(".careplan-community-source-error .alert-message").html(response.message);
            }
            fadeOutAlertMessages();
            table_load();
        },
        error: function(xhr, status, error){
            checkHttpStatus(xhr);
            $('span#careplan-choose-community-source-error').text(xhr.responseJSON.errors['careplan-community-source-radiobutton'][0]).show();
            table_load();
        }
    });
});

$('.careplan-community-source-slider').scroll(function(){
    if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight){
        var page = $('.careplan-community-source-sidebar-table-container table').attr('data-next-page') ? $('.careplan-community-source-sidebar-table-container table').attr('data-next-page') : null;
        if(page){
            filterCommunitySources(page, 'yes');
        }
        
    }
});

function filterCommunitySources(page=null, append=null)
{
    let url = careplan_get_community_sources_for_sidebar;
    if(page){
        url = page;
    }
    $.ajax({
        url: url,
        type: 'get',
        data: {
            'criteria': 'filter',
            'category': $('#careplan-community-source-category').val(),
            'searchText': $('#careplan-community-resource-search-bar').val(),
            'careplanId': $('input[name=carePlan_id]').val(),
            'patient_id': $('input[name=patient_id]').val(),
        },
        success: function(response){
            if(append == 'yes'){
                $('#careplan-community-resource-tbody-rows').append(response.html);
                $('.careplan-community-source-sidebar-table-container table').attr('data-next-page', response["next-page"]);
            }
            else{
                $('#careplan-community-resource-tbody-rows').html(response.html);
                $('.careplan-community-source-sidebar-table-container table').attr('data-next-page', response["next-page"]);
            }
            initCustomForms();
            $('.dable-eye').prop("disabled", true ).css('color', 'grey');
            $('.dable-radio').prop("disabled", true ).parents('span').css('background-color', 'lightgray');
        },
        error: function(xhr, status, error){
            checkHttpStatus(xhr);
        }
    });
}
/*careplan community source script ends here*/