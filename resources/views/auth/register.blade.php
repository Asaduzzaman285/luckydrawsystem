<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ShopWin — Create Account</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --purple: #6C2FF2; --purple-dark: #4A1DB0; --purple-mid: #8B4FF8;
    --electric: #FFD43B; --coral: #FF6B6B; --mint: #00D68F;
    --bg: #FAFBFF; --surface: #F0F2FF; --border: #E4E7F8;
    --text: #1A1A2E; --muted: #7B82A8; --white: #FFFFFF;
  }
  html, body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; }
  .page { display: flex; min-height: 100vh; }

  /* ══ BRAND ══ */
  .brand {
    flex: 0 0 42%; position: sticky; top: 0; height: 100vh; overflow: hidden;
    background: linear-gradient(155deg, #13073A 0%, #2B1080 45%, #5020C8 80%, #7B3EF5 100%);
    display: flex; flex-direction: column; justify-content: space-between; padding: 42px 44px;
  }
  .glow { position: absolute; border-radius: 50%; filter: blur(70px); pointer-events: none; }
  .glow-1 { width: 300px; height: 300px; background: rgba(255,212,59,0.2); top: -80px; right: -60px; animation: breathe 7s ease-in-out infinite; }
  .glow-2 { width: 200px; height: 200px; background: rgba(0,214,143,0.12); bottom: 80px; left: -60px; animation: breathe 9s ease-in-out infinite 2s; }
  .glow-3 { width: 150px; height: 150px; background: rgba(255,107,107,0.14); top: 48%; right: 20px; animation: breathe 5s ease-in-out infinite 3s; }
  @keyframes breathe { 0%,100%{transform:scale(1);opacity:1;} 50%{transform:scale(1.2);opacity:0.65;} }

  .brand-logo { position: relative; z-index: 3; display: flex; align-items: center; gap: 12px; }
  .logo-box { width: 44px; height: 44px; background: var(--electric); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; box-shadow: 0 0 28px rgba(255,212,59,0.5); }
  .logo-name { font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 700; color: #fff; }
  .logo-name span { color: var(--electric); }
  .logo-tag { font-size: 9px; letter-spacing: 0.18em; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-top: 2px; }

  .brand-center { position: relative; z-index: 3; }

  .headline { font-family: 'Space Grotesk', sans-serif; font-size: clamp(28px,2.8vw,42px); font-weight: 700; line-height: 1.12; color: #fff; margin-bottom: 14px; }
  .headline .hl { color: var(--electric); }
  .sub-desc { font-size: 13px; line-height: 1.7; color: rgba(255,255,255,0.5); max-width: 280px; margin-bottom: 24px; }

  /* leaderboard preview */
  .lb-card {
    background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.12);
    border-radius: 18px; padding: 18px; margin-bottom: 16px;
  }
  .lb-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
  .lb-title { font-family: 'Space Grotesk', sans-serif; font-size: 12px; font-weight: 700; color: #fff; letter-spacing: 0.06em; text-transform: uppercase; }
  .lb-live { font-size: 9px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--coral); background: rgba(255,107,107,0.15); border-radius: 100px; padding: 3px 10px; }
  .lb-row { display: flex; align-items: center; gap: 10px; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,0.07); }
  .lb-row:last-child { border-bottom: none; padding-bottom: 0; }
  .lb-rank { width: 24px; height: 24px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-family: 'Space Grotesk', sans-serif; font-size: 12px; font-weight: 700; flex-shrink: 0; }
  .r1 { background: rgba(255,212,59,0.25); color: var(--electric); }
  .r2 { background: rgba(255,255,255,0.15); color: rgba(255,255,255,0.8); }
  .r3 { background: rgba(255,107,107,0.2); color: var(--coral); }
  .lb-name { flex: 1; font-size: 12px; font-weight: 600; color: rgba(255,255,255,0.85); }
  .lb-district { font-size: 10px; color: rgba(255,255,255,0.4); }
  .lb-earned { font-family: 'Space Grotesk', sans-serif; font-size: 12px; font-weight: 700; color: var(--electric); }

  /* how it works */
  .how-steps { display: flex; align-items: center; gap: 0; }
  .step { display: flex; flex-direction: column; align-items: center; text-align: center; flex: 1; }
  .step-num { width: 32px; height: 32px; border-radius: 10px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 16px; margin-bottom: 8px; }
  .step-t { font-size: 10px; font-weight: 700; color: #fff; margin-bottom: 2px; }
  .step-s { font-size: 9px; color: rgba(255,255,255,0.4); line-height: 1.4; }
  .step-arrow { font-size: 16px; color: rgba(255,255,255,0.2); flex-shrink: 0; margin: 0 4px; padding-bottom: 20px; }

  /* ticker */
  .ticker-bar { position: relative; z-index: 3; display: flex; align-items: center; gap: 10px; background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1); border-radius: 100px; padding: 8px 16px; overflow: hidden; }
  .t-label { font-size: 9px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--coral); flex-shrink: 0; }
  .t-track { flex: 1; overflow: hidden; }
  .t-inner { display: flex; gap: 28px; animation: scroll 22s linear infinite; white-space: nowrap; }
  @keyframes scroll { 0%{transform:translateX(0);} 100%{transform:translateX(-50%);} }
  .t-item { font-size: 11px; color: rgba(255,255,255,0.55); flex-shrink: 0; }
  .t-item b { color: var(--electric); }

  /* ══ FORM ══ */
  .form-side {
    flex: 1; background: var(--white);
    display: flex; align-items: flex-start; justify-content: center;
    padding: 44px 44px 60px; overflow-y: auto; position: relative;
  }
  .form-side::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:linear-gradient(90deg,#6C2FF2,#FFD43B,#FF6B6B,#00D68F); }

  .fc { width: 100%; max-width: 400px; animation: up 0.5s ease; }
  @keyframes up { from{opacity:0;transform:translateY(18px);} to{opacity:1;transform:translateY(0);} }

  .form-topbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
  .back-l { display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; color: var(--muted); text-decoration: none; transition: color 0.2s; letter-spacing: 0.04em; }
  .back-l:hover { color: var(--purple); }
  .bonus-pill { display: flex; align-items: center; gap: 6px; background: linear-gradient(135deg,#FFFAE6,#FFF0A0); border: 1.5px solid var(--electric); border-radius: 100px; padding: 5px 12px; }
  .bonus-pill span { font-size: 10px; font-weight: 700; color: #7A5400; }

  .fh { font-family: 'Space Grotesk', sans-serif; font-size: 26px; font-weight: 700; color: var(--text); margin-bottom: 5px; }
  .fh em { color: var(--purple); font-style: normal; }
  .fs { font-size: 12px; color: var(--muted); margin-bottom: 24px; }

  /* progress */
  .prog-bar { height: 4px; background: var(--surface); border-radius: 4px; margin-bottom: 26px; overflow: hidden; }
  .prog-fill { height: 100%; background: linear-gradient(90deg, var(--purple), var(--electric)); border-radius: 4px; width: 100%; }

  /* section dividers */
  .sec-div {
    display: flex; align-items: center; gap: 10px;
    font-size: 10px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase;
    color: var(--purple); margin-bottom: 14px; margin-top: 6px;
  }
  .sec-div::after { content:''; flex:1; height:1.5px; background: linear-gradient(90deg,rgba(108,47,242,0.25),transparent); border-radius:2px; }

  .fg { margin-bottom: 16px; }
  .fg-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px; }
  .fl { display: block; font-size: 11px; font-weight: 700; color: #4A5080; margin-bottom: 7px; }
  .opt-tag { font-size: 8px; background: rgba(108,47,242,0.1); color: var(--purple); border-radius: 4px; padding: 2px 6px; margin-left: 6px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; }
  .fw { position: relative; }
  .fi { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 14px; opacity: 0.4; pointer-events: none; transition: opacity 0.2s; }
  .fw:focus-within .fi { opacity: 1; }

  input[type=text], input[type=email], input[type=password], select {
    width: 100%; background: var(--surface);
    border: 2px solid var(--border); border-radius: 12px;
    padding: 12px 13px 12px 42px;
    font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 500;
    color: var(--text); outline: none; transition: all 0.22s; appearance: none;
  }
  input::placeholder { color: #C0C6E0; font-weight: 400; }
  input:focus, select:focus { border-color: var(--purple); background: #fff; box-shadow: 0 0 0 4px rgba(108,47,242,0.07); }

  .pw-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 15px; opacity: 0.3; transition: opacity 0.2s; }
  .pw-btn:hover { opacity: 1; }

  .sel-wrap::after { content:'▾'; position:absolute; right:13px; top:50%; transform:translateY(-50%); color:var(--muted); pointer-events:none; font-size:11px; }
  .sel-wrap select { padding-right: 30px; }
  select option { background: #fff; }

  /* password strength */
  .pw-strength { display: flex; gap: 4px; margin-top: 6px; }
  .ps-bar { flex: 1; height: 3px; border-radius: 2px; background: var(--border); transition: background 0.3s; }
  .ps-bar.weak { background: var(--coral); }
  .ps-bar.medium { background: var(--electric); }
  .ps-bar.strong { background: var(--mint); }

  .btn-p {
    width: 100%; padding: 15px;
    background: linear-gradient(135deg, #4A1DB0, #6C2FF2, #8B4FF8);
    color: #fff; border: none; border-radius: 13px; cursor: pointer;
    font-family: 'Space Grotesk', sans-serif; font-size: 13px; font-weight: 700;
    letter-spacing: 0.04em; transition: all 0.28s;
    box-shadow: 0 8px 24px rgba(108,47,242,0.35);
    margin-top: 8px; margin-bottom: 14px; position: relative; overflow: hidden;
  }
  .btn-p::after { content:''; position:absolute; inset:0; background:linear-gradient(135deg,rgba(255,255,255,0.15),transparent 55%); opacity:0; transition:opacity 0.3s; }
  .btn-p:hover { transform:translateY(-2px); box-shadow:0 14px 36px rgba(108,47,242,0.45); }
  .btn-p:hover::after { opacity:1; }

  /* what you get on join */
  .join-perks {
    display: flex; gap: 8px; margin-bottom: 18px;
    background: linear-gradient(135deg, #F0F2FF, #EEF0FF);
    border: 1.5px solid rgba(108,47,242,0.15); border-radius: 14px; padding: 14px;
  }
  .jp { flex: 1; text-align: center; }
  .jp-val { font-family: 'Space Grotesk', sans-serif; font-size: 16px; font-weight: 700; color: var(--purple); }
  .jp-label { font-size: 9px; color: var(--muted); font-weight: 600; margin-top: 2px; letter-spacing: 0.04em; }
  .jp-div { width: 1px; background: var(--border); flex-shrink: 0; }

  .signin-note { text-align: center; font-size: 12px; color: var(--muted); margin-top: 4px; }
  .signin-note a { color: var(--purple); font-weight: 700; text-decoration: none; }
  .signin-note a:hover { color: var(--purple-dark); }
  .terms-note { text-align: center; font-size: 10px; color: #B0B6D0; margin-top: 14px; line-height: 1.6; }
  .terms-note a { color: var(--muted); text-decoration: underline; }

  .form-side::-webkit-scrollbar { width: 4px; }
  .form-side::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
  @media(max-width:768px){ .brand{display:none;} .fg-row{grid-template-columns:1fr;} }
</style>
</head>
<body>
<div class="page">

  <!-- BRAND -->
  <aside class="brand">
    <div class="glow glow-1"></div>
    <div class="glow glow-2"></div>
    <div class="glow glow-3"></div>

    <div class="brand-logo">
      <div class="logo-box">🛍</div>
      <div>
        <div class="logo-name">Shop<span>Win</span></div>
        <div class="logo-tag">Shop · Earn Cashback · Win Big</div>
      </div>
    </div>

    <div class="brand-center">
      <h2 class="headline">Join <span class="hl">64,000+</span><br>Smart Shoppers<br>Winning Daily</h2>
      <p class="sub-desc">Every purchase earns cashback. Every order is a lottery ticket. Top buyers win leaderboard prizes monthly.</p>

      <!-- leaderboard preview -->
      <div class="lb-card">
        <div class="lb-header">
          <div class="lb-title">📊 This Month's Leaderboard</div>
          <div class="lb-live">Live</div>
        </div>
        <div class="lb-row">
          <div class="lb-rank r1">🥇</div>
          <div class="lb-name">Karim M. <span class="lb-district">· Dhaka</span></div>
          <div class="lb-earned">৳12,400</div>
        </div>
        <div class="lb-row">
          <div class="lb-rank r2">2</div>
          <div class="lb-name">Sumaiya A. <span class="lb-district">· Rajshahi</span></div>
          <div class="lb-earned">৳9,850</div>
        </div>
        <div class="lb-row">
          <div class="lb-rank r3">3</div>
          <div class="lb-name">Nadia B. <span class="lb-district">· Ctg</span></div>
          <div class="lb-earned">৳7,200</div>
        </div>
      </div>

      <!-- how it works -->
      <div class="how-steps">
        <div class="step">
          <div class="step-num">🛍</div>
          <div class="step-t">Shop</div>
          <div class="step-s">Buy from our store</div>
        </div>
        <div class="step-arrow">→</div>
        <div class="step">
          <div class="step-num">💸</div>
          <div class="step-t">Earn</div>
          <div class="step-s">Get cashback instantly</div>
        </div>
        <div class="step-arrow">→</div>
        <div class="step">
          <div class="step-num">🎰</div>
          <div class="step-t">Win</div>
          <div class="step-s">Weekly jackpot draws</div>
        </div>
      </div>
    </div>

    <div class="ticker-bar">
      <div class="t-label">🔴 Live</div>
      <div class="t-track">
        <div class="t-inner">
          <span class="t-item">Rahim K. won <b>৳25,000</b> ·</span>
          <span class="t-item">Sumaiya A. hit <b>#1 Leaderboard</b> 🏆 ·</span>
          <span class="t-item">Karim M. won <b>৳2,50,000 Jackpot!</b> 🎉 ·</span>
          <span class="t-item">Nadia B. earned <b>৳8,400</b> cashback ·</span>
          <span class="t-item">Rahim K. won <b>৳25,000</b> ·</span>
          <span class="t-item">Sumaiya A. hit <b>#1 Leaderboard</b> 🏆 ·</span>
          <span class="t-item">Karim M. won <b>৳2,50,000 Jackpot!</b> 🎉 ·</span>
          <span class="t-item">Nadia B. earned <b>৳8,400</b> cashback ·</span>
        </div>
      </div>
    </div>
  </aside>

  <!-- FORM -->
  <main class="form-side">
    <div class="fc">
      <div class="form-topbar">
        <a href="login.html" class="back-l">← Back to Sign In</a>
        <div class="bonus-pill"><span>🎁 ৳200 Welcome Bonus on Join!</span></div>
      </div>

      <div class="prog-bar"><div class="prog-fill"></div></div>

      <div class="fh">Create <em>Account</em></div>
      <div class="fs">Join free and start shopping, earning & winning today</div>

      <!-- what you get -->
      <div class="join-perks">
        <div class="jp">
          <div class="jp-val">৳200</div>
          <div class="jp-label">Welcome Bonus</div>
        </div>
        <div class="jp-div"></div>
        <div class="jp">
          <div class="jp-val">15%</div>
          <div class="jp-label">Max Cashback</div>
        </div>
        <div class="jp-div"></div>
        <div class="jp">
          <div class="jp-val">Free</div>
          <div class="jp-label">Draw Entry</div>
        </div>
      </div>

      <!-- PERSONAL -->
      <div class="sec-div">👤 Personal Info</div>
      <div class="fg-row">
        <div class="fg">
          <label class="fl">Full Name</label>
          <div class="fw">
            <span class="fi">✦</span>
            <input type="text" placeholder="Your name" />
          </div>
        </div>
        <div class="fg">
          <label class="fl">Phone Number</label>
          <div class="fw">
            <span class="fi">📱</span>
            <input type="text" placeholder="01711223344" />
          </div>
        </div>
      </div>

      <!-- LOCATION -->
      <div class="sec-div">📍 Location</div>
      <div class="fg-row">
        <div class="fg">
          <label class="fl">District</label>
          <div class="fw sel-wrap">
            <span class="fi">🗺</span>
            <select id="district_id" onchange="loadUpazilla(this.value)">
              <option value="">Select District</option>
              <option>Dhaka</option><option>Rajshahi</option><option>Chittagong</option>
              <option>Khulna</option><option>Sylhet</option><option>Barisal</option>
              <option>Rangpur</option><option>Mymensingh</option>
            </select>
          </div>
        </div>
        <div class="fg">
          <label class="fl">Upazilla</label>
          <div class="fw sel-wrap">
            <span class="fi">📌</span>
            <select id="upazilla_id"><option value="">Select Upazilla</option></select>
          </div>
        </div>
      </div>

      <div class="fg">
        <label class="fl">Email Address <span class="opt-tag">Optional</span></label>
        <div class="fw">
          <span class="fi">✉</span>
          <input type="email" placeholder="email@example.com" />
        </div>
      </div>

      <!-- SECURITY -->
      <div class="sec-div">🔐 Set Password</div>
      <div class="fg-row">
        <div class="fg">
          <label class="fl">Password</label>
          <div class="fw">
            <span class="fi">🔐</span>
            <input type="password" id="pw1" placeholder="Create password" oninput="checkStrength(this.value)" />
            <button class="pw-btn" onclick="togglePw('pw1',this)" type="button">👁</button>
          </div>
          <div class="pw-strength">
            <div class="ps-bar" id="ps1"></div>
            <div class="ps-bar" id="ps2"></div>
            <div class="ps-bar" id="ps3"></div>
          </div>
        </div>
        <div class="fg">
          <label class="fl">Confirm</label>
          <div class="fw">
            <span class="fi">🔐</span>
            <input type="password" id="pw2" placeholder="Verify password" />
            <button class="pw-btn" onclick="togglePw('pw2',this)" type="button">👁</button>
          </div>
        </div>
      </div>

      <button class="btn-p" type="button">🎰 &nbsp; Create Account & Start Winning!</button>

      <div class="signin-note">Already have an account? <a href="login.html">Sign In</a></div>
      <div class="terms-note">By joining you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></div>
    </div>
  </main>
</div>

<script>
function togglePw(id,btn) {
  const el = document.getElementById(id);
  el.type = el.type==='password'?'text':'password';
  btn.textContent = el.type==='password'?'👁':'🙈';
}
function checkStrength(val) {
  const bars = [document.getElementById('ps1'),document.getElementById('ps2'),document.getElementById('ps3')];
  bars.forEach(b => b.className='ps-bar');
  if(val.length===0) return;
  if(val.length>=4) bars[0].classList.add('weak');
  if(val.length>=8) bars[1].classList.add('medium');
  if(val.length>=12 && /[A-Z]/.test(val) && /[0-9]/.test(val)) bars[2].classList.add('strong');
}
function loadUpazilla(d) {
  const sel = document.getElementById('upazilla_id');
  const map = {
    'Dhaka':['Dhanmondi','Gulshan','Mirpur','Uttara','Mohammadpur','Tejgaon'],
    'Rajshahi':['Boalia','Motihar','Rajpara','Shah Makhdum','Paba'],
    'Chittagong':['Agrabad','Halishahar','Pahartali','Patenga','Double Mooring'],
    'Khulna':['Sonadanga','Khalishpur','Daulatpur','Khan Jahan Ali'],
    'Sylhet':['Sylhet Sadar','Beanibazar','Companiganj','Golapganj'],
    'Barisal':['Barisal Sadar','Babuganj','Bakerganj','Wazirpur'],
    'Rangpur':['Rangpur Sadar','Badarganj','Gangachhara','Mithapukur'],
    'Mymensingh':['Mymensingh Sadar','Bhaluka','Fulbaria','Muktagacha']
  };
  sel.innerHTML='<option value="">Select Upazilla</option>';
  if(!d||!map[d]) return;
  map[d].forEach(u=>{ const o=document.createElement('option'); o.value=u; o.text=u; sel.appendChild(o); });
}
</script>
</body>
</html>