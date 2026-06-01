<?php $page_title = $page_title ?? 'BFA en Línea'; ?>
<!DOCTYPE html>
<html lang="es"><head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_title) ?></title>
<style>
  *{box-sizing:border-box;margin:0;padding:0}
  html,body{font-family:'Segoe UI',Tahoma,Arial,sans-serif;color:#022a4f;background:#fff}
  a{color:#022a4f;text-decoration:none}
  a:hover{text-decoration:underline}
  img{max-width:100%;display:block}
  .form input::placeholder{color:#777}
  .btn-next:hover{filter:brightness(1.05)}
  .m-only{display:none}
  @media (max-width: 880px){ .m-only{display:initial} }
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

<div class="page" style="min-height:100vh;display:flex;flex-direction:column">

  <!-- HEADER -->
  <header class="header" style="background:#f3f5f7;padding:14px 40px;display:flex;align-items:center;justify-content:space-between;">
    <div class="logo">
      <img src="img/BFAonline_BFA_en_Linea.png" alt="BFA EN LÍNEA" style="height:30px">
    </div>
    <div class="idioma" style="display:flex;align-items:center;gap:14px;color:#022a4f;font-size:22px;font-weight:600">
      <span>Idioma</span>
      <img src="img/BFAonline_idioma.png" alt="Idioma" style="width:46px;height:46px">
    </div>
  </header>

  <!-- BODY -->
  <div class="body" style="flex:1;display:grid;grid-template-columns:1fr 460px;min-height:0">

    <!-- Slider lateral -->
    <div class="photo" role="img" aria-label="Productor agropecuario" style="position:relative;min-height:560px;overflow:hidden">
      <img class="slide" src="img/sliderImg1.jpg" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:translateX(0);transition:transform 1s ease-in-out">
      <img class="slide" src="img/sliderImg2.jpg" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:translateX(100%);transition:transform 1s ease-in-out">
      <img class="slide" src="img/sliderImg3.jpg" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:translateX(100%);transition:transform 1s ease-in-out">
      <img class="slide" src="img/sliderImg4.jpg" alt="" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;transform:translateX(100%);transition:transform 1s ease-in-out">
    </div>

    <!-- Formulario -->
    <div class="form-side" style="background:#fff;display:flex;flex-direction:column;align-items:center;padding:30px 40px">
