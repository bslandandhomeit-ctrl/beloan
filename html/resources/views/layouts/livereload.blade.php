@if(app()->environment('local'))
<script>
(function () {
    function connect() {
        var ws = new WebSocket('ws://' + location.hostname + ':3030');
        ws.onmessage = function () { location.reload(); };
        ws.onclose = function () { setTimeout(connect, 2000); };
    }
    try { connect(); } catch (e) {}
})();
</script>
@endif
