{{-- resources/views/clients.blade.php --}}
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

.clients-page {
  font-family: var(--ff-body);
  background: var(--c-bg);
  color: var(--c-text);
  min-height: 100vh;
  padding: 28px;
  position: relative;
  overflow-x: hidden;
}

/* Noise texture */
.clients-page::before {
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
.clients-page::after {
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

.clients-page > * {
  position: relative;
  z-index: 1;
}

/* Toolbar */
.toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 24px;
  flex-wrap: wrap;
}

.search-wrapper {
  position: relative;
  flex: 1;
  max-width: 320px;
}

.search-icon {
  position: absolute;
  left: 14px;
  top: 50%;
  transform: translateY(-50%);
  width: 14px;
  height: 14px;
  color: var(--c-dimmer);
}

.search-input {
  width: 100%;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--c-border);
  border-radius: 14px;
  padding: 10px 16px 10px 40px;
  font-size: 13px;
  color: var(--c-text);
  transition: all 0.2s;
}

.search-input:focus {
  outline: none;
  border-color: var(--c-cyan);
  box-shadow: 0 0 0 3px rgba(0,229,255,0.1);
}

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
  margin-bottom: 24px;
}

@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .toolbar {
    flex-direction: column;
    align-items: stretch;
  }
  .search-wrapper {
    max-width: 100%;
  }
}

.stat-card {
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: 18px;
  padding: 20px;
  transition: all 0.2s;
  animation: slideUp 0.4s var(--ease-out) both;
}

.stat-card:hover {
  border-color: var(--c-border2);
  transform: translateY(-2px);
}

.stat-icon {
  font-size: 28px;
  margin-bottom: 12px;
}

.stat-value {
  font-family: var(--ff-display);
  font-size: 28px;
  font-weight: 700;
  color: var(--c-text);
  margin-bottom: 4px;
}

.stat-label {
  font-size: 12px;
  color: var(--c-muted);
}

/* Table */
.clients-table-container {
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: 20px;
  overflow: hidden;
}

.table-header {
  display: grid;
  grid-template-columns: 2fr 1.5fr 120px 100px 100px 100px 40px;
  gap: 16px;
  padding: 16px 20px;
  border-bottom: 1px solid var(--c-border);
  font-family: var(--ff-display);
  font-size: 10px;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: var(--c-muted);
  background: rgba(0,0,0,0.2);
}

@media (max-width: 1024px) {
  .table-header {
    grid-template-columns: 2fr 1.5fr 100px 80px 80px 80px 40px;
    gap: 12px;
  }
}

@media (max-width: 768px) {
  .table-header {
    display: none;
  }
}

.client-row {
  display: grid;
  grid-template-columns: 2fr 1.5fr 120px 100px 100px 100px 40px;
  gap: 16px;
  padding: 16px 20px;
  border-bottom: 1px solid var(--c-border);
  transition: all 0.2s;
  cursor: pointer;
  animation: slideUp 0.4s var(--ease-out) both;
}

@media (max-width: 1024px) {
  .client-row {
    grid-template-columns: 2fr 1.5fr 100px 80px 80px 80px 40px;
    gap: 12px;
  }
}

@media (max-width: 768px) {
  .client-row {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 20px;
    position: relative;
  }
}

.client-row:hover {
  background: var(--c-glass);
}

.client-row:last-child {
  border-bottom: none;
}

.client-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.client-avatar {
  width: 44px;
  height: 44px;
  border-radius: 14px;
  background: linear-gradient(135deg, var(--c-cyan), var(--c-purple));
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 18px;
  flex-shrink: 0;
}

.client-details {
  flex: 1;
  min-width: 0;
}

.client-name {
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 4px;
}

.client-company {
  font-size: 11px;
  color: var(--c-muted);
}

.contact-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
  justify-content: center;
}

.contact-email, .contact-phone {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  color: var(--c-muted);
}

.contact-email svg, .contact-phone svg {
  width: 11px;
  height: 11px;
  flex-shrink: 0;
}

.platforms {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

.platform-icon {
  font-size: 16px;
}

.revenue {
  font-size: 14px;
  font-weight: 700;
  color: var(--c-cyan);
  display: flex;
  align-items: center;
}

.task-stats {
  display: flex;
  align-items: center;
  gap: 12px;
}

.task-stat {
  display: flex;
  align-items: center;
  gap: 4px;
  font-size: 11px;
  color: var(--c-muted);
}

.task-stat svg {
  width: 12px;
  height: 12px;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 10px;
  font-weight: 600;
  letter-spacing: 0.3px;
}

.status-active {
  background: rgba(0,255,179,0.12);
  color: var(--c-green);
}

.status-inactive {
  background: rgba(255,255,255,0.05);
  color: var(--c-muted);
}

.arrow-icon {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  color: var(--c-dimmer);
}

@media (max-width: 768px) {
  .arrow-icon {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
  }
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.8);
  backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s;
}

.modal-overlay.active {
  opacity: 1;
  visibility: visible;
}

.modal {
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: 24px;
  padding: 28px;
  max-width: 560px;
  width: 90%;
  max-height: 85vh;
  overflow-y: auto;
  animation: slideUp 0.3s var(--ease-out);
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 24px;
}

.modal-title {
  font-family: var(--ff-display);
  font-size: 18px;
  font-weight: 700;
}

.modal-close {
  background: none;
  border: none;
  color: var(--c-muted);
  cursor: pointer;
  padding: 4px;
  transition: color 0.2s;
}

.modal-close:hover {
  color: var(--c-text);
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 16px;
}

.form-label {
  font-size: 11px;
  font-weight: 600;
  color: var(--c-muted);
  margin-bottom: 6px;
  display: block;
  letter-spacing: 0.5px;
}

.form-input, .form-textarea {
  width: 100%;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--c-border);
  border-radius: 12px;
  padding: 10px 14px;
  font-size: 13px;
  color: var(--c-text);
  transition: all 0.2s;
}

.form-input:focus, .form-textarea:focus {
  outline: none;
  border-color: var(--c-cyan);
  box-shadow: 0 0 0 3px rgba(0,229,255,0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
}

.platform-buttons {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.platform-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--c-border);
  border-radius: 20px;
  font-size: 12px;
  color: var(--c-muted);
  cursor: pointer;
  transition: all 0.2s;
}

.platform-btn.active {
  border-color: var(--c-cyan);
  background: var(--c-cyan-dim);
  color: var(--c-cyan);
}

.platform-btn:hover {
  border-color: var(--c-border2);
  background: rgba(255,255,255,0.08);
}

.modal-actions {
  display: flex;
  gap: 12px;
  margin-top: 24px;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 10px 20px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
  flex: 1;
}

.btn-primary {
  background: var(--c-cyan);
  color: #05070f;
}

.btn-primary:hover {
  background: #00c4e0;
  transform: translateY(-1px);
}

.btn-secondary {
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--c-border);
  color: var(--c-text);
}

.btn-secondary:hover {
  border-color: var(--c-border2);
  background: rgba(255,255,255,0.08);
}

/* Detail Modal */
.detail-avatar {
  width: 56px;
  height: 56px;
  border-radius: 18px;
  background: linear-gradient(135deg, var(--c-cyan), var(--c-purple));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  font-weight: 700;
}

.detail-stats-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin: 20px 0;
}

.detail-stat-card {
  background: var(--c-glass);
  border: 1px solid var(--c-border);
  border-radius: 16px;
  padding: 16px;
}

.detail-stat-label {
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: var(--c-muted);
  margin-bottom: 8px;
}

.detail-stat-value {
  font-size: 20px;
  font-weight: 700;
  color: var(--c-cyan);
}

/* Animations */
@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.animate-slide-up {
  animation: slideUp 0.4s var(--ease-out) both;
}

/* Toast */
.toast {
  position: fixed;
  bottom: 24px;
  right: 24px;
  padding: 12px 20px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 600;
  z-index: 1100;
  animation: slideUp 0.3s var(--ease-out);
}

.toast-success {
  background: var(--c-green);
  color: #05070f;
}

.toast-error {
  background: var(--c-red);
  color: white;
}

.toast-info {
  background: var(--c-cyan);
  color: #05070f;
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
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 8px;
}

.empty-state p {
  font-size: 13px;
  color: var(--c-muted);
}
</style>

<div class="clients-page">
  <!-- Toolbar -->
  <div class="toolbar">
    <div class="search-wrapper">
      <svg class="search-icon" fill="currentColor" viewBox="0 0 24 24">
        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
      </svg>
      <input type="text" class="search-input" id="searchInput" placeholder="Search by name, email, or company..." oninput="filterClients()">
    </div>
    <div style="display: flex; gap: 12px;">
      <div class="loading-spinner" id="loadingIcon" style="display: none;">
        <svg width="18" height="18" fill="currentColor" class="animate-spin" viewBox="0 0 24 24">
          <path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8z"/>
        </svg>
      </div>
      <button onclick="showCreateModal()" class="btn-primary" style="display: inline-flex;">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
          <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
        </svg>
        Add Client
      </button>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card" style="animation-delay: 0ms;">
      <div class="stat-icon">👥</div>
      <div class="stat-value" id="totalClients">12</div>
      <div class="stat-label">Total Clients</div>
    </div>
    <div class="stat-card" style="animation-delay: 50ms;">
      <div class="stat-icon">✅</div>
      <div class="stat-value" id="activeCount">8</div>
      <div class="stat-label">Active Clients</div>
    </div>
    <div class="stat-card" style="animation-delay: 100ms;">
      <div class="stat-icon">💰</div>
      <div class="stat-value" id="totalRevenue">$28,450</div>
      <div class="stat-label">Total Revenue</div>
    </div>
    <div class="stat-card" style="animation-delay: 150ms;">
      <div class="stat-icon">⚡</div>
      <div class="stat-value" id="openIssues">15</div>
      <div class="stat-label">Open Issues</div>
    </div>
  </div>

  <!-- Clients Table -->
  <div class="clients-table-container">
    <div class="table-header">
      <span>Client</span>
      <span>Contact</span>
      <span>Platforms</span>
      <span>Revenue</span>
      <span>Tasks</span>
      <span>Status</span>
      <span></span>
    </div>
    <div id="clientsContainer"></div>
  </div>
</div>

<!-- Create Client Modal -->
<div id="createModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title">Add New Client</h3>
      <button class="modal-close" onclick="hideCreateModal()">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
      </button>
    </div>
    
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Full Name *</label>
        <input type="text" id="clientName" class="form-input" placeholder="John Doe">
      </div>
      <div class="form-group">
        <label class="form-label">Email *</label>
        <input type="email" id="clientEmail" class="form-input" placeholder="john@company.com">
      </div>
      <div class="form-group">
        <label class="form-label">Phone</label>
        <input type="text" id="clientPhone" class="form-input" placeholder="+1 555 0000">
      </div>
      <div class="form-group">
        <label class="form-label">Company</label>
        <input type="text" id="clientCompany" class="form-input" placeholder="Acme Corp">
      </div>
    </div>
    
    <div class="form-group">
      <label class="form-label">Platforms</label>
      <div class="platform-buttons">
        <button type="button" class="platform-btn" data-platform="whatsapp" onclick="togglePlatform('whatsapp')">
          <span>📱</span> WhatsApp
        </button>
        <button type="button" class="platform-btn" data-platform="slack" onclick="togglePlatform('slack')">
          <span>⚡</span> Slack
        </button>
        <button type="button" class="platform-btn" data-platform="email" onclick="togglePlatform('email')">
          <span>📧</span> Email
        </button>
        <button type="button" class="platform-btn" data-platform="telegram" onclick="togglePlatform('telegram')">
          <span>✈️</span> Telegram
        </button>
      </div>
    </div>
    
    <div class="form-group">
      <label class="form-label">Notes</label>
      <textarea id="clientNotes" class="form-textarea" placeholder="Internal notes about this client..."></textarea>
    </div>
    
    <div class="modal-actions">
      <button onclick="createClient()" class="btn-primary" id="createClientBtn">
        <span id="createBtnText">Add Client</span>
      </button>
      <button onclick="hideCreateModal()" class="btn-secondary">Cancel</button>
    </div>
  </div>
</div>

<!-- Client Detail Modal -->
<div id="detailModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div style="display: flex; align-items: center; gap: 16px;">
        <div class="detail-avatar" id="detailAvatar">JD</div>
        <div>
          <h3 class="modal-title" id="detailName">John Doe</h3>
          <div style="font-size: 12px; color: var(--c-muted);" id="detailCompany">Acme Corp</div>
        </div>
      </div>
      <button class="modal-close" onclick="hideDetailModal()">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
      </button>
    </div>
    
    <div class="detail-stats-grid">
      <div class="detail-stat-card">
        <div class="detail-stat-label">Contact</div>
        <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px;">
          <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18l4-4h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"/>
          </svg>
          <span id="detailEmail" style="font-size: 12px;">john@example.com</span>
        </div>
        <div style="display: flex; align-items: center; gap: 8px; margin-top: 8px;" id="detailPhoneContainer">
          <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
          </svg>
          <span id="detailPhone" style="font-size: 12px;"></span>
        </div>
      </div>
      <div class="detail-stat-card">
        <div class="detail-stat-label">Stats</div>
        <div style="display: flex; justify-content: space-between; margin-top: 8px;">
          <span style="font-size: 12px; color: var(--c-muted);">Revenue</span>
          <span class="detail-stat-value" id="detailRevenue" style="font-size: 16px;">$0</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-top: 8px;">
          <span style="font-size: 12px; color: var(--c-muted);">Open Tasks</span>
          <span style="font-size: 14px; font-weight: 600;" id="detailTasks">0</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-top: 8px;">
          <span style="font-size: 12px; color: var(--c-muted);">Conversations</span>
          <span style="font-size: 14px; font-weight: 600;" id="detailConversations">0</span>
        </div>
      </div>
    </div>
    
    <div class="form-group">
      <label class="form-label">Active Platforms</label>
      <div id="detailPlatforms" style="display: flex; gap: 12px;"></div>
    </div>
    
    <div id="detailNotesContainer" style="display: none;">
      <div class="form-group">
        <label class="form-label">Notes</label>
        <div style="background: var(--c-glass); border: 1px solid var(--c-border); border-radius: 12px; padding: 12px; font-size: 12px; color: var(--c-muted);" id="detailNotes"></div>
      </div>
    </div>
    
    <div class="modal-actions">
      <button onclick="hideDetailModal()" class="btn-secondary">Close</button>
    </div>
  </div>
</div>

<script>
// Client Data
let clients = [
  { id: 1, name: 'John Doe', email: 'john@example.com', phone: '+1 555 0123', company: 'Acme Corp', status: 'active', platforms: ['whatsapp', 'email'], total_revenue: 5400, open_tasks: 3, open_conversations: 2, notes: 'Key client, priority support' },
  { id: 2, name: 'Sarah Wilson', email: 'sarah@techstartup.com', phone: '+1 555 0456', company: 'Tech Startup', status: 'active', platforms: ['slack', 'email'], total_revenue: 3200, open_tasks: 1, open_conversations: 4, notes: 'Early stage startup, flexible pricing' },
  { id: 3, name: 'Michael Chen', email: 'mchen@enterprise.com', phone: '', company: 'Enterprise Client', status: 'active', platforms: ['whatsapp', 'slack', 'email'], total_revenue: 12500, open_tasks: 5, open_conversations: 3, notes: 'Large enterprise, requires custom features' },
  { id: 4, name: 'Emily Davis', email: 'emily@design.co', phone: '+1 555 0789', company: 'Design Co', status: 'inactive', platforms: ['email'], total_revenue: 1800, open_tasks: 0, open_conversations: 1, notes: 'Project completed, follow up for new work' },
  { id: 5, name: 'Robert Johnson', email: 'rjohnson@consulting.com', phone: '+1 555 0234', company: 'Consulting Firm', status: 'active', platforms: ['slack', 'telegram'], total_revenue: 7800, open_tasks: 2, open_conversations: 2, notes: 'B2B consulting, high value client' }
];

let filteredClients = [...clients];
let selectedPlatforms = [];

// Render clients
function renderClients() {
  const container = document.getElementById('clientsContainer');
  
  if (filteredClients.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        <svg fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
        </svg>
        <h3>No clients found</h3>
        <p>Try adjusting your search or add a new client</p>
      </div>
    `;
    updateStats();
    return;
  }
  
  container.innerHTML = filteredClients.map((client, i) => `
    <div class="client-row" style="animation-delay: ${i * 30}ms;" onclick="showClientDetail(${client.id})">
      <div class="client-info">
        <div class="client-avatar">${client.name.charAt(0).toUpperCase()}</div>
        <div class="client-details">
          <div class="client-name">${escapeHtml(client.name)}</div>
          ${client.company ? `<div class="client-company">${escapeHtml(client.company)}</div>` : ''}
        </div>
      </div>
      
      <div class="contact-info">
        <div class="contact-email">
          <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18l4-4h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2z"/>
          </svg>
          <span>${escapeHtml(client.email)}</span>
        </div>
        ${client.phone ? `
          <div class="contact-phone">
            <svg fill="currentColor" viewBox="0 0 24 24">
              <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
            </svg>
            <span>${escapeHtml(client.phone)}</span>
          </div>
        ` : ''}
      </div>
      
      <div class="platforms">
        ${client.platforms.map(p => `<span class="platform-icon">${p === 'whatsapp' ? '📱' : p === 'slack' ? '⚡' : p === 'email' ? '📧' : '✈️'}</span>`).join('')}
      </div>
      
      <div class="revenue">$${client.total_revenue.toLocaleString()}</div>
      
      <div class="task-stats">
        <div class="task-stat">
          <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
          </svg>
          ${client.open_tasks}
        </div>
        <div class="task-stat">
          <svg fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/>
          </svg>
          ${client.open_conversations}
        </div>
      </div>
      
      <div>
        <span class="status-badge ${client.status === 'active' ? 'status-active' : 'status-inactive'}">${client.status}</span>
      </div>
      
      <div class="arrow-icon">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
          <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
        </svg>
      </div>
    </div>
  `).join('');
  
  updateStats();
}

// Filter clients
function filterClients() {
  const searchTerm = document.getElementById('searchInput').value.toLowerCase();
  filteredClients = clients.filter(client => 
    !searchTerm ||
    client.name.toLowerCase().includes(searchTerm) ||
    client.email.toLowerCase().includes(searchTerm) ||
    (client.company && client.company.toLowerCase().includes(searchTerm))
  );
  renderClients();
}

// Update stats
function updateStats() {
  const totalRevenue = filteredClients.reduce((sum, client) => sum + client.total_revenue, 0);
  const activeCount = filteredClients.filter(client => client.status === 'active').length;
  const openIssues = filteredClients.reduce((sum, client) => sum + client.open_tasks, 0);
  
  document.getElementById('totalClients').textContent = filteredClients.length;
  document.getElementById('activeCount').textContent = activeCount;
  document.getElementById('totalRevenue').textContent = `$${totalRevenue.toLocaleString()}`;
  document.getElementById('openIssues').textContent = openIssues;
}

// Show create modal
function showCreateModal() {
  document.getElementById('createModal').classList.add('active');
  resetCreateForm();
}

// Hide create modal
function hideCreateModal() {
  document.getElementById('createModal').classList.remove('active');
}

// Reset form
function resetCreateForm() {
  document.getElementById('clientName').value = '';
  document.getElementById('clientEmail').value = '';
  document.getElementById('clientPhone').value = '';
  document.getElementById('clientCompany').value = '';
  document.getElementById('clientNotes').value = '';
  selectedPlatforms = [];
  document.querySelectorAll('.platform-btn').forEach(btn => {
    btn.classList.remove('active');
  });
}

// Toggle platform
function togglePlatform(platform) {
  const btn = document.querySelector(`.platform-btn[data-platform="${platform}"]`);
  const index = selectedPlatforms.indexOf(platform);
  
  if (index > -1) {
    selectedPlatforms.splice(index, 1);
    btn.classList.remove('active');
  } else {
    selectedPlatforms.push(platform);
    btn.classList.add('active');
  }
}

// Create client
function createClient() {
  const name = document.getElementById('clientName').value.trim();
  const email = document.getElementById('clientEmail').value.trim();
  
  if (!name || !email) {
    showToast('Name and email are required', 'error');
    return;
  }
  
  const btn = document.getElementById('createClientBtn');
  const originalText = btn.innerHTML;
  btn.innerHTML = '<svg width="16" height="16" fill="currentColor" class="animate-spin" viewBox="0 0 24 24"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8z"/></svg> Creating...';
  
  setTimeout(() => {
    const newClient = {
      id: clients.length + 1,
      name,
      email,
      phone: document.getElementById('clientPhone').value.trim(),
      company: document.getElementById('clientCompany').value.trim(),
      status: 'active',
      platforms: selectedPlatforms.length > 0 ? [...selectedPlatforms] : ['email'],
      total_revenue: 0,
      open_tasks: 0,
      open_conversations: 0,
      notes: document.getElementById('clientNotes').value.trim()
    };
    
    clients.unshift(newClient);
    filteredClients = [...clients];
    renderClients();
    hideCreateModal();
    showToast('Client added successfully', 'success');
    btn.innerHTML = originalText;
  }, 800);
}

// Show client detail
function showClientDetail(clientId) {
  const client = clients.find(c => c.id === clientId);
  if (!client) return;
  
  document.getElementById('detailAvatar').textContent = client.name.charAt(0).toUpperCase();
  document.getElementById('detailName').textContent = client.name;
  document.getElementById('detailCompany').textContent = client.company || 'No company';
  document.getElementById('detailEmail').textContent = client.email;
  
  if (client.phone) {
    document.getElementById('detailPhoneContainer').style.display = 'flex';
    document.getElementById('detailPhone').textContent = client.phone;
  } else {
    document.getElementById('detailPhoneContainer').style.display = 'none';
  }
  
  document.getElementById('detailRevenue').textContent = `$${client.total_revenue.toLocaleString()}`;
  document.getElementById('detailTasks').textContent = client.open_tasks;
  document.getElementById('detailConversations').textContent = client.open_conversations;
  
  const platformsContainer = document.getElementById('detailPlatforms');
  platformsContainer.innerHTML = client.platforms.map(p => `
    <div style="display: flex; align-items: center; gap: 6px; background: var(--c-glass); padding: 4px 12px; border-radius: 20px;">
      <span>${p === 'whatsapp' ? '📱' : p === 'slack' ? '⚡' : p === 'email' ? '📧' : '✈️'}</span>
      <span style="font-size: 12px;">${p}</span>
    </div>
  `).join('');
  
  if (client.notes) {
    document.getElementById('detailNotesContainer').style.display = 'block';
    document.getElementById('detailNotes').textContent = client.notes;
  } else {
    document.getElementById('detailNotesContainer').style.display = 'none';
  }
  
  document.getElementById('detailModal').classList.add('active');
}

// Hide detail modal
function hideDetailModal() {
  document.getElementById('detailModal').classList.remove('active');
}

// Show toast
function showToast(message, type = 'info') {
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

// Escape HTML
function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

// Initialize
renderClients();
</script>
@endsection