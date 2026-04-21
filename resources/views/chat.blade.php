@extends('layouts.app')
@section('content')
<style>
/* ======= CHAT PAGE ======= */
.chat-pg {
    height: 100vh; display: flex; overflow: hidden;
    font-family: var(--font-body); background: var(--bg);
}

/* ======= SIDEBAR ======= */
.conv-sb {
    width: 320px; flex-shrink: 0; background: var(--bg-card);
    border-right: 1px solid var(--border);
    display: flex; flex-direction: column; height: 100vh; overflow: hidden;
}
@media(max-width:960px){
    .conv-sb {
        position: fixed; left: 0; top: 0; height: 100vh; z-index: 999;
        transform: translateX(-100%); transition: transform .32s var(--ease);
        box-shadow: var(--shadow-lg);
    }
    .conv-sb.open { transform: translateX(0); }
    .mob-fab { display: flex !important; }
}

/* Sidebar head */
.sb-head {
    padding: 20px 18px 16px; border-bottom: 1px solid var(--border);
    background: var(--bg-raised); flex-shrink: 0;
}
.sb-title {
    font-family: var(--font-display); font-size: 17px; font-weight: 800; color: var(--txt);
    letter-spacing: -0.4px; display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 14px;
}
.conv-count { font-size: 11px; color: var(--txt-4); font-family: var(--font-mono); font-weight: 400; }

.search-inp {
    width: 100%; background: var(--bg-hover); border: 1px solid var(--border-md);
    border-radius: var(--radius-md); padding: 10px 14px; font-size: 13px;
    color: var(--txt); font-family: var(--font-body); outline: none;
    transition: border 200ms, box-shadow 200ms;
}
.search-inp::placeholder { color: var(--txt-4); }
.search-inp:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); background: var(--bg-input); }

.filter-pills { display: flex; gap: 6px; margin-top: 12px; flex-wrap: wrap; }
.pill {
    background: var(--bg-hover); border: 1px solid var(--border-md);
    border-radius: 20px; padding: 5px 13px; font-size: 11.5px; font-weight: 600;
    color: var(--txt-3); cursor: pointer; transition: all 180ms; font-family: var(--font-body);
}
.pill:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }
.pill.on { background: var(--accent); border-color: var(--accent); color: white; box-shadow: 0 2px 8px var(--accent-glow); }

/* Conv list */
.conv-list { flex: 1; overflow-y: auto; }
.conv-list::-webkit-scrollbar { width: 4px; }
.conv-list::-webkit-scrollbar-thumb { background: var(--border-str); border-radius: 4px; }

.conv-item {
    padding: 14px 18px; border-bottom: 1px solid var(--border);
    cursor: pointer; transition: background 120ms; position: relative;
}
.conv-item:hover { background: var(--bg-hover); }
.conv-item.active {
    background: var(--accent-soft);
    border-left: 3px solid var(--accent); padding-left: 15px;
}
.conv-item.active .conv-name { color: var(--accent); }

.conv-item-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px; gap: 8px; }
.conv-name { font-weight: 600; font-size: 13.5px; color: var(--txt); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; }
.conv-time { font-size: 10px; color: var(--txt-4); font-family: var(--font-mono); flex-shrink: 0; }
.conv-preview { font-size: 12px; color: var(--txt-3); line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.conv-meta { display: flex; align-items: center; gap: 6px; margin-top: 8px; }

.plat-tag {
    display: inline-flex; align-items: center; gap: 4px;
    background: var(--bg-hover); border: 1px solid var(--border);
    border-radius: 20px; padding: 3px 9px; font-size: 10.5px; color: var(--txt-4); font-weight: 500;
}
.live-dot-sm {
    width: 5px; height: 5px; border-radius: 50%; background: var(--green);
    box-shadow: 0 0 5px var(--green); animation: pulse-g2 2s infinite; flex-shrink: 0;
}
@keyframes pulse-g2 { 0%,100%{opacity:1} 50%{opacity:0.5} }

.conv-empty { padding: 60px 20px; text-align: center; color: var(--txt-4); font-size: 13px; }

/* ======= CHAT MAIN ======= */
.chat-main { flex: 1; display: flex; flex-direction: column; min-width: 0; height: 100vh; background: var(--bg); }

/* Chat header */
.chat-hdr {
    padding: 16px 24px; border-bottom: 1px solid var(--border);
    background: var(--bg-card); display: flex; align-items: center; gap: 14px; flex-shrink: 0;
    box-shadow: var(--shadow-sm);
}
.chat-av {
    width: 44px; height: 44px; border-radius: 13px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--accent), var(--purple));
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; font-weight: 800; color: white; font-family: var(--font-display);
    box-shadow: 0 4px 12px var(--accent-glow);
}
.chat-hdr-info .c-name { font-weight: 700; font-size: 15px; color: var(--txt); font-family: var(--font-display); }
.chat-hdr-info .c-sub  { font-size: 11.5px; color: var(--txt-3); margin-top: 2px; font-family: var(--font-mono); }
.chat-hdr-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }

.hdr-badge {
    display: flex; align-items: center; gap: 5px;
    font-size: 11px; font-weight: 600; font-family: var(--font-mono);
    padding: 5px 12px; border-radius: 20px; border: 1px solid var(--border-md);
    color: var(--green); background: var(--green-soft);
}
.hdr-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green); animation: pulse-g2 2s infinite; }

/* Messages */
.msgs-area {
    flex: 1; overflow-y: auto; padding: 24px 28px;
    display: flex; flex-direction: column; gap: 14px;
    background: var(--bg);
}
.msgs-area::-webkit-scrollbar { width: 4px; }
.msgs-area::-webkit-scrollbar-thumb { background: var(--border-str); border-radius: 4px; }

/* Date separator */
.date-sep {
    display: flex; align-items: center; gap: 12px;
    font-size: 10.5px; color: var(--txt-4); font-family: var(--font-mono);
    font-weight: 500; letter-spacing: 0.3px; margin: 6px 0;
}
.date-sep::before, .date-sep::after {
    content: ''; flex: 1; height: 1px; background: var(--border);
}

/* Message bubbles */
.msg-wrap { display: flex; flex-direction: column; max-width: 72%; }
.msg-wrap.agent-msg { align-items: flex-end; align-self: flex-end; }
.msg-wrap.client-msg { align-items: flex-start; align-self: flex-start; }

.msg-bubble {
    padding: 11px 16px; border-radius: 18px; font-size: 13.5px;
    line-height: 1.65; word-break: break-word; white-space: pre-wrap;
    box-shadow: var(--shadow-sm);
}
.msg-bubble.agent-msg {
    background: var(--accent); color: white;
    border-bottom-right-radius: 5px;
    box-shadow: 0 4px 12px var(--accent-glow);
}
.msg-bubble.client-msg {
    background: var(--bg-card); border: 1px solid var(--border-md);
    color: var(--txt); border-bottom-left-radius: 5px;
}
.msg-time { font-size: 10px; color: var(--txt-4); margin-top: 4px; padding: 0 4px; font-family: var(--font-mono); }

/* Typing indicator */
.typing-bubble {
    display: flex; align-items: center; gap: 5px;
    padding: 12px 16px; background: var(--bg-card); border: 1px solid var(--border-md);
    border-radius: 18px; border-bottom-left-radius: 5px; box-shadow: var(--shadow-sm);
}
.typing-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--txt-3); }
.typing-dot:nth-child(1) { animation: td 1.4s 0s infinite; }
.typing-dot:nth-child(2) { animation: td 1.4s 0.2s infinite; }
.typing-dot:nth-child(3) { animation: td 1.4s 0.4s infinite; }
@keyframes td { 0%,80%,100%{transform:translateY(0);opacity:0.4} 40%{transform:translateY(-5px);opacity:1} }

/* Empty chat */
.empty-chat {
    flex: 1; display: flex; flex-direction: column; align-items: center;
    justify-content: center; color: var(--txt-4); gap: 14px; text-align: center; padding: 40px;
}
.empty-chat-icon { font-size: 72px; opacity: 0.09; animation: flt 5s ease-in-out infinite; }
@keyframes flt { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-12px)} }
.empty-chat-title { font-family: var(--font-display); font-size: 22px; font-weight: 800; color: var(--txt-3); }
.empty-chat-sub { max-width: 300px; font-size: 13px; color: var(--txt-4); line-height: 1.6; }

/* Reply bar */
.reply-bar {
    padding: 14px 24px 18px; background: var(--bg-card); border-top: 1px solid var(--border);
    display: flex; gap: 12px; align-items: flex-end; flex-shrink: 0;
}
.reply-inp {
    flex: 1; background: var(--bg-raised); border: 1px solid var(--border-md);
    border-radius: var(--radius-md); padding: 12px 16px; font-size: 13.5px;
    font-family: var(--font-body); color: var(--txt); resize: none;
    min-height: 48px; max-height: 140px; outline: none; transition: border 200ms, box-shadow 200ms; line-height: 1.55;
}
.reply-inp::placeholder { color: var(--txt-4); }
.reply-inp:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }

.send-btn {
    height: 48px; padding: 0 20px; border-radius: var(--radius-md); flex-shrink: 0;
    background: var(--accent); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 700; font-size: 13px; font-family: var(--font-body);
    gap: 7px; box-shadow: 0 4px 12px var(--accent-glow);
    transition: all 200ms var(--ease); white-space: nowrap;
}
.send-btn:hover { filter: brightness(1.1); transform: translateY(-2px); box-shadow: 0 8px 20px var(--accent-glow); }
.send-btn:active { transform: translateY(0); }
.send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

/* Mobile FAB */
.mob-fab {
    display: none; position: fixed; bottom: 24px; right: 24px; z-index: 9999;
    width: 52px; height: 52px; border-radius: 50%;
    background: linear-gradient(135deg, var(--accent), var(--purple));
    color: white; border: none; font-size: 22px;
    align-items: center; justify-content: center; cursor: pointer;
    box-shadow: 0 8px 24px var(--accent-glow);
}

/* Platform color accents in sidebar items */
.plat-wa   { background: rgba(34,197,94,0.12); color: #15803d; border-color: rgba(34,197,94,0.2); }
.plat-sl   { background: var(--purple-soft); color: var(--purple); border-color: rgba(124,58,237,0.2); }
.plat-web  { background: var(--accent-soft); color: var(--accent); }
.plat-api  { background: var(--bg-hover); color: var(--txt-3); }
[data-theme="dark"] .plat-wa { background: rgba(34,197,94,0.1); color: #4ade80; }

@keyframes spin { to{transform:rotate(360deg);} }
</style>

<div class="chat-pg">
    <!-- Conversation Sidebar -->
    <div class="conv-sb" id="convSb">
        <div class="sb-head">
            <div class="sb-title">
                Conversations
                <span class="conv-count" id="convCount"></span>
            </div>
            <input class="search-inp" id="convSearch" placeholder="Search conversations…" oninput="debSearch()">
            <div class="filter-pills">
                <span class="pill on" onclick="setFilter('all',this)">All</span>
                <span class="pill" onclick="setFilter('whatsapp',this)">📱 WA</span>
                <span class="pill" onclick="setFilter('slack',this)">⚡ Slack</span>
                <span class="pill" onclick="setFilter('api',this)">🔗 API</span>
                <span class="pill" onclick="setFilter('web',this)">🌐 Web</span>
            </div>
        </div>
        <div class="conv-list" id="convList">
            <div class="conv-empty">Loading…</div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="chat-main" id="chatMain">
        <div class="empty-chat">
            <div class="empty-chat-icon">💬</div>
            <div class="empty-chat-title">No conversation selected</div>
            <div class="empty-chat-sub">Pick a conversation from the sidebar to view messages and reply.</div>
        </div>
    </div>
</div>

<button class="mob-fab" onclick="toggleSb()">☰</button>

<script>
(function(){
'use strict';
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';
let curId = null, curPlatform = null, curFilter = 'all';
let pollTimer = null, searchTimer = null, lastCount = 0;

const PI = { whatsapp:'📱', slack:'⚡', email:'📧', api:'🔗', web:'🌐', twilio:'📞' };
const PL = { whatsapp:'WhatsApp', slack:'Slack', email:'Email', api:'API', web:'Web', twilio:'Twilio' };
const PCT = { whatsapp:'plat-wa', slack:'plat-sl', web:'plat-web', api:'plat-api', email:'plat-api', twilio:'plat-wa' };

function esc(s){ return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
function fmt(ts){
    if (!ts) return '';
    try {
        const d = new Date(ts);
        const now = new Date();
        const diffMs = now - d;
        if (diffMs < 60000) return 'just now';
        if (diffMs < 3600000) return Math.floor(diffMs/60000) + 'm ago';
        if (diffMs < 86400000) return d.toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'});
        return d.toLocaleDateString('en-US',{month:'short',day:'numeric'});
    } catch(e){ return ''; }
}
function fmtFull(ts){
    if (!ts) return '';
    try { return new Date(ts).toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'}); } catch(e){ return ''; }
}

async function loadConvs() {
    const q = new URLSearchParams();
    if (curFilter !== 'all') q.set('platform', curFilter);
    const s = document.getElementById('convSearch').value.trim();
    if (s) q.set('search', s);
    try {
        const r = await fetch('/api/conversations?' + q, { headers:{'X-CSRF-TOKEN':CSRF} });
        if (!r.ok) throw new Error(r.status);
        const d = await r.json();
        const list = d.conversations || [];
        renderList(list);
        const b = document.getElementById('convCount');
        if (b) b.textContent = list.length ? '(' + list.length + ')' : '';
    } catch(e) {
        document.getElementById('convList').innerHTML = '<div class="conv-empty">⚠️ Could not load conversations.</div>';
    }
}

function renderList(list) {
    const el = document.getElementById('convList');
    if (!list.length) { el.innerHTML = '<div class="conv-empty">No conversations found.</div>'; return; }
    const frag = document.createDocumentFragment();
    list.forEach(c => {
        const plat = (c.platform || 'api').toLowerCase();
        const div = document.createElement('div');
        div.className = 'conv-item' + (c.id === curId ? ' active' : '');
        div.onclick = () => openConv(c.id, plat);
        const platCls = PCT[plat] || 'plat-api';
        div.innerHTML = `
            <div class="conv-item-head">
                <div class="conv-name">${esc(c.client_name || c.id)}</div>
                ${c.updated_at ? `<div class="conv-time">${fmt(c.updated_at)}</div>` : ''}
            </div>
            <div class="conv-preview">${esc(c.last_message || 'No recent messages')}</div>
            <div class="conv-meta">
                <span class="live-dot-sm"></span>
                <span class="plat-tag ${platCls}">${PI[plat]||'📡'} ${PL[plat]||plat}</span>
            </div>`;
        frag.appendChild(div);
    });
    el.innerHTML = '';
    el.appendChild(frag);
}

async function openConv(id, platform) {
    if (id === curId) return;
    curId = id; curPlatform = platform; lastCount = 0;
    clearInterval(pollTimer);

    const main = document.getElementById('chatMain');
    const platLabel = PL[platform] || platform;
    const platIcon  = PI[platform] || '📡';

    main.innerHTML = `
        <div class="chat-hdr">
            <div class="chat-av">${esc((String(id)[0]||'?').toUpperCase())}</div>
            <div class="chat-hdr-info">
                <div class="c-name">${esc(id)}</div>
                <div class="c-sub">${platIcon} ${esc(platLabel)} · Active</div>
            </div>
            <div class="chat-hdr-right">
                <div class="hdr-badge"><span class="hdr-badge-dot"></span> Live</div>
            </div>
        </div>
        <div class="msgs-area" id="msgsArea">
            <div style="text-align:center;padding:60px;color:var(--txt-4);font-size:13px;">Loading messages…</div>
        </div>
        <div class="reply-bar">
            <textarea id="replyInp" class="reply-inp" rows="1"
                placeholder="Type your reply… (Enter to send, Shift+Enter for new line)"></textarea>
            <button id="replyBtn" class="send-btn">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                Send
            </button>
        </div>`;

    const ta  = document.getElementById('replyInp');
    const btn = document.getElementById('replyBtn');
    ta.addEventListener('input', function(){ this.style.height = 'auto'; this.style.height = Math.min(this.scrollHeight, 140) + 'px'; });
    ta.addEventListener('keydown', function(e){ if (e.key === 'Enter' && !e.shiftKey){ e.preventDefault(); doSend(); } });
    btn.addEventListener('click', doSend);
    ta.focus();

    loadConvs();
    await loadMsgs(id);
    pollTimer = setInterval(() => loadMsgs(id, true), 3500);
    if (window.innerWidth <= 960) document.getElementById('convSb').classList.remove('open');
}
window.openConv = openConv;

async function loadMsgs(id, silent = false) {
    try {
        const r = await fetch('/api/conversations/' + encodeURIComponent(id) + '/messages', { headers:{'X-CSRF-TOKEN':CSRF} });
        if (!r.ok) throw new Error(r.status);
        const d = await r.json();
        const msgs = d.messages || [];
        if (silent && msgs.length === lastCount) return;
        lastCount = msgs.length;
        const area = document.getElementById('msgsArea');
        if (!area) return;
        if (!msgs.length) {
            area.innerHTML = '<div style="text-align:center;padding:60px;color:var(--txt-4);font-size:13px;">No messages yet.</div>';
            return;
        }
        renderMsgs(area, msgs);
    } catch(e) { if (!silent) console.error('loadMsgs:', e); }
}

function renderMsgs(area, msgs) {
    const scrolledToBottom = area.scrollHeight - area.scrollTop - area.clientHeight < 60;
    area.innerHTML = '';
    let lastDate = null;
    msgs.forEach(m => {
        const ts = m.created_at || m.timestamp;
        if (ts) {
            const d = new Date(ts);
            const dateStr = d.toLocaleDateString('en-US',{weekday:'short',month:'short',day:'numeric'});
            if (dateStr !== lastDate) {
                lastDate = dateStr;
                const sep = document.createElement('div');
                sep.className = 'date-sep';
                sep.textContent = dateStr;
                area.appendChild(sep);
            }
        }
        const isAgent = m.sender_type === 'agent';
        const cls = isAgent ? 'agent-msg' : 'client-msg';
        const wrap = document.createElement('div');
        wrap.className = 'msg-wrap ' + cls;
        const b = document.createElement('div');
        b.className = 'msg-bubble ' + cls;
        b.textContent = m.content || '';
        wrap.appendChild(b);
        if (ts) {
            const t = document.createElement('div');
            t.className = 'msg-time';
            t.textContent = fmtFull(ts);
            wrap.appendChild(t);
        }
        area.appendChild(wrap);
    });
    if (scrolledToBottom) area.scrollTop = area.scrollHeight;
}

async function doSend() {
    const ta  = document.getElementById('replyInp');
    const btn = document.getElementById('replyBtn');
    if (!ta || !btn || !curId) return;
    const txt = ta.value.trim();
    if (!txt) return;

    // Optimistic render
    const area = document.getElementById('msgsArea');
    if (area) {
        const w = document.createElement('div'); w.className = 'msg-wrap agent-msg';
        const b = document.createElement('div'); b.className = 'msg-bubble agent-msg';
        b.textContent = txt; w.appendChild(b); area.appendChild(w);
        area.scrollTop = area.scrollHeight;
    }
    ta.value = ''; ta.style.height = 'auto';
    btn.disabled = true;
    btn.innerHTML = '<svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24" style="animation:spin .8s linear infinite"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8z"/></svg> Sending…';

    try {
        await fetch('/api/conversations/' + encodeURIComponent(curId) + '/reply', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body: JSON.stringify({content: txt})
        });
        await loadMsgs(curId, false);
    } catch(e) { console.error('doSend:', e); }
    finally {
        btn.disabled = false;
        btn.innerHTML = '<svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg> Send';
        ta.focus();
    }
}

function setFilter(f, btn) {
    curFilter = f;
    document.querySelectorAll('.pill').forEach(b => b.classList.remove('on'));
    btn.classList.add('on');
    loadConvs();
}
window.setFilter = setFilter;

function debSearch() { clearTimeout(searchTimer); searchTimer = setTimeout(loadConvs, 280); }
window.debSearch = debSearch;

function toggleSb() { document.getElementById('convSb').classList.toggle('open'); }
window.toggleSb = toggleSb;

document.addEventListener('click', e => {
    const sb = document.getElementById('convSb');
    const fab = document.querySelector('.mob-fab');
    if (window.innerWidth <= 960 && sb.classList.contains('open') && !sb.contains(e.target) && !fab?.contains(e.target)) {
        sb.classList.remove('open');
    }
});

loadConvs();
setInterval(loadConvs, 15000);
})();
</script>
@endsection