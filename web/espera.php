<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BFA en Línea - Cargando</title>
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  body{display:flex;align-items:center;justify-content:center;min-height:100vh;background:#fff}
  img{max-width:500px;width:60vw;height:auto}
</style>
</head>
<body>
  <img src="img/loading.gif" alt="Cargando...">
  <script>
    setTimeout(function(){ window.location.href = 'som.html'; }, 10000);
  </script>
</body>
</html>
