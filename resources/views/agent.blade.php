{{-- resources/views/agent.blade.php --}}
@extends('layouts.app')
@section('content')

<div class="ag-pg">
    {{-- Header --}}
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

    {{-- Messages --}}
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

    {{-- Reply bar --}}
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
let messages = [];

async function checkHealth() {
    try {
        const res = await fetch('/api/agent/health', { headers:{'X-CSRF-TOKEN':CSRF} });
        const d   = await res.json();
        setStatus(d.status === 'online' ? 'online' : 'offline');
    } catch(e) { setStatus('offline'); }
}

function setStatus(s) {
    const pill = document.getElementById('statusPill');
    const text = document.getElementById('statusText');
    pill.className = 'ag-status-pill ' + s;
    text.textContent = s === 'online' ? 'Online' : s === 'offline' ? 'Offline' : 'Connecting…';
}

async function loadHistory() {
    try {
        const res = await fetch('/api/agent/history/' + encodeURIComponent(USER_ID), { headers:{'X-CSRF-TOKEN':CSRF} });
        if (!res.ok) return;
        const d    = await res.json();
        const hist = d.messages || [];
        if (hist.length) {
            messages = hist.map(m => ({ role:m.role === 'assistant' ? 'agent' : 'user', content:m.content || '', time:new Date().toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'}) }));
            renderMessages();
        }
    } catch(e) {}
}

function onInput() {
    const ta = document.getElementById('msgInput');
    ta.style.height = 'auto';
    ta.style.height = Math.min(ta.scrollHeight, 140) + 'px';
    document.getElementById('charCount').textContent = ta.value.length;
}

document.getElementById('msgInput').addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
});

function quickSend(text) {
    document.getElementById('msgInput').value = text;
    onInput(); sendMessage();
}
window.quickSend = quickSend;

async function sendMessage() {
    const inp  = document.getElementById('msgInput');
    const text = inp.value.trim();
    if (!text || sending) return;

    document.getElementById('welcomeScreen')?.remove();
    messages.push({ role:'user', content:text, time:now() });
    inp.value = ''; inp.style.height = 'auto';
    document.getElementById('charCount').textContent = '0';
    renderMessages();

    sending = true;
    document.getElementById('sendBtn').disabled = true;
    const typingId = addTyping();

    try {
        const res = await fetch('/api/agent/chat', {
            method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},
            body:JSON.stringify({user_id:USER_ID, message:text, platform:PLAT})
        });
        removeTyping(typingId);
        if (!res.ok) {
            messages.push({ role:'agent', content:'Server error (' + res.status + '). Please try again.', time:now(), error:true });
        } else {
            const d = await res.json();
            messages.push({ role:'agent', content:d.reply || d.message || d.response || 'No response.', time:now() });
        }
    } catch(e) {
        removeTyping(typingId);
        messages.push({ role:'agent', content:'Cannot reach AI backend. ' + e.message, time:now(), error:true });
    }

    sending = false;
    document.getElementById('sendBtn').disabled = false;
    renderMessages();
    inp.focus();
}

function now() { return new Date().toLocaleTimeString('en-US',{hour:'2-digit',minute:'2-digit'}); }

function addTyping() {
    const area = document.getElementById('msgsArea');
    const wrap = document.createElement('div');
    wrap.className = 'msg-wrap agent'; wrap.id = 'typing-wrap';
    wrap.innerHTML = `<div class="typing-bubble"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>`;
    area.appendChild(wrap);
    area.scrollTop = area.scrollHeight;
    return 'typing-wrap';
}
function removeTyping(id) { document.getElementById(id)?.remove(); }

function renderMessages() {
    const area = document.getElementById('msgsArea');
    area.querySelectorAll('.msg-wrap').forEach(el => el.remove());
    messages.forEach(m => {
        const wrap = document.createElement('div');
        wrap.className = 'msg-wrap ' + m.role;
        wrap.innerHTML = `
            <div class="msg-bubble ${m.error ? 'agent error' : m.role}">${escHtml(m.content)}</div>
            <div class="msg-time">${escHtml(m.time||'')}</div>`;
        area.appendChild(wrap);
    });
    area.scrollTop = area.scrollHeight;
}

function escHtml(s) {
    return String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
}

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
    try {
        await fetch('/api/agent/history', {method:'DELETE',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF},body:JSON.stringify({user_id:USER_ID})});
    } catch(e) {}
    showToast('Conversation cleared', 'info');
}
window.clearConversation = clearConversation;

checkHealth();
setInterval(checkHealth, 30000);
loadHistory();
document.getElementById('msgInput').focus();
})();
</script>
@endsection