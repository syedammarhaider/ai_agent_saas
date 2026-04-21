@extends('layouts.app')
@section('content')
<style>
.ag-pg {
    height: 100vh; display: flex; flex-direction: column;
    font-family: var(--font-body); overflow: hidden;
    background: var(--bg);
}

/* ─── TOP BAR ─── */
.ag-header {
    padding: 14px 24px; background: var(--bg-card); border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 14px; flex-shrink: 0;
}

.ag-av {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    background: linear-gradient(135deg, var(--accent), var(--purple));
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; font-weight: 800; color: white;
    box-shadow: 0 4px 12px var(--accent-glow);
}

.ag-info .name { font-weight: 700; font-size: 15px; color: var(--txt); }
.ag-info .sub  { font-size: 12px; color: var(--txt-3); margin-top: 2px; font-family: var(--font-mono); }

.ag-header-actions { margin-left: auto; display: flex; align-items: center; gap: 10px; }

.ag-status-pill {
    display: flex; align-items: center; gap: 6px; padding: 5px 13px; border-radius: 20px;
    font-size: 12px; font-weight: 700; font-family: var(--font-mono);
    border: 1px solid rgba(13,128,80,0.2); background: var(--green-soft); color: var(--green);
    transition: all 300ms;
}
.ag-status-pill.offline { background: var(--red-soft); color: var(--red); border-color: rgba(197,48,48,0.2); }
.ag-status-pill.checking { background: var(--amber-soft); color: var(--amber); border-color: rgba(180,83,9,0.2); }

.status-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; animation: pulse-live 2s infinite; }
@keyframes pulse-live { 0%,100%{box-shadow:0 0 0 0 currentColor} 70%{box-shadow:0 0 0 6px transparent} }

.icon-btn {
    width: 36px; height: 36px; border-radius: var(--radius-md);
    background: var(--bg-hover); border: 1px solid var(--border-md); cursor: pointer;
    display: flex; align-items: center; justify-content: center; color: var(--txt-3);
    transition: all 160ms; font-family: var(--font-body);
}
.icon-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }

/* ─── MESSAGES ─── */
.ag-msgs {
    flex: 1; overflow-y: auto; padding: 24px;
    display: flex; flex-direction: column; gap: 16px;
}

/* Welcome screen */
.ag-welcome {
    flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 12px; text-align: center; padding-bottom: 60px;
}
.ag-welcome-icon { font-size: 56px; opacity: 0.12; }
.ag-welcome-title { font-size: 20px; font-weight: 700; color: var(--txt); }
.ag-welcome-sub { font-size: 13px; color: var(--txt-3); max-width: 340px; line-height: 1.6; }

.quickstart { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; margin-top: 16px; }
.qs-btn {
    padding: 8px 16px; border-radius: var(--radius-md); background: var(--bg-card);
    border: 1px solid var(--border-md); font-size: 12.5px; font-weight: 500;
    color: var(--txt-2); cursor: pointer; font-family: var(--font-body); transition: all 160ms;
}
.qs-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }

/* Message bubbles */
.msg-wrap { display: flex; flex-direction: column; max-width: 75%; }
.msg-wrap.user  { align-items: flex-end; align-self: flex-end; }
.msg-wrap.agent { align-items: flex-start; align-self: flex-start; }

.msg-bubble {
    padding: 12px 16px; border-radius: 18px; font-size: 14px; line-height: 1.65;
    word-break: break-word; white-space: pre-wrap;
    box-shadow: var(--shadow-sm);
}

.msg-bubble.user {
    background: var(--accent); color: white; border-bottom-right-radius: 5px;
}

.msg-bubble.agent {
    background: var(--bg-card); border: 1px solid var(--border-md);
    color: var(--txt); border-bottom-left-radius: 5px;
}

.msg-time {
    font-size: 10px; color: var(--txt-4); margin-top: 4px; padding: 0 4px;
    font-family: var(--font-mono);
}

/* Typing indicator */
.typing-bubble {
    display: flex; align-items: center; gap: 4px;
    padding: 14px 18px; background: var(--bg-card); border: 1px solid var(--border-md);
    border-radius: 18px; border-bottom-left-radius: 5px;
}
.typing-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--txt-3); }
.typing-dot:nth-child(1) { animation: td 1.4s 0s infinite; }
.typing-dot:nth-child(2) { animation: td 1.4s 0.2s infinite; }
.typing-dot:nth-child(3) { animation: td 1.4s 0.4s infinite; }
@keyframes td { 0%,80%,100%{transform:translateY(0);opacity:0.4} 40%{transform:translateY(-5px);opacity:1} }

/* Error bubble */
.msg-bubble.error {
    background: var(--red-soft); border: 1px solid rgba(197,48,48,0.2);
    color: var(--red); font-family: var(--font-mono); font-size: 13px;
}

/* ─── REPLY BAR ─── */
.ag-reply {
    padding: 14px 24px 18px; background: var(--bg-card); border-top: 1px solid var(--border);
    display: flex; gap: 12px; align-items: flex-end; flex-shrink: 0;
}

.reply-inp {
    flex: 1; background: var(--bg-raised); border: 1px solid var(--border-md);
    border-radius: 14px; padding: 12px 16px; font-size: 14px; font-family: var(--font-body);
    color: var(--txt); resize: none; min-height: 48px; max-height: 140px; outline: none;
    transition: border 200ms, box-shadow 200ms; line-height: 1.55;
}
.reply-inp::placeholder { color: var(--txt-4); }
.reply-inp:focus { border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-soft); }

.send-btn {
    width: 48px; height: 48px; border-radius: 14px; flex-shrink: 0;
    background: var(--accent); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center; color: white;
    box-shadow: 0 4px 12px var(--accent-glow); transition: all 200ms var(--ease);
}
.send-btn:hover { filter: brightness(1.1); transform: translateY(-2px); }
.send-btn:active { transform: translateY(0); }
.send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

.char-count { font-size: 10px; color: var(--txt-4); text-align: right; margin-top: 4px; font-family: var(--font-mono); }
</style>

<div class="ag-pg">
    <!-- Header -->
    <div class="ag-header">
        <div class="ag-av">🤖</div>
        <div class="ag-info">
            <div class="name">AI Agent</div>
            <div class="sub" id="agentSub">NexusAI · Intelligent Assistant</div>
        </div>
        <div class="ag-header-actions">
            <div class="ag-status-pill checking" id="statusPill">
                <span class="status-dot"></span>
                <span id="statusText">Connecting…</span>
            </div>
            <button class="icon-btn" onclick="clearConversation()" title="Clear conversation">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Messages area -->
    <div class="ag-msgs" id="msgsArea">
        <div class="ag-welcome" id="welcomeScreen">
            <div class="ag-welcome-icon">🤖</div>
            <div class="ag-welcome-title">Hello! I'm your AI Agent</div>
            <div class="ag-welcome-sub">I can help you manage tasks, answer questions, and assist with client management. What can I do for you today?</div>
            <div class="quickstart">
                <button class="qs-btn" onclick="quickSend('What can you help me with?')">What can you do?</button>
                <button class="qs-btn" onclick="quickSend('Show me a summary of my clients')">Client summary</button>
                <button class="qs-btn" onclick="quickSend('How many active conversations are there?')">Active chats</button>
                <button class="qs-btn" onclick="quickSend('What is my dashboard status?')">Dashboard status</button>
            </div>
        </div>
    </div>

    <!-- Reply bar -->
    <div class="ag-reply">
        <div style="flex:1;display:flex;flex-direction:column;gap:4px;">
            <textarea
                class="reply-inp"
                id="msgInput"
                placeholder="Type a message… (Enter to send, Shift+Enter for new line)"
                maxlength="2000"
                rows="1"
                oninput="onInput()"
            ></textarea>
            <div class="char-count"><span id="charCount">0</span>/2000</div>
        </div>
        <button class="send-btn" id="sendBtn" onclick="sendMessage()" title="Send message">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/>
            </svg>
        </button>
    </div>
</div>

<script>
(function(){
'use strict';
const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
const USER_ID = 'web_' + (localStorage.getItem('agent_uid') || (() => { const id = Date.now().toString(36); localStorage.setItem('agent_uid',id); return id; })());
const PLAT = 'web';

let sending = false;
let messages = []; // { role, content, time }

// ── HEALTH CHECK ──
async function checkHealth() {
    try {
        const res = await fetch('/api/agent/health', { headers:{'X-CSRF-TOKEN':CSRF} });
        const d = await res.json();
        setStatus(d.status === 'online' ? 'online' : 'offline');
    } catch(e) { setStatus('offline'); }
}

function setStatus(s) {
    const pill = document.getElementById('statusPill');
    const text = document.getElementById('statusText');
    pill.className = 'ag-status-pill ' + s;
    text.textContent = s === 'online' ? 'Online' : s === 'offline' ? 'Offline' : 'Connecting…';
}

// ── LOAD HISTORY ──
async function loadHistory() {
    try {
        const res = await fetch('/api/agent/history/' + encodeURIComponent(USER_ID), { headers:{'X-CSRF-TOKEN':CSRF} });
        if (!res.ok) return;
        const d = await res.json();
        const hist = d.messages || [];
        if (hist.length) {
            messages = hist.map(m => ({ role: m.role === 'assistant' ? 'agent' : 'user', content: m.content || '', time: new Date().toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'}) }));
            renderMessages();
        }
    } catch(e) {}
}

// ── INPUT HANDLING ──
function onInput() {
    const ta = document.getElementById('msgInput');
    ta.style.height = 'auto';
    ta.style.height = Math.min(ta.scrollHeight, 140) + 'px';
    const len = ta.value.length;
    document.getElementById('charCount').textContent = len;
}

document.getElementById('msgInput').addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
});

// ── QUICKSTART ──
function quickSend(text) {
    document.getElementById('msgInput').value = text;
    onInput();
    sendMessage();
}
window.quickSend = quickSend;

// ── SEND MESSAGE ──
async function sendMessage() {
    const inp = document.getElementById('msgInput');
    const text = inp.value.trim();
    if (!text || sending) return;

    // Hide welcome
    const welcome = document.getElementById('welcomeScreen');
    if (welcome) welcome.remove();

    // Add user message
    messages.push({ role: 'user', content: text, time: now() });
    inp.value = ''; inp.style.height = 'auto';
    document.getElementById('charCount').textContent = '0';
    renderMessages();

    // Typing indicator
    sending = true;
    const sendBtn = document.getElementById('sendBtn');
    sendBtn.disabled = true;
    const typingId = addTyping();

    try {
        const res = await fetch('/api/agent/chat', {
            method: 'POST',
            headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':CSRF },
            body: JSON.stringify({ user_id: USER_ID, message: text, platform: PLAT })
        });

        removeTyping(typingId);

        if (!res.ok) {
            const errText = 'Server error (' + res.status + '). Please try again.';
            messages.push({ role:'agent', content: errText, time: now(), error: true });
        } else {
            const d = await res.json();
            const reply = d.reply || d.message || d.response || 'I received your message but could not generate a response.';
            messages.push({ role:'agent', content: reply, time: now() });
        }
    } catch(e) {
        removeTyping(typingId);
        let errMsg = 'Cannot reach the AI backend. ';
        if (e.message && e.message.includes('fetch')) {
            errMsg += 'Ensure the Python server is running on port 8003: `python -m uvicorn main:app --host 0.0.0.0 --port 8003`';
        } else {
            errMsg += e.message;
        }
        messages.push({ role:'agent', content: errMsg, time: now(), error: true });
    }

    sending = false;
    sendBtn.disabled = false;
    renderMessages();
    inp.focus();
}

function now() {
    return new Date().toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'});
}

// ── TYPING INDICATOR ──
let typingEl = null;
function addTyping() {
    const area = document.getElementById('msgsArea');
    const wrap = document.createElement('div');
    wrap.className = 'msg-wrap agent'; wrap.id = 'typing-wrap';
    wrap.innerHTML = `<div class="typing-bubble"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>`;
    area.appendChild(wrap);
    area.scrollTop = area.scrollHeight;
    return 'typing-wrap';
}

function removeTyping(id) {
    document.getElementById(id)?.remove();
}

// ── RENDER ──
function renderMessages() {
    const area = document.getElementById('msgsArea');
    // Remove all msg-wrap elements
    area.querySelectorAll('.msg-wrap').forEach(el => el.remove());

    messages.forEach(m => {
        const wrap = document.createElement('div');
        wrap.className = 'msg-wrap ' + m.role;
        const bubCls = m.error ? 'agent error' : m.role;
        wrap.innerHTML = `
            <div class="msg-bubble ${bubCls}">${escHtml(m.content)}</div>
            <div class="msg-time">${escHtml(m.time || '')}</div>`;
        area.appendChild(wrap);
    });

    area.scrollTop = area.scrollHeight;
}

function escHtml(s) {
    return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
}

// ── CLEAR ──
async function clearConversation() {
    if (messages.length && !confirm('Clear this conversation?')) return;
    messages = [];
    const area = document.getElementById('msgsArea');
    area.querySelectorAll('.msg-wrap').forEach(el => el.remove());
    if (!document.getElementById('welcomeScreen')) {
        const w = document.createElement('div');
        w.className = 'ag-welcome'; w.id = 'welcomeScreen';
        w.innerHTML = `
            <div class="ag-welcome-icon">🤖</div>
            <div class="ag-welcome-title">Hello! I'm your AI Agent</div>
            <div class="ag-welcome-sub">I can help you manage tasks, answer questions, and assist with client management.</div>
            <div class="quickstart">
                <button class="qs-btn" onclick="quickSend('What can you help me with?')">What can you do?</button>
                <button class="qs-btn" onclick="quickSend('Show me a summary of my clients')">Client summary</button>
                <button class="qs-btn" onclick="quickSend('How many active conversations are there?')">Active chats</button>
            </div>`;
        area.appendChild(w);
    }
    // Try to clear backend history
    try {
        await fetch('/api/agent/history', { method:'DELETE', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF}, body: JSON.stringify({user_id:USER_ID}) });
    } catch(e) {}
    showToast('Conversation cleared', 'info');
}
window.clearConversation = clearConversation;

// ── INIT ──
checkHealth();
setInterval(checkHealth, 30000);
loadHistory();
document.getElementById('msgInput').focus();
})();
</script>
@endsection