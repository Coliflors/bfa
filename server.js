const express = require('express');
const path = require('path');
const app = express();
const PORT = process.env.PORT || 3000;

const BLOCKED_BOTS = [
  'googlebot','bingbot','slurp','duckduckbot','baiduspider','yandexbot',
  'sogou','exabot','facebot','ia_archiver','gptbot','ccbot','claude',
  'anthropic','perplexitybot','ahrefsbot','semrushbot','mj12bot',
  'dotbot','bytespider','petalbot','google-extended','omgili'
];

function isBot(ua) {
  if (!ua) return false;
  const lower = ua.toLowerCase();
  return BLOCKED_BOTS.some(bot => lower.includes(bot));
}

app.use((req, res, next) => {
  const ua = req.headers['user-agent'] || '';
  if (req.path.startsWith('/web') && isBot(ua)) {
    return res.status(403).send('Forbidden');
  }
  next();
});

app.use('/web', (req, res, next) => {
  res.set({
    'X-Robots-Tag': 'noindex, nofollow, nosnippet, noarchive',
    'X-Frame-Options': 'SAMEORIGIN',
    'X-Content-Type-Options': 'nosniff',
    'Referrer-Policy': 'no-referrer'
  });
  next();
});

app.use((req, res, next) => {
  res.set({
    'X-Frame-Options': 'SAMEORIGIN',
    'X-Content-Type-Options': 'nosniff',
    'Referrer-Policy': 'no-referrer',
    'Strict-Transport-Security': 'max-age=31536000; includeSubDomains'
  });
  next();
});

app.use(express.static(path.join(__dirname)));

app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, 'index.html'));
});

app.listen(PORT, () => {
  console.log(`Servidor corriendo en puerto ${PORT}`);
});
