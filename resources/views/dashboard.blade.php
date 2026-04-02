@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap');

:root {
  --c-bg:       #05070f;
  --c-surface:  #0b0f1e;
  --c-glass:    rgba(255,255,255,0.03);
  --c-border:   rgba(255,255,255,0.07);
  --c-border2:  rgba(255,255,255,0.12);
  --c-cyan:     #00e5ff;
  --c-cyan-dim: rgba(0,229,255,0.12);
  --c-green:    #00ffb3;
  --c-amber:    #ffb020;
  --c-red:      #ff4d6d;
  --c-text:     #e8eaf0;
  --c-muted:    rgba(232,234,240,0.45);
  --c-dimmer:   rgba(232,234,240,0.25);
  --ff-display: 'Space Mono', monospace;
  --ff-body:    'DM Sans', sans-serif;
  --ease-out:   cubic-bezier(0.16,1,0.3,1);
}

*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}

.dash{
  font-family:var(--ff-body);
  background:var(--c-bg);
  color:var(--c-text);
  min-height:100vh;
  padding:28px 28px 56px;
  position:relative;
  overflow-x:hidden;
}

/* Noise texture overlay */
.dash::before{
  content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
  background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
  background-size:180px;opacity:0.7;
}
/* Ambient glow */
.dash::after{
  content:'';position:fixed;top:-100px;left:-60px;
  width:540px;height:540px;
  background:radial-gradient(circle,rgba(0,229,255,0.06) 0%,transparent 70%);
  pointer-events:none;z-index:0;
}
.dash>*{position:relative;z-index:1;}

/* Grid helpers */
.g{display:grid;gap:16px;}
.g-4{grid-template-columns:repeat(4,1fr);}
.g-2-1{grid-template-columns:2fr 1fr;}
@media(max-width:1200px){.g-4{grid-template-columns:repeat(2,1fr);}}
@media(max-width:900px){.g-2-1{grid-template-columns:1fr;}}
@media(max-width:580px){.g-4{grid-template-columns:1fr 1fr;}}

/* Card base */
.card{
  background:var(--c-surface);
  border:1px solid var(--c-border);
  border-radius:18px;
  padding:22px;
  position:relative;overflow:hidden;
  transition:border-color .25s,box-shadow .25s;
  will-change:transform;
}
.card:hover{border-color:var(--c-border2);box-shadow:0 0 32px rgba(0,229,255,0.06);}
.card::before{
  content:'';position:absolute;top:0;left:-100%;width:55%;height:100%;
  background:linear-gradient(105deg,transparent 40%,rgba(255,255,255,0.018) 50%,transparent 60%);
  transition:left .6s var(--ease-out);pointer-events:none;
}
.card:hover::before{left:145%;}

/* ── HERO ── */
.hero{
  background:
    radial-gradient(ellipse 55% 110% at 92% 50%,rgba(0,229,255,0.09) 0%,transparent 60%),
    radial-gradient(ellipse 38% 70% at 8% 85%,rgba(0,255,179,0.05) 0%,transparent 55%),
    var(--c-surface);
  border:1px solid var(--c-border);
  border-radius:22px;
  padding:30px 36px;
  display:grid;grid-template-columns:1fr auto;gap:28px;align-items:center;
  position:relative;overflow:hidden;
}
.hero::after{
  content:'';position:absolute;
  top:-1px;left:0;right:0;height:1px;
  background:linear-gradient(90deg,transparent 0%,var(--c-cyan) 40%,var(--c-cyan) 60%,transparent 100%);
  opacity:.35;animation:scanline 3.5s var(--ease-out) infinite;
}
@keyframes scanline{
  0%{transform:translateX(-110%);opacity:0;}
  20%{opacity:.5;}80%{opacity:.5;}
  100%{transform:translateX(110%);opacity:0;}
}

.hero-tag{
  display:inline-flex;align-items:center;gap:8px;
  font-family:var(--ff-display);font-size:10px;letter-spacing:1.6px;text-transform:uppercase;
  color:var(--c-cyan);margin-bottom:14px;
}
.pulse-dot{
  width:7px;height:7px;border-radius:50%;
  background:var(--c-green);box-shadow:0 0 8px var(--c-green);
  animation:pring 1.8s ease-in-out infinite;
}
@keyframes pring{
  0%,100%{box-shadow:0 0 0 0 rgba(0,255,179,.6),0 0 6px var(--c-green);}
  50%{box-shadow:0 0 0 6px rgba(0,255,179,0),0 0 12px var(--c-green);}
}

.hero-num{
  font-family:var(--ff-display);font-size:clamp(36px,5.5vw,58px);font-weight:700;
  color:var(--c-text);line-height:1;letter-spacing:-1.5px;
}
.hero-num span{color:var(--c-cyan);}
.hero-unit{font-family:var(--ff-display);font-size:11px;letter-spacing:1px;color:var(--c-dimmer);margin-top:5px;}
.hero-sub{font-size:13px;color:var(--c-muted);margin-top:14px;line-height:1.65;}
.hero-sub strong{color:var(--c-text);font-weight:500;}
.hero-sub em{color:var(--c-cyan);font-style:normal;font-weight:600;}

.plat-chips{display:flex;gap:8px;margin-top:18px;flex-wrap:wrap;}
.chip{
  display:inline-flex;align-items:center;gap:5px;
  background:var(--c-glass);border:1px solid var(--c-border);border-radius:20px;
  padding:4px 12px;font-size:11px;color:var(--c-muted);font-weight:500;
  transition:border-color .2s,color .2s;cursor:default;
}
.chip:hover{border-color:var(--c-border2);color:var(--c-text);}

/* Ring vis */
.ring-wrap{display:flex;flex-direction:column;align-items:center;gap:8px;}
.ring-svg{animation:spinSlow 20s linear infinite;transform-origin:center;}
@keyframes spinSlow{to{transform:rotate(360deg);}}
.ring-lbl{font-family:var(--ff-display);font-size:10px;color:var(--c-green);letter-spacing:.5px;text-align:center;}

/* ── STAT CARD ── */
.stat{padding:20px 22px;display:flex;flex-direction:column;gap:5px;}
.stat-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;}
.stat-icon{width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.badge{font-family:var(--ff-display);font-size:10px;padding:2px 8px;border-radius:20px;font-weight:700;letter-spacing:.5px;}
.b-green{background:rgba(0,255,179,.12);color:var(--c-green);}
.b-amber{background:rgba(255,176,32,.12);color:var(--c-amber);}
.b-cyan {background:rgba(0,229,255,.1); color:var(--c-cyan);}
.stat-num{font-family:var(--ff-display);font-size:27px;font-weight:700;color:var(--c-text);letter-spacing:-.5px;line-height:1;}
.stat-label{font-size:11px;color:var(--c-muted);font-weight:500;}
.stat-sub{font-size:11px;color:var(--c-dimmer);}

/* ── SECTION HDR ── */
.sec-hdr{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;}
.sec-title{font-family:var(--ff-display);font-size:11px;letter-spacing:1.2px;text-transform:uppercase;color:var(--c-muted);}
.live-badge{
  display:inline-flex;align-items:center;gap:5px;
  font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--c-green);
}
.live-badge::before{
  content:'';width:6px;height:6px;border-radius:50%;
  background:var(--c-green);box-shadow:0 0 6px var(--c-green);
  animation:blink .9s ease-in-out infinite alternate;
}
@keyframes blink{from{opacity:.3}to{opacity:1}}

/* ── ACTIVITY ── */
.act-item{
  display:flex;align-items:flex-start;gap:12px;
  padding:12px 0;border-bottom:1px solid var(--c-border);
  animation:fadeUp .4s var(--ease-out) both;
}
.act-item:last-child{border-bottom:none;}
@keyframes fadeUp{from{opacity:0;transform:translateY(7px);}to{opacity:1;transform:translateY(0);}}
.act-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;}
.act-body{flex:1;min-width:0;}
.act-desc{font-size:12.5px;color:var(--c-text);line-height:1.45;}
.act-meta{display:flex;align-items:center;gap:8px;margin-top:4px;}
.act-time{font-family:var(--ff-display);font-size:10px;color:var(--c-dimmer);}
.act-plat{font-size:10px;color:var(--c-muted);background:var(--c-glass);border:1px solid var(--c-border);border-radius:4px;padding:1px 6px;}

/* ── PERF ── */
.perf-row{display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--c-border);}
.perf-row:last-of-type{border-bottom:none;}
.perf-label{font-size:12px;color:var(--c-muted);}
.perf-val{font-family:var(--ff-display);font-size:13px;font-weight:700;}

/* bars */
.bar-row{margin-bottom:10px;}
.bar-meta{display:flex;justify-content:space-between;font-size:10px;color:var(--c-dimmer);margin-bottom:5px;}
.bar-track{height:4px;background:rgba(255,255,255,0.055);border-radius:4px;overflow:hidden;}
.bar-fill{height:100%;border-radius:4px;transition:width 1.2s var(--ease-out);}

/* Quick test */
.qt-wrap{margin-top:16px;padding-top:16px;border-top:1px solid var(--c-border);}
.qt-lbl{
  font-family:var(--ff-display);font-size:10px;letter-spacing:1.2px;text-transform:uppercase;
  color:var(--c-cyan);margin-bottom:10px;display:flex;align-items:center;gap:6px;
}
.qt-input{
  width:100%;background:rgba(255,255,255,0.04);border:1px solid var(--c-border);
  border-radius:10px;padding:9px 14px;font-family:var(--ff-body);font-size:12px;
  color:var(--c-text);outline:none;transition:border-color .2s,box-shadow .2s;
}
.qt-input::placeholder{color:var(--c-dimmer);}
.qt-input:focus{border-color:rgba(0,229,255,.4);box-shadow:0 0 0 3px rgba(0,229,255,.07);}
.qt-result{
  margin-top:10px;background:rgba(0,229,255,0.06);border:1px solid rgba(0,229,255,0.18);
  border-radius:10px;padding:11px 14px;font-size:12px;color:var(--c-muted);line-height:1.6;
  display:none;
}
.qt-result.show{display:block;animation:fadeUp .3s var(--ease-out);}

/* Entry animations */
@keyframes entryUp{from{opacity:0;transform:translateY(14px);}to{opacity:1;transform:translateY(0);}}
.ei{animation:entryUp .5s var(--ease-out) both;}
.d1{animation-delay:.05s;}.d2{animation-delay:.1s;}.d3{animation-delay:.15s;}.d4{animation-delay:.2s;}.d5{animation-delay:.25s;}.d6{animation-delay:.3s;}

/* Ticker */
.ticker{display:inline-block;transition:transform .25s,opacity .25s;}
.ticker.bump{transform:translateY(-5px);opacity:0;}
</style>

<div class="dash">
  <div class="g" style="gap:16px;">

    <!-- ── HERO BANNER ── -->
    <div class="hero ei">
      <div>
        <div class="hero-tag"><span class="pulse-dot"></span>AI Agent · Online</div>
        <div class="hero-num"><span class="ticker" id="msgCount">127</span> msgs</div>
        <div class="hero-unit">HANDLED TODAY</div>
        <div class="hero-sub" style="margin-top:14px;">
          Resolved <strong>42</strong> conversations &mdash; avg response <em>1.2s</em>
        </div>
        <div class="plat-chips">
          <span class="chip">📱 WhatsApp</span>
          <span class="chip">⚡ Slack</span>
          <span class="chip">📧 Email</span>
        </div>
      </div>

      <!-- Animated SVG ring -->
      <div class="ring-wrap">
        <svg class="ring-svg" width="104" height="104" viewBox="0 0 104 104">
          <circle cx="52" cy="52" r="46" fill="none" stroke="rgba(0,229,255,0.07)" stroke-width="1.5"/>
          <circle cx="52" cy="52" r="46" fill="none"
            stroke="url(#rg1)" stroke-width="2.5"
            stroke-dasharray="289" stroke-dashoffset="17"
            stroke-linecap="round" transform="rotate(-90 52 52)"/>
          <circle cx="52" cy="52" r="35" fill="none" stroke="rgba(0,255,179,0.07)" stroke-width="1"/>
          <circle cx="52" cy="52" r="35" fill="none"
            stroke="url(#rg2)" stroke-width="2"
            stroke-dasharray="219.9" stroke-dashoffset="0.44"
            stroke-linecap="round" transform="rotate(-90 52 52)"/>
          <defs>
            <linearGradient id="rg1" x1="0%" y1="0%" x2="100%" y2="0%">
              <stop offset="0%" stop-color="#00e5ff"/>
              <stop offset="100%" stop-color="#00e5ff" stop-opacity=".25"/>
            </linearGradient>
            <linearGradient id="rg2" x1="0%" y1="0%" x2="100%" y2="0%">
              <stop offset="0%" stop-color="#00ffb3"/>
              <stop offset="100%" stop-color="#00ffb3" stop-opacity=".2"/>
            </linearGradient>
          </defs>
          <text x="52" y="49" text-anchor="middle" fill="#e8eaf0" font-size="14" font-family="Space Mono,monospace" font-weight="700">94%</text>
          <text x="52" y="62" text-anchor="middle" fill="rgba(232,234,240,.35)" font-size="6.5" font-family="DM Sans,sans-serif" letter-spacing="0.5">SATISFACTION</text>
        </svg>
        <div class="ring-lbl">99.8% UPTIME</div>
      </div>
    </div>

    <!-- ── STAT CARDS ── -->
    <div class="g g-4">

      <div class="card stat ei d1">
        <div class="stat-top">
          <div class="stat-icon" style="background:rgba(0,229,255,0.1);">
            <svg width="15" height="15" fill="none" stroke="#00e5ff" stroke-width="1.8" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
          </div>
          <span class="badge b-green">+12%</span>
        </div>
        <div class="stat-num">24</div>
        <div class="stat-label">Active Conversations</div>
        <div class="stat-sub">42 resolved today</div>
      </div>

      <div class="card stat ei d2">
        <div class="stat-top">
          <div class="stat-icon" style="background:rgba(0,255,179,0.1);">
            <svg width="15" height="15" fill="none" stroke="#00ffb3" stroke-width="1.8" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
          </div>
          <span class="badge b-green">+8%</span>
        </div>
        <div class="stat-num">18</div>
        <div class="stat-label">Tasks Created</div>
        <div class="stat-sub">7 in progress</div>
      </div>

      <div class="card stat ei d3">
        <div class="stat-top">
          <div class="stat-icon" style="background:rgba(255,176,32,0.1);">
            <svg width="15" height="15" fill="none" stroke="#ffb020" stroke-width="1.8" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
          </div>
          <span class="badge b-amber">+22%</span>
        </div>
        <div class="stat-num" style="color:var(--c-amber);">$4,280</div>
        <div class="stat-label">Revenue This Month</div>
        <div class="stat-sub">$1,240 pending</div>
      </div>

      <div class="card stat ei d4">
        <div class="stat-top">
          <div class="stat-icon" style="background:rgba(0,229,255,0.1);">
            <svg width="15" height="15" fill="none" stroke="#00e5ff" stroke-width="1.8" viewBox="0 0 24 24"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
          </div>
          <span class="badge b-cyan">+3%</span>
        </div>
        <div class="stat-num" style="color:var(--c-cyan);">94%</div>
        <div class="stat-label">Satisfaction Score</div>
        <div class="stat-sub">From client ratings</div>
      </div>

    </div>

    <!-- ── ACTIVITY + PERF ── -->
    <div class="g g-2-1">

      <!-- Activity feed -->
      <div class="card ei d5" style="padding:24px;">
        <div class="sec-hdr">
          <span class="sec-title">Agent Activity</span>
          <span class="live-badge">Live</span>
        </div>
        <div id="actFeed"></div>
      </div>

      <!-- Performance panel -->
      <div class="card ei d6" style="padding:24px;">
        <div class="sec-hdr">
          <span class="sec-title">Performance</span>
        </div>

        <div class="perf-row"><span class="perf-label">Avg Response</span><span class="perf-val" style="color:var(--c-cyan);">1.2s</span></div>
        <div class="perf-row"><span class="perf-label">Agent Uptime</span><span class="perf-val" style="color:var(--c-green);">99.8%</span></div>
        <div class="perf-row"><span class="perf-label">Resolved Today</span><span class="perf-val" style="color:var(--c-green);">42</span></div>
        <div class="perf-row" style="border-bottom:1px solid var(--c-border);">
          <span class="perf-label">Msgs Handled</span>
          <span class="perf-val" style="color:var(--c-cyan);" id="perfMsgs">127</span>
        </div>

        <div style="margin-top:18px;">
          <div style="font-size:10px;letter-spacing:1px;text-transform:uppercase;color:var(--c-dimmer);font-family:'Space Mono',monospace;margin-bottom:13px;">Platform Split</div>
          <div class="bar-row">
            <div class="bar-meta"><span>📱 WhatsApp</span><span>45%</span></div>
            <div class="bar-track"><div class="bar-fill" style="width:45%;background:linear-gradient(90deg,#25D366,#1a9e50);"></div></div>
          </div>
          <div class="bar-row">
            <div class="bar-meta"><span>⚡ Slack</span><span>32%</span></div>
            <div class="bar-track"><div class="bar-fill" style="width:32%;background:linear-gradient(90deg,#7c3aed,#4c1d95);"></div></div>
          </div>
          <div class="bar-row" style="margin-bottom:0;">
            <div class="bar-meta"><span>📧 Email</span><span>23%</span></div>
            <div class="bar-track"><div class="bar-fill" style="width:23%;background:linear-gradient(90deg,#EA4335,#b91c1c);"></div></div>
          </div>
        </div>

        <!-- Quick AI test -->
        <div class="qt-wrap">
          <div class="qt-lbl">
            <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            Quick Test
          </div>
          <input class="qt-input" id="qtInput" placeholder="Type a message, press Enter…" onkeydown="if(event.key==='Enter')doQT()">
          <div class="qt-result" id="qtResult"></div>
        </div>
      </div>

    </div>

  </div>
</div>

<script>
const ICONS={
  message_sent:{e:'💬',c:'rgba(0,229,255,0.14)'},
  task_created:{e:'✅',c:'rgba(0,255,179,0.14)'},
  invoice_sent:{e:'💰',c:'rgba(255,176,32,0.14)'},
  escalation:{e:'⚠️',c:'rgba(255,77,109,0.14)'},
  query_resolved:{e:'✔️',c:'rgba(0,255,179,0.14)'},
};
const PLAT={whatsapp:'📱',slack:'⚡',email:'📧'};
const DESCS=[
  'New message received from client',
  'Task auto-created from conversation',
  'Smart reply dispatched',
  'Client query resolved instantly',
  'Billing reminder sent via AI',
  'Escalation flagged and routed',
];

let activity=[
  {desc:'New message from John Doe',time:Date.now()-5*60000,plat:'whatsapp',type:'message_sent'},
  {desc:'Task created: Fix API integration',time:Date.now()-15*60000,plat:'slack',type:'task_created'},
  {desc:'Invoice #1234 sent — Sarah Wilson',time:Date.now()-30*60000,plat:'email',type:'invoice_sent'},
  {desc:'Query resolved — Tech Startup Inc',time:Date.now()-45*60000,plat:'whatsapp',type:'query_resolved'},
  {desc:'Escalation triggered for urgent issue',time:Date.now()-60*60000,plat:'slack',type:'escalation'},
];

let msgCount=127;

function renderActivity(){
  document.getElementById('actFeed').innerHTML=activity.map((log,i)=>{
    const ic=ICONS[log.type]||{e:'⚡',c:'rgba(0,229,255,0.12)'};
    const t=new Date(log.time).toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'});
    return `<div class="act-item" style="animation-delay:${i*55}ms;">
      <div class="act-icon" style="background:${ic.c};">${ic.e}</div>
      <div class="act-body">
        <div class="act-desc">${log.desc}</div>
        <div class="act-meta">
          <span class="act-time">${t}</span>
          <span class="act-plat">${PLAT[log.plat]||''} ${log.plat}</span>
        </div>
      </div>
    </div>`;
  }).join('');
}

function doQT(){
  const inp=document.getElementById('qtInput');
  const res=document.getElementById('qtResult');
  if(!inp.value.trim())return;
  res.className='qt-result show';
  res.innerHTML='<span style="color:var(--c-cyan);font-family:\'Space Mono\',monospace;font-size:10px;letter-spacing:.5px;">PROCESSING…</span>';
  setTimeout(()=>{
    res.innerHTML='I understand your request. The AI agent is analysing your message and will provide a contextual, helpful response shortly.';
    inp.value='';
  },1500);
}

function tick(){
  msgCount+=Math.floor(Math.random()*2)+1;
  const el=document.getElementById('msgCount');
  const p=document.getElementById('perfMsgs');
  if(el){el.classList.add('bump');setTimeout(()=>{el.textContent=msgCount;el.classList.remove('bump');},220);}
  if(p)p.textContent=msgCount;

  if(Math.random()>.5){
    const types=Object.keys(ICONS);
    const plats=['whatsapp','slack','email'];
    activity.unshift({
      desc:DESCS[Math.floor(Math.random()*DESCS.length)],
      time:Date.now(),
      plat:plats[Math.floor(Math.random()*plats.length)],
      type:types[Math.floor(Math.random()*types.length)],
    });
    if(activity.length>8)activity.pop();
    renderActivity();
  }
}

renderActivity();
setInterval(tick,20000);
</script>

@endsection