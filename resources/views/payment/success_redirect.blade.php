<!-- resources/views/payment/success_redirect.blade.php -->

<h3 style="text-align:center">Please wait 10 seconds. This page will auto redirect you</h3>
<script>
    setTimeout(() => {
        window.location.href = '{{ $redirect }}';
    }, 10000);
</script>
