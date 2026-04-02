{{-- resources/views/agent.blade.php --}}
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
  --c-text:     #e8eaf0;
  --c-muted:    rgba(232,234,240,0.45);
  --c-dimmer:   rgba(232,234,240,0.25);
  --ff-display: 'Space Mono', monospace;
  --ff-body:    'DM Sans', sans-serif;
  --ease-out:   cubic-bezier(0.16,1,0.3,1);
}

.agent-page {
  font-family: var(--ff-body);
  background: var(--c-bg);
  color: var(--c-text);
  min-height: 100vh;
  padding: 28px;
  position: relative;
  overflow-x: hidden;
}

/* Noise texture */
.agent-page::before {
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
.agent-page::after {
  content: '';
  position: fixed;
  top: -100px;
  left: -60px;
  width: 540px;
  height: 540px;
  background: radial-gradient(circle, rgba(0,229,255,0.06) 0%, transparent 70%);
  pointer-events: none;
  z-index: 0;
}

.agent-page > * {
  position: relative;
  z-index: 1;
}

/* Grid */
.agent-grid {
  display: grid;
  gap: 20px;
}

.agent-grid-main {
  grid-template-columns: 2fr 1fr;
}

@media (max-width: 1024px) {
  .agent-grid-main {
    grid-template-columns: 1fr;
  }
}

.agent-grid-3 {
  grid-template-columns: repeat(3, 1fr);
}

@media (max-width: 768px) {
  .agent-grid-3 {
    grid-template-columns: 1fr;
  }
}

.agent-grid-2 {
  grid-template-columns: repeat(2, 1fr);
}

@media (max-width: 640px) {
  .agent-grid-2 {
    grid-template-columns: 1fr;
  }
}

/* Card */
.agent-card {
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: 20px;
  padding: 24px;
  position: relative;
  overflow: hidden;
  transition: border-color 0.25s, box-shadow 0.25s;
}

.agent-card:hover {
  border-color: var(--c-border2);
  box-shadow: 0 0 32px rgba(0, 229, 255, 0.06);
}

.agent-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 55%;
  height: 100%;
  background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.018) 50%, transparent 60%);
  transition: left 0.6s var(--ease-out);
  pointer-events: none;
}

.agent-card:hover::before {
  left: 145%;
}

/* Header Card */
.agent-header {
  background: linear-gradient(135deg, rgba(0,229,255,0.08) 0%, rgba(0,0,0,0) 70%);
  border: 1px solid var(--c-border);
  border-radius: 20px;
  padding: 28px 32px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 20px;
  position: relative;
  overflow: hidden;
}

.agent-header::after {
  content: '';
  position: absolute;
  top: -1px;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent 0%, var(--c-cyan) 40%, var(--c-cyan) 60%, transparent 100%);
  opacity: 0.35;
  animation: scanline 3.5s var(--ease-out) infinite;
}

@keyframes scanline {
  0% {
    transform: translateX(-110%);
    opacity: 0;
  }
  20% {
    opacity: 0.5;
  }
  80% {
    opacity: 0.5;
  }
  100% {
    transform: translateX(110%);
    opacity: 0;
  }
}

.agent-logo {
  width: 56px;
  height: 56px;
  background: var(--c-cyan-dim);
  border-radius: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid rgba(0,229,255,0.2);
}

.agent-status {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 8px;
}

.status-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--c-green);
  box-shadow: 0 0 8px var(--c-green);
  animation: pulse 1.8s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(0,255,179,0.6), 0 0 6px var(--c-green);
  }
  50% {
    box-shadow: 0 0 0 6px rgba(0,255,179,0), 0 0 12px var(--c-green);
  }
}

/* Buttons */
.agent-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 20px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 600;
  font-family: var(--ff-body);
  cursor: pointer;
  transition: all 0.2s;
  border: none;
}

.agent-btn-primary {
  background: var(--c-cyan);
  color: #05070f;
}

.agent-btn-primary:hover {
  background: #00c4e0;
  transform: translateY(-1px);
}

.agent-btn-secondary {
  background: var(--c-glass);
  border: 1px solid var(--c-border);
  color: var(--c-text);
}

.agent-btn-secondary:hover {
  border-color: var(--c-border2);
  background: rgba(255,255,255,0.05);
}

.agent-btn-danger {
  background: rgba(255,77,109,0.1);
  border: 1px solid rgba(255,77,109,0.3);
  color: var(--c-red);
}

.agent-btn-danger:hover {
  background: rgba(255,77,109,0.2);
}

/* Section Headers */
.agent-section-title {
  font-family: var(--ff-display);
  font-size: 11px;
  letter-spacing: 1.2px;
  text-transform: uppercase;
  color: var(--c-muted);
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 20px;
}

/* Model Options */
.model-option {
  background: var(--c-glass);
  border: 1px solid var(--c-border);
  border-radius: 14px;
  padding: 14px;
  cursor: pointer;
  transition: all 0.2s;
}

.model-option.active {
  border-color: var(--c-cyan);
  background: var(--c-cyan-dim);
}

.model-option:hover {
  border-color: var(--c-border2);
  background: rgba(255,255,255,0.03);
}

/* Inputs */
.agent-input {
  width: 100%;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--c-border);
  border-radius: 12px;
  padding: 12px 16px;
  font-family: var(--ff-body);
  font-size: 13px;
  color: var(--c-text);
  transition: all 0.2s;
}

.agent-input:focus {
  outline: none;
  border-color: var(--c-cyan);
  box-shadow: 0 0 0 3px rgba(0,229,255,0.1);
}

.agent-textarea {
  min-height: 120px;
  resize: vertical;
  font-family: 'Space Mono', monospace;
  font-size: 12px;
  line-height: 1.6;
}

/* Range Slider */
.agent-range {
  width: 100%;
  height: 4px;
  -webkit-appearance: none;
  background: var(--c-border);
  border-radius: 2px;
  outline: none;
}

.agent-range::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 16px;
  height: 16px;
  border-radius: 50%;
  background: var(--c-cyan);
  cursor: pointer;
  box-shadow: 0 0 8px var(--c-cyan);
}

/* Toggle Switch */
.agent-toggle {
  width: 44px;
  height: 24px;
  border-radius: 12px;
  background: var(--c-border);
  position: relative;
  cursor: pointer;
  transition: all 0.2s;
}

.agent-toggle.active {
  background: var(--c-cyan);
}

.agent-toggle-knob {
  position: absolute;
  top: 2px;
  left: 2px;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background: white;
  transition: transform 0.2s;
}

.agent-toggle.active .agent-toggle-knob {
  transform: translateX(20px);
}

/* Badges */
.agent-badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 10px;
  font-weight: 600;
  letter-spacing: 0.3px;
}

.agent-badge-success {
  background: rgba(0,255,179,0.12);
  color: var(--c-green);
}

.agent-badge-info {
  background: rgba(0,229,255,0.1);
  color: var(--c-cyan);
}

.agent-badge-warning {
  background: rgba(255,176,32,0.12);
  color: var(--c-amber);
}

/* Activity Log */
.activity-item {
  display: flex;
  gap: 12px;
  padding: 12px 0;
  border-bottom: 1px solid var(--c-border);
  animation: fadeUp 0.4s var(--ease-out) both;
}

.activity-item:last-child {
  border-bottom: none;
}

@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(8px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.activity-icon {
  width: 32px;
  height: 32px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.activity-time {
  font-family: var(--ff-display);
  font-size: 10px;
  color: var(--c-dimmer);
}

/* Live Badge */
.live-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: var(--c-green);
}

.live-badge::before {
  content: '';
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--c-green);
  box-shadow: 0 0 6px var(--c-green);
  animation: blink 0.9s ease-in-out infinite alternate;
}

@keyframes blink {
  from {
    opacity: 0.3;
  }
  to {
    opacity: 1;
  }
}

/* Test Result */
.test-result {
  margin-top: 12px;
  background: rgba(0,229,255,0.06);
  border: 1px solid rgba(0,229,255,0.18);
  border-radius: 14px;
  padding: 14px;
  display: none;
}

.test-result.show {
  display: block;
  animation: fadeUp 0.3s var(--ease-out);
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

/* Animations */
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Sticky sidebar */
.sticky-sidebar {
  position: sticky;
  top: 24px;
  height: fit-content;
}
</style>

<div class="agent-page">
  <div class="agent-grid agent-grid-main" style="gap: 20px;">
    <!-- Left Column -->
    <div class="agent-grid" style="gap: 20px;">
      <!-- Header -->
      <div class="agent-header">
        <div style="display: flex; align-items: center; gap: 20px;">
          <div class="agent-logo">
            <svg width="28" height="28" fill="currentColor" class="text-[var(--c-cyan)]" viewBox="0 0 24 24">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
            </svg>
          </div>
          <div>
            <h2 style="font-family: var(--ff-display); font-size: 20px; font-weight: 700;">AI Agent</h2>
            <div class="agent-status">
              <span class="status-dot"></span>
              <span style="font-size: 12px; font-weight: 600; color: var(--c-green);" id="agentStatusText">Running</span>
              <span style="font-size: 11px; color: var(--c-dimmer);">· <strong style="color: var(--c-cyan);" id="currentModel">gemini-3-flash-preview</strong></span>
              <span style="font-size: 11px; color: var(--c-dimmer);">· Auto-reply: <strong style="color: var(--c-green);" id="autoReplyStatus">ON</strong></span>
            </div>
          </div>
        </div>
        <div style="display: flex; gap: 12px;">
          <button onclick="saveConfig()" class="agent-btn agent-btn-secondary">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
            </svg>
            Save
          </button>
          <button onclick="toggleAgent()" id="toggleAgentBtn" class="agent-btn agent-btn-primary">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
              <path d="M6 6h12v12H6z"/>
            </svg>
            Stop
          </button>
        </div>
      </div>

      <!-- AI Model -->
      <div class="agent-card">
        <div class="agent-section-title">
          <svg width="14" height="14" fill="currentColor" class="text-[var(--c-cyan)]" viewBox="0 0 24 24">
            <path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/>
          </svg>
          AI Model
        </div>
        <div class="agent-grid-3" style="gap: 12px;">
          <div onclick="selectModel('gemini-3-flash-preview')" class="model-option active" data-model="gemini-3-flash-preview">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
              <span style="font-size: 13px; font-weight: 600;">Gemini 2.0 Flash</span>
              <span class="agent-badge agent-badge-success">Active</span>
            </div>
            <span style="font-size: 10px; color: var(--c-dimmer);">Google DeepMind</span>
          </div>
          <div onclick="selectModel('gemini-1.5-pro')" class="model-option" data-model="gemini-1.5-pro">
            <div style="margin-bottom: 6px;">
              <span style="font-size: 13px; font-weight: 600;">Gemini 1.5 Pro</span>
            </div>
            <span style="font-size: 10px; color: var(--c-dimmer);">Google DeepMind</span>
          </div>
          <div onclick="selectModel('gemini-1.5-flash')" class="model-option" data-model="gemini-1.5-flash">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
              <span style="font-size: 13px; font-weight: 600;">Gemini 1.5 Flash</span>
              <span class="agent-badge agent-badge-info">Fast</span>
            </div>
            <span style="font-size: 10px; color: var(--c-dimmer);">Google DeepMind</span>
          </div>
        </div>
      </div>

      <!-- System Prompt -->
      <div class="agent-card">
        <div class="agent-section-title">
          <svg width="14" height="14" fill="currentColor" class="text-[var(--c-cyan)]" viewBox="0 0 24 24">
            <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
          </svg>
          System Prompt
        </div>
        <textarea id="systemPrompt" class="agent-input agent-textarea" placeholder="Enter system prompt...">You are an AI assistant for a SaaS business. Help clients with their questions, create tasks when needed, and escalate urgent issues to human agents. Be concise, professional, and helpful.</textarea>
      </div>

      <!-- Parameters -->
      <div class="agent-card">
        <div class="agent-section-title">
          <svg width="14" height="14" fill="currentColor" class="text-[var(--c-cyan)]" viewBox="0 0 24 24">
            <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
          </svg>
          Parameters
        </div>
        <div class="agent-grid-2" style="gap: 24px;">
          <div>
            <label style="font-size: 12px; font-weight: 600; color: var(--c-muted); margin-bottom: 8px; display: block;">Temperature: <span id="tempValue">0.7</span></label>
            <input type="range" min="0" max="1" step="0.1" value="0.7" class="agent-range" id="temperatureSlider" oninput="updateTemp(this.value)">
            <div style="display: flex; justify-content: space-between; font-size: 10px; color: var(--c-dimmer); margin-top: 6px;">
              <span>Precise</span>
              <span>Creative</span>
            </div>
          </div>
          <div>
            <label style="font-size: 12px; font-weight: 600; color: var(--c-muted); margin-bottom: 8px; display: block;">Max Tokens: <span id="tokensValue">1024</span></label>
            <input type="range" min="256" max="4096" step="256" value="1024" class="agent-range" id="tokensSlider" oninput="updateTokens(this.value)">
            <div style="display: flex; justify-content: space-between; font-size: 10px; color: var(--c-dimmer); margin-top: 6px;">
              <span>256</span>
              <span>4096</span>
            </div>
          </div>
        </div>
        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--c-border);">
          <div>
            <div style="font-size: 12px; font-weight: 600;">Auto-Reply</div>
            <div style="font-size: 10px; color: var(--c-dimmer);">Automatically respond to all incoming messages</div>
          </div>
          <div id="autoReplyToggle" class="agent-toggle active" onclick="toggleAutoReply()">
            <div class="agent-toggle-knob"></div>
          </div>
        </div>
      </div>

      <!-- Live Test -->
      <div class="agent-card">
        <div class="agent-section-title">
          <svg width="14" height="14" fill="currentColor" class="text-[var(--c-cyan)]" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
          </svg>
          Live AI Test
        </div>
        <div style="display: flex; gap: 12px;">
          <input id="testMessage" class="agent-input" style="flex: 1;" placeholder='Try: "The API is returning errors" or "When will my project be done?"' onkeydown="if(event.key==='Enter') testAgent()">
          <button onclick="testAgent()" class="agent-btn agent-btn-primary">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
              <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
            </svg>
          </button>
        </div>
        <div id="testResult" class="test-result"></div>
      </div>

      <!-- Platforms + Keywords -->
      <div class="agent-grid-2" style="gap: 20px;">
        <div class="agent-card">
          <div class="agent-section-title">
            <svg width="14" height="14" fill="currentColor" class="text-[var(--c-cyan)]" viewBox="0 0 24 24">
              <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/>
            </svg>
            Platforms
          </div>
          <div style="space-y: 10px;">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0;">
              <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 18px;">📱</span>
                <span style="font-size: 13px;">WhatsApp</span>
              </div>
              <div class="platform-toggle agent-toggle active" data-platform="whatsapp" onclick="togglePlatform('whatsapp')">
                <div class="agent-toggle-knob"></div>
              </div>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0;">
              <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 18px;">⚡</span>
                <span style="font-size: 13px;">Slack</span>
              </div>
              <div class="platform-toggle agent-toggle" data-platform="slack" onclick="togglePlatform('slack')">
                <div class="agent-toggle-knob"></div>
              </div>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0;">
              <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 18px;">📧</span>
                <span style="font-size: 13px;">Email</span>
              </div>
              <div class="platform-toggle agent-toggle active" data-platform="email" onclick="togglePlatform('email')">
                <div class="agent-toggle-knob"></div>
              </div>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0;">
              <div style="display: flex; align-items: center; gap: 10px;">
                <span style="font-size: 18px;">✈️</span>
                <span style="font-size: 13px;">Telegram</span>
              </div>
              <div class="platform-toggle agent-toggle" data-platform="telegram" onclick="togglePlatform('telegram')">
                <div class="agent-toggle-knob"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="agent-card">
          <div class="agent-section-title">
            <svg width="14" height="14" fill="currentColor" class="text-[var(--c-amber)]" viewBox="0 0 24 24">
              <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
            </svg>
            Escalation Keywords
          </div>
          <div id="keywordsContainer" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px;">
            <span class="agent-badge agent-badge-warning" style="gap: 6px;">urgent <button onclick="removeKeyword('urgent')" style="background: none; border: none; color: inherit; cursor: pointer;">×</button></span>
            <span class="agent-badge agent-badge-warning" style="gap: 6px;">emergency <button onclick="removeKeyword('emergency')" style="background: none; border: none; color: inherit; cursor: pointer;">×</button></span>
            <span class="agent-badge agent-badge-warning" style="gap: 6px;">critical <button onclick="removeKeyword('critical')" style="background: none; border: none; color: inherit; cursor: pointer;">×</button></span>
          </div>
          <input id="keywordInput" class="agent-input" style="font-size: 12px;" placeholder="Add keyword + Enter" onkeydown="if(event.key==='Enter' && this.value) addKeyword(this.value)">
        </div>
      </div>
    </div>

    <!-- Right Column - Activity Log -->
    <div class="agent-card sticky-sidebar">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
        <div class="agent-section-title" style="margin-bottom: 0;">
          <svg width="14" height="14" fill="currentColor" class="text-[var(--c-cyan)]" viewBox="0 0 24 24">
            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
          </svg>
          Activity Log
        </div>
        <span class="live-badge">Live</span>
      </div>
      <div id="activityLog" style="max-height: 500px; overflow-y: auto;">
        <div style="text-align: center; padding: 40px 20px; font-size: 12px; color: var(--c-dimmer);">No activity yet</div>
      </div>
    </div>
  </div>
</div>

<script>
// Configuration
let config = {
  model: 'gemini-3-flash-preview',
  auto_reply: true,
  enabled_platforms: ['whatsapp', 'email'],
  escalation_keywords: ['urgent', 'emergency', 'critical'],
  temperature: 0.7,
  max_tokens: 1024,
  system_prompt: 'You are an AI assistant for a SaaS business. Help clients with their questions, create tasks when needed, and escalate urgent issues to human agents. Be concise, professional, and helpful.'
};

let agentOnline = true;

// Activity log
let activities = [
  { description: 'Agent started successfully', time: Date.now() - 2 * 60000 },
  { description: 'Configuration loaded from storage', time: Date.now() - 1 * 60000 },
  { description: 'Model set to Gemini 2.0 Flash', time: Date.now() - 30 * 1000 }
];

// Initialize
function init() {
  // Set initial values
  document.getElementById('systemPrompt').value = config.system_prompt;
  document.getElementById('temperatureSlider').value = config.temperature;
  document.getElementById('tempValue').textContent = config.temperature;
  document.getElementById('tokensSlider').value = config.max_tokens;
  document.getElementById('tokensValue').textContent = config.max_tokens;
  document.getElementById('currentModel').textContent = config.model;
  
  // Set active model styling
  document.querySelectorAll('.model-option').forEach(opt => {
    if (opt.dataset.model === config.model) {
      opt.classList.add('active');
    } else {
      opt.classList.remove('active');
    }
  });
  
  // Set platform toggles
  document.querySelectorAll('.platform-toggle').forEach(toggle => {
    const platform = toggle.dataset.platform;
    if (config.enabled_platforms.includes(platform)) {
      toggle.classList.add('active');
    } else {
      toggle.classList.remove('active');
    }
  });
  
  renderActivity();
}

function selectModel(modelId) {
  config.model = modelId;
  document.getElementById('currentModel').textContent = modelId;
  
  document.querySelectorAll('.model-option').forEach(opt => {
    if (opt.dataset.model === modelId) {
      opt.classList.add('active');
    } else {
      opt.classList.remove('active');
    }
  });
  
  addActivity(`Model switched to ${modelId}`);
}

function togglePlatform(platform) {
  const index = config.enabled_platforms.indexOf(platform);
  const toggle = document.querySelector(`.platform-toggle[data-platform="${platform}"]`);
  
  if (index > -1) {
    config.enabled_platforms.splice(index, 1);
    toggle.classList.remove('active');
    addActivity(`Platform ${platform} disabled`);
  } else {
    config.enabled_platforms.push(platform);
    toggle.classList.add('active');
    addActivity(`Platform ${platform} enabled`);
  }
}

function toggleAutoReply() {
  config.auto_reply = !config.auto_reply;
  const toggle = document.getElementById('autoReplyToggle');
  const statusText = document.getElementById('autoReplyStatus');
  
  if (config.auto_reply) {
    toggle.classList.add('active');
    statusText.textContent = 'ON';
    statusText.style.color = 'var(--c-green)';
    addActivity('Auto-reply enabled');
  } else {
    toggle.classList.remove('active');
    statusText.textContent = 'OFF';
    statusText.style.color = 'var(--c-dimmer)';
    addActivity('Auto-reply disabled');
  }
}

function updateTemp(value) {
  config.temperature = parseFloat(value);
  document.getElementById('tempValue').textContent = value;
}

function updateTokens(value) {
  config.max_tokens = parseInt(value);
  document.getElementById('tokensValue').textContent = value;
}

function addKeyword(keyword) {
  const kw = keyword.trim().toLowerCase();
  if (!config.escalation_keywords.includes(kw)) {
    config.escalation_keywords.push(kw);
    updateKeywordsDisplay();
    document.getElementById('keywordInput').value = '';
    addActivity(`Added escalation keyword: ${kw}`);
  }
}

function removeKeyword(keyword) {
  const index = config.escalation_keywords.indexOf(keyword);
  if (index > -1) {
    config.escalation_keywords.splice(index, 1);
    updateKeywordsDisplay();
    addActivity(`Removed escalation keyword: ${keyword}`);
  }
}

function updateKeywordsDisplay() {
  const container = document.getElementById('keywordsContainer');
  container.innerHTML = config.escalation_keywords.map(kw => `
    <span class="agent-badge agent-badge-warning" style="gap: 6px;">
      ${kw} 
      <button onclick="removeKeyword('${kw}')" style="background: none; border: none; color: inherit; cursor: pointer;">×</button>
    </span>
  `).join('');
}

function toggleAgent() {
  agentOnline = !agentOnline;
  const btn = document.getElementById('toggleAgentBtn');
  const statusText = document.getElementById('agentStatusText');
  const statusDot = document.querySelector('.status-dot');
  
  if (agentOnline) {
    btn.innerHTML = '<svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M6 6h12v12H6z"/></svg> Stop';
    btn.classList.remove('agent-btn-secondary');
    btn.classList.add('agent-btn-primary');
    statusText.textContent = 'Running';
    statusText.style.color = 'var(--c-green)';
    statusDot.style.background = 'var(--c-green)';
    statusDot.style.boxShadow = '0 0 8px var(--c-green)';
    addActivity('Agent started');
  } else {
    btn.innerHTML = '<svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg> Start';
    btn.classList.remove('agent-btn-primary');
    btn.classList.add('agent-btn-secondary');
    statusText.textContent = 'Offline';
    statusText.style.color = 'var(--c-red)';
    statusDot.style.background = 'var(--c-red)';
    statusDot.style.boxShadow = '0 0 8px var(--c-red)';
    addActivity('Agent stopped');
  }
}

function saveConfig() {
  config.system_prompt = document.getElementById('systemPrompt').value;
  
  const btn = event.target.closest('button');
  const originalHTML = btn.innerHTML;
  btn.innerHTML = '<svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24" class="animate-spin"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8z"/></svg> Saving...';
  
  setTimeout(() => {
    btn.innerHTML = originalHTML;
    addActivity('Configuration saved successfully');
    showToast('Configuration saved!', 'success');
  }, 800);
}

function testAgent() {
  const message = document.getElementById('testMessage').value;
  if (!message.trim()) return;
  
  const resultDiv = document.getElementById('testResult');
  resultDiv.innerHTML = `
    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
      <svg width="12" height="12" fill="currentColor" class="animate-spin" viewBox="0 0 24 24">
        <path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8z"/>
      </svg>
      <span style="font-size: 11px; color: var(--c-cyan);">Gemini is thinking...</span>
    </div>
  `;
  resultDiv.classList.add('show');
  
  addActivity(`Test message: "${message.substring(0, 50)}${message.length > 50 ? '...' : ''}"`);
  
  setTimeout(() => {
    resultDiv.innerHTML = `
      <div style="display: flex; gap: 12px; margin-bottom: 12px;">
        <svg width="14" height="14" fill="currentColor" class="text-[var(--c-cyan)]" viewBox="0 0 24 24">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
        </svg>
        <div style="flex: 1;">
          <div style="font-size: 11px; font-weight: 600; color: var(--c-cyan); margin-bottom: 8px;">GEMINI REPLY</div>
          <p style="font-size: 12px; line-height: 1.5; color: var(--c-text);">
            I understand your concern. Let me help you with that. Based on your message, I recommend checking the configuration first. Would you like me to guide you through the troubleshooting steps?
          </p>
        </div>
      </div>
      <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <span class="agent-badge agent-badge-info">⚡ Action: analyze</span>
        <span class="agent-badge agent-badge-warning">📋 Task: investigate</span>
      </div>
    `;
    document.getElementById('testMessage').value = '';
  }, 1500);
}

function addActivity(description) {
  activities.unshift({
    description: description,
    time: Date.now()
  });
  
  if (activities.length > 20) activities.pop();
  renderActivity();
}

function renderActivity() {
  const container = document.getElementById('activityLog');
  
  if (activities.length === 0) {
    container.innerHTML = '<div style="text-align: center; padding: 40px 20px; font-size: 12px; color: var(--c-dimmer);">No activity yet</div>';
    return;
  }
  
  container.innerHTML = activities.map((act, i) => `
    <div class="activity-item" style="animation-delay: ${i * 50}ms;">
      <div class="activity-icon" style="background: var(--c-cyan-dim);">
        <svg width="12" height="12" fill="currentColor" class="text-[var(--c-cyan)]" viewBox="0 0 24 24">
          <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18l4-4h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"/>
        </svg>
      </div>
      <div style="flex: 1;">
        <div style="font-size: 12px; color: var(--c-text);">${act.description}</div>
        <div class="activity-time">${formatTime(act.time)}</div>
      </div>
    </div>
  `).join('');
}

function formatTime(timestamp) {
  const diff = Date.now() - timestamp;
  if (diff < 60000) return 'just now';
  if (diff < 3600000) return `${Math.floor(diff / 60000)} min ago`;
  if (diff < 86400000) return `${Math.floor(diff / 3600000)} hr ago`;
  return new Date(timestamp).toLocaleTimeString();
}

function showToast(message, type = 'info') {
  const toast = document.createElement('div');
  toast.style.cssText = `
    position: fixed;
    bottom: 24px;
    right: 24px;
    padding: 12px 20px;
    background: ${type === 'success' ? 'var(--c-green)' : 'var(--c-cyan)'};
    color: #05070f;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    z-index: 1000;
    animation: fadeUp 0.3s var(--ease-out);
    font-family: var(--ff-body);
  `;
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

// Auto-simulate activity every 15 seconds
setInterval(() => {
  if (agentOnline) {
    const actions = [
      'Message processed successfully',
      'AI response generated',
      'Task auto-created from conversation',
      'Client query resolved',
      'Smart reply dispatched'
    ];
    const randomAction = actions[Math.floor(Math.random() * actions.length)];
    addActivity(randomAction);
  }
}, 15000);

// Initialize
init();
</script>
@endsection