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
    width: 360px; flex-shrink: 0; position: sticky; top: 24px;
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-lg);
    animation: slideIn 0.28s var(--ease-back) both;
}
@keyframes slideIn { from { opacity:0; transform:translateX(18px) scale(0.97); } to { opacity:1; transform:translateX(0) scale(1); } }
@media(max-width:1100px) { .detail-panel { width: 320px; } }
@media(max-width:900px) {
    .detail-panel {
        position: fixed; right: 16px; top: 90px; width: calc(100vw - 32px);
        max-width: 400px; z-index: 700; max-height: calc(100vh - 110px); overflow-y: auto;
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

/* Status Dropdown */
.status-dropdown {
    position: relative; width: 100%; margin-bottom: 12px;
}
.status-select {
    width: 100%; padding: 10px 13px; background: var(--bg-input);
    border: 1px solid var(--border-md); border-radius: var(--radius-md);
    font-size: 13px; font-family: var(--font-body); color: var(--txt);
    cursor: pointer; outline: none; transition: all var(--trans);
}
.status-select:hover { border-color: var(--border-str); }
.status-select:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }

/* Message Box */
.msg-box-section { padding: 16px 20px; background: var(--bg-raised); }
.msg-textarea {
    width: 100%; min-height: 100px; padding: 12px; background: var(--bg-input);
    border: 1px solid var(--border-md); border-radius: var(--radius-md);
    font-size: 13px; font-family: var(--font-body); color: var(--txt);
    resize: vertical; outline: none; transition: all var(--trans);
}
.msg-textarea::placeholder { color: var(--txt-4); }
.msg-textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }

.send-msg-btn {
    width: 100%; padding: 10px; margin-top: 10px; border-radius: var(--radius-md);
    font-size: 13px; font-weight: 600; font-family: var(--font-body);
    cursor: pointer; border: none; transition: all 160ms; text-align: center;
    background: var(--accent); color: white;
    display: flex; align-items: center; justify-content: center; gap: 7px;
}
.send-msg-btn:hover { filter: brightness(1.1); }
.send-msg-btn:disabled { opacity: 0.5; cursor: not-allowed; }

/* Project Details */
.project-details-box {
    background: var(--bg-raised); border: 1px solid var(--border);
    border-radius: var(--radius-md); padding: 14px; margin-top: 10px;
}
.project-details-text {
    font-size: 13px; line-height: 1.6; color: var(--txt-2);
    white-space: pre-wrap; word-wrap: break-word;
}

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
                In Progress
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
        <button class="fpill" onclick="setFilter('whatsapp',this)">📱 WhatsApp</button>
        <button class="fpill" onclick="setFilter('slack',this)">⚡ Slack</button>
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
                <div class="field-wrap full">
                    <label class="field-label">Channel *</label>
                    <select class="inp" id="mChannel">
                        <option value="whatsapp">📱 WhatsApp</option>
                        <option value="slack">⚡ Slack</option>
                    </select>
                </div>
                <div class="field-wrap full">
                    <label class="field-label">Project Details</label>
                    <textarea class="inp" id="mProject" rows="3" placeholder="Brief description of the project..."></textarea>
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
};

const AVATAR_COLORS = ['#4F46E5','#7C3AED','#059669','#D97706','#DC2626','#0891B2','#64748B','#0D9488'];

const STATUS_CONFIG = {
    in_progress: { label: 'In Progress', color: '#4F46E5', cls: 'badge-blue' },
    completed:   { label: 'Completed',   color: '#059669', cls: 'badge-green' },
    cancelled:   { label: 'Cancelled',   color: '#DC2626', cls: 'badge-red' },
};

let allClients = [], curFilter = 'all', searchQuery = '', selectedId = null, searchTimer = null;

function esc(s){ return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function avatarColor(name) {
    let h = 0; for (const c of String(name||'A')) h = (h * 31 + c.charCodeAt(0)) % AVATAR_COLORS.length;
    return AVATAR_COLORS[Math.abs(h)];
}
function initials(name) {
    return String(name||'?').split(' ').map(w=>w[0]).join('').slice(0,2).toUpperCase();
}
function platCfg(plat) { return PLAT_CONFIG[String(plat).toLowerCase()] || PLAT_CONFIG.whatsapp; }
function statusCfg(status) { return STATUS_CONFIG[status] || STATUS_CONFIG.in_progress; }

async function load() {
    try {
        const q = new URLSearchParams();
        if (searchQuery) q.set('search', searchQuery);
        if (curFilter !== 'all') q.set('platform', curFilter);
        const res = await fetch('/api/clients?' + q, { headers:{'X-CSRF-TOKEN':CSRF} });
        if (!res.ok) throw new Error(res.status);
        const d = await res.json();
        allClients = d.clients ?? [];
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
        const pc = platCfg((c.platforms||[])[0] || c.channel || 'whatsapp');
        const sc = statusCfg(c.status);
        const bgCol = avatarColor(c.name);
        const isSelected = c.id === selectedId;
        return `
        <div class="cl-row ${isSelected ? 'selected' : ''}" onclick="selectClient('${esc(c.id)}')" id="row-${esc(c.id)}">
            <div class="cl-name-cell">
                <div class="cl-av" style="background:${bgCol}">${esc(initials(c.name))}</div>
                <div>
                    <div class="cl-name">${esc(c.name || 'Unknown')}</div>
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
                <span class="badge ${sc.cls}">${sc.label}</span>
            </div>
            <div class="cl-actions">
                <button class="del-btn" onclick="event.stopPropagation(); deleteClient('${esc(c.id)}', '${esc(c.name)}')">Delete</button>
            </div>
        </div>`;
    }).join('');
}

function updateStats() {
    const total = allClients.length;
    const active = allClients.filter(c => c.status === 'in_progress').length;
    const wa = allClients.filter(c => ((c.platforms||[])[0]||c.channel||'').toLowerCase() === 'whatsapp').length;
    const plats = new Set(allClients.flatMap(c => c.platforms || (c.channel ? [c.channel] : ['whatsapp'])));
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
    const pc = platCfg((c.platforms||[])[0] || c.channel || 'whatsapp');
    const bgCol = avatarColor(c.name);
    const sc = statusCfg(c.status);
    const mount = document.getElementById('detailPanelMount');
    mount.innerHTML = `
    <div class="detail-panel" id="detailPanel">
        <div class="dp-header" style="background:linear-gradient(135deg,${bgCol}ee,${bgCol}88);">
            <button class="dp-close" onclick="selectClient('${esc(c.id)}')">×</button>
            <div class="dp-avatar">${esc(initials(c.name))}</div>
            <div class="dp-name">${esc(c.name || 'Unknown')}</div>
            <div class="dp-sub">${pc.icon} ${pc.label}</div>
        </div>
        <div class="dp-section">
            <div class="dp-section-label">Contact Info</div>
            <div class="dp-row"><span class="dp-key">Email</span><span class="dp-val">${esc(c.email || '—')}</span></div>
            <div class="dp-row"><span class="dp-key">Phone</span><span class="dp-val">${esc(c.phone || '—')}</span></div>
        </div>
        <div class="dp-section">
            <div class="dp-section-label">Status Management</div>
            <div class="status-dropdown">
                <select class="status-select" id="statusSelect-${c.id}" onchange="changeStatus('${c.id}', this.value)">
                    <option value="in_progress" ${c.status === 'in_progress' ? 'selected' : ''}>⚡ In Progress</option>
                    <option value="completed" ${c.status === 'completed' ? 'selected' : ''}>✅ Completed</option>
                    <option value="cancelled" ${c.status === 'cancelled' ? 'selected' : ''}>❌ Cancelled</option>
                </select>
            </div>
            <div style="font-size:11px;color:var(--txt-4);margin-top:6px;">
                Changing status will send an email notification to the client
            </div>
        </div>
        ${c.project_details ? `
        <div class="dp-section">
            <div class="dp-section-label">Project Details</div>
            <div class="project-details-box">
                <div class="project-details-text">${esc(c.project_details)}</div>
            </div>
        </div>
        ` : ''}
        <div class="msg-box-section">
            <div class="dp-section-label">Send Message to Client</div>
            <textarea class="msg-textarea" id="msgText-${c.id}" placeholder="Type your message to ${c.name}..."></textarea>
            <button class="send-msg-btn" onclick="sendMessage('${c.id}')">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Send Email
            </button>
        </div>
        <div class="dp-actions">
            ${c.email ? `<a href="mailto:${esc(c.email)}" class="dp-action-btn dp-action-primary">📧 Open Email Client</a>` : ''}
            ${c.phone && c.channel === 'whatsapp' ? `<a href="https://wa.me/${c.phone.replace(/\D/g,'')}" target="_blank" class="dp-action-btn dp-action-ghost">📱 Open WhatsApp</a>` : ''}
            <a href="{{ route('chat') }}" class="dp-action-btn dp-action-ghost">💬 View Conversations (${c.conversation_count || 0})</a>
            <button class="dp-action-btn dp-action-danger" onclick="deleteClient('${esc(c.id)}','${esc(c.name)}')">
                🗑️ Delete Client
            </button>
        </div>
    </div>`;
}

async function changeStatus(id, newStatus) {
    const client = allClients.find(c => String(c.id) === String(id));
    if (!client || client.status === newStatus) return;

    const sc = statusCfg(newStatus);
    if (!confirm(`Change status to "${sc.label}"?\n\nAn email notification will be sent to ${client.email}`)) {
        // Revert dropdown
        document.getElementById('statusSelect-' + id).value = client.status;
        return;
    }

    try {
        const res = await fetch('/api/clients/' + encodeURIComponent(id) + '/status', {
            method: 'PATCH',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF },
            body: JSON.stringify({ status: newStatus })
        });

        if (res.ok) {
            const data = await res.json();
            showToast(data.email_sent ? 'Status updated and email sent!' : 'Status updated', 'success');
            load(); // Reload to reflect changes
        } else {
            showToast('Failed to update status', 'error');
            document.getElementById('statusSelect-' + id).value = client.status;
        }
    } catch(e) {
        showToast('Error: ' + e.message, 'error');
        document.getElementById('statusSelect-' + id).value = client.status;
    }
}
window.changeStatus = changeStatus;

async function sendMessage(id) {
    const textarea = document.getElementById('msgText-' + id);
    const message = textarea.value.trim();
    
    if (!message) {
        showToast('Please enter a message', 'error');
        return;
    }

    const client = allClients.find(c => String(c.id) === String(id));
    if (!confirm(`Send this message to ${client.name} (${client.email})?`)) return;

    try {
        const res = await fetch('/api/clients/' + encodeURIComponent(id) + '/send-message', {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF },
            body: JSON.stringify({ message })
        });

        const data = await res.json();
        
        if (data.success) {
            showToast('Message sent successfully!', 'success');
            textarea.value = '';
        } else {
            showToast(data.message || 'Failed to send message', 'error');
        }
    } catch(e) {
        showToast('Error: ' + e.message, 'error');
    }
}
window.sendMessage = sendMessage;

async function deleteClient(id, name) {
    if (!confirm(`Delete "${name}" and ALL associated conversations and messages?\n\nThis action cannot be undone.`)) return;
    
    try {
        const res = await fetch('/api/clients/' + encodeURIComponent(id), { 
            method:'DELETE', 
            headers:{'X-CSRF-TOKEN':CSRF} 
        });
        
        const data = await res.json();
        
        if (data.success) {
            if (selectedId === id) { 
                selectedId = null; 
                document.getElementById('detailPanelMount').innerHTML = ''; 
            }
            showToast('Client and all associated data deleted', 'success');
            load();
        } else {
            showToast(data.message || 'Delete failed', 'error');
        }
    } catch(e) { 
        showToast('Delete failed: ' + e.message, 'error'); 
    }
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
    const phone = document.getElementById('mPhone').value.trim();
    const channel = document.getElementById('mChannel').value;
    const project = document.getElementById('mProject').value.trim();
    
    if (!name)  { showToast('Name is required', 'error'); return; }
    if (!email) { showToast('Email is required', 'error'); return; }
    
    try {
        const res = await fetch('/api/clients', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({
                name, email, phone, channel,
                project_details: project
            })
        });
        
        if (res.ok) {
            closeAddModal();
            ['mName','mEmail','mPhone','mProject'].forEach(id => { document.getElementById(id).value = ''; });
            document.getElementById('mChannel').value = 'whatsapp';
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