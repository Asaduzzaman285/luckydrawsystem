<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LuckyDraw Pro — Create Account</title>
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
  html, body { height: 100%; font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--text); }

  #canvas-bg { position: fixed; inset: 0; z-index: 0; opacity: 0.35; }

  .page { position: relative; z-index: 1; display: flex; align-items: stretch; min-height: 100vh; width: 100vw; }

  /* ── BRAND PANEL ── */
  .brand-panel {
    flex: 0 0 40%;
    display: flex; flex-direction: column; justify-content: space-between;
    padding: 52px 48px;
    background: linear-gradient(145deg, #0E1120 0%, #161A2C 55%, #1B1030 100%);
    border-right: 1px solid var(--border);
    position: sticky; top: 0; height: 100vh; overflow: hidden;
  }
  .brand-panel::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 65% at 20% 70%, rgba(201,168,76,0.09) 0%, transparent 65%),
                radial-gradient(ellipse 55% 70% at 85% 15%, rgba(90,70,200,0.06) 0%, transparent 60%);
    pointer-events: none;
  }

  .ring { position: absolute; border-radius: 50%; border: 1px solid rgba(201,168,76,0.1); animation: spin linear infinite; }
  .ring-1 { width: 440px; height: 440px; top: -90px; right: -170px; animation-duration: 42s; }
  .ring-2 { width: 260px; height: 260px; bottom: 60px; left: -80px; animation-duration: 30s; animation-direction: reverse; }
  .ring-3 { width: 150px; height: 150px; top: 42%; right: 50px; animation-duration: 19s; border-color: rgba(201,168,76,0.06); }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* step progress visual */
  .steps-visual {
    position: absolute; bottom: 130px; right: 44px;
    display: flex; flex-direction: column; gap: 12px;
    z-index: 2;
  }
  .step-item { display: flex; align-items: center; gap: 10px; }
  .step-dot {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700; flex-shrink: 0;
  }
  .step-dot.done { background: linear-gradient(135deg, var(--gold), var(--gold-dark)); color: #0A0C12; box-shadow: 0 0 16px rgba(201,168,76,0.35); }
  .step-dot.pending { background: var(--surface2); border: 1px solid var(--border); color: var(--muted); }
  .step-text { font-size: 11px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; }
  .step-text.done { color: var(--gold-light); }
  .step-text.pending { color: var(--muted); }

  .brand-logo { display: flex; align-items: center; gap: 14px; position: relative; z-index: 2; }
  .logo-icon { width: 48px; height: 48px; background: linear-gradient(135deg, var(--gold), var(--gold-dark)); border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 22px; box-shadow: 0 0 28px rgba(201,168,76,0.35); }
  .logo-text { font-family: 'Playfair Display', serif; font-size: 15px; font-weight: 700; color: var(--gold-light); line-height: 1.2; }
  .logo-sub { font-size: 9px; letter-spacing: 0.2em; color: var(--muted); text-transform: uppercase; margin-top: 2px; }

  .brand-center { position: relative; z-index: 2; }
  .brand-headline { font-family: 'Playfair Display', serif; font-size: clamp(34px,3.5vw,52px); font-weight: 700; line-height: 1.12; color: #fff; margin-bottom: 18px; }
  .brand-headline em { color: var(--gold); font-style: italic; }
  .brand-desc { font-size: 14px; line-height: 1.75; color: var(--muted); max-width: 280px; font-weight: 300; margin-bottom: 30px; }

  .perks { display: flex; flex-direction: column; gap: 13px; }
  .perk { display: flex; align-items: center; gap: 12px; }
  .perk-icon { width: 32px; height: 32px; border-radius: 10px; background: rgba(201,168,76,0.1); border: 1px solid rgba(201,168,76,0.2); display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
  .perk-text { font-size: 12px; color: var(--muted); font-weight: 400; line-height: 1.4; }
  .perk-text strong { color: var(--text); font-weight: 600; display: block; font-size: 12px; }

  .brand-footer { position: relative; z-index: 2; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; color: var(--muted); opacity: 0.4; }

  /* ── FORM PANEL ── */
  .form-panel {
    flex: 1; display: flex; align-items: flex-start; justify-content: center;
    padding: 52px 44px; position: relative; overflow-y: auto;
  }
  .form-panel::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 55% 45% at 65% 25%, rgba(201,168,76,0.04) 0%, transparent 65%);
    pointer-events: none;
  }

  .form-card { width: 100%; max-width: 440px; position: relative; z-index: 2; animation: fadeUp 0.6s ease; padding-bottom: 40px; }
  @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }

  .form-top-bar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 32px;
  }
  .back-link {
    display: flex; align-items: center; gap: 7px;
    font-size: 11px; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase;
    color: var(--muted); text-decoration: none; transition: color 0.2s;
  }
  .back-link:hover { color: var(--gold); }
  .step-counter { font-size: 10px; letter-spacing: 0.12em; text-transform: uppercase; color: var(--muted); }
  .step-counter span { color: var(--gold); font-weight: 700; }

  .form-heading { font-family: 'Playfair Display', serif; font-size: 30px; font-weight: 700; color: #fff; margin-bottom: 6px; line-height: 1.2; }
  .form-heading em { color: var(--gold); font-style: italic; }
  .form-sub { font-size: 12px; color: var(--muted); letter-spacing: 0.04em; margin-bottom: 30px; }

  /* progress bar */
  .progress-bar { height: 3px; background: var(--surface2); border-radius: 2px; margin-bottom: 30px; overflow: hidden; }
  .progress-fill { height: 100%; width: 100%; background: linear-gradient(90deg, var(--gold-dark), var(--gold)); border-radius: 2px; }

  /* section label */
  .section-label {
    font-size: 9px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase;
    color: var(--gold); margin-bottom: 16px; margin-top: 4px;
    display: flex; align-items: center; gap: 8px;
  }
  .section-label::after { content: ''; flex: 1; height: 1px; background: rgba(201,168,76,0.15); }

  .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
  .field-group { margin-bottom: 18px; }

  .field-label { display: block; font-size: 9px; font-weight: 700; letter-spacing: 0.22em; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; transition: color 0.2s; }
  .field-wrap { position: relative; }
  .field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 13px; pointer-events: none; transition: color 0.2s; }
  .field-wrap:focus-within .field-icon { color: var(--gold); }

  input[type=text], input[type=email], input[type=password], select {
    width: 100%; background: var(--surface);
    border: 1px solid rgba(255,255,255,0.07); border-radius: 12px;
    padding: 13px 14px 13px 40px;
    font-family: 'DM Sans', sans-serif; font-size: 13px; font-weight: 400;
    color: var(--text); outline: none; transition: all 0.25s ease;
    appearance: none;
  }
  input::placeholder { color: #2A2E48; }
  input:focus, select:focus { border-color: var(--gold); background: var(--surface2); box-shadow: 0 0 0 3px rgba(201,168,76,0.09); }

  .pw-toggle { position: absolute; right: 13px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--muted); cursor: pointer; font-size: 14px; transition: color 0.2s; padding: 4px; }
  .pw-toggle:hover { color: var(--gold); }

  .select-wrap::after { content: '▾'; position: absolute; right: 14px; top: 50%; transform: translateY(-50%); color: var(--muted); pointer-events: none; font-size: 11px; }
  .select-wrap select { padding-right: 32px; }
  select option { background: #12151F; }

  /* optional badge */
  .optional-badge { font-size: 8px; letter-spacing: 0.1em; text-transform: uppercase; background: rgba(201,168,76,0.1); color: var(--gold); border-radius: 4px; padding: 2px 6px; margin-left: 6px; font-weight: 700; }

  .btn-primary {
    width: 100%; padding: 15px;
    background: linear-gradient(135deg, #D4A843 0%, #C9A84C 50%, #9A7420 100%);
    color: #0A0C12; border: none; border-radius: 14px; cursor: pointer;
    font-family: 'DM Sans', sans-serif; font-size: 11px; font-weight: 700;
    letter-spacing: 0.2em; text-transform: uppercase;
    transition: all 0.3s ease;
    box-shadow: 0 8px 32px rgba(201,168,76,0.28), 0 2px 8px rgba(0,0,0,0.4);
    position: relative; overflow: hidden; margin-top: 8px; margin-bottom: 14px;
  }
  .btn-primary::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, transparent 55%); opacity:0; transition: opacity 0.3s; }
  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 40px rgba(201,168,76,0.42); }
  .btn-primary:hover::after { opacity: 1; }
  .btn-primary:active { transform: translateY(0); }

  .signin-link { text-align: center; font-size: 11px; color: var(--muted); margin-top: 6px; }
  .signin-link a { color: var(--gold); text-decoration: none; font-weight: 600; margin-left: 5px; }
  .signin-link a:hover { color: var(--gold-light); }

  .terms-note { text-align: center; font-size: 9px; color: #252840; letter-spacing: 0.08em; margin-top: 18px; line-height: 1.7; }
  .terms-note a { color: var(--gold-dark); text-decoration: none; }
  .terms-note a:hover { color: var(--gold); }

  .form-panel::-webkit-scrollbar { width: 4px; }
  .form-panel::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

  @media (max-width: 768px) { .brand-panel { display: none; } .form-panel { padding: 32px 20px; } .field-row { grid-template-columns: 1fr; } }
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

    <div class="steps-visual">
      <div class="step-item">
        <div class="step-dot done">✓</div>
        <div class="step-text done">Personal Info</div>
      </div>
      <div class="step-item">
        <div class="step-dot done">✓</div>
        <div class="step-text done">Location</div>
      </div>
      <div class="step-item">
        <div class="step-dot done">✓</div>
        <div class="step-text done">Security</div>
      </div>
    </div>

    <div class="brand-logo">
      <div class="logo-icon">🎲</div>
      <div>
        <div class="logo-text">LuckyDraw Pro</div>
        <div class="logo-sub">v4.0 · Premium Edition</div>
      </div>
    </div>

    <div class="brand-center">
      <h2 class="brand-headline">Join the<br><em>Winning</em><br>Community</h2>
      <p class="brand-desc">Thousands of members win every week. Your first draw could change everything.</p>
      <div class="perks">
        <div class="perk">
          <div class="perk-icon">🎁</div>
          <div class="perk-text">
            <strong>Free Welcome Draw</strong>
            Get one free entry just for joining
          </div>
        </div>
        <div class="perk">
          <div class="perk-icon">⚡</div>
          <div class="perk-text">
            <strong>Instant Results</strong>
            Live draws with real-time notifications
          </div>
        </div>
        <div class="perk">
          <div class="perk-icon">🔒</div>
          <div class="perk-text">
            <strong>100% Secure</strong>
            Bank-grade encryption, verified payouts
          </div>
        </div>
      </div>
    </div>

    <div class="brand-footer">© 2025 LuckyDraw Pro · All rights reserved</div>
  </aside>

  <!-- FORM PANEL -->
  <main class="form-panel">
    <div class="form-card">

      <div class="form-top-bar">
        <a href="login.html" class="back-link">← Back to Sign In</a>
        <div class="step-counter">Step <span>1</span> of 1</div>
      </div>

      <div class="progress-bar"><div class="progress-fill"></div></div>

      <h2 class="form-heading">Create <em>Account</em></h2>
      <p class="form-sub">Join our lucky draw community — it's free</p>

      <!-- PERSONAL -->
      <div class="section-label">Personal Details</div>

      <div class="field-row">
        <div class="field-group">
          <label class="field-label">Full Name</label>
          <div class="field-wrap">
            <span class="field-icon">✦</span>
            <input type="text" placeholder="Your full name" />
          </div>
        </div>
        <div class="field-group">
          <label class="field-label">Phone Number</label>
          <div class="field-wrap">
            <span class="field-icon">◎</span>
            <input type="text" placeholder="01711223344" />
          </div>
        </div>
      </div>

      <!-- LOCATION -->
      <div class="section-label">Location</div>

      <div class="field-row">
        <div class="field-group">
          <label class="field-label">District</label>
          <div class="field-wrap select-wrap">
            <span class="field-icon">◈</span>
            <select id="district_id" onchange="fetchUpazillas(this.value)">
              <option value="">Select District</option>
              <option>Dhaka</option>
              <option>Rajshahi</option>
              <option>Chittagong</option>
              <option>Khulna</option>
              <option>Sylhet</option>
              <option>Barisal</option>
              <option>Rangpur</option>
              <option>Mymensingh</option>
            </select>
          </div>
        </div>
        <div class="field-group">
          <label class="field-label">Upazilla</label>
          <div class="field-wrap select-wrap">
            <span class="field-icon">◈</span>
            <select id="upazilla_id">
              <option value="">Select Upazilla</option>
            </select>
          </div>
        </div>
      </div>

      <!-- EMAIL -->
      <div class="field-group">
        <label class="field-label">Email Address <span class="optional-badge">Optional</span></label>
        <div class="field-wrap">
          <span class="field-icon">@</span>
          <input type="email" placeholder="email@example.com" />
        </div>
      </div>

      <!-- SECURITY -->
      <div class="section-label">Security</div>

      <div class="field-row">
        <div class="field-group">
          <label class="field-label">Password</label>
          <div class="field-wrap">
            <span class="field-icon">◈</span>
            <input type="password" id="pw-reg1" placeholder="Create password" />
            <button class="pw-toggle" onclick="togglePw('pw-reg1',this)" type="button">👁</button>
          </div>
        </div>
        <div class="field-group">
          <label class="field-label">Confirm</label>
          <div class="field-wrap">
            <span class="field-icon">◈</span>
            <input type="password" id="pw-reg2" placeholder="Verify password" />
            <button class="pw-toggle" onclick="togglePw('pw-reg2',this)" type="button">👁</button>
          </div>
        </div>
      </div>

      <button class="btn-primary" type="button">✦ &nbsp; Create My Account</button>

      <div class="signin-link">Already have an account? <a href="login.html">Sign In</a></div>

      <div class="terms-note">
        By joining you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
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
function fetchUpazillas(districtId) {
  const sel = document.getElementById('upazilla_id');
  const map = {
    'Dhaka':['Dhanmondi','Gulshan','Mirpur','Uttara','Mohammadpur'],
    'Rajshahi':['Boalia','Motihar','Rajpara','Shah Makhdum'],
    'Chittagong':['Agrabad','Halishahar','Pahartali','Patenga'],
    'Khulna':['Sonadanga','Khalishpur','Daulatpur'],
    'Sylhet':['Sylhet Sadar','Beanibazar','Companiganj'],
    'Barisal':['Barisal Sadar','Babuganj','Bakerganj'],
    'Rangpur':['Rangpur Sadar','Badarganj','Gangachhara'],
    'Mymensingh':['Mymensingh Sadar','Bhaluka','Fulbaria']
  };
  sel.innerHTML = '<option value="">Select Upazilla</option>';
  if (!districtId || !map[districtId]) return;
  map[districtId].forEach(u => {
    const o = document.createElement('option'); o.value = u; o.text = u; sel.appendChild(o);
  });
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