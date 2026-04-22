{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')
@section('content')

<div class="db-pg">

    {{-- Header --}}
    <div class="db-head anim">
        <div>
            <div class="db-title">Client Activity Center</div>
            <div class="db-sub" id="nowDate"></div>
        </div>
        <div class="live-pill"><span class="live-dot"></span> Live</div>
    </div>

    {{-- Hero --}}
    <div class="hero-card anim anim-d1">
        <div>
            <div class="hero-tag">
                <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor"><circle cx="5" cy="5" r="5"/></svg>
                Client Activity Center
            </div>
            <div class="hero-num">
                <span id="totalClientsHero">{{ $clientStats['total'] ?? 0 }}</span>
                <span class="hero-unit"> clients</span>
            </div>
            <div class="hero-sub">
                <span id="newThisWeek">{{ $clientStats['new_this_week'] ?? 0 }}</span> new this week &nbsp;·&nbsp;
                engagement <strong id="engagementRate">{{ $clientStats['engagement_rate'] ?? 0 }}%</strong>
            </div>
            <div class="plat-chips">
                <span class="plat-chip">📊 Total: {{ $clientStats['total'] ?? 0 }}</span>
                <span class="plat-chip">✅ Active: {{ $clientStats['active'] ?? 0 }}</span>
                <span class="plat-chip">🏁 Completed: {{ $clientStats['completed'] ?? 0 }}</span>
                <span class="plat-chip">💬 Engaged: {{ $clientStats['with_conversations'] ?? 0 }}</span>
            </div>
            <a href="{{ route('clients') }}" class="hero-link">
                👥 Manage Clients
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
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
                <text x="52" y="62" text-anchor="middle" fill="var(--txt-3)" font-size="8.5"
                    font-family="DM Mono, monospace" letter-spacing="1">SAT</text>
            </svg>
            <div class="ring-label">99.8% uptime</div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid anim anim-d2">
        <div class="stat-card" style="--grad: linear-gradient(90deg, #4F46E5, #7C3AED)">
            <div class="stat-ico">👥</div>
            <div class="stat-val" id="activeClients">{{ $clientStats['active'] ?? 0 }}</div>
            <div class="stat-lbl">Active Clients</div>
        </div>
        <div class="stat-card" style="--grad: linear-gradient(90deg, #059669, #10B981)">
            <div class="stat-ico">🆕</div>
            <div class="stat-val" id="newThisWeekCard" style="color:var(--green)">{{ $clientStats['new_this_week'] ?? 0 }}</div>
            <div class="stat-lbl">New This Week</div>
        </div>
        <div class="stat-card" style="--grad: linear-gradient(90deg, #0891B2, #06B6D4)">
            <div class="stat-ico">🏁</div>
            <div class="stat-val" id="completedClients">{{ $clientStats['completed'] ?? 0 }}</div>
            <div class="stat-lbl">Completed</div>
        </div>
        <div class="stat-card" style="--grad: linear-gradient(90deg, #7C3AED, #8B5CF6)">
            <div class="stat-ico">📈</div>
            <div class="stat-val" id="engRateCard" style="color:var(--purple)">{{ $clientStats['engagement_rate'] ?? 0 }}%</div>
            <div class="stat-lbl">Engagement Rate</div>
        </div>
    </div>

    {{-- Bottom --}}
    <div class="bottom-grid anim anim-d3">
        {{-- Activity Feed --}}
        <div class="panel">
            <div class="panel-head">
                <span class="panel-title">Recent Client Activity</span>
                <span class="panel-live">LIVE</span>
            </div>
            <div class="act-list" id="actFeed">
                @forelse($recentClients as $client)
                <div class="act-item">
                    <div class="act-icon">
                        {{ $client['channel'] == 'whatsapp' ? '📱' : ($client['channel'] == 'slack' ? '⚡' : '🌐') }}
                    </div>
                    <div class="act-body">
                        <div class="act-name">
                            {{ $client['name'] }}
                            <span class="src-tag src-{{ $client['channel'] }}">{{ ucfirst($client['channel']) }}</span>
                            <span class="via">· {{ $client['conversation_count'] }} convs</span>
                        </div>
                        <div class="act-preview">{{ $client['latest_message'] ?? 'No messages yet' }}</div>
                        <div class="act-time">{{ $client['updated_at']->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <div class="act-empty">
                    <span class="act-empty-icon">📭</span>
                    No client activity yet. Check back soon!
                </div>
                @endforelse
            </div>
        </div>

        {{-- Analytics Panel --}}
        <div class="panel">
            <div class="panel-head">
                <span class="panel-title">Client Analytics</span>
            </div>
            <div class="perf-body">
                <div class="perf-row">
                    <span class="perf-key">Total Clients</span>
                    <span class="perf-val" id="totalClientsPerf">{{ $clientStats['total'] ?? 0 }}</span>
                </div>
                <div class="perf-row">
                    <span class="perf-key">Active Clients</span>
                    <span class="perf-val" id="activeClientsPerf" style="color:var(--accent)">{{ $clientStats['active'] ?? 0 }}</span>
                </div>
                <div class="perf-row">
                    <span class="perf-key">Completed</span>
                    <span class="perf-val" id="completedPerf" style="color:var(--green)">{{ $clientStats['completed'] ?? 0 }}</span>
                </div>
                <div class="perf-row">
                    <span class="perf-key">With Conversations</span>
                    <span class="perf-val" id="withConversations">{{ $clientStats['with_conversations'] ?? 0 }}</span>
                </div>
            </div>
            <div class="bar-section">
                <div class="bar-section-title">Platform Split</div>
                <div class="bar-row">
                    <div class="bar-top">
                        <span class="bar-key">📱 WhatsApp</span>
                        <span class="bar-pct" id="waPct">0%</span>
                    </div>
                    <div class="bar-track"><div class="bar-fill" id="waBar" style="background:#22C55E"></div></div>
                </div>
                <div class="bar-row">
                    <div class="bar-top">
                        <span class="bar-key">⚡ Slack</span>
                        <span class="bar-pct" id="slPct">0%</span>
                    </div>
                    <div class="bar-track"><div class="bar-fill" id="slBar" style="background:var(--purple)"></div></div>
                </div>
                <div class="bar-row">
                    <div class="bar-top">
                        <span class="bar-key">🌐 Web / API</span>
                        <span class="bar-pct" id="wbPct">0%</span>
                    </div>
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

/* Date */
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
        fetch('/api/dashboard/messages', { headers:{'X-CSRF-TOKEN':CSRF} }),
    ]);

    let s = {};
    if (sRes.status === 'fulfilled' && sRes.value.ok) {
        try { const d = await sRes.value.json(); s = d.stats || d || {}; } catch(e){}
    }

    let clients = [];
    if (cRes.status === 'fulfilled' && cRes.value.ok) {
        try { const cd = await cRes.value.json(); clients = cd.clients || cd || []; } catch(e){}
    }

    setText('totalClientsHero',  (s.total_clients   || 0).toLocaleString());
    setText('newThisWeek',       (s.new_this_week   || 0).toLocaleString());
    setText('engagementRate',    (s.engagement_rate || 0) + '%');
    setText('activeClients',     (s.active_clients  || 0).toLocaleString());
    setText('newThisWeekCard',   (s.new_this_week   || 0).toLocaleString());
    setText('completedClients',  (s.completed_clients || 0).toLocaleString());
    setText('totalClientsPerf',  (s.total_clients   || 0).toLocaleString());
    setText('activeClientsPerf', (s.active_clients  || 0).toLocaleString());
    setText('completedPerf',     (s.completed_clients || 0).toLocaleString());
    setText('withConversations', (s.with_conversations || 0).toLocaleString());
    setText('engRateCard',       (s.engagement_rate || 0) + '%');

    const sat = s.satisfaction || 94;
    setText('satPctText', sat + '%');
    const arc = document.getElementById('ringArc');
    if (arc) {
        const circ   = 2 * Math.PI * 44;
        const offset = circ - (sat / 100) * circ;
        arc.setAttribute('stroke-dasharray', circ.toFixed(1));
        arc.setAttribute('stroke-dashoffset', offset.toFixed(1));
    }

    /* Platform split */
    const pc = { whatsapp:0, slack:0, web:0, api:0, twilio:0 };
    clients.forEach(c => {
        const p = (c.platform||'api').toLowerCase();
        pc[p] !== undefined ? pc[p]++ : pc.api++;
    });
    const tot = Object.values(pc).reduce((a,b)=>a+b,0) || 1;
    const wa  = Math.round((pc.whatsapp + pc.twilio) / tot * 100);
    const sl  = Math.round(pc.slack / tot * 100);
    const wb  = Math.max(0, 100 - wa - sl);
    setText('waPct', wa+'%'); setText('slPct', sl+'%'); setText('wbPct', wb+'%');
    setStyle('waBar','width', wa+'%');
    setStyle('slBar','width', sl+'%');
    setStyle('wbBar','width', wb+'%');

    renderFeed(clients, s.activity || []);
}

function renderFeed(clients, fallback) {
    const el = document.getElementById('actFeed');
    const items = clients.slice(0,8).map(c => {
        const plat = (c.platform||'api').toLowerCase();
        return {
            icon: PLAT_ICONS[plat] || '⚡',
            label: PLAT_LABELS[plat] || plat,
            srcCls: PLAT_SRC[plat] || 'src-api',
            name: c.client_name || c.name || 'Unknown',
            preview: c.last_message ? c.last_message.slice(0,65) + (c.last_message.length > 65 ? '…' : '') : 'No messages yet',
            timeStr: fmtTime(c.updated_at || c.created_at),
            convCount: c.conversation_count || 0
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
                <span class="via">· ${i.convCount} convs</span>
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