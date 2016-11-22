<!DOCTYPE html>
<html lang="ch-zn">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>重邮点歌台</title>
    <script>
        !function(a,b){function c(){var b=f.getBoundingClientRect().width;b/i>540&&(b=540*i);var c=b/10;f.style.fontSize=c+"px",k.rem=a.rem=c}var d,e=a.document,f=e.documentElement,g=e.querySelector('meta[name="viewport"]'),h=e.querySelector('meta[name="flexible"]'),i=0,j=0,k=b.flexible||(b.flexible={});if(g){console.warn("将根据已有的meta标签来设置缩放比例");var l=g.getAttribute("content").match(/initial\-scale=([\d\.]+)/);l&&(j=parseFloat(l[1]),i=parseInt(1/j))}else if(h){var m=h.getAttribute("content");if(m){var n=m.match(/initial\-dpr=([\d\.]+)/),o=m.match(/maximum\-dpr=([\d\.]+)/);n&&(i=parseFloat(n[1]),j=parseFloat((1/i).toFixed(2))),o&&(i=parseFloat(o[1]),j=parseFloat((1/i).toFixed(2)))}}if(!i&&!j){var p=(a.navigator.appVersion.match(/android/gi),a.navigator.appVersion.match(/iphone/gi)),q=a.devicePixelRatio;i=p?q>=3&&(!i||i>=3)?3:q>=2&&(!i||i>=2)?2:1:1,j=1/i}if(f.setAttribute("data-dpr",i),!g)if(g=e.createElement("meta"),g.setAttribute("name","viewport"),g.setAttribute("content","initial-scale="+j+", maximum-scale="+j+", minimum-scale="+j+", user-scalable=no"),f.firstElementChild)f.firstElementChild.appendChild(g);else{var r=e.createElement("div");r.appendChild(g),e.write(r.innerHTML)}a.addEventListener("resize",function(){clearTimeout(d),d=setTimeout(c,300)},!1),a.addEventListener("pageshow",function(a){a.persisted&&(clearTimeout(d),d=setTimeout(c,300))},!1),"complete"===e.readyState?e.body.style.fontSize=12*i+"px":e.addEventListener("DOMContentLoaded",function(){e.body.style.fontSize=12*i+"px"},!1),c(),k.dpr=a.dpr=i,k.refreshRem=c,k.rem2px=function(a){var b=parseFloat(a)*this.rem;return"string"==typeof a&&a.match(/rem$/)&&(b+="px"),b},k.px2rem=function(a){var b=parseFloat(a)/this.rem;return"string"==typeof a&&a.match(/px$/)&&(b+="rem"),b}}(window,window.lib||(window.lib={}));
    </script>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('jukebox/css/request_song.css') }}">
</head>
<body>
<section class="notice">
    重邮点歌台”是由红岩网校工作站、阳光校园我广播站联合创办。新学期点歌节目强势回归，节千万目的播出时间。重邮点歌台”是由红岩网校工作发的广播站联合创办。新学期点歌节目强势回归，节等我目的播出时间。
</section>
<section class='request-music'>
    <div class="request-detail">
        <form>
            <div class="to-who">
                <div class="to-who-trd">
                    歌曲名
                </div>
                <input maxlength='15' class='to-who-input song-name-input' type='text'>
            </div>
            <div class="to-who">
                <div class="to-who-trd singer">
                    演唱者
                </div>
                <input maxlength='15' class='to-who-input singer-name-input' type='text'>
            </div>
            <div class="to-who">
                <div class="to-who-trd">
                    想送的人
                </div>
                <input maxlength='15' class='to-who-input' type='text'>
            </div>
            <div class="want-say">
                <div class="to-who-say">
                    想说的话
                </div>
                <textarea maxlength='150' class='say-input'></textarea>
            </div>
            <div class="send">
                <div class="no-user">
                    <div class="no-user-border"></div>
                    匿名发送
                </div>
                <p class='error'>
                    信息未完成
                </p>
            </div>
        <form/>
    </div>
    <div class="submit">
        发&nbsp&nbsp表
    </div>
</section>
<script>
    (function () {
        var	btn = document.querySelector('.submit'),
                error = document.querySelector('.error'),
                songName = document.querySelector('.song-name-input'),
                singerName = document.querySelector('.singer-name-input'),
                receiver = document.querySelector('.to-who-input'),
                message = document.querySelector('.say-input'),
                flag = document.querySelector('.no-user');

        var sendObj = {
            songName: '',
            singerName: '',
            receiver: '',
            message: '',
            flag: false,
        };

        flag.addEventListener('click',function () {
            if (sendObj.flag) {
                flag.children[0].style.background = '#d54d46';
            } else {
                flag.children[0].style.background = '';
            }
            sendObj.flag = !sendObj.flag
        });

        btn.addEventListener('click',function () {
            for (var key in sendObj) {
                if ('flag' != key && sendObj.hasOwnProperty(key)) {
                    sendObj[key] = eval(key).value;
                }
            }

            if (!songName.value || !singerName.value || !receiver.value) {
                error.style.display = 'block';
            } else {
                btn.innerHTML = '加&nbsp载&nbsp中';
                error.style.display = 'none';
                sendAjax('/jukebox/song/wish', sendObj, function (res) {
                    alert('发表成功');
                    btn.innerHTML = '发表';
                });
            }
        });

        function sendAjax (src,obj,callback) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST',src,true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status <= 304) {
                    callback(xhr.responseText);
                }
            }
            //console.log(JSON.stringify(obj), obj);
            xhr.send(JSON.stringify(obj));
        }
    }());
</script>
</body>
</html>