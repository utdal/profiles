@if(config('app.analytics_id'))
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.analytics_id') }}"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{{ config('app.analytics_id') }}');
    </script>
@endif

@if(config('app.silktide_analytics_url'))
    <script async src="{{ config('app.silktide_analytics_url') }}"></script>
    <script> (function (s,i,l,k,y) {
        s[i] = s[i] || y;
        s[l] = s[l] || [];
        s[k] = function(e, p) { p = p || {}; p.event = e; s[l].push(p); };
        s[k]('page_load');
    })(window, 'stConfig', 'stEvents', 'silktide', {});
    </script>
@endif

