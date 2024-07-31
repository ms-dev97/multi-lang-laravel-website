<script src="{{ asset('assets/admin/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('{{ $selector }}').select2({
            allowClear: true,
            placeholder: '{{ $placeholder }}',
            width: '100%',
        });
    });
</script>