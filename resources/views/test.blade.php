<html>
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>

<body>
    <form id="form1" action="">
        <input type="text" name="name" >
        <input type="submit" id="btnSubmit" value="Submit" />
    </form>
</body>
<script type="text/javascript">
    $(document).ready(function () {
        $('input#btnSubmit').on('click', function () {
            var myForm = $("form#form1");
            if (myForm) {
                $(this).prop('disabled', true);
                $(myForm).submit();
            }
        });
    });

</script>
</html>