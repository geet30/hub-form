<div class="page-footer">
    <div class="page-footer-inner">
        {{ now()->year }} &copy; {{ trans('label.form_builder') }}.
    </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script>
var APP_URL = {!! json_encode(url('/')) !!}
var rela_time_room_ids=[];
if($("#userActionId").length){
    var rela_time_room_ids=[$("#userActionId").val()];
}

var login_user_id="<?php echo Auth::id(); ?>";

</script>
{{-- Bundle js --}}
<script src="{{ asset('js/app.js') }}" type="text/javascript"></script>
{{-- Bundle js --}}
{{-- <script src="{{url('assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script> --}}
{{-- <script src="{{url('assets/global/plugins/jquery-migrate.min.js')}}" type="text/javascript"></script> --}}
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
{{-- <script src="{{url('assets/global/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>
<script src="{{url('assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script> --}}
<script src="{{url('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js')}}" type="text/javascript"></script>
{{-- <script src="{{url('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript">
</script> --}}
{{-- <script src="{{url('assets/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
<script src="{{url('assets/global/plugins/jquery.cokie.min.js')}}" type="text/javascript"></script> --}}

<script src="{{url('assets/global/plugins/uniform/jquery.uniform.min.js')}}" type="text/javascript"></script>
{{-- <script src="{{url('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script> --}}
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
{{-- <script src="{{url('assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js')}}" type="text/javascript">
</script> --}}
{{-- <script src="{{url('assets/global/plugins/flot/jquery.flot.min.js')}}" type="text/javascript"></script>
<script src="{{url('assets/global/plugins/flot/jquery.flot.resize.min.js')}}" type="text/javascript"></script>
<script src="{{url('assets/global/plugins/flot/jquery.flot.categories.min.js')}}" type="text/javascript"></script>
<script src="{{url('assets/global/plugins/jquery.pulsate.min.js')}}" type="text/javascript"></script> --}}
<script src="{{ asset('assets/global/plugins/bootstrap-daterangepicker/moment.min.js') }}" type="text/javascript"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone-with-data.js" ></script> --}}
<script src="{{url('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js')}}" type="text/javascript">
</script>
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
{{-- <script src="{{url('assets/global/plugins/fullcalendar/fullcalendar.min.js')}}" type="text/javascript"></script> --}}
<script src="{{url('assets/global/plugins/jquery.sparkline.min.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{url('assets/js/bootbox.min.js')}}" type="text/javascript"></script>
<script src="{{url('assets/global/scripts/metronic.js')}}" type="text/javascript"></script>
<script src="{{url('assets/admin/layout/scripts/layout.js')}}" type="text/javascript"></script>
<script src="{{url('assets/admin/layout/scripts/quick-sidebar.js')}}" type="text/javascript"></script>
<script src="{{url('assets/admin/layout/scripts/demo.js')}}" type="text/javascript"></script>
<script src="{{url('assets/admin/pages/scripts/index.js')}}" type="text/javascript"></script>
{{-- <script src="{{url('assets/admin/pages/scripts/tasks.js')}}" type="text/javascript"></script> --}}
{{-- <script src="{{url('assets/js/jquery.validation.min.js')}}" type="text/javascript"></script> --}}
<script src="{{url('assets/js/bootstrap-datepicker.js')}}"></script>
<script src="{{url('assets/js/dropdown.js')}}"></script>
{{-- <script src="../../assets/js/jquery.dataTables.min.js" type="text/javascript"></script> --}}
<script src="{{url('assets/js/jquery.dataTables.min.js')}}"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.5.2/bootbox.min.js"></script> --}}
<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>


<script src="{{url('assets/firebase/chat.js')}}"></script>

@yield ('footer_scripts')

<!-- END PAGE LEVEL SCRIPTS -->
<script>

    jQuery(document).ready(function () {
        var now = new Date();
        var time = now.getTime();
        time += 10800 * 1000;
        now.setTime(time);

        // console.log(moment.tz.guess());
        
        var current_timezone =  Intl.DateTimeFormat().resolvedOptions().timeZone;
        document.cookie = 'user_timezone='+current_timezone + '; expires=' + now.toUTCString();

        Metronic.init(); // init metronic core componets
        Layout.init(); // init layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        Index.init();
        Index.initDashboardDaterange();

        // Index.initJQVMAP(); // init index page's custom scripts
        // Index.initCalendar(); // init index page's custom scripts
        // Index.initCharts(); // init index page's custom scripts
        // Index.initChat();
        // Index.initMiniCharts();
        // Tasks.initDashboardWidget();

        $(".sub-menu li a").click(function () {
            $(this).parent().addClass('active').siblings().removeClass('active');

        });

        $("form").submit(function() {
            if($("form").valid()){
                $(this).find('input[type="submit"]').prop("disabled", true);
                $(this).find('button[type="submit"]').prop("disabled", true);
                $("#update_and_assign, #save_and_assign").prop("disabled", false);
                return true;
            }
        });
    });

    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

    $('body').on('click', '.mark-as-read', function () {
        var id = $(this).data("id");
        var href = $(this).attr("href");
        if (id != undefined) {
            $.ajax({
                url: '/admin/notifications/mark-read/' + id,
                type: 'PUT',
                dataType: 'JSON',
                success: function (data) {
                    location.href = href;
                }
            });
        }
    });
    $(".alert").fadeTo(2000, 500).slideUp(500, function () {
        $(".alert").slideUp(500);
    });
    bootbox.setDefaults({
        closeButton: true,
    });
    /**
     * Show Success alert
     */
    function alertSuccess(message, type = 'success') {
        bootbox.hideAll();
        bootbox.alert({
            message: message,
            size: 'small',
            onEscape: true
        });
    }
    /**
     * Show Error alert
     */
    function alertError(message, type = 'error') {
        bootbox.hideAll();
        bootbox.alert({
            message: message,
            size: 'small',
            onEscape: true
        });
    }

    /**
     * Show Error alert
     */
    function errorHandler(errorObj) {
        response = errorObj.responseJSON;
        if(errorObj.status == 500){
            alertError(response.message);
        }
    }

    /*** 
     * accept or reject
     * the action
    */
    function accept_reject_action(data_val, type){
        var type = $.trim(type);
        // $('.pre_loader').show();
        var id = $(data_val).data('id');
        var status = 1;
        var comments = '';
        var notification_id = $(data_val).attr('target')
        if(type == 2){
            comments = $('.comments').val();
            if(comments == ''){
                $('.comment_error').text('Please enter the comments.');
            }else{
                $('.comment_error').text('');
                status = 5;
                $('#reject_comment').hide();
            }
        }else if(type == 1){
            status = 2;
        }
        // console.log(status);
        // console.log(notification_id);
        // return true;
        if(status != 1 && notification_id != ''){
            $.ajax({
                url: '/admin/accept_reject/'+id,
                type: 'PUT',
                data: {'status': status, 'comments': comments, 'notification_id':notification_id},
                success: function (response) {
                    $('.notification_'+notification_id).remove();
                    alertSuccess(response.message);
                },
                complete: function(){
                    $('.pre_loader').hide();
                    $('.comment-btn').data('id', '');
                    $('.comments').val('');
                    $('.comment_error').text('');
                    $('.comment-btn').attr('target', '');
                    $('#reject_comment').hide();
                },
                error: function (error) {
                    errorHandler(error);
                }
            });
        }
    }

    // open comment modal
    function open_model(id_data){
        $('.pre_loader').show();
        $('#reject_comment').show();
        var action_id = $(id_data).data('id')
        $('.comment-btn').data('id', action_id);
        $('.comments').val('');
        $('.pre_loader').hide();
        var notify_id = $(id_data).attr('target');
        $('.comment-btn').attr('target', notify_id);
    }

    // close comment modal
    $('.close_comment_modal').click(function(){
        $('.comment-btn').data('id', '');
        $('.comments').val('');
        $('.comment_error').text('');
        $('.comment-btn').attr('target', '');
        $('#reject_comment').hide();
    });




    function approved_supplier(data_val, type){
        var type = $.trim(type);
        // $('.pre_loader').show();
        var id = $(data_val).data('id');//user_id
        var notification_id = $(data_val).attr('target')
        if(status != 1 && notification_id != ''){
            $.ajax({
                url: '/admin/cms/suppliers/approved_supplier',
                type: 'post',
                data: {'notification_id':notification_id,"id":id},
                success: function (response) {
                    $('.notification_'+notification_id).remove();
                    alertSuccess(response.message);
                },
                complete: function(){
                    $('.pre_loader').hide();
                },
                error: function (error) {
                    errorHandler(error);
                }
            });
        }
    }
</script>.

<!-- END JAVASCRIPTS -->


</body>
<!-- END BODY -->

</html>
