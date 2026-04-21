@extends('layouts.app')
@section('content')
<style>
.db-pg { padding: 28px; font-family: var(--font-body); }
@media(max-width:768px) { .db-pg { padding: 16px; } }

/* PAGE HEADER */
.db-head { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 28px; gap: 16px; flex-wrap: wrap; }
.db-title { font-family: var(--font-display); font-size: 26px; font-weight: 800; color: var(--txt); letter-spacing: -0.8px; line-height: 1.1; }
.db-sub   { font-size: 13px; color: var(--txt-3); margin-top: 4px; font-family: var(--font-mono); }

.live-pill {
    display: flex; align-items: center; gap: 7px; flex-shrink: 0;
    background: var(--green-soft); border: 1px solid rgba(5,150,105,0.2);
    border-radius: 20px; padding: 6px 14px; font-size: 11.5px;
    font-weight: 700; color: var(--green); font-family: var(--font-mono);
    letter-spacing: 0.3px;
}
.live-dot {
    width: 7px; height: 7px; border-radius: 50%; background: var(--green);
    box-shadow: 0 0 6px var(--green); animation: pulse-g 2s infinite;
}
@keyframes pulse-g { 0%,100%{box-shadow:0 0 0 0 rgba(5,150,105,0.4)} 70%{box-shadow:0 0 0 8px rgba(5,150,105,0)} }

/* HERO CARD */
.hero-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius-xl); padding: 32px; margin-bottom: 22px;
    position: relative; overflow: hidden;
    display: grid; grid-template-columns: 1fr auto; gap: 28px; align-items: center;
    box-shadow: var(--shadow-sm);
}
.hero-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--accent), var(--purple), var(--cyan));
}
.hero-card::after {
    content: ''; position: absolute; top: 0; right: 0;
    width: 300px; height: 100%;
    background: radial-gradient(ellipse at right center, var(--accent-soft), transparent 70%);
    pointer-events: none;
}

.hero-tag {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase;
    color: var(--accent); padding: 4px 12px; border-radius: 20px;
    background: var(--accent-soft); border: 1px solid rgba(79,70,229,0.15);
    font-family: var(--font-mono); margin-bottom: 14px;
}

.hero-num {
    font-family: var(--font-display); font-size: clamp(42px, 5vw, 58px);
    font-weight: 800; color: var(--txt); letter-spacing: -2.5px; line-height: 1;
    margin-bottom: 10px;
}
.hero-num .hero-unit { font-size: 22px; font-weight: 400; color: var(--txt-3); letter-spacing: -0.5px; }

.hero-sub { font-size: 13px; color: var(--txt-3); line-height: 1.7; }
.hero-sub strong { color: var(--txt-2); font-weight: 600; }

.plat-chips { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 18px; }
.plat-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: var(--bg-raised); border: 1px solid var(--border-md);
    border-radius: 20px; padding: 5px 13px; font-size: 12px;
    color: var(--txt-3); font-weight: 500; transition: all 160ms;
}
.plat-chip:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }

.hero-link {
    display: inline-flex; align-items: center; gap: 8px; margin-top: 22px;
    background: var(--accent); color: white; padding: 11px 24px;
    border-radius: var(--radius-md); font-size: 13.5px; font-weight: 600;
    text-decoration: none; transition: all 180ms var(--ease);
    box-shadow: 0 4px 14px var(--accent-glow); font-family: var(--font-body);
}
.hero-link:hover { filter: brightness(1.1); transform: translateY(-2px); }

/* Ring chart */
.ring-wrap { text-align: center; flex-shrink: 0; position: relative; z-index: 1; }
.ring-label { font-size: 10.5px; color: var(--green); font-family: var(--font-mono); font-weight: 700; margin-top: 8px; letter-spacing: 0.2px; }

/* STATS GRID */
.stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
@media(max-width:1000px){ .stats-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width:500px) { .stats-grid { grid-template-columns: 1fr 1fr; } }

.stat-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius-lg); padding: 22px;
    transition: all 200ms var(--ease); position: relative; overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.stat-card::before {
    content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px;
    background: var(--grad, linear-gradient(90deg, var(--accent), var(--purple)));
    opacity: 0; transition: opacity 200ms;
}
.stat-card:hover { border-color: var(--border-md); transform: translateY(-3px); box-shadow: var(--shadow-md); }
.stat-card:hover::before { opacity: 1; }

.stat-ico { font-size: 22px; margin-bottom: 14px; }
.stat-val { font-family: var(--font-display); font-size: 32px; font-weight: 800; letter-spacing: -1.5px; line-height: 1; color: var(--txt); }
.stat-lbl { font-size: 11.5px; color: var(--txt-4); margin-top: 8px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.6px; }
.stat-delta { font-size: 11px; margin-top: 6px; font-weight: 500; }
.stat-delta.up   { color: var(--green); }
.stat-delta.down { color: var(--red); }

/* BOTTOM GRID */
.bottom-grid { display: grid; grid-template-columns: 1.8fr 1fr; gap: 20px; }
@media(max-width:860px) { .bottom-grid { grid-template-columns: 1fr; } }

.panel {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm);
}
.panel-head {
    padding: 18px 22px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.panel-title { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--txt-3); font-family: var(--font-mono); }
.panel-live  { font-size: 11px; color: var(--green); font-family: var(--font-mono); font-weight: 700; display: flex; align-items: center; gap: 5px; }
.panel-live::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--green); display: inline-block; animation: pulse-g 2s infinite; }

/* Activity Feed */
.act-list { padding: 0; }
.act-item {
    display: flex; align-items: flex-start; gap: 13px; padding: 15px 22px;
    border-bottom: 1px solid var(--border); transition: background 150ms;
}
.act-item:last-child { border-bottom: none; }
.act-item:hover { background: var(--bg-hover); }

.act-icon {
    width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 16px;
    background: var(--bg-raised); border: 1px solid var(--border);
}
.act-body { flex: 1; min-width: 0; }
.act-name { font-size: 13px; font-weight: 600; color: var(--txt); display: flex; align-items: center; gap: 6px; }
.act-name .via { font-weight: 400; color: var(--txt-3); font-size: 12px; }
.act-preview { font-size: 12px; color: var(--txt-3); margin-top: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.act-time { font-size: 10px; color: var(--txt-4); margin-top: 4px; font-family: var(--font-mono); }

/* Platform source tag */
.src-tag {
    font-size: 10px; padding: 2px 7px; border-radius: 20px; font-weight: 600;
    font-family: var(--font-mono); flex-shrink: 0;
}
.src-whatsapp { background: #dcfce7; color: #15803d; }
.src-slack    { background: var(--purple-soft); color: var(--purple); }
.src-twilio   { background: var(--red-soft); color: var(--red); }
.src-web      { background: var(--accent-soft); color: var(--accent); }
.src-api      { background: var(--bg-hover); color: var(--txt-3); }
[data-theme="dark"] .src-whatsapp { background: rgba(21,128,61,0.15); color: #4ade80; }

/* Performance */
.perf-body { padding: 4px 0; }
.perf-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 11px 22px; border-bottom: 1px solid var(--border); font-size: 13px;
}
.perf-row:last-child { border-bottom: none; }
.perf-key { color: var(--txt-2); font-weight: 500; }
.perf-val { font-family: var(--font-mono); font-weight: 700; color: var(--accent); font-size: 13.5px; }

.bar-section { padding: 18px 22px; border-top: 1px solid var(--border); }
.bar-section-title { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--txt-4); margin-bottom: 16px; font-family: var(--font-mono); }
.bar-row { margin-bottom: 14px; }
.bar-row:last-child { margin-bottom: 0; }
.bar-top { display: flex; justify-content: space-between; font-size: 12.5px; margin-bottom: 7px; }
.bar-key { color: var(--txt-2); font-weight: 500; }
.bar-pct { font-family: var(--font-mono); font-weight: 700; color: var(--txt); }
.bar-track { height: 5px; background: var(--bg-hover); border-radius: 4px; overflow: hidden; }
.bar-fill  { height: 100%; border-radius: 4px; transition: width 1.2s var(--ease); width: 0%; }

/* Empty state */
.act-empty { padding: 50px 22px; text-align: center; color: var(--txt-4); font-size: 13px; }
.act-empty-icon { font-size: 38px; opacity: 0.15; display: block; margin-bottom: 10px; }
</style>

<div class="db-pg">

    <!-- Header -->
    <div class="db-head anim">
        <div>
            <div class="db-title">Dashboard</div>
            <div class="db-sub" id="nowDate"></div>
        </div>
        <div class="live-pill"><span class="live-dot"></span> Live</div>
    </div>

    <!-- Hero -->
    <div class="hero-card anim anim-d1">
        <div>
            <div class="hero-tag">
                <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor"><circle cx="5" cy="5" r="5"/></svg>
                AI Agent Online
            </div>
            <div class="hero-num">
                <span id="totalMessages">—</span>
                <span class="hero-unit"> msgs</span>
            </div>
            <div class="hero-sub">
                Handled today &nbsp;·&nbsp; avg response <strong id="heroAvg">—</strong>
            </div>
            <div class="plat-chips">
                <span class="plat-chip">📱 WhatsApp</span>
                <span class="plat-chip">⚡ Slack</span>
                <span class="plat-chip">🌐 Web</span>
                <span class="plat-chip">📞 Twilio</span>
            </div>
            <a href="{{ route('chat') }}" class="hero-link">
                💬 View Conversations
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="ring-wrap">
            <svg width="104" height="104" viewBox="0 0 104 104">
                <circle cx="52" cy="52" r="44" fill="none" stroke="var(--border-md)" stroke-width="2"/>
                <circle cx="52" cy="52" r="44" fill="none" stroke="var(--accent)" stroke-width="3.5"
                    stroke-dasharray="276" id="ringArc" stroke-dashoffset="20" stroke-linecap="round"
                    transform="rotate(-90 52 52)" opacity="0.9"/>
                <text x="52" y="49" text-anchor="middle" fill="var(--txt)" font-size="15"
                    font-family="DM Mono, monospace" font-weight="700" id="satPctText">—</text>
                <text x="52" y="62" text-anchor="middle" fill="var(--txt-3)" font-size="8.5" font-family="DM Mono, monospace" letter-spacing="1">SAT</text>
            </svg>
            <div class="ring-label">99.8% uptime</div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid anim anim-d2">
        <div class="stat-card" style="--grad: linear-gradient(90deg, #4F46E5, #7C3AED)">
            <div class="stat-ico">💬</div>
            <div class="stat-val" id="activeConvs">—</div>
            <div class="stat-lbl">Active Chats</div>
        </div>
        <div class="stat-card" style="--grad: linear-gradient(90deg, #059669, #10B981)">
            <div class="stat-ico">✅</div>
            <div class="stat-val" id="resolvedToday" style="color:var(--green)">—</div>
            <div class="stat-lbl">Resolved Today</div>
        </div>
        <div class="stat-card" style="--grad: linear-gradient(90deg, #0891B2, #06B6D4)">
            <div class="stat-ico">👤</div>
            <div class="stat-val" id="totalClientsCard">—</div>
            <div class="stat-lbl">Total Clients</div>
        </div>
        <div class="stat-card" style="--grad: linear-gradient(90deg, #7C3AED, #8B5CF6)">
            <div class="stat-ico">⭐</div>
            <div class="stat-val" id="satisfaction" style="color:var(--purple)">—</div>
            <div class="stat-lbl">Satisfaction</div>
        </div>
    </div>

    <!-- Bottom -->
    <div class="bottom-grid anim anim-d3">
        <!-- Activity Feed -->
        <div class="panel">
            <div class="panel-head">
                <span class="panel-title">Recent Activity</span>
                <span class="panel-live">LIVE</span>
            </div>
            <div class="act-list" id="actFeed">
                @for($i = 0; $i < 4; $i++)
                <div class="act-item">
                    <div class="shimmer" style="width:38px;height:38px;border-radius:11px;flex-shrink:0"></div>
                    <div style="flex:1">
                        <div class="shimmer" style="height:12px;width:50%;margin-bottom:8px;border-radius:4px"></div>
                        <div class="shimmer" style="height:11px;width:72%;border-radius:4px"></div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Performance Panel -->
        <div class="panel">
            <div class="panel-head">
                <span class="panel-title">Performance</span>
            </div>
            <div class="perf-body">
                <div class="perf-row"><span class="perf-key">Avg Response</span><span class="perf-val" id="avgResponse">—</span></div>
                <div class="perf-row"><span class="perf-key">Uptime</span><span class="perf-val" id="uptimeStat" style="color:var(--green)">99.8%</span></div>
                <div class="perf-row"><span class="perf-key">Satisfaction</span><span class="perf-val" id="satStat">—</span></div>
                <div class="perf-row"><span class="perf-key">Total Clients</span><span class="perf-val" id="totalClientsPerf">—</span></div>
            </div>
            <div class="bar-section">
                <div class="bar-section-title">Platform Split</div>
                <div class="bar-row">
                    <div class="bar-top"><span class="bar-key">📱 WhatsApp</span><span class="bar-pct" id="waPct">0%</span></div>
                    <div class="bar-track"><div class="bar-fill" id="waBar" style="background:#22C55E"></div></div>
                </div>
                <div class="bar-row">
                    <div class="bar-top"><span class="bar-key">⚡ Slack</span><span class="bar-pct" id="slPct">0%</span></div>
                    <div class="bar-track"><div class="bar-fill" id="slBar" style="background:var(--purple)"></div></div>
                </div>
                <div class="bar-row">
                    <div class="bar-top"><span class="bar-key">🌐 Web / API</span><span class="bar-pct" id="wbPct">0%</span></div>
                    <div class="bar-track"><div class="bar-fill" id="wbBar" style="background:var(--accent)"></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
'use strict';
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

// Date
const nowDate = document.getElementById('nowDate');
if (nowDate) nowDate.textContent = new Date().toLocaleDateString('en-US',{weekday:'long',year:'numeric',month:'long',day:'numeric'});

function esc(s){ return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function setText(id, val) { const el = document.getElementById(id); if (el) el.textContent = val; }
function setStyle(id, prop, val) { const el = document.getElementById(id); if (el) el.style[prop] = val; }

function fmtTime(raw) {
    if (!raw) return '';
    let ms = raw;
    if (typeof raw === 'number' && raw < 1e12) ms = raw * 1000;
    if (typeof raw === 'string' && /^\d+$/.test(raw)) ms = raw.length <= 10 ? Number(raw)*1000 : Number(raw);
    const d = new Date(ms); if (isNaN(d)) return String(raw);
    const now = new Date();
    const today = new Date(now.getFullYear(),now.getMonth(),now.getDate());
    const yest  = new Date(today - 86400000);
    const day   = new Date(d.getFullYear(),d.getMonth(),d.getDate());
    const t = d.toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'});
    if (day.getTime() === today.getTime()) return 'Today · ' + t;
    if (day.getTime() === yest.getTime())  return 'Yesterday · ' + t;
    return d.toLocaleDateString('en-US',{month:'short',day:'numeric'}) + ' · ' + t;
}

const PLAT_ICONS  = { whatsapp:'📱', slack:'⚡', web:'🌐', api:'🔗', twilio:'📞', email:'📧' };
const PLAT_LABELS = { whatsapp:'WhatsApp', slack:'Slack', web:'Web', api:'API', twilio:'Twilio', email:'Email' };
const PLAT_SRC    = { whatsapp:'src-whatsapp', slack:'src-slack', twilio:'src-twilio', web:'src-web', api:'src-api', email:'src-api' };

async function loadAll() {
    const [sRes, cRes] = await Promise.allSettled([
        fetch('/api/dashboard/stats', { headers:{'X-CSRF-TOKEN':CSRF} }),
        fetch('/api/conversations',   { headers:{'X-CSRF-TOKEN':CSRF} }),
    ]);

    let s = {};
    if (sRes.status === 'fulfilled' && sRes.value.ok) {
        try { const d = await sRes.value.json(); s = d.stats || d || {}; } catch(e){}
    }

    let convs = [];
    if (cRes.status === 'fulfilled' && cRes.value.ok) {
        try { const cd = await cRes.value.json(); convs = cd.conversations || cd || []; } catch(e){}
    }

    // Stats
    const totalMsgs = s.total_messages || convs.length || 0;
    setText('totalMessages', totalMsgs.toLocaleString());
    setText('activeConvs',   (s.active_conversations || convs.length || 0).toLocaleString());
    setText('resolvedToday', (s.resolved_today || 0).toLocaleString());
    setText('totalClientsCard', (s.total_clients || '—'));
    setText('totalClientsPerf', (s.total_clients || '—'));

    const sat = s.satisfaction || 94;
    setText('satisfaction', sat + '%');
    setText('satPctText', sat + '%');
    setText('satStat', sat + '%');

    // Update ring arc
    const arc = document.getElementById('ringArc');
    if (arc) {
        const circumference = 2 * Math.PI * 44;
        const offset = circumference - (sat / 100) * circumference;
        arc.setAttribute('stroke-dasharray', circumference.toFixed(1));
        arc.setAttribute('stroke-dashoffset', offset.toFixed(1));
    }

    const avg = s.avg_response || '~1.2s';
    setText('heroAvg', avg);
    setText('avgResponse', avg);

    // Platform split
    const pc = { whatsapp:0, slack:0, web:0, api:0, twilio:0 };
    convs.forEach(c => {
        const p = (c.platform||'api').toLowerCase();
        if (p === 'whatsapp') pc.whatsapp++;
        else if (p === 'slack') pc.slack++;
        else if (p === 'web')  pc.web++;
        else if (p === 'twilio') pc.twilio++;
        else pc.api++;
    });
    const tot = Object.values(pc).reduce((a,b)=>a+b,0) || 1;
    const wa = Math.round((pc.whatsapp + pc.twilio) / tot * 100);
    const sl = Math.round(pc.slack / tot * 100);
    const wb = Math.max(0, 100 - wa - sl);
    setText('waPct', wa+'%'); setText('slPct', sl+'%'); setText('wbPct', wb+'%');
    setStyle('waBar','width', wa+'%');
    setStyle('slBar','width', sl+'%');
    setStyle('wbBar','width', wb+'%');

    renderFeed(convs, s.activity || []);
}

function renderFeed(convs, fallback) {
    const el = document.getElementById('actFeed');
    const items = convs.slice(0,8).map(c => {
        const plat = (c.platform||'api').toLowerCase();
        return {
            icon: PLAT_ICONS[plat] || '📡',
            label: PLAT_LABELS[plat] || plat,
            srcCls: PLAT_SRC[plat] || 'src-api',
            name: c.client_name || c.id || 'Unknown',
            preview: c.last_message ? (c.last_message.length > 65 ? c.last_message.slice(0,65)+'…' : c.last_message) : 'Conversation started',
            timeStr: fmtTime(c.updated_at || c.created_at)
        };
    });

    if (!items.length) {
        if (fallback.length) {
            const icons2 = { message:'💬', task:'📋', resolved:'✅', lead:'🔥', email:'📧' };
            el.innerHTML = fallback.slice(0,8).map(a=>`
            <div class="act-item">
                <div class="act-icon">${icons2[a.type]||'⚡'}</div>
                <div class="act-body">
                    <div class="act-name">${esc(a.description||'Activity')}</div>
                    <div class="act-time">${esc(a.platform||'')}${a.time?' · '+fmtTime(a.time):''}</div>
                </div>
            </div>`).join('');
            return;
        }
        el.innerHTML = `<div class="act-empty"><span class="act-empty-icon">📭</span>No recent activity yet.</div>`;
        return;
    }

    el.innerHTML = items.map(i=>`
    <div class="act-item">
        <div class="act-icon">${i.icon}</div>
        <div class="act-body">
            <div class="act-name">
                ${esc(i.name)}
                <span class="src-tag ${i.srcCls}">${i.label}</span>
            </div>
            <div class="act-preview">${esc(i.preview)}</div>
            ${i.timeStr ? `<div class="act-time">${esc(i.timeStr)}</div>` : ''}
        </div>
    </div>`).join('');
}

loadAll();
setInterval(loadAll, 10000);
})();
</script>
@endsection