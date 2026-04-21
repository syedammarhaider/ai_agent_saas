@extends('layouts.app')
@section('content')
<style>
.cl-pg { padding: 28px; font-family: var(--font-body); min-height: 100vh; }
@media(max-width:768px) { .cl-pg { padding: 16px; } }

/* TOOLBAR */
.cl-toolbar { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 26px; flex-wrap: wrap; }
.pg-title { font-family: var(--font-display); font-size: 26px; font-weight: 800; color: var(--txt); letter-spacing: -0.8px; }
.pg-subtitle { font-size: 12px; color: var(--txt-4); margin-top: 4px; font-family: var(--font-mono); }
.toolbar-right { display: flex; align-items: center; gap: 10px; margin-left: auto; flex-wrap: wrap; }

.search-wrap { position: relative; display: flex; align-items: center; }
.search-wrap svg { position: absolute; left: 11px; color: var(--txt-4); pointer-events: none; }
.search-wrap .inp { padding-left: 36px; width: 240px; }

/* STATS */
.stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 22px; }
@media(max-width:900px) { .stats-row { grid-template-columns: repeat(2, 1fr); } }

.st-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius-lg); padding: 18px 20px;
    transition: all 200ms var(--ease); box-shadow: var(--shadow-sm);
}
.st-card:hover { border-color: var(--border-md); transform: translateY(-2px); box-shadow: var(--shadow-md); }
.st-label { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: var(--txt-4); margin-bottom: 10px; display: flex; align-items: center; gap: 6px; font-family: var(--font-mono); }
.st-value { font-family: var(--font-display); font-size: 30px; font-weight: 800; color: var(--txt); letter-spacing: -1.2px; line-height: 1; }

/* FILTER PILLS */
.filter-bar { display: flex; gap: 6px; margin-bottom: 20px; flex-wrap: wrap; }
.fpill {
    padding: 6px 15px; border-radius: 20px; font-size: 12px; font-weight: 600;
    border: 1px solid var(--border-md); background: var(--bg-card); color: var(--txt-3);
    cursor: pointer; transition: all 180ms var(--ease); font-family: var(--font-body);
}
.fpill:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }
.fpill.on { background: var(--accent); border-color: var(--accent); color: white; box-shadow: 0 2px 8px var(--accent-glow); }

/* MAIN LAYOUT */
.cl-main { display: flex; gap: 20px; align-items: flex-start; }
.cl-list-wrap { flex: 1; min-width: 0; }

/* TABLE */
.cl-table-card {
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-sm);
}
.cl-table-head {
    display: grid; grid-template-columns: 2fr 2fr 1.2fr 1fr 80px;
    padding: 12px 20px; border-bottom: 1px solid var(--border);
    background: var(--bg-raised);
}
.cl-th { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--txt-4); font-family: var(--font-mono); }

.cl-row {
    display: grid; grid-template-columns: 2fr 2fr 1.2fr 1fr 80px;
    padding: 14px 20px; border-bottom: 1px solid var(--border);
    cursor: pointer; transition: background 120ms; align-items: center;
}
.cl-row:last-child { border-bottom: none; }
.cl-row:hover { background: var(--bg-hover); }
.cl-row.selected { background: var(--accent-soft); border-left: 3px solid var(--accent); }

.cl-av {
    width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 13px; color: white; font-family: var(--font-display);
}
.cl-name-cell { display: flex; align-items: center; gap: 11px; min-width: 0; }
.cl-name { font-size: 13.5px; font-weight: 600; color: var(--txt); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cl-company { font-size: 11.5px; color: var(--txt-4); margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.cl-contact-cell { display: flex; flex-direction: column; gap: 2px; justify-content: center; min-width: 0; }
.cl-email { font-size: 12px; color: var(--txt-2); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cl-phone { font-size: 11.5px; color: var(--txt-4); }

.cl-actions { display: flex; align-items: center; justify-content: flex-end; }
.del-btn {
    padding: 5px 11px; border-radius: 8px; font-size: 11px; font-weight: 600;
    background: var(--red-soft); color: var(--red); border: 1px solid rgba(220,38,38,0.15);
    cursor: pointer; font-family: var(--font-body); transition: all 150ms;
}
.del-btn:hover { background: var(--red); color: white; }

/* EMPTY / LOADING */
.cl-empty { padding: 70px 20px; text-align: center; }
.cl-empty-icon { font-size: 48px; margin-bottom: 14px; opacity: 0.12; display: block; }
.cl-empty-text { font-size: 14px; color: var(--txt-4); }

.sk-item { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 13px; }
.sk-av { width: 38px; height: 38px; border-radius: 11px; flex-shrink: 0; }

/* DETAIL PANEL */
.detail-panel {
    width: 316px; flex-shrink: 0; position: sticky; top: 24px;
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-lg);
    animation: slideIn 0.28s var(--ease-back) both;
}
@keyframes slideIn { from { opacity:0; transform:translateX(18px) scale(0.97); } to { opacity:1; transform:translateX(0) scale(1); } }
@media(max-width:1100px) { .detail-panel { width: 280px; } }
@media(max-width:900px) {
    .detail-panel {
        position: fixed; right: 16px; top: 90px; width: calc(100vw - 32px);
        max-width: 360px; z-index: 700;
    }
}

.dp-header { padding: 22px 20px 18px; position: relative; }
.dp-close {
    position: absolute; top: 14px; right: 14px;
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(255,255,255,0.2); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center; color: white;
    transition: background 160ms; font-size: 18px; line-height: 1;
}
.dp-close:hover { background: rgba(255,255,255,0.35); }
.dp-avatar {
    width: 58px; height: 58px; border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 24px; color: white;
    background: rgba(255,255,255,0.25); border: 2px solid rgba(255,255,255,0.4);
    margin-bottom: 12px; font-family: var(--font-display);
}
.dp-name { font-family: var(--font-display); font-size: 18px; font-weight: 800; color: white; line-height: 1.2; }
.dp-sub  { font-size: 12px; color: rgba(255,255,255,0.75); margin-top: 4px; font-family: var(--font-mono); }

.dp-section { padding: 16px 20px; border-bottom: 1px solid var(--border); }
.dp-section:last-child { border-bottom: none; }
.dp-section-label {
    font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
    color: var(--txt-4); margin-bottom: 12px; font-family: var(--font-mono);
}
.dp-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 9px; gap: 10px; }
.dp-row:last-child { margin-bottom: 0; }
.dp-key { font-size: 12px; color: var(--txt-3); flex-shrink: 0; }
.dp-val { font-size: 12.5px; color: var(--txt); font-weight: 500; text-align: right; word-break: break-all; }

.dp-actions { padding: 14px 20px; display: flex; flex-direction: column; gap: 8px; }
.dp-action-btn {
    width: 100%; padding: 10px; border-radius: var(--radius-md);
    font-size: 13px; font-weight: 600; font-family: var(--font-body);
    cursor: pointer; border: none; transition: all 160ms; text-align: center;
    text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 7px;
}
.dp-action-primary { background: var(--accent); color: white; }
.dp-action-primary:hover { filter: brightness(1.1); }
.dp-action-ghost { background: var(--bg-hover); color: var(--txt-2); border: 1px solid var(--border-md); }
.dp-action-ghost:hover { background: var(--bg-raised); color: var(--txt); }
.dp-action-danger { background: var(--red-soft); color: var(--red); border: 1px solid rgba(220,38,38,0.18); }
.dp-action-danger:hover { background: var(--red); color: white; }

/* ADD CLIENT MODAL */
.modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,0.45); backdrop-filter: blur(6px);
    z-index: 9000; align-items: center; justify-content: center;
}
.modal-overlay.open { display: flex; }
.modal-box {
    background: var(--bg-card); border: 1px solid var(--border-md);
    border-radius: var(--radius-xl); width: min(520px, 95vw);
    box-shadow: var(--shadow-lg);
    animation: modal-in 0.28s var(--ease-back);
}
@keyframes modal-in { from{opacity:0;transform:scale(0.95)translateY(10px)} to{opacity:1;transform:scale(1)translateY(0)} }
.modal-header {
    padding: 22px 24px 18px; border-bottom: 1px solid var(--border);
    display: flex; align-items: center; justify-content: space-between;
}
.modal-title { font-family: var(--font-display); font-size: 17px; font-weight: 700; color: var(--txt); }
.modal-close-btn {
    width: 32px; height: 32px; border-radius: 50%; background: var(--bg-hover);
    border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;
    color: var(--txt-3); font-size: 18px; transition: all 160ms;
}
.modal-close-btn:hover { background: var(--bg-raised); color: var(--txt); }
.modal-body { padding: 20px 24px; }
.field-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media(max-width:480px) { .field-grid { grid-template-columns: 1fr; } }
.field-wrap { display: flex; flex-direction: column; gap: 5px; }
.field-wrap.full { grid-column: 1 / -1; }
.field-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px; color: var(--txt-3); font-family: var(--font-mono); }
.modal-footer {
    padding: 16px 24px; border-top: 1px solid var(--border);
    display: flex; gap: 10px; justify-content: flex-end;
}
</style>

<div class="cl-pg">

    <!-- Toolbar -->
    <div class="cl-toolbar anim">
        <div>
            <div class="pg-title">Clients</div>
            <div class="pg-subtitle" id="clientSubtitle">Loading…</div>
        </div>
        <div class="toolbar-right">
            <div class="search-wrap">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                <input class="inp" id="clientSearch" placeholder="Search clients…" autocomplete="off" oninput="debounceSearch()">
            </div>
            <button class="btn btn-primary" onclick="openAddModal()">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
                Add Client
            </button>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-row anim anim-d1">
        <div class="st-card">
            <div class="st-label">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                Total
            </div>
            <div class="st-value" id="stTotal">—</div>
        </div>
        <div class="st-card">
            <div class="st-label" style="color:var(--green)">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Active
            </div>
            <div class="st-value" id="stActive" style="color:var(--green)">—</div>
        </div>
        <div class="st-card">
            <div class="st-label" style="color:var(--cyan)">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                WhatsApp
            </div>
            <div class="st-value" id="stWA" style="color:var(--cyan)">—</div>
        </div>
        <div class="st-card">
            <div class="st-label" style="color:var(--purple)">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/></svg>
                Platforms
            </div>
            <div class="st-value" id="stPlats" style="color:var(--purple)">—</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-bar anim anim-d2">
        <button class="fpill on" onclick="setFilter('all',this)">All Clients</button>
        <button class="fpill" onclick="setFilter('active',this)">Active</button>
        <button class="fpill" onclick="setFilter('whatsapp',this)">📱 WhatsApp</button>
        <button class="fpill" onclick="setFilter('slack',this)">⚡ Slack</button>
        <button class="fpill" onclick="setFilter('api',this)">🔗 API</button>
        <button class="fpill" onclick="setFilter('web',this)">🌐 Web</button>
    </div>

    <!-- Main -->
    <div class="cl-main anim anim-d3">
        <div class="cl-list-wrap">
            <div class="cl-table-card">
                <div class="cl-table-head">
                    <div class="cl-th">Client</div>
                    <div class="cl-th">Contact</div>
                    <div class="cl-th">Platform</div>
                    <div class="cl-th">Status</div>
                    <div class="cl-th" style="text-align:right">Action</div>
                </div>
                <div id="clRows">
                    @for($i = 0; $i < 4; $i++)
                    <div class="sk-item">
                        <div class="shimmer sk-av" style="border-radius:11px;flex-shrink:0"></div>
                        <div style="flex:1">
                            <div class="shimmer" style="height:12px;width:48%;margin-bottom:7px;border-radius:4px"></div>
                            <div class="shimmer" style="height:11px;width:32%;border-radius:4px"></div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
        <div id="detailPanelMount"></div>
    </div>
</div>

<!-- Add Client Modal -->
<div class="modal-overlay" id="addModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title">Add New Client</div>
            <button class="modal-close-btn" onclick="closeAddModal()">×</button>
        </div>
        <div class="modal-body">
            <div class="field-grid">
                <div class="field-wrap full">
                    <label class="field-label">Full Name *</label>
                    <input class="inp" id="mName" placeholder="e.g. Ahmed Khan">
                </div>
                <div class="field-wrap">
                    <label class="field-label">Email Address *</label>
                    <input class="inp" id="mEmail" type="email" placeholder="ahmed@example.com">
                </div>
                <div class="field-wrap">
                    <label class="field-label">Phone Number</label>
                    <input class="inp" id="mPhone" placeholder="+92 300 1234567">
                </div>
                <div class="field-wrap">
                    <label class="field-label">Company</label>
                    <input class="inp" id="mCompany" placeholder="Company or Org">
                </div>
                <div class="field-wrap">
                    <label class="field-label">Channel</label>
                    <select class="inp" id="mChannel">
                        <option value="api">API</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="slack">Slack</option>
                        <option value="web">Web</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeAddModal()">Cancel</button>
            <button class="btn btn-primary" onclick="saveClient()">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                Save Client
            </button>
        </div>
    </div>
</div>

<script>
(function(){
'use strict';
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

const PLAT_CONFIG = {
    whatsapp: { icon:'📱', label:'WhatsApp', cls:'badge-green',  color:'#22C55E' },
    slack:    { icon:'⚡', label:'Slack',     cls:'badge-purple', color:'var(--purple)' },
    api:      { icon:'🔗', label:'API',       cls:'badge-blue',   color:'var(--accent)' },
    web:      { icon:'🌐', label:'Web',       cls:'badge-cyan',   color:'var(--cyan)' },
    twilio:   { icon:'📞', label:'Twilio',    cls:'badge-red',    color:'var(--red)' },
    email:    { icon:'📧', label:'Email',     cls:'badge-amber',  color:'var(--amber)' },
};

const AVATAR_COLORS = ['#4F46E5','#7C3AED','#059669','#D97706','#DC2626','#0891B2','#64748B','#0D9488'];

let allClients = [], curFilter = 'all', searchQuery = '', selectedId = null, searchTimer = null;

function esc(s){ return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function avatarColor(name) {
    let h = 0; for (const c of String(name||'A')) h = (h * 31 + c.charCodeAt(0)) % AVATAR_COLORS.length;
    return AVATAR_COLORS[Math.abs(h)];
}
function initials(name) {
    return String(name||'?').split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase();
}
function platCfg(plat) { return PLAT_CONFIG[String(plat).toLowerCase()] || PLAT_CONFIG.api; }

async function load() {
    try {
        const q = new URLSearchParams();
        if (searchQuery) q.set('search', searchQuery);
        if (curFilter !== 'all' && curFilter !== 'active') q.set('platform', curFilter);
        const res = await fetch('/api/clients?' + q, { headers:{'X-CSRF-TOKEN':CSRF} });
        if (!res.ok) throw new Error(res.status);
        const d = await res.json();
        allClients = d.clients ?? [];
        if (curFilter === 'active') allClients = allClients.filter(c => (c.status ?? 'active') === 'active');
        render();
        updateStats();
    } catch(e) {
        document.getElementById('clRows').innerHTML = `<div class="cl-empty"><span class="cl-empty-icon">⚠️</span><div class="cl-empty-text">Could not load clients. Check backend connection.</div></div>`;
    }
}

function render() {
    const el = document.getElementById('clRows');
    const sub = document.getElementById('clientSubtitle');
    if (sub) sub.textContent = allClients.length + ' client' + (allClients.length !== 1 ? 's' : '') + ' found';

    if (!allClients.length) {
        el.innerHTML = `<div class="cl-empty"><span class="cl-empty-icon">👤</span><div class="cl-empty-text">No clients yet. Add your first client!</div></div>`;
        return;
    }

    el.innerHTML = allClients.map(c => {
        const pc = platCfg((c.platforms||[])[0] || c.channel || 'api');
        const bgCol = avatarColor(c.name);
        const isSelected = c.id === selectedId;
        const status = c.status ?? 'active';
        return `
        <div class="cl-row ${isSelected ? 'selected' : ''}" onclick="selectClient('${esc(c.id)}')" id="row-${esc(c.id)}">
            <div class="cl-name-cell">
                <div class="cl-av" style="background:${bgCol}">${esc(initials(c.name))}</div>
                <div>
                    <div class="cl-name">${esc(c.name || 'Unknown')}</div>
                    <div class="cl-company">${esc(c.company || '—')}</div>
                </div>
            </div>
            <div class="cl-contact-cell">
                <div class="cl-email">${esc(c.email || '—')}</div>
                <div class="cl-phone">${esc(c.phone || '')}</div>
            </div>
            <div>
                <span class="badge ${pc.cls}">${pc.icon} ${pc.label}</span>
            </div>
            <div>
                <span class="badge ${status === 'active' ? 'badge-green' : 'badge-neutral'}">${status}</span>
            </div>
            <div class="cl-actions">
                <button class="del-btn" onclick="event.stopPropagation(); deleteClient('${esc(c.id)}', '${esc(c.name)}')">Delete</button>
            </div>
        </div>`;
    }).join('');
}

function updateStats() {
    const total = allClients.length;
    const active = allClients.filter(c => (c.status ?? 'active') === 'active').length;
    const wa = allClients.filter(c => ((c.platforms||[])[0]||c.channel||'api').toLowerCase() === 'whatsapp').length;
    const plats = new Set(allClients.flatMap(c => c.platforms || (c.channel ? [c.channel] : ['api'])));
    document.getElementById('stTotal').textContent = total;
    document.getElementById('stActive').textContent = active;
    document.getElementById('stWA').textContent = wa;
    document.getElementById('stPlats').textContent = plats.size || '—';
}

function selectClient(id) {
    const client = allClients.find(c => String(c.id) === String(id));
    if (!client) return;
    if (selectedId === id) {
        selectedId = null;
        document.getElementById('detailPanelMount').innerHTML = '';
        document.querySelectorAll('.cl-row').forEach(r => r.classList.remove('selected'));
        return;
    }
    selectedId = id;
    document.querySelectorAll('.cl-row').forEach(r => r.classList.remove('selected'));
    const row = document.getElementById('row-' + id);
    if (row) row.classList.add('selected');
    renderDetailPanel(client);
}
window.selectClient = selectClient;

function renderDetailPanel(c) {
    const pc = platCfg((c.platforms||[])[0] || c.channel || 'api');
    const bgCol = avatarColor(c.name);
    const status = c.status ?? 'active';
    const mount = document.getElementById('detailPanelMount');
    mount.innerHTML = `
    <div class="detail-panel" id="detailPanel">
        <div class="dp-header" style="background:linear-gradient(135deg,${bgCol}ee,${bgCol}88);">
            <button class="dp-close" onclick="selectClient('${esc(c.id)}')">×</button>
            <div class="dp-avatar">${esc(initials(c.name))}</div>
            <div class="dp-name">${esc(c.name || 'Unknown')}</div>
            <div class="dp-sub">${pc.icon} ${pc.label} · ${esc(status)}</div>
        </div>
        <div class="dp-section">
            <div class="dp-section-label">Contact Info</div>
            <div class="dp-row"><span class="dp-key">Email</span><span class="dp-val">${esc(c.email || '—')}</span></div>
            <div class="dp-row"><span class="dp-key">Phone</span><span class="dp-val">${esc(c.phone || '—')}</span></div>
            <div class="dp-row"><span class="dp-key">Company</span><span class="dp-val">${esc(c.company || '—')}</span></div>
            <div class="dp-row"><span class="dp-key">Status</span><span class="dp-val"><span class="badge ${status === 'active' ? 'badge-green' : 'badge-neutral'}">${esc(status)}</span></span></div>
        </div>
        <div class="dp-section">
            <div class="dp-section-label">Channel</div>
            <div class="dp-row"><span class="dp-key">Platform</span><span class="dp-val"><span class="badge ${pc.cls}">${pc.icon} ${pc.label}</span></span></div>
        </div>
        <div class="dp-actions">
            ${c.email ? `<a href="mailto:${esc(c.email)}" class="dp-action-btn dp-action-primary">📧 Send Email</a>` : ''}
            ${c.phone ? `<a href="https://wa.me/${c.phone.replace(/\D/g,'')}" target="_blank" class="dp-action-btn dp-action-ghost">📱 Open WhatsApp</a>` : ''}
            <a href="{{ route('chat') }}" class="dp-action-btn dp-action-ghost">💬 View Conversations</a>
            <button class="dp-action-btn dp-action-danger" onclick="deleteClient('${esc(c.id)}','${esc(c.name)}')">
                🗑️ Delete Client
            </button>
        </div>
    </div>`;
}

async function deleteClient(id, name) {
    if (!confirm('Delete "' + (name || 'this client') + '"? This cannot be undone.')) return;
    try {
        const res = await fetch('/api/clients/' + encodeURIComponent(id), { method:'DELETE', headers:{'X-CSRF-TOKEN':CSRF} });
        if (res.ok) {
            if (selectedId === id) { selectedId = null; document.getElementById('detailPanelMount').innerHTML = ''; }
            showToast('Client deleted', 'success');
            load();
        }
    } catch(e) { showToast('Delete failed', 'error'); }
}
window.deleteClient = deleteClient;

function setFilter(f, btn) {
    curFilter = f;
    document.querySelectorAll('.fpill').forEach(b => b.classList.remove('on'));
    btn.classList.add('on');
    load();
}
window.setFilter = setFilter;

function debounceSearch() {
    searchQuery = document.getElementById('clientSearch').value.trim();
    clearTimeout(searchTimer);
    searchTimer = setTimeout(load, 280);
}
window.debounceSearch = debounceSearch;

function openAddModal()  { document.getElementById('addModal').classList.add('open'); }
function closeAddModal() { document.getElementById('addModal').classList.remove('open'); }
window.openAddModal  = openAddModal;
window.closeAddModal = closeAddModal;

document.getElementById('addModal').addEventListener('click', e => {
    if (e.target === document.getElementById('addModal')) closeAddModal();
});

async function saveClient() {
    const name  = document.getElementById('mName').value.trim();
    const email = document.getElementById('mEmail').value.trim();
    if (!name)  { showToast('Name is required', 'error'); return; }
    if (!email) { showToast('Email is required', 'error'); return; }
    try {
        const res = await fetch('/api/clients', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({
                name, email,
                phone: document.getElementById('mPhone').value,
                company: document.getElementById('mCompany').value,
                channel: document.getElementById('mChannel').value,
            })
        });
        if (res.ok) {
            closeAddModal();
            ['mName','mEmail','mPhone','mCompany'].forEach(id => { document.getElementById(id).value = ''; });
            document.getElementById('mChannel').value = 'api';
            showToast('Client added!', 'success');
            load();
        } else {
            showToast('Failed to save. Check inputs.', 'error');
        }
    } catch(e) { showToast('Error: ' + e.message, 'error'); }
}
window.saveClient = saveClient;

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeAddModal();
    if (e.key === 'Enter' && document.getElementById('addModal').classList.contains('open')) saveClient();
});

load();
setInterval(load, 20000);
})();
</script>
@endsection