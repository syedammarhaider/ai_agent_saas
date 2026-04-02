{{-- resources/views/chat.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

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
  --c-purple:   #a855f7;
  --c-text:     #e8eaf0;
  --c-muted:    rgba(232,234,240,0.45);
  --c-dimmer:   rgba(232,234,240,0.25);
  --ff-display: 'Space Mono', monospace;
  --ff-body:    'DM Sans', sans-serif;
  --ease-out:   cubic-bezier(0.16,1,0.3,1);
}

.chat-page {
  font-family: var(--ff-body);
  background: var(--c-bg);
  color: var(--c-text);
  min-height: 100vh;
  padding: 28px;
  position: relative;
  overflow-x: hidden;
}

/* Noise texture */
.chat-page::before {
  content: '';
  position: fixed;
  inset: 0;
  z-index: 0;
  pointer-events: none;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
  background-size: 180px;
  opacity: 0.7;
}

/* Glow effect */
.chat-page::after {
  content: '';
  position: fixed;
  top: -100px;
  right: -60px;
  width: 540px;
  height: 540px;
  background: radial-gradient(circle, rgba(0,229,255,0.06) 0%, transparent 70%);
  pointer-events: none;
  z-index: 0;
}

.chat-page > * {
  position: relative;
  z-index: 1;
}

/* Chat Container */
.chat-container {
  max-width: 1400px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 320px 1fr;
  gap: 20px;
  min-height: calc(100vh - 56px);
}

@media (max-width: 768px) {
  .chat-container {
    grid-template-columns: 1fr;
    gap: 16px;
  }
  .chat-page {
    padding: 16px;
  }
}

/* Sidebar */
.chat-sidebar {
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: 20px;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.sidebar-header {
  padding: 20px;
  border-bottom: 1px solid var(--c-border);
  background: linear-gradient(135deg, rgba(0,229,255,0.05) 0%, transparent 100%);
}

.sidebar-title {
  font-family: var(--ff-display);
  font-size: 12px;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: var(--c-muted);
  margin-bottom: 16px;
}

/* Search */
.chat-search {
  position: relative;
  margin-bottom: 16px;
}

.chat-search input {
  width: 100%;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--c-border);
  border-radius: 12px;
  padding: 10px 12px 10px 36px;
  font-size: 13px;
  color: var(--c-text);
  transition: all 0.2s;
}

.chat-search input:focus {
  outline: none;
  border-color: var(--c-cyan);
  box-shadow: 0 0 0 3px rgba(0,229,255,0.1);
}

.chat-search svg {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  width: 14px;
  height: 14px;
  color: var(--c-dimmer);
}

/* Conversations List */
.conversations-list {
  flex: 1;
  overflow-y: auto;
  padding: 8px;
}

.conversation-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  border-radius: 14px;
  cursor: pointer;
  transition: all 0.2s;
  margin-bottom: 4px;
}

.conversation-item:hover {
  background: var(--c-glass);
}

.conversation-item.active {
  background: var(--c-cyan-dim);
  border: 1px solid rgba(0,229,255,0.2);
}

.conversation-avatar {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  background: linear-gradient(135deg, var(--c-cyan), var(--c-purple));
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 18px;
  flex-shrink: 0;
}

.conversation-info {
  flex: 1;
  min-width: 0;
}

.conversation-name {
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 4px;
}

.conversation-preview {
  font-size: 12px;
  color: var(--c-muted);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.conversation-time {
  font-size: 10px;
  color: var(--c-dimmer);
  margin-top: 4px;
}

.conversation-badge {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--c-green);
  box-shadow: 0 0 6px var(--c-green);
  flex-shrink: 0;
}

/* Main Chat Area */
.chat-main {
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: 20px;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.chat-header {
  padding: 20px 24px;
  border-bottom: 1px solid var(--c-border);
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: linear-gradient(135deg, rgba(0,229,255,0.03) 0%, transparent 100%);
}

.chat-header-info {
  display: flex;
  align-items: center;
  gap: 16px;
}

.chat-header-avatar {
  width: 48px;
  height: 48px;
  border-radius: 14px;
  background: linear-gradient(135deg, var(--c-cyan), var(--c-purple));
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 18px;
}

.chat-header-details h3 {
  font-size: 16px;
  font-weight: 700;
  margin-bottom: 4px;
}

.chat-header-details p {
  font-size: 12px;
  color: var(--c-muted);
  display: flex;
  align-items: center;
  gap: 6px;
}

.status-online {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--c-green);
  box-shadow: 0 0 6px var(--c-green);
}

.chat-actions {
  display: flex;
  gap: 8px;
}

.chat-action-btn {
  padding: 8px;
  border-radius: 10px;
  background: var(--c-glass);
  border: 1px solid var(--c-border);
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.chat-action-btn:hover {
  border-color: var(--c-border2);
  background: rgba(255,255,255,0.05);
}

/* Messages Area */
.messages-area {
  flex: 1;
  overflow-y: auto;
  padding: 24px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.message {
  display: flex;
  gap: 12px;
  animation: fadeInUp 0.3s var(--ease-out);
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.message-avatar {
  width: 32px;
  height: 32px;
  border-radius: 10px;
  background: linear-gradient(135deg, var(--c-cyan), var(--c-purple));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 600;
  flex-shrink: 0;
}

.message-content {
  flex: 1;
  max-width: 70%;
}

.message-bubble {
  background: var(--c-glass);
  border: 1px solid var(--c-border);
  border-radius: 18px;
  padding: 12px 16px;
  display: inline-block;
  max-width: 100%;
}

.message-bubble p {
  font-size: 13px;
  line-height: 1.5;
  margin: 0;
}

.message-time {
  font-size: 10px;
  color: var(--c-dimmer);
  margin-top: 4px;
  margin-left: 8px;
}

/* AI Message Styling */
.message.ai .message-bubble {
  background: var(--c-cyan-dim);
  border-color: rgba(0,229,255,0.3);
}

.message.ai .message-bubble::before {
  content: '🤖';
  font-size: 12px;
  margin-right: 8px;
}

/* User Message Styling */
.message.user {
  flex-direction: row-reverse;
}

.message.user .message-content {
  text-align: right;
}

.message.user .message-bubble {
  background: linear-gradient(135deg, rgba(0,229,255,0.15), rgba(168,85,247,0.15));
  border-color: rgba(0,229,255,0.2);
}

/* Typing Indicator */
.typing-indicator {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 12px 16px;
  background: var(--c-glass);
  border: 1px solid var(--c-border);
  border-radius: 18px;
  width: fit-content;
}

.typing-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--c-cyan);
  animation: typing 1.4s infinite;
}

.typing-dot:nth-child(2) { animation-delay: 0.2s; }
.typing-dot:nth-child(3) { animation-delay: 0.4s; }

@keyframes typing {
  0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
  30% { transform: translateY(-6px); opacity: 1; }
}

/* Input Area */
.chat-input-area {
  padding: 20px 24px;
  border-top: 1px solid var(--c-border);
  display: flex;
  gap: 12px;
  align-items: flex-end;
}

.chat-input-wrapper {
  flex: 1;
  position: relative;
}

.chat-input {
  width: 100%;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--c-border);
  border-radius: 20px;
  padding: 12px 50px 12px 16px;
  font-size: 13px;
  color: var(--c-text);
  resize: none;
  font-family: var(--ff-body);
  transition: all 0.2s;
}

.chat-input:focus {
  outline: none;
  border-color: var(--c-cyan);
  box-shadow: 0 0 0 3px rgba(0,229,255,0.1);
}

.chat-send-btn {
  position: absolute;
  right: 12px;
  bottom: 8px;
  background: var(--c-cyan);
  border: none;
  border-radius: 12px;
  padding: 6px 10px;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.chat-send-btn:hover {
  transform: scale(1.05);
  background: #00c4e0;
}

.chat-attach-btn {
  background: var(--c-glass);
  border: 1px solid var(--c-border);
  border-radius: 12px;
  padding: 10px;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.chat-attach-btn:hover {
  border-color: var(--c-border2);
  background: rgba(255,255,255,0.05);
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 60px 20px;
}

.empty-state svg {
  width: 80px;
  height: 80px;
  margin-bottom: 20px;
  opacity: 0.3;
}

.empty-state h3 {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 8px;
}

.empty-state p {
  font-size: 13px;
  color: var(--c-muted);
}

/* Scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: var(--c-border);
}

::-webkit-scrollbar-thumb {
  background: var(--c-cyan);
  border-radius: 3px;
}
</style>

<div class="chat-page">
  <div class="chat-container">
    <!-- Sidebar - Conversations List -->
    <div class="chat-sidebar">
      <div class="sidebar-header">
        <div class="sidebar-title">Messages</div>
        <div class="chat-search">
          <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
          </svg>
          <input type="text" id="searchConversations" placeholder="Search conversations..." oninput="filterConversations()">
        </div>
      </div>
      
      <div class="conversations-list" id="conversationsList">
        <!-- Conversations will be populated here -->
      </div>
    </div>

    <!-- Main Chat Area -->
    <div class="chat-main">
      <!-- Chat Header -->
      <div class="chat-header" id="chatHeader">
        <div class="chat-header-info">
          <div class="chat-header-avatar" id="chatAvatar">JS</div>
          <div class="chat-header-details">
            <h3 id="chatName">John Smith</h3>
            <p>
              <span class="status-online"></span>
              <span id="chatStatus">Online</span>
            </p>
          </div>
        </div>
        <div class="chat-actions">
          <button class="chat-action-btn" onclick="openAIAssistant()" title="AI Assistant">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
          </button>
          <button class="chat-action-btn" onclick="clearChat()" title="Clear Chat">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
              <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Messages Area -->
      <div class="messages-area" id="messagesArea">
        <div class="empty-state">
          <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/>
          </svg>
          <h3>No conversation selected</h3>
          <p>Select a conversation from the sidebar to start messaging</p>
        </div>
      </div>

      <!-- Input Area -->
      <div class="chat-input-area" id="chatInputArea" style="display: none;">
        <button class="chat-attach-btn" onclick="attachFile()" title="Attach file">
          <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
            <path d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5c0-1.38 1.12-2.5 2.5-2.5s2.5 1.12 2.5 2.5v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z"/>
          </svg>
        </button>
        <div class="chat-input-wrapper">
          <textarea class="chat-input" id="messageInput" placeholder="Type a message..." rows="1" onkeydown="handleMessageKeydown(event)"></textarea>
          <button class="chat-send-btn" onclick="sendMessage()">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
              <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Mock conversations data
let conversations = [
  {
    id: 1,
    name: 'John Smith',
    avatar: 'JS',
    lastMessage: 'Thanks for your help!',
    lastMessageTime: Date.now() - 5 * 60000,
    unread: false,
    status: 'online',
    messages: [
      { id: 1, sender: 'user', text: 'Hi John, how can I help you?', time: Date.now() - 30 * 60000 },
      { id: 2, sender: 'ai', text: 'I need help with the API integration. It seems to be failing.', time: Date.now() - 25 * 60000 },
      { id: 3, sender: 'user', text: 'Let me check that for you. What error are you getting?', time: Date.now() - 20 * 60000 },
      { id: 4, sender: 'ai', text: 'Getting a 401 unauthorized error even though my API key is correct.', time: Date.now() - 15 * 60000 },
      { id: 5, sender: 'user', text: 'Thanks for your help!', time: Date.now() - 5 * 60000 }
    ]
  },
  {
    id: 2,
    name: 'Sarah Wilson',
    avatar: 'SW',
    lastMessage: 'When is the deadline?',
    lastMessageTime: Date.now() - 2 * 3600000,
    unread: true,
    status: 'offline',
    messages: [
      { id: 1, sender: 'ai', text: 'Hi Sarah, what project are we discussing?', time: Date.now() - 3 * 3600000 },
      { id: 2, sender: 'user', text: 'The dashboard redesign project. When is the deadline?', time: Date.now() - 2 * 3600000 },
      { id: 3, sender: 'ai', text: 'Let me check that for you...', time: Date.now() - 1.5 * 3600000 }
    ]
  },
  {
    id: 3,
    name: 'Michael Chen',
    avatar: 'MC',
    lastMessage: 'Great job on the deployment!',
    lastMessageTime: Date.now() - 1 * 86400000,
    unread: false,
    status: 'online',
    messages: [
      { id: 1, sender: 'user', text: 'The deployment is complete!', time: Date.now() - 1.2 * 86400000 },
      { id: 2, sender: 'ai', text: 'Great job on the deployment! Everything looks good.', time: Date.now() - 1 * 86400000 }
    ]
  },
  {
    id: 4,
    name: 'Emily Davis',
    avatar: 'ED',
    lastMessage: 'Can you review the design?',
    lastMessageTime: Date.now() - 2 * 86400000,
    unread: false,
    status: 'offline',
    messages: [
      { id: 1, sender: 'ai', text: 'Hi Emily, how can I help?', time: Date.now() - 2.2 * 86400000 },
      { id: 2, sender: 'user', text: 'Can you review the design files I sent?', time: Date.now() - 2 * 86400000 }
    ]
  },
  {
    id: 5,
    name: 'Robert Johnson',
    avatar: 'RJ',
    lastMessage: 'Payment received. Thank you!',
    lastMessageTime: Date.now() - 3 * 86400000,
    unread: false,
    status: 'online',
    messages: [
      { id: 1, sender: 'user', text: 'Invoice #1234 has been paid', time: Date.now() - 3.1 * 86400000 },
      { id: 2, sender: 'ai', text: 'Payment received. Thank you for your business!', time: Date.now() - 3 * 86400000 }
    ]
  }
];

let currentConversation = null;
let isTyping = false;

// Render conversations list
function renderConversations() {
  const container = document.getElementById('conversationsList');
  const searchTerm = document.getElementById('searchConversations')?.value.toLowerCase() || '';
  
  const filtered = conversations.filter(conv => 
    conv.name.toLowerCase().includes(searchTerm)
  );
  
  if (filtered.length === 0) {
    container.innerHTML = '<div style="text-align: center; padding: 40px; color: var(--c-dimmer); font-size: 12px;">No conversations found</div>';
    return;
  }
  
  container.innerHTML = filtered.map(conv => `
    <div class="conversation-item ${currentConversation?.id === conv.id ? 'active' : ''}" onclick="selectConversation(${conv.id})">
      <div class="conversation-avatar">${conv.avatar}</div>
      <div class="conversation-info">
        <div class="conversation-name">${conv.name}</div>
        <div class="conversation-preview">${conv.lastMessage}</div>
        <div class="conversation-time">${formatTime(conv.lastMessageTime)}</div>
      </div>
      ${conv.unread ? '<div class="conversation-badge"></div>' : ''}
    </div>
  `).join('');
}

// Select conversation
function selectConversation(id) {
  currentConversation = conversations.find(c => c.id === id);
  if (!currentConversation) return;
  
  // Mark as read
  currentConversation.unread = false;
  
  // Update header
  document.getElementById('chatAvatar').textContent = currentConversation.avatar;
  document.getElementById('chatName').textContent = currentConversation.name;
  document.getElementById('chatStatus').textContent = currentConversation.status === 'online' ? 'Online' : 'Offline';
  
  // Show input area
  document.getElementById('chatInputArea').style.display = 'flex';
  
  // Render messages
  renderMessages();
  renderConversations();
}

// Render messages
function renderMessages() {
  const container = document.getElementById('messagesArea');
  
  if (!currentConversation || currentConversation.messages.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        <svg fill="currentColor" viewBox="0 0 24 24">
          <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
        </svg>
        <h3>No messages yet</h3>
        <p>Start a conversation with ${currentConversation?.name}</p>
      </div>
    `;
    return;
  }
  
  container.innerHTML = currentConversation.messages.map(msg => `
    <div class="message ${msg.sender}">
      <div class="message-avatar">${msg.sender === 'ai' ? '🤖' : (currentConversation?.avatar || 'U')}</div>
      <div class="message-content">
        <div class="message-bubble">
          <p>${escapeHtml(msg.text)}</p>
        </div>
        <div class="message-time">${formatTime(msg.time)}</div>
      </div>
    </div>
  `).join('');
  
  // Scroll to bottom
  container.scrollTop = container.scrollHeight;
}

// Send message
function sendMessage() {
  const input = document.getElementById('messageInput');
  const text = input.value.trim();
  
  if (!text || !currentConversation) return;
  
  // Add user message
  const userMessage = {
    id: Date.now(),
    sender: 'user',
    text: text,
    time: Date.now()
  };
  currentConversation.messages.push(userMessage);
  
  // Update last message
  currentConversation.lastMessage = text;
  currentConversation.lastMessageTime = Date.now();
  
  renderMessages();
  renderConversations();
  input.value = '';
  
  // Show typing indicator
  showTypingIndicator();
  
  // Simulate AI response after 1-2 seconds
  setTimeout(() => {
    const aiResponse = generateAIResponse(text);
    const aiMessage = {
      id: Date.now(),
      sender: 'ai',
      text: aiResponse,
      time: Date.now()
    };
    currentConversation.messages.push(aiMessage);
    currentConversation.lastMessage = aiResponse;
    currentConversation.lastMessageTime = Date.now();
    
    hideTypingIndicator();
    renderMessages();
    renderConversations();
  }, 1000 + Math.random() * 1000);
}

// Generate AI response
function generateAIResponse(userMessage) {
  const responses = [
    "I understand your concern. Let me help you with that. Could you provide more details?",
    "Thanks for reaching out! I'm analyzing your request and will get back to you shortly.",
    "Great question! Based on what you're asking, I recommend checking the documentation first.",
    "I see what you mean. Let me escalate this to our technical team for review.",
    "That's an interesting point. I'll make sure to note that for future improvements.",
    "I'm here to help! Can you tell me more about what you're trying to accomplish?",
    "Thanks for the feedback! I'll make sure to incorporate this into our next update."
  ];
  return responses[Math.floor(Math.random() * responses.length)];
}

// Show typing indicator
function showTypingIndicator() {
  if (isTyping) return;
  isTyping = true;
  
  const container = document.getElementById('messagesArea');
  const typingHtml = `
    <div class="message ai" id="typingIndicator">
      <div class="message-avatar">🤖</div>
      <div class="message-content">
        <div class="typing-indicator">
          <div class="typing-dot"></div>
          <div class="typing-dot"></div>
          <div class="typing-dot"></div>
        </div>
      </div>
    </div>
  `;
  container.insertAdjacentHTML('beforeend', typingHtml);
  container.scrollTop = container.scrollHeight;
}

// Hide typing indicator
function hideTypingIndicator() {
  const indicator = document.getElementById('typingIndicator');
  if (indicator) indicator.remove();
  isTyping = false;
}

// Handle enter key
function handleMessageKeydown(event) {
  if (event.key === 'Enter' && !event.shiftKey) {
    event.preventDefault();
    sendMessage();
  }
}

// Filter conversations
function filterConversations() {
  renderConversations();
}

// Open AI Assistant
function openAIAssistant() {
  showToast('AI Assistant activated! How can I help you?', 'info');
}

// Clear chat
function clearChat() {
  if (currentConversation && confirm('Are you sure you want to clear this conversation?')) {
    currentConversation.messages = [];
    currentConversation.lastMessage = 'No messages yet';
    renderMessages();
    renderConversations();
    showToast('Conversation cleared', 'success');
  }
}

// Attach file
function attachFile() {
  showToast('File attachment coming soon!', 'info');
}

// Format time
function formatTime(timestamp) {
  const diff = Date.now() - timestamp;
  if (diff < 60000) return 'Just now';
  if (diff < 3600000) return `${Math.floor(diff / 60000)} min ago`;
  if (diff < 86400000) return `${Math.floor(diff / 3600000)} hr ago`;
  if (diff < 604800000) return `${Math.floor(diff / 86400000)} days ago`;
  return new Date(timestamp).toLocaleDateString();
}

// Escape HTML
function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

// Show toast
function showToast(message, type = 'info') {
  const toast = document.createElement('div');
  toast.style.cssText = `
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    padding: 12px 24px;
    background: ${type === 'success' ? 'var(--c-green)' : 'var(--c-cyan)'};
    color: #05070f;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    z-index: 1000;
    animation: fadeInUp 0.3s var(--ease-out);
    white-space: nowrap;
  `;
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

// Auto-select first conversation on load
function init() {
  renderConversations();
  if (conversations.length > 0 && !currentConversation) {
    selectConversation(conversations[0].id);
  }
}

// Auto-resize textarea
document.addEventListener('input', function(e) {
  if (e.target.classList.contains('chat-input')) {
    e.target.style.height = 'auto';
    e.target.style.height = Math.min(e.target.scrollHeight, 100) + 'px';
  }
});

// Initialize
init();
</script>
@endsection