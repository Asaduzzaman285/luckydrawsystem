<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LuckyDraw Pro — Sign In</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --gold: #C9A84C;
    --gold-light: #F0D080;
    --gold-dark: #8B6914;
    --bg: #0A0C12;
    --surface: #12151F;
    --surface2: #1A1E2E;
    --border: rgba(201,168,76,0.15);
    --text: #EEE8D5;
    --muted: #6B7199;
  }
  html, body { height: 100%; font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); overflow: hidden; }

  #canvas-bg { position: fixed; inset: 0; z-index: 0; opacity: 0.35; }

  .page { position: relative; z-index: 1; display: flex; align-items: stretch; height: 100vh; width: 100vw; }

  /* ── BRAND PANEL ── */
  .brand-panel {
    flex: 0 0 44%;
    display: flex; flex-direction: column; justify-content: space-between;
    padding: 52px 50px;
    background: linear-gradient(145deg, #0E1120 0%, #161A2C 55%, #1E1430 100%);
    border-right: 1px solid var(--border);
    position: relative; overflow: hidden;
  }
  .brand-panel::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 90% 60% at 15% 75%, rgba(201,168,76,0.09) 0%, transparent 65%),
                radial-gradient(ellipse 50% 70% at 85% 15%, rgba(100,80,200,0.06) 0%, transparent 60%);
    pointer-events: none;
  }

  .ring {
    position: absolute; border-radius: 50%;
    border: 1px solid rgba(201,168,76,0.1);
    animation: spin linear infinite;
  }
  .ring-1 { width: 460px; height: 460px; top: -100px; right: -180px; animation-duration: 45s; }
  .ring-2 { width: 280px; height: 280px; bottom: 40px; left: -90px; animation-duration: 32s; animation-direction: reverse; }
  .ring-3 { width: 160px; height: 160px; top: 40%; right: 40px; animation-duration: 20s; border-color: rgba(201,168,76,0.06); }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* floating ticket card */
  .ticket-card {
    position: absolute; bottom: 140px; right: 40px;
    background: linear-gradient(135deg, rgba(201,168,76,0.15), rgba(201,168,76,0.05));
    border: 1px solid rgba(201,168,76,0.25);
    border-radius: 16px; padding: 18px 22px;
    backdrop-filter: blur(10px);
    animation: float 5s ease-in-out infinite;
    z-index: 2;
  }
  @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
  .ticket-label { font-size: 9px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--gold); margin-bottom: 6px; }
  .ticket-amount { font-family: 'Playfair Display', serif; font-size: 26px; color: #fff; font-weight: 700; }
  .ticket-sub { font-size: 10px; color: var(--muted); margin-top: 3px; }

  .brand-logo { display: flex; align-items: center; gap: 14px; position: relative; z-index: 2; }
  .logo-icon {
    width: 48px; height: 48px;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    border-radius: 15px;
    display: flex; align-items: center; justify-content: center; font-size: 22px;
    box-shadow: 0 0 28px rgba(201,168,76,0.35);
  }
  .logo-text { font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 700; color: var(--gold-light); line-height: 1.2; }
  .logo-sub { font-size: 9px; letter-spacing: 0.2em; color: var(--muted); text-transform: uppercase; margin-top: 2px; }

  .brand-center { position: relative; z-index: 2; }
  .brand-headline { font-family: 'Playfair Display', serif; font-size: clamp(36px,3.8vw,56px); font-weight: 700; line-height: 1.1; color: #fff; margin-bottom: 18px; }
  .brand-headline em { color: var(--gold); font-style: italic; }
  .brand-desc { font-size: 14px; line-height: 1.75; color: var(--muted); max-width: 290px; font-weight: 300; margin-bottom: 36px; }

  .stats-row { display: flex; gap: 36px; }
  .stat-num { font-family: 'Playfair Display', serif; font-size: 30px; font-weight: 700; color: var(--gold); line-height: 1; }
  .stat-label { font-size: 9px; letter-spacing: 0.15em; text-transform: uppercase; color: var(--muted); margin-top: 5px; }

  .brand-footer { position: relative; z-index: 2; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: var(--muted); opacity: 0.4; }

  /* ── FORM PANEL ── */
  .form-panel {
    flex: 1; display: flex; align-items: center; justify-content: center;
    padding: 48px 40px; position: relative; overflow-y: auto;
  }
  .form-panel::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 55% 45% at 65% 35%, rgba(201,168,76,0.04) 0%, transparent 65%);
    pointer-events: none;
  }

  .form-card { width: 100%; max-width: 400px; position: relative; z-index: 2; animation: fadeUp 0.6s ease; }
  @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }

  .security-badge {
    display: flex; align-items: center; gap: 8px;
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 10px; padding: 9px 14px; margin-bottom: 28px;
    font-size: 10px; color: var(--muted); letter-spacing: 0.05em;
  }
  .security-badge .dot { width: 6px; height: 6px; border-radius: 50%; background: #4ADE80; box-shadow: 0 0 6px rgba(74,222,128,0.7); flex-shrink:0; }

  .form-heading { font-family: 'Playfair Display', serif; font-size: 32px; font-weight: 700; color: #fff; margin-bottom: 6px; line-height: 1.2; }
  .form-heading em { color: var(--gold); font-style: italic; }
  .form-sub { font-size: 12px; color: var(--muted); letter-spacing: 0.04em; margin-bottom: 32px; }

  .field-group { margin-bottom: 22px; }
  .field-label { display: block; font-size: 9px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--muted); margin-bottom: 9px; transition: color 0.2s; }
  .field-wrap { position: relative; }
  .field-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 13px; pointer-events: none; transition: color 0.2s; }
  .field-wrap:focus-within .field-icon { color: var(--gold); }

  input[type=text], input[type=email], input[type=password] {
    width: 100%; background: var(--surface);
    border: 1px solid rgba(255,255,255,0.07); border-radius: 13px;
    padding: 14px 14px 14px 42px;
    font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 400;
    color: var(--text); outline: none; transition: all 0.25s ease;
  }
  input::placeholder { color: #2E3352; }
  input:focus { border-color: var(--gold); background: var(--surface2); box-shadow: 0 0 0 3px rgba(201,168,76,0.09); }

  .pw-toggle { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--muted); cursor: pointer; font-size: 14px; transition: color 0.2s; padding: 4px; }
  .pw-toggle:hover { color: var(--gold); }

  .field-label-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 9px; }
  .forgot-link { font-size: 9px; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); text-decoration: none; font-weight: 700; transition: color 0.2s; }
  .forgot-link:hover { color: var(--gold); }

  .remember-label { display: flex; align-items: center; gap: 9px; font-size: 11px; color: var(--muted); cursor: pointer; font-weight: 500; margin-bottom: 28px; }
  .remember-label input[type=checkbox] { width: 16px; height: 16px; accent-color: var(--gold); }

  .btn-primary {
    width: 100%; padding: 15px;
    background: linear-gradient(135deg, #D4A843 0%, #C9A84C 50%, #9A7420 100%);
    color: #0A0C12; border: none; border-radius: 14px; cursor: pointer;
    font-family: 'DM Sans', sans-serif; font-size: 11px; font-weight: 700;
    letter-spacing: 0.2em; text-transform: uppercase;
    transition: all 0.3s ease;
    box-shadow: 0 8px 32px rgba(201,168,76,0.28), 0 2px 8px rgba(0,0,0,0.4);
    position: relative; overflow: hidden; margin-bottom: 14px;
  }
  .btn-primary::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, transparent 55%); opacity:0; transition: opacity 0.3s; }
  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 40px rgba(201,168,76,0.42), 0 4px 12px rgba(0,0,0,0.5); }
  .btn-primary:hover::after { opacity: 1; }
  .btn-primary:active { transform: translateY(0); }

  .btn-ghost {
    width: 100%; padding: 14px; background: transparent; color: var(--muted);
    border: 1px solid rgba(255,255,255,0.08); border-radius: 14px; cursor: pointer;
    font-family: 'DM Sans', sans-serif; font-size: 11px; font-weight: 600;
    letter-spacing: 0.12em; text-transform: uppercase; transition: all 0.25s ease;
    text-decoration: none; display: block; text-align: center;
  }
  .btn-ghost:hover { border-color: var(--gold); color: var(--gold); background: rgba(201,168,76,0.05); }

  .form-footer-note { text-align: center; margin-top: 24px; font-size: 9px; letter-spacing: 0.12em; color: #252840; text-transform: uppercase; }
  .form-footer-note a { color: var(--gold-dark); text-decoration: none; }
  .form-footer-note a:hover { color: var(--gold); }

  .form-panel::-webkit-scrollbar { width: 4px; }
  .form-panel::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

  @media (max-width: 768px) { .brand-panel { display: none; } .form-panel { padding: 32px 20px; } }
</style>
</head>
<body>
<canvas id="canvas-bg"></canvas>
<div class="page">

  <!-- BRAND PANEL -->
  <aside class="brand-panel">
    <div class="ring ring-1"></div>
    <div class="ring ring-2"></div>
    <div class="ring ring-3"></div>

    <div class="ticket-card">
      <div class="ticket-label">Today's Jackpot</div>
      <div class="ticket-amount">৳ 5,00,000</div>
      <div class="ticket-sub">Draw at midnight · 3,241 entries</div>
    </div>

    <div class="brand-logo">
      <div class="logo-icon">🎲</div>
      <div>
        <div class="logo-text">LuckyDraw Pro</div>
        <div class="logo-sub">v4.0 · Premium Edition</div>
      </div>
    </div>

    <div class="brand-center">
      <h2 class="brand-headline">Your Luck<br>Starts <em>Here</em></h2>
      <p class="brand-desc">Bangladesh's most trusted lucky draw platform. Real prizes, transparent draws — every single day.</p>
      <div class="stats-row">
        <div class="stat">
          <div class="stat-num">64K+</div>
          <div class="stat-label">Members</div>
        </div>
        <div class="stat">
          <div class="stat-num">৳2.4Cr</div>
          <div class="stat-label">Paid Out</div>
        </div>
        <div class="stat">
          <div class="stat-num">100%</div>
          <div class="stat-label">Verified</div>
        </div>
      </div>
    </div>

    <div class="brand-footer">© 2025 LuckyDraw Pro · All rights reserved</div>
  </aside>

  <!-- FORM PANEL -->
  <main class="form-panel">
    <div class="form-card">
      <div class="security-badge">
        <div class="dot"></div>
        Secured · 256-bit SSL Encrypted
      </div>

      <h2 class="form-heading">Welcome <em>Back</em></h2>
      <p class="form-sub">Sign in to access your lucky draws</p>

      <div class="field-group">
        <label class="field-label">Phone or Email</label>
        <div class="field-wrap">
          <span class="field-icon">✦</span>
          <input type="text" placeholder="Enter phone or email" />
        </div>
      </div>

      <div class="field-group">
        <div class="field-label-row">
          <label class="field-label" style="margin-bottom:0">Password</label>
          <a href="#" class="forgot-link">Forgot Password?</a>
        </div>
        <div class="field-wrap">
          <span class="field-icon">◈</span>
          <input type="password" id="pw-login" placeholder="Enter your password" />
          <button class="pw-toggle" onclick="togglePw('pw-login',this)" type="button">👁</button>
        </div>
      </div>

      <label class="remember-label">
        <input type="checkbox" checked /> Remember me for 30 days
      </label>

      <button class="btn-primary" type="button">✦ &nbsp; Sign In Now</button>
      <a href="register.html" class="btn-ghost">Create New Account</a>

      <div class="form-footer-note">
        LuckyDraw Pro / v4.0 &nbsp;·&nbsp; <a href="#">Terms</a> &nbsp;·&nbsp; <a href="#">Privacy</a>
      </div>
    </div>
  </main>
</div>

<script>
function togglePw(id, btn) {
  const el = document.getElementById(id);
  el.type = el.type === 'password' ? 'text' : 'password';
  btn.textContent = el.type === 'password' ? '👁' : '🙈';
}
(function(){
  const canvas = document.getElementById('canvas-bg');
  const ctx = canvas.getContext('2d');
  let W, H, particles = [];
  function resize() { W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
  resize(); window.addEventListener('resize', resize);
  for(let i=0;i<80;i++) particles.push({ x:Math.random()*1920, y:Math.random()*1080, r:Math.random()*1.4+0.3, dx:(Math.random()-0.5)*0.28, dy:(Math.random()-0.5)*0.28, a:Math.random(), gold:Math.random()>0.55 });
  function draw() {
    ctx.clearRect(0,0,W,H);
    particles.forEach(p => {
      p.x+=p.dx; p.y+=p.dy;
      if(p.x<0)p.x=W; if(p.x>W)p.x=0; if(p.y<0)p.y=H; if(p.y>H)p.y=0;
      ctx.beginPath(); ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
      ctx.fillStyle = p.gold ? `rgba(201,168,76,${p.a*0.65})` : `rgba(100,110,210,${p.a*0.4})`;
      ctx.fill();
    });
    for(let i=0;i<particles.length;i++) for(let j=i+1;j<particles.length;j++) {
      const dx=particles[i].x-particles[j].x, dy=particles[i].y-particles[j].y, d=Math.sqrt(dx*dx+dy*dy);
      if(d<120){ ctx.beginPath(); ctx.moveTo(particles[i].x,particles[i].y); ctx.lineTo(particles[j].x,particles[j].y); ctx.strokeStyle=`rgba(201,168,76,${(1-d/120)*0.07})`; ctx.lineWidth=0.5; ctx.stroke(); }
    }
    requestAnimationFrame(draw);
  }
  draw();
})();
</script>
</body>
</html>