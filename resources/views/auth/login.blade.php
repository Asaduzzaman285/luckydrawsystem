<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ShopWin — Sign In</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --purple: #6C2FF2; --purple-dark: #4A1DB0; --purple-mid: #8B4FF8;
    --electric: #FFD43B; --electric-dark: #E6BC1A;
    --coral: #FF6B6B; --mint: #00D68F;
    --bg: #FAFBFF; --surface: #F0F2FF; --border: #E4E7F8;
    --text: #1A1A2E; --muted: #7B82A8; --white: #FFFFFF;
  }
  html, body { height: 100%; font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); color: var(--text); overflow: hidden; }
  .page { display: flex; height: 100vh; width: 100vw; }

  /* ══ BRAND LEFT ══ */
  .brand {
    flex: 0 0 48%; position: relative; overflow: hidden;
    background: linear-gradient(155deg, #13073A 0%, #2B1080 45%, #5020C8 80%, #7B3EF5 100%);
    display: flex; flex-direction: column; justify-content: space-between; padding: 42px 46px;
  }
  .glow { position: absolute; border-radius: 50%; filter: blur(70px); pointer-events: none; }
  .glow-1 { width: 320px; height: 320px; background: rgba(255,212,59,0.2); top: -100px; right: -80px; animation: breathe 7s ease-in-out infinite; }
  .glow-2 { width: 240px; height: 240px; background: rgba(0,214,143,0.12); bottom: 60px; left: -70px; animation: breathe 9s ease-in-out infinite 2s; }
  .glow-3 { width: 160px; height: 160px; background: rgba(255,107,107,0.14); top: 45%; right: 30px; animation: breathe 6s ease-in-out infinite 4s; }
  @keyframes breathe { 0%,100%{transform:scale(1);opacity:1;} 50%{transform:scale(1.2);opacity:0.65;} }

  /* floating prize card */
  .prize-card {
    position: absolute; top: 44px; right: 46px;
    background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18);
    backdrop-filter: blur(14px); border-radius: 20px; padding: 16px 20px;
    animation: floatY 4s ease-in-out infinite; z-index: 4;
  }
  @keyframes floatY { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-8px);} }
  .prize-label { font-size: 9px; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase; color: var(--electric); margin-bottom: 4px; }
  .prize-amount { font-family: 'Space Grotesk', sans-serif; font-size: 24px; font-weight: 700; color: #fff; line-height: 1; }
  .prize-sub { font-size: 10px; color: rgba(255,255,255,0.5); margin-top: 3px; }

  /* logo */
  .brand-logo { position: relative; z-index: 3; display: flex; align-items: center; gap: 13px; }
  .logo-box {
    width: 46px; height: 46px; background: var(--electric);
    border-radius: 14px; display: flex; align-items: center; justify-content: center;
    font-size: 22px; box-shadow: 0 0 30px rgba(255,212,59,0.55);
  }
  .logo-name { font-family: 'Space Grotesk', sans-serif; font-size: 22px; font-weight: 700; color: #fff; }
  .logo-name span { color: var(--electric); }
  .logo-tagline { font-size: 9px; letter-spacing: 0.18em; text-transform: uppercase; color: rgba(255,255,255,0.4); margin-top: 2px; }

  /* center content */
  .brand-center { position: relative; z-index: 3; }
  .live-pill {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(255,107,107,0.18); border: 1px solid rgba(255,107,107,0.4);
    border-radius: 100px; padding: 5px 13px; margin-bottom: 18px;
  }
  .live-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--coral); box-shadow: 0 0 8px var(--coral); animation: blink 1.2s infinite; }
  @keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0.2;} }
  .live-text { font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--coral); }

  .headline { font-family: 'Space Grotesk', sans-serif; font-size: clamp(30px,3vw,46px); font-weight: 700; line-height: 1.1; color: #fff; margin-bottom: 14px; }
  .headline .hl { color: var(--electric); }
  .sub-desc { font-size: 13px; line-height: 1.7; color: rgba(255,255,255,0.5); max-width: 300px; margin-bottom: 26px; }

  /* reward pills */
  .reward-pills { display: flex; flex-direction: column; gap: 10px; }
  .rpill {
    display: flex; align-items: center; gap: 13px;
    background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px; padding: 13px 16px;
    transition: border-color 0.3s, transform 0.3s;
    cursor: default;
  }
  .rpill:hover { border-color: rgba(255,212,59,0.4); transform: translateX(5px); }
  .rpill-icon { width: 38px; height: 38px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0; }
  .ri-y { background: rgba(255,212,59,0.18); }
  .ri-r { background: rgba(255,107,107,0.18); }
  .ri-g { background: rgba(0,214,143,0.15); }
  .rpill-body { flex: 1; }
  .rpill-title { font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 1px; }
  .rpill-sub { font-size: 10px; color: rgba(255,255,255,0.45); }
  .rpill-val { font-family: 'Space Grotesk', sans-serif; font-size: 13px; font-weight: 700; padding: 3px 10px; border-radius: 100px; white-space: nowrap; }
  .rv-y { background: rgba(255,212,59,0.18); color: var(--electric); }
  .rv-r { background: rgba(255,107,107,0.18); color: var(--coral); }
  .rv-g { background: rgba(0,214,143,0.15); color: var(--mint); }

  /* live ticker */
  .ticker-bar {
    position: relative; z-index: 3;
    display: flex; align-items: center; gap: 10px;
    background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1);
    border-radius: 100px; padding: 9px 16px; overflow: hidden;
  }
  .t-label { font-size: 9px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--coral); flex-shrink: 0; }
  .t-track { flex: 1; overflow: hidden; }
  .t-inner { display: flex; gap: 28px; animation: scroll 20s linear infinite; white-space: nowrap; }
  @keyframes scroll { 0%{transform:translateX(0);} 100%{transform:translateX(-50%);} }
  .t-item { font-size: 11px; color: rgba(255,255,255,0.55); flex-shrink: 0; }
  .t-item b { color: var(--electric); }

  /* ══ FORM RIGHT ══ */
  .form-side {
    flex: 1; background: var(--white);
    display: flex; align-items: center; justify-content: center;
    padding: 48px 44px; overflow-y: auto; position: relative;
  }
  .form-side::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
    background: linear-gradient(90deg, #6C2FF2, #FFD43B, #FF6B6B, #00D68F);
  }

  .fc { width: 100%; max-width: 370px; animation: up 0.5s ease; }
  @keyframes up { from{opacity:0;transform:translateY(18px);} to{opacity:1;transform:translateY(0);} }

  .cb-pill {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(135deg,#FFFAE6,#FFF3B0);
    border: 1.5px solid #FFD43B; border-radius: 100px;
    padding: 7px 16px; margin-bottom: 22px;
    box-shadow: 0 4px 16px rgba(255,212,59,0.2);
  }
  .cb-pill span { font-size: 11px; font-weight: 700; color: #8B6000; }

  .fh { font-family: 'Space Grotesk', sans-serif; font-size: 27px; font-weight: 700; color: var(--text); margin-bottom: 5px; }
  .fh em { color: var(--purple); font-style: normal; }
  .fs { font-size: 12px; color: var(--muted); margin-bottom: 26px; }

  .fg { margin-bottom: 17px; }
  .fl { display: block; font-size: 11px; font-weight: 700; color: #4A5080; margin-bottom: 7px; }
  .fw { position: relative; }
  .fi { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-size: 15px; opacity: 0.45; transition: opacity 0.2s; pointer-events: none; }
  .fw:focus-within .fi { opacity: 1; }

  input[type=text], input[type=password] {
    width: 100%; background: var(--surface);
    border: 2px solid var(--border); border-radius: 13px;
    padding: 13px 14px 13px 46px;
    font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 500;
    color: var(--text); outline: none; transition: all 0.22s;
  }
  input::placeholder { color: #C0C6E0; font-weight: 400; }
  input:focus { border-color: var(--purple); background: #fff; box-shadow: 0 0 0 4px rgba(108,47,242,0.07); }

  .pw-btn { position: absolute; right: 13px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 15px; opacity: 0.35; transition: opacity 0.2s; }
  .pw-btn:hover { opacity: 1; }

  .fr { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
  .rl { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--muted); cursor: pointer; }
  .rl input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--purple); }
  .fl-link { font-size: 11px; font-weight: 700; color: var(--purple); text-decoration: none; }
  .fl-link:hover { color: var(--purple-dark); }

  .btn-p {
    width: 100%; padding: 15px;
    background: linear-gradient(135deg, #4A1DB0, #6C2FF2, #8B4FF8);
    color: #fff; border: none; border-radius: 13px; cursor: pointer;
    font-family: 'Space Grotesk', sans-serif; font-size: 13px; font-weight: 700;
    letter-spacing: 0.04em; transition: all 0.28s;
    box-shadow: 0 8px 24px rgba(108,47,242,0.35);
    margin-bottom: 12px; position: relative; overflow: hidden;
  }
  .btn-p::after { content:''; position:absolute; inset:0; background: linear-gradient(135deg,rgba(255,255,255,0.15),transparent 55%); opacity:0; transition:opacity 0.3s; }
  .btn-p:hover { transform:translateY(-2px); box-shadow:0 14px 36px rgba(108,47,242,0.45); }
  .btn-p:hover::after { opacity:1; }

  .div-row { display: flex; align-items: center; gap: 10px; margin: 4px 0 12px; color: var(--muted); font-size: 11px; }
  .div-row::before, .div-row::after { content:''; flex:1; height:1px; background:var(--border); }

  .btn-g {
    width: 100%; padding: 14px; background: transparent;
    border: 2px solid var(--border); border-radius: 13px; cursor: pointer;
    font-family: 'Space Grotesk', sans-serif; font-size: 12px; font-weight: 700;
    color: var(--muted); transition: all 0.22s; text-decoration: none; display: block; text-align: center;
  }
  .btn-g:hover { border-color: var(--purple); color: var(--purple); background: rgba(108,47,242,0.04); }

  .trust {
    display: flex; justify-content: center; gap: 18px;
    margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--border);
  }
  .ti { display: flex; align-items: center; gap: 5px; font-size: 10px; color: var(--muted); font-weight: 600; }
  .ti::before { content:''; width:5px; height:5px; border-radius:50%; background: var(--mint); display:block; }

  .form-side::-webkit-scrollbar { width: 4px; }
  .form-side::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
  @media(max-width:768px){ .brand{display:none;} }
</style>
</head>
<body>
<div class="page">

  <!-- LEFT BRAND -->
  <aside class="brand">
    <div class="glow glow-1"></div>
    <div class="glow glow-2"></div>
    <div class="glow glow-3"></div>

    <div class="prize-card">
      <div class="prize-label">🏆 This Week's Jackpot</div>
      <div class="prize-amount">৳ 10,00,000</div>
      <div class="prize-sub">Draw: Sunday 11:59 PM · 12,340 tickets</div>
    </div>

    <div class="brand-logo">
      <div class="logo-box">🛍</div>
      <div>
        <div class="logo-name">Shop<span>Win</span></div>
        <div class="logo-tagline">Shop · Earn Cashback · Win Big</div>
      </div>
    </div>

    <div class="brand-center">
      <div class="live-pill">
        <div class="live-dot"></div>
        <div class="live-text">Live Draws Every Week</div>
      </div>
      <h2 class="headline">Every Order is<br>Your <span class="hl">Lottery</span><br>Ticket 🎰</h2>
      <p class="sub-desc">Shop from thousands of products, earn real cashback instantly, and get entered into our weekly leaderboard prize draws.</p>

      <div class="reward-pills">
        <div class="rpill">
          <div class="rpill-icon ri-y">💸</div>
          <div class="rpill-body">
            <div class="rpill-title">Instant Cashback</div>
            <div class="rpill-sub">Every purchase earns you real money back</div>
          </div>
          <div class="rpill-val rv-y">Up to 15%</div>
        </div>
        <div class="rpill">
          <div class="rpill-icon ri-r">🎰</div>
          <div class="rpill-body">
            <div class="rpill-title">Jackpot Draw</div>
            <div class="rpill-sub">1 ticket per ৳100 spent. Auto-entered.</div>
          </div>
          <div class="rpill-val rv-r">৳10 Lac</div>
        </div>
        <div class="rpill">
          <div class="rpill-icon ri-g">📊</div>
          <div class="rpill-body">
            <div class="rpill-title">Leaderboard Prizes</div>
            <div class="rpill-sub">Top 100 buyers win bonus every month</div>
          </div>
          <div class="rpill-val rv-g">Top 100</div>
        </div>
      </div>
    </div>

    <div class="ticker-bar">
      <div class="t-label">🔴 Live</div>
      <div class="t-track">
        <div class="t-inner">
          <span class="t-item">Rahim K. won <b>৳25,000</b> cashback ·</span>
          <span class="t-item">Sumaiya A. reached <b>#1 Leaderboard</b> 🏆 ·</span>
          <span class="t-item">Karim M. won <b>৳2,50,000 Jackpot!</b> 🎉 ·</span>
          <span class="t-item">Nadia B. earned <b>৳8,400</b> this week ·</span>
          <span class="t-item">Rahim K. won <b>৳25,000</b> cashback ·</span>
          <span class="t-item">Sumaiya A. reached <b>#1 Leaderboard</b> 🏆 ·</span>
          <span class="t-item">Karim M. won <b>৳2,50,000 Jackpot!</b> 🎉 ·</span>
          <span class="t-item">Nadia B. earned <b>৳8,400</b> this week ·</span>
        </div>
      </div>
    </div>
  </aside>

  <!-- RIGHT FORM -->
  <main class="form-side">
    <div class="fc">
      <div class="cb-pill">
        <span>🎁 Sign in & claim your pending ৳450 cashback reward!</span>
      </div>

      <div class="fh">Welcome <em>Back!</em></div>
      <div class="fs">Sign in to shop, earn cashback & enter today's draw</div>

      <div class="fg">
        <label class="fl">Phone Number or Email</label>
        <div class="fw">
          <span class="fi">📱</span>
          <input type="text" placeholder="Enter phone or email" />
        </div>
      </div>

      <div class="fg">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:7px">
          <label class="fl" style="margin-bottom:0">Password</label>
          <a href="#" class="fl-link">Forgot password?</a>
        </div>
        <div class="fw">
          <span class="fi">🔐</span>
          <input type="password" id="pw" placeholder="Enter your password" />
          <button class="pw-btn" onclick="togglePw()" type="button">👁</button>
        </div>
      </div>

      <div class="fr">
        <label class="rl">
          <input type="checkbox" checked /> Keep me signed in
        </label>
      </div>

      <button class="btn-p" type="button">🚀 &nbsp; Sign In & Start Earning</button>
      <div class="div-row">New to ShopWin?</div>
      <a href="register.html" class="btn-g">🎁 Create Free Account — Get ৳200 Welcome Bonus!</a>

      <div class="trust">
        <div class="ti">SSL Secured</div>
        <div class="ti">64K+ Members</div>
        <div class="ti">Instant Payout</div>
      </div>
    </div>
  </main>
</div>
<script>
function togglePw() {
  const el = document.getElementById('pw');
  el.type = el.type==='password'?'text':'password';
  el.nextElementSibling.textContent = el.type==='password'?'👁':'🙈';
}
</script>
</body>
</html>