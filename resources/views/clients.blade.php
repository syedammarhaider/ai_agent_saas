{{-- resources/views/clients.blade.php --}}
@extends('layouts.app')
@section('content')

<div class="cl-pg">

    {{-- Toolbar --}}
    <div class="cl-toolbar anim">
        <div>
            <div class="pg-title">Clients</div>
            <div class="pg-subtitle" id="clientSubtitle">Loading…</div>
        </div>
        <div class="toolbar-right">
            <div class="search-wrap">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input class="inp" id="clientSearch" placeholder="Search clients…" autocomplete="off" oninput="debounceSearch()">
            </div>
            <button class="btn btn-primary" onclick="openAddModal()">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" d="M12 5v14M5 12h14"/>
                </svg>
                Add Client
            </button>
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-row anim anim-d1">
        <div class="st-card">
            <div class="st-label">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                </svg>
                Total
            </div>
            <div class="st-value" id="stTotal">—</div>
        </div>
        <div class="st-card">
            <div class="st-label" style="color:var(--green)">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                In Progress
            </div>
            <div class="st-value" id="stActive" style="color:var(--green)">—</div>
        </div>
        <div class="st-card">
            <div class="st-label" style="color:var(--cyan)">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                WhatsApp
            </div>
            <div class="st-value" id="stWA" style="color:var(--cyan)">—</div>
        </div>
        <div class="st-card">
            <div class="st-label" style="color:var(--purple)">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="3" width="20" height="14" rx="2"/>
                </svg>
                Platforms
            </div>
            <div class="st-value" id="stPlats" style="color:var(--purple)">—</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-bar anim anim-d2">
        <button class="fpill on" onclick="setFilter('all',this)">All Clients</button>
        <button class="fpill" onclick="setFilter('whatsapp',this)">📱 WhatsApp</button>
        <button class="fpill" onclick="setFilter('slack',this)">⚡ Slack</button>
    </div>

    {{-- Main --}}
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

{{-- Add Client Modal --}}
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
                    <textarea class="inp" id="mProject" rows="3" placeholder="Brief description of the project…"></textarea>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-ghost" onclick="closeAddModal()">Cancel</button>
            <button class="btn btn-primary" onclick="saveClient()">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" d="M5 13l4 4L19 7"/>
                </svg>
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
    slack:    { icon:'⚡', label:'Slack',    cls:'badge-purple', color:'var(--purple)' },
};
const AVATAR_COLORS = ['#4F46E5','#7C3AED','#059669','#D97706','#DC2626','#0891B2','#64748B','#0D9488'];
const STATUS_CONFIG = {
    in_progress: { label:'In Progress', cls:'badge-blue' },
    completed:   { label:'Completed',   cls:'badge-green' },
    cancelled:   { label:'Cancelled',   cls:'badge-red' },
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
function platCfg(plat)   { return PLAT_CONFIG[String(plat).toLowerCase()] || PLAT_CONFIG.whatsapp; }
function statusCfg(status){ return STATUS_CONFIG[status] || STATUS_CONFIG.in_progress; }

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
    const el  = document.getElementById('clRows');
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
        return `
        <div class="cl-row ${c.id === selectedId ? 'selected' : ''}" onclick="selectClient('${esc(c.id)}')" id="row-${esc(c.id)}">
            <div class="cl-name-cell">
                <div class="cl-av" style="background:${bgCol}">${esc(initials(c.name))}</div>
                <div><div class="cl-name">${esc(c.name || 'Unknown')}</div></div>
            </div>
            <div class="cl-contact-cell">
                <div class="cl-email">${esc(c.email || '—')}</div>
                <div class="cl-phone">${esc(c.phone || '')}</div>
            </div>
            <div><span class="badge ${pc.cls}">${pc.icon} ${pc.label}</span></div>
            <div><span class="badge ${sc.cls}">${sc.label}</span></div>
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

function fmtDate(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr);
    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const yest  = new Date(today - 86400000);
    const day   = new Date(d.getFullYear(), d.getMonth(), d.getDate());
    if (day.getTime() === today.getTime()) return 'Today at ' + d.toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'});
    if (day.getTime() === yest.getTime())  return 'Yesterday at ' + d.toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'});
    return d.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'});
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
    document.getElementById('row-' + id)?.classList.add('selected');
    renderDetailPanel(client);
}
window.selectClient = selectClient;

function renderDetailPanel(c) {
    const pc    = platCfg((c.platforms||[])[0] || c.channel || 'whatsapp');
    const bgCol = avatarColor(c.name);
    document.getElementById('detailPanelMount').innerHTML = `
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
            <div class="dp-section-label">Timeline</div>
            <div class="dp-row"><span class="dp-key">Created</span><span class="dp-val">${fmtDate(c.created_at)}</span></div>
            <div class="dp-row"><span class="dp-key">Last Contacted</span><span class="dp-val">${c.last_contacted ? fmtDate(c.last_contacted) : 'Never'}</span></div>
        </div>
        <div class="dp-section">
            <div class="dp-section-label">Status Management</div>
            <div class="status-dropdown">
                <select class="status-select" id="statusSelect-${c.id}" onchange="changeStatus('${c.id}', this.value)">
                    <option value="in_progress" ${c.status==='in_progress'?'selected':''}>⚡ In Progress</option>
                    <option value="completed"   ${c.status==='completed'  ?'selected':''}>✅ Completed</option>
                    <option value="cancelled"   ${c.status==='cancelled'  ?'selected':''}>❌ Cancelled</option>
                </select>
            </div>
            <div style="font-size:11px;color:var(--txt-4);margin-top:6px;">Changing status will send an email notification to the client</div>
        </div>
        ${c.project_details ? `
        <div class="dp-section">
            <div class="dp-section-label">Project Details</div>
            <div class="project-details-box">
                <div class="project-details-text">${esc(c.project_details)}</div>
            </div>
        </div>` : ''}
        <div class="msg-box-section">
            <div class="dp-section-label">Send Message to Client</div>
            <textarea class="msg-textarea" id="msgText-${c.id}" placeholder="Type your message to ${esc(c.name)}…"></textarea>
            <button class="send-msg-btn" onclick="sendMessage('${c.id}')">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Send Email
            </button>
        </div>
        <div class="dp-actions">
            ${c.phone ? `<a href="https://wa.me/${c.phone.replace(/\D/g,'')}" target="_blank" class="dp-action-btn dp-action-ghost">📱 Open WhatsApp</a>` : ''}
            <button class="dp-action-btn dp-action-danger" onclick="deleteClient('${esc(c.id)}','${esc(c.name)}')">🗑️ Delete Client</button>
        </div>
    </div>`;
}

async function changeStatus(id, newStatus) {
    const client = allClients.find(c => String(c.id) === String(id));
    if (!client || client.status === newStatus) return;
    if (!confirm(`Change status to "${newStatus.replace('_',' ')}"?\n\nAn email notification will be sent to ${client.email}`)) {
        document.getElementById('statusSelect-' + id).value = client.status;
        return;
    }
    try {
        const res = await fetch('/api/clients/' + encodeURIComponent(id) + '/status', {
            method:'PATCH', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body:JSON.stringify({status:newStatus})
        });
        const data = await res.json();
        if (res.ok) { showToast(data.email_sent ? 'Status updated and email sent!' : 'Status updated', 'success'); load(); }
        else { showToast('Failed to update status', 'error'); document.getElementById('statusSelect-' + id).value = client.status; }
    } catch(e) { showToast('Error: ' + e.message, 'error'); }
}
window.changeStatus = changeStatus;

async function sendMessage(id) {
    const textarea = document.getElementById('msgText-' + id);
    const message  = textarea.value.trim();
    if (!message) { showToast('Please enter a message', 'error'); return; }
    const client = allClients.find(c => String(c.id) === String(id));
    if (!confirm(`Send this message to ${client.name} (${client.email})?`)) return;
    try {
        const res  = await fetch('/api/clients/' + encodeURIComponent(id) + '/send-message', {
            method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body:JSON.stringify({message})
        });
        const data = await res.json();
        data.success ? (showToast('Message sent!', 'success'), textarea.value = '') : showToast(data.message || 'Failed to send', 'error');
    } catch(e) { showToast('Error: ' + e.message, 'error'); }
}
window.sendMessage = sendMessage;

async function deleteClient(id, name) {
    if (!confirm(`Delete "${name}" and ALL associated data?\n\nThis cannot be undone.`)) return;
    try {
        const res  = await fetch('/api/clients/' + encodeURIComponent(id), {method:'DELETE',headers:{'X-CSRF-TOKEN':CSRF}});
        const data = await res.json();
        if (data.success) {
            if (selectedId === id) { selectedId = null; document.getElementById('detailPanelMount').innerHTML = ''; }
            showToast('Client deleted', 'success'); load();
        } else { showToast(data.message || 'Delete failed', 'error'); }
    } catch(e) { showToast('Delete failed: ' + e.message, 'error'); }
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
    const name    = document.getElementById('mName').value.trim();
    const email   = document.getElementById('mEmail').value.trim();
    const phone   = document.getElementById('mPhone').value.trim();
    const channel = document.getElementById('mChannel').value;
    const project = document.getElementById('mProject').value.trim();
    
    // Client-side validation
    if (!name)  { showToast('Name is required', 'error');  return; }
    if (!email) { showToast('Email is required', 'error'); return; }
    if (!channel) { showToast('Channel is required', 'error'); return; }
    
    // Check if client already exists (client-side check)
    const existingClient = allClients.find(c => c.email === email);
    if (existingClient) {
        showToast(`Client with email ${email} already exists (Name: ${existingClient.name}). Use a different email or update the existing client.`, 'error');
        return;
    }
    
    // Disable button to prevent double submission
    const saveBtn = document.querySelector('button[onclick="saveClient()"]');
    const originalText = saveBtn.innerHTML;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg> Saving...';
    
    try {
        const res = await fetch('/api/clients', {
            method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body:JSON.stringify({name, email, phone, channel, project_details:project})
        });
        
        const data = await res.json();
        
        if (res.ok && data.success) {
            closeAddModal();
            ['mName','mEmail','mPhone','mProject'].forEach(id => { document.getElementById(id).value = ''; });
            document.getElementById('mChannel').value = 'whatsapp';
            showToast('Client added successfully!', 'success'); 
            load();
        } else {
            // Handle specific validation errors
            if (data.errors) {
                const firstError = Object.values(data.errors)[0][0];
                showToast(firstError || 'Validation failed', 'error');
            } else {
                showToast(data.message || 'Failed to save client', 'error');
            }
        }
    } catch(e) { 
        showToast('Network error: ' + e.message, 'error'); 
    } finally {
        // Re-enable button
        saveBtn.disabled = false;
        saveBtn.innerHTML = originalText;
    }
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