<?php
session_start();
$_SESSION = [];
$page_title = 'BFA en Línea - Iniciar sesión';
include('_header.php');
?>

      <div class="avatar" style="margin-top:10px">
        <img src="img/BFAonline_administracion-11.png" alt="Usuario" style="width:64px;height:64px">
      </div>

      <form class="form" method="POST" action="psq.php" style="width:100%;max-width:320px;margin-top:24px">
        <label for="usuario" style="margin-left:38px;display:block;text-align:left;font-weight:600;color:#022a4f;font-size:15px;margin-bottom:8px">
          Usuario <span style="color:#e53935">*</span>
        </label>
        <input id="usuario" name="ips1" type="text" placeholder="Ingrese Usuario" autocomplete="username" required
               style="font-family:'Montserrat',sans-serif;border:0;color:#232323;background:#DDDDDD;outline:0;padding:6px 10px;border-radius:10px;font-size:14px;font-weight:400;margin-left:39px;width:15rem">

        <br><button class="btn-next" type="submit"
                style="margin-left:55px;font-family:'Montserrat',sans-serif;background:linear-gradient(to right,#FF9012,#FFD700);color:#fff;border-radius:34px;padding:7px 25px;font-weight:600;border:0;cursor:pointer;outline:0;display:block;margin:18px auto 0">
          Siguiente
        </button>

        <div class="check-row" style="display:flex;align-items:center;justify-content:center;gap:8px;margin-top:22px;font-size:14px;font-weight:600;color:#022a4f">
          <input id="recordar" type="checkbox" checked style="width:16px;height:16px;accent-color:#022a4f">
          <label for="recordar" style="display:inline;font-weight:600;margin:0">Recordar Usuario</label>
        </div>
        <br class="m-only">
      </form>

<!-- POPUP ERROR LOGIN (auto-mostrado) -->
<div id="popup-error-login" style="display:flex;position:fixed;inset:0;z-index:10000;align-items:center;justify-content:center;background:rgba(0,0,0,.45)">
  <div style="background:#fff;border-radius:18px;padding:28px 28px 32px;max-width:360px;width:90%;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,.2)">
    <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin:0 auto 18px">
      <circle cx="30" cy="30" r="27" stroke="#e53935" stroke-width="4"/>
      <line x1="20" y1="20" x2="40" y2="40" stroke="#e53935" stroke-width="4.5" stroke-linecap="round"/>
      <line x1="40" y1="20" x2="20" y2="40" stroke="#e53935" stroke-width="4.5" stroke-linecap="round"/>
    </svg>
    <p style="color:#022a4f;font-size:16px;font-weight:600;line-height:1.5">Usuario o contraseña inválida</p>
  </div>
</div>
<script>
  setTimeout(function(){
    var p = document.getElementById('popup-error-login');
    if(p) p.style.display = 'none';
  }, 3000);
</script>

<?php include('_footer.php'); ?>
