<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
<script>
$(document).ready(function() {
    $('#anbar').change(function() {
        var anbar = $(this).val();
        $.ajax({
            url: 'fetch_material_names.php',
            type: 'POST',
            data: {anbar: anbar},
            success: function(response) {
                $('#material_name').html(response);
            }
        });
    });
});
</script>
