<!DOCTYPE html>
<html lang="ch-zn">
<head>
    <meta charset="UTF-8">
    <title>重邮点歌台</title>
    <script>
        !function(a,b){function c(){var b=f.getBoundingClientRect().width;b/i>540&&(b=540*i);var c=b/10;f.style.fontSize=c+"px",k.rem=a.rem=c}var d,e=a.document,f=e.documentElement,g=e.querySelector('meta[name="viewport"]'),h=e.querySelector('meta[name="flexible"]'),i=0,j=0,k=b.flexible||(b.flexible={});if(g){console.warn("将根据已有的meta标签来设置缩放比例");var l=g.getAttribute("content").match(/initial\-scale=([\d\.]+)/);l&&(j=parseFloat(l[1]),i=parseInt(1/j))}else if(h){var m=h.getAttribute("content");if(m){var n=m.match(/initial\-dpr=([\d\.]+)/),o=m.match(/maximum\-dpr=([\d\.]+)/);n&&(i=parseFloat(n[1]),j=parseFloat((1/i).toFixed(2))),o&&(i=parseFloat(o[1]),j=parseFloat((1/i).toFixed(2)))}}if(!i&&!j){var p=(a.navigator.appVersion.match(/android/gi),a.navigator.appVersion.match(/iphone/gi)),q=a.devicePixelRatio;i=p?q>=3&&(!i||i>=3)?3:q>=2&&(!i||i>=2)?2:1:1,j=1/i}if(f.setAttribute("data-dpr",i),!g)if(g=e.createElement("meta"),g.setAttribute("name","viewport"),g.setAttribute("content","initial-scale="+j+", maximum-scale="+j+", minimum-scale="+j+", user-scalable=no"),f.firstElementChild)f.firstElementChild.appendChild(g);else{var r=e.createElement("div");r.appendChild(g),e.write(r.innerHTML)}a.addEventListener("resize",function(){clearTimeout(d),d=setTimeout(c,300)},!1),a.addEventListener("pageshow",function(a){a.persisted&&(clearTimeout(d),d=setTimeout(c,300))},!1),"complete"===e.readyState?e.body.style.fontSize=12*i+"px":e.addEventListener("DOMContentLoaded",function(){e.body.style.fontSize=12*i+"px"},!1),c(),k.dpr=a.dpr=i,k.refreshRem=c,k.rem2px=function(a){var b=parseFloat(a)*this.rem;return"string"==typeof a&&a.match(/rem$/)&&(b+="px"),b},k.px2rem=function(a){var b=parseFloat(a)/this.rem;return"string"==typeof a&&a.match(/px$/)&&(b+="rem"),b}}(window,window.lib||(window.lib={}));
    </script>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('jukebox/css/song_list.css') }}">
</head>
<body>
<header>
    <img class='header-img' src="http://enroll.lot.cat{{ $describe['album_cover'] }}">
    <div class="litte-img-wapper">
        <img class='litte-img' src="http://enroll.lot.cat{{ $describe['album_cover'] }}">
    </div>
    <p class='song-title'>
        {{ $describe['album_name'] }}
			<span class='list-data-num'>[{{ $describe['album_size'] }}M]</span>
    </p>
    <p class='by-user'>by {{ $describe['album_author'] }}</p>
    <p class='date-time'>@datetime($describe['broadcast_at'])</p>
</header>
<section class="song-list">
    @foreach($albums as $album)
    <a href="{{ URL::route('songs', $album['song_id']) }}">
        <div class="song-lister">
            <div class="lister-number">
                {{ $count++ }}
            </div>
            <div class="lister-content">
                <p class="song-name">
                    {{ $album['song_name'] }}—{{ $album['song_singer'] }}
                </p>
                <p class="by-who">
                    by  {{ $album['sheets']['name'] }}
                </p>
            </div>
        </div>
    </a>
    @endforeach
</section>
<script src='{{ URL::asset('jukebox/js/tools/sea.js') }}'></script>
</body>
</body>
</html>