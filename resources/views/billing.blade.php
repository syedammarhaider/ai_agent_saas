{{-- resources/views/billing.blade.php --}}
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

.billing-page {
  font-family: var(--ff-body);
  background: var(--c-bg);
  color: var(--c-text);
  min-height: 100vh;
  padding: 28px;
  position: relative;
  overflow-x: hidden;
}

/* Noise texture */
.billing-page::before {
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
.billing-page::after {
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

.billing-page > * {
  position: relative;
  z-index: 1;
}

/* Stats Grid */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  margin-bottom: 28px;
}

@media (max-width: 768px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }
  .billing-page {
    padding: 20px;
  }
}

.stat-card {
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: 20px;
  padding: 20px;
  transition: all 0.2s;
  animation: slideUp 0.4s var(--ease-out) both;
}

.stat-card:hover {
  border-color: var(--c-border2);
  transform: translateY(-2px);
}

.stat-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 12px;
}

.stat-icon {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.stat-change {
  font-size: 11px;
  font-weight: 600;
  padding: 2px 8px;
  border-radius: 20px;
  background: rgba(0,255,179,0.12);
  color: var(--c-green);
}

.stat-value {
  font-family: var(--ff-display);
  font-size: 28px;
  font-weight: 700;
  color: var(--c-text);
  margin-bottom: 6px;
}

.stat-label {
  font-size: 12px;
  color: var(--c-muted);
  margin-bottom: 4px;
}

.stat-sub {
  font-size: 10px;
  color: var(--c-dimmer);
}

/* Main Layout */
.billing-layout {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 24px;
}

@media (max-width: 1024px) {
  .billing-layout {
    grid-template-columns: 1fr;
    gap: 20px;
  }
}

/* Invoices Section */
.invoices-section {
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: 20px;
  overflow: hidden;
}

.invoices-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px;
  border-bottom: 1px solid var(--c-border);
  flex-wrap: wrap;
  gap: 16px;
}

.invoices-title {
  font-family: var(--ff-display);
  font-size: 14px;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.tabs-group {
  display: flex;
  align-items: center;
  gap: 6px;
  background: var(--c-glass);
  border-radius: 12px;
  padding: 4px;
}

.tab-btn {
  padding: 6px 14px;
  border-radius: 10px;
  font-size: 11px;
  font-weight: 600;
  text-transform: capitalize;
  cursor: pointer;
  transition: all 0.2s;
  background: transparent;
  border: none;
  color: var(--c-muted);
}

.tab-btn.active {
  background: var(--c-cyan);
  color: #05070f;
}

.tab-btn:hover:not(.active) {
  color: var(--c-text);
  background: rgba(255,255,255,0.05);
}

.new-invoice-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  background: var(--c-cyan);
  color: #05070f;
  border: none;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.new-invoice-btn:hover {
  background: #00c4e0;
  transform: translateY(-1px);
}

/* Invoice Table */
.invoice-table-header {
  display: grid;
  grid-template-columns: 1fr 100px 110px 110px 100px;
  gap: 16px;
  padding: 14px 24px;
  font-family: var(--ff-display);
  font-size: 10px;
  letter-spacing: 1px;
  text-transform: uppercase;
  color: var(--c-muted);
  border-bottom: 1px solid var(--c-border);
  background: rgba(0,0,0,0.2);
}

@media (max-width: 640px) {
  .invoice-table-header {
    display: none;
  }
}

.invoice-row {
  display: grid;
  grid-template-columns: 1fr 100px 110px 110px 100px;
  gap: 16px;
  padding: 16px 24px;
  border-bottom: 1px solid var(--c-border);
  cursor: pointer;
  transition: all 0.2s;
  animation: slideUp 0.3s var(--ease-out) both;
}

@media (max-width: 640px) {
  .invoice-row {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 20px;
    position: relative;
  }
}

.invoice-row:hover {
  background: var(--c-glass);
}

.client-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.client-avatar {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  background: linear-gradient(135deg, var(--c-cyan), var(--c-purple));
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 14px;
  flex-shrink: 0;
}

.client-details {
  flex: 1;
  min-width: 0;
}

.client-name {
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 4px;
}

.invoice-due {
  font-size: 10px;
  color: var(--c-muted);
}

.invoice-number {
  font-family: var(--ff-display);
  font-size: 12px;
  color: var(--c-muted);
  display: flex;
  align-items: center;
}

.invoice-amount {
  font-size: 14px;
  font-weight: 700;
  color: var(--c-text);
  display: flex;
  align-items: center;
}

.status-select {
  background: transparent;
  border: none;
  font-size: 11px;
  font-weight: 600;
  color: var(--c-text);
  cursor: pointer;
  padding: 4px 8px;
  border-radius: 8px;
}

.status-select option {
  background: var(--c-surface);
  color: var(--c-text);
}

.invoice-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}

.action-btn {
  padding: 6px;
  border-radius: 8px;
  background: transparent;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.action-btn:hover {
  background: rgba(255,255,255,0.05);
}

/* Right Sidebar */
.sidebar-card {
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: 20px;
  padding: 20px;
  margin-bottom: 20px;
}

.sidebar-title {
  font-family: var(--ff-display);
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.5px;
  margin-bottom: 16px;
}

.chart-placeholder {
  height: 140px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--c-glass);
  border-radius: 12px;
  color: var(--c-muted);
  font-size: 12px;
}

.summary-stats {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.summary-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px solid var(--c-border);
}

.summary-label {
  font-size: 12px;
  color: var(--c-muted);
}

.summary-value {
  font-size: 14px;
  font-weight: 700;
}

.summary-value.paid { color: var(--c-green); }
.summary-value.pending { color: var(--c-amber); }
.summary-value.overdue { color: var(--c-red); }

/* Alert Card */
.alert-card {
  background: rgba(255,77,109,0.05);
  border: 1px solid rgba(255,77,109,0.2);
  border-radius: 16px;
  padding: 16px;
  margin-top: 20px;
}

.alert-header {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 8px;
}

.alert-icon {
  width: 20px;
  height: 20px;
  color: var(--c-red);
}

.alert-title {
  font-size: 12px;
  font-weight: 600;
  color: var(--c-red);
}

.alert-text {
  font-size: 11px;
  color: var(--c-muted);
  margin-bottom: 12px;
}

.alert-btn {
  width: 100%;
  padding: 8px;
  background: rgba(255,255,255,0.05);
  border: 1px solid var(--c-border);
  border-radius: 10px;
  font-size: 11px;
  font-weight: 600;
  color: var(--c-text);
  cursor: pointer;
  transition: all 0.2s;
}

.alert-btn:hover {
  background: rgba(255,255,255,0.1);
  border-color: var(--c-border2);
}

/* Modal */
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
  max-width: 600px;
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
}

.modal-close:hover {
  color: var(--c-text);
}

/* Form Styles */
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

.form-input, .form-select, .form-textarea {
  width: 100%;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--c-border);
  border-radius: 12px;
  padding: 10px 14px;
  font-size: 13px;
  color: var(--c-text);
  transition: all 0.2s;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
  outline: none;
  border-color: var(--c-cyan);
  box-shadow: 0 0 0 3px rgba(0,229,255,0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 80px;
}

.item-row {
  display: grid;
  grid-template-columns: 1fr 80px 100px 32px;
  gap: 12px;
  align-items: center;
  margin-bottom: 12px;
}

.add-item-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  color: var(--c-cyan);
  background: none;
  border: none;
  cursor: pointer;
  margin-top: 8px;
}

.remove-item-btn {
  background: none;
  border: none;
  color: var(--c-muted);
  cursor: pointer;
  padding: 6px;
  border-radius: 6px;
}

.remove-item-btn:hover {
  color: var(--c-red);
  background: rgba(255,77,109,0.1);
}

.total-amount {
  text-align: right;
  font-size: 18px;
  font-weight: 700;
  color: var(--c-cyan);
  margin-top: 16px;
  padding-top: 16px;
  border-top: 1px solid var(--c-border);
}

.modal-actions {
  display: flex;
  gap: 12px;
  margin-top: 24px;
}

.btn-primary {
  flex: 1;
  padding: 10px;
  background: var(--c-cyan);
  color: #05070f;
  border: none;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.btn-primary:hover {
  background: #00c4e0;
  transform: translateY(-1px);
}

.btn-secondary {
  flex: 1;
  padding: 10px;
  background: rgba(255,255,255,0.05);
  border: 1px solid var(--c-border);
  border-radius: 12px;
  font-size: 13px;
  font-weight: 600;
  color: var(--c-text);
  cursor: pointer;
  transition: all 0.2s;
}

.btn-secondary:hover {
  background: rgba(255,255,255,0.1);
  border-color: var(--c-border2);
}

/* Detail Modal */
.detail-items {
  margin: 16px 0;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  padding: 12px 0;
  border-bottom: 1px solid var(--c-border);
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-item-desc {
  font-size: 13px;
}

.detail-item-price {
  font-size: 13px;
  font-weight: 600;
}

.detail-total {
  display: flex;
  justify-content: space-between;
  padding-top: 16px;
  margin-top: 16px;
  border-top: 1px solid var(--c-border);
}

.detail-total-label {
  font-size: 14px;
  font-weight: 600;
}

.detail-total-value {
  font-size: 20px;
  font-weight: 700;
  color: var(--c-cyan);
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

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.animate-spin {
  animation: spin 1s linear infinite;
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

<div class="billing-page">
  <!-- Stats Cards -->
  <div class="stats-grid">
    <div class="stat-card" style="animation-delay: 0ms;">
      <div class="stat-header">
        <div class="stat-icon" style="background: rgba(255,176,32,0.1);">
          <svg width="20" height="20" fill="currentColor" style="color: var(--c-amber);" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
          </svg>
        </div>
        <span class="stat-change">+22%</span>
      </div>
      <div class="stat-value" id="revenueThisMonth">$4,280</div>
      <div class="stat-label">Revenue This Month</div>
      <div class="stat-sub">+22% vs last month</div>
    </div>

    <div class="stat-card" style="animation-delay: 50ms;">
      <div class="stat-header">
        <div class="stat-icon" style="background: rgba(0,229,255,0.1);">
          <svg width="20" height="20" fill="currentColor" style="color: var(--c-cyan);" viewBox="0 0 24 24">
            <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
          </svg>
        </div>
        <span class="stat-change">+15%</span>
      </div>
      <div class="stat-value" id="totalCollected">$8,950</div>
      <div class="stat-label">Total Collected</div>
    </div>

    <div class="stat-card" style="animation-delay: 100ms;">
      <div class="stat-header">
        <div class="stat-icon" style="background: rgba(255,255,255,0.05);">
          <svg width="20" height="20" fill="currentColor" style="color: var(--c-muted);" viewBox="0 0 24 24">
            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
          </svg>
        </div>
      </div>
      <div class="stat-value" id="pendingAmount">$1,240</div>
      <div class="stat-label">Pending Payment</div>
      <div class="stat-sub">3 invoices awaiting</div>
    </div>

    <div class="stat-card" style="animation-delay: 150ms;">
      <div class="stat-header">
        <div class="stat-icon" style="background: rgba(255,77,109,0.1);">
          <svg width="20" height="20" fill="currentColor" style="color: var(--c-red);" viewBox="0 0 24 24">
            <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
          </svg>
        </div>
      </div>
      <div class="stat-value" id="overdueAmount">$0</div>
      <div class="stat-label">Overdue</div>
      <div class="stat-sub">None overdue</div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="billing-layout">
    <!-- Invoices Table -->
    <div class="invoices-section">
      <div class="invoices-header">
        <h3 class="invoices-title">Invoices</h3>
        <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
          <div class="tabs-group">
            <button onclick="setActiveTab('all')" class="tab-btn active">All</button>
            <button onclick="setActiveTab('draft')" class="tab-btn">Draft</button>
            <button onclick="setActiveTab('sent')" class="tab-btn">Sent</button>
            <button onclick="setActiveTab('paid')" class="tab-btn">Paid</button>
            <button onclick="setActiveTab('overdue')" class="tab-btn">Overdue</button>
          </div>
          <button onclick="showCreateModal()" class="new-invoice-btn">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
              <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
            </svg>
            New Invoice
          </button>
        </div>
      </div>

      <div class="invoice-table-header">
        <span>Client</span>
        <span>Invoice #</span>
        <span>Amount</span>
        <span>Status</span>
        <span>Actions</span>
      </div>
      <div id="invoicesContainer"></div>
    </div>

    <!-- Right Sidebar -->
    <div>
      <div class="sidebar-card">
        <h4 class="sidebar-title">Revenue Trend</h4>
        <p class="stat-sub" style="margin-bottom: 16px;">Last 7 days</p>
        <div class="chart-placeholder">
          <div style="text-align: center;">
            <svg width="48" height="48" fill="currentColor" class="opacity-50" viewBox="0 0 24 24">
              <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            <p class="stat-sub" style="margin-top: 8px;">Chart visualization</p>
          </div>
        </div>
      </div>

      <div class="sidebar-card">
        <h4 class="sidebar-title">Summary</h4>
        <div class="summary-stats">
          <div class="summary-item">
            <span class="summary-label">Total Invoices</span>
            <span class="summary-value" id="totalInvoices">8</span>
          </div>
          <div class="summary-item">
            <span class="summary-label">Paid</span>
            <span class="summary-value paid" id="paidInvoices">5</span>
          </div>
          <div class="summary-item">
            <span class="summary-label">Pending</span>
            <span class="summary-value pending" id="pendingInvoices">3</span>
          </div>
          <div class="summary-item">
            <span class="summary-label">Overdue</span>
            <span class="summary-value overdue" id="overdueInvoices">0</span>
          </div>
          <div class="summary-item" style="border-bottom: none;">
            <span class="summary-label">Draft</span>
            <span class="summary-value" id="draftInvoices">0</span>
          </div>
        </div>
      </div>

      <div id="overdueAlert" class="alert-card" style="display: none;">
        <div class="alert-header">
          <svg class="alert-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
          </svg>
          <span class="alert-title">Overdue Alert</span>
        </div>
        <p class="alert-text" id="overdueAlertText">$0 overdue. AI agent has sent automatic follow-up reminders.</p>
        <button onclick="sendReminder()" class="alert-btn">Send AI Reminder</button>
      </div>
    </div>
  </div>
</div>

<!-- Create Invoice Modal -->
<div id="createModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title">Create New Invoice</h3>
      <button class="modal-close" onclick="hideCreateModal()">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
      </button>
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Client *</label>
        <select id="invoiceClient" class="form-select">
          <option value="">Select client...</option>
          <option value="1">John Doe - Acme Corp</option>
          <option value="2">Sarah Wilson - Tech Startup</option>
          <option value="3">Michael Chen - Enterprise Client</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Due Date *</label>
        <input type="date" id="invoiceDueDate" class="form-input">
      </div>
    </div>

    <div class="form-group">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
        <label class="form-label">Line Items</label>
        <button onclick="addInvoiceItem()" class="add-item-btn">
          <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
          </svg>
          Add Item
        </button>
      </div>
      <div id="invoiceItems"></div>
      <div class="total-amount">Total: $<span id="invoiceTotal">0</span></div>
    </div>

    <div class="form-group">
      <label class="form-label">Notes (Optional)</label>
      <textarea id="invoiceNotes" class="form-textarea" placeholder="Additional notes..."></textarea>
    </div>

    <div class="modal-actions">
      <button onclick="createInvoice()" class="btn-primary" id="createInvoiceBtn">
        <span id="createBtnText">Create Invoice</span>
      </button>
      <button onclick="hideCreateModal()" class="btn-secondary">Cancel</button>
    </div>
  </div>
</div>

<!-- Invoice Detail Modal -->
<div id="detailModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <div>
        <h3 class="modal-title" id="detailNumber">INV-001</h3>
        <span id="detailClient" class="stat-sub">Client Name</span>
      </div>
      <div style="display: flex; align-items: center; gap: 12px;">
        <span id="detailStatus" class="stat-change" style="background: var(--c-cyan-dim); color: var(--c-cyan);">Status</span>
        <button class="modal-close" onclick="hideDetailModal()">
          <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
          </svg>
        </button>
      </div>
    </div>

    <div id="detailItems" class="detail-items"></div>

    <div class="detail-total">
      <span class="detail-total-label">Total</span>
      <span class="detail-total-value" id="detailAmount">$0</span>
    </div>

    <div id="detailNotesContainer" style="display: none; margin-top: 16px;">
      <div class="form-group">
        <label class="form-label">Notes</label>
        <div style="background: var(--c-glass); border-radius: 12px; padding: 12px; font-size: 12px;" id="detailNotes"></div>
      </div>
    </div>

    <div id="detailActions" class="modal-actions" style="margin-top: 24px;"></div>
  </div>
</div>

<script>
// Invoice Data
let invoices = [
  { id: 1, number: 'INV-001', client_name: 'John Doe', amount: 1200, status: 'paid', due_date: '2024-01-15', created_at: '2024-01-01', items: [{ description: 'Development Services', quantity: 1, unit_price: 1200, total: 1200 }], notes: null },
  { id: 2, number: 'INV-002', client_name: 'Sarah Wilson', amount: 800, status: 'paid', due_date: '2024-01-20', created_at: '2024-01-05', items: [{ description: 'Consulting', quantity: 8, unit_price: 100, total: 800 }], notes: 'Monthly consulting retainer' },
  { id: 3, number: 'INV-003', client_name: 'Michael Chen', amount: 2500, status: 'sent', due_date: '2024-02-01', created_at: '2024-01-10', items: [{ description: 'Custom Development', quantity: 1, unit_price: 2500, total: 2500 }], notes: null },
  { id: 4, number: 'INV-004', client_name: 'Emily Davis', amount: 600, status: 'paid', due_date: '2024-01-25', created_at: '2024-01-12', items: [{ description: 'Design Services', quantity: 1, unit_price: 600, total: 600 }], notes: null },
  { id: 5, number: 'INV-005', client_name: 'Robert Johnson', amount: 1800, status: 'sent', due_date: '2024-02-05', created_at: '2024-01-15', items: [{ description: 'Support Package', quantity: 1, unit_price: 1800, total: 1800 }], notes: 'Quarterly support package' },
  { id: 6, number: 'INV-006', client_name: 'John Doe', amount: 450, status: 'sent', due_date: '2024-02-10', created_at: '2024-01-18', items: [{ description: 'Additional Features', quantity: 1, unit_price: 450, total: 450 }], notes: null },
  { id: 7, number: 'INV-007', client_name: 'Sarah Wilson', amount: 320, status: 'paid', due_date: '2024-01-30', created_at: '2024-01-20', items: [{ description: 'Training Session', quantity: 2, unit_price: 160, total: 320 }], notes: null },
  { id: 8, number: 'INV-008', client_name: 'Michael Chen', amount: 1500, status: 'paid', due_date: '2024-02-15', created_at: '2024-01-22', items: [{ description: 'Maintenance Contract', quantity: 1, unit_price: 1500, total: 1500 }], notes: 'Annual maintenance' }
];

let activeTab = 'all';
let invoiceItems = [{ description: '', quantity: 1, unit_price: 0 }];
let selectedInvoice = null;

const STATUS_CONFIG = {
  draft: { class: '', label: 'Draft' },
  sent: { class: '', label: 'Sent' },
  paid: { class: '', label: 'Paid' },
  overdue: { class: '', label: 'Overdue' }
};

function setActiveTab(tab) {
  activeTab = tab;
  document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.classList.remove('active');
  });
  event.target.classList.add('active');
  renderInvoices();
  updateStats();
}

function renderInvoices() {
  const container = document.getElementById('invoicesContainer');
  const filtered = activeTab === 'all' ? invoices : invoices.filter(i => i.status === activeTab);
  
  if (filtered.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        <svg fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
        </svg>
        <h3>No invoices found</h3>
        <p>Create a new invoice to get started</p>
      </div>
    `;
    return;
  }
  
  container.innerHTML = filtered.map((invoice, i) => `
    <div class="invoice-row" style="animation-delay: ${i * 50}ms;" onclick="showInvoiceDetail(${invoice.id})">
      <div class="client-info">
        <div class="client-avatar">${invoice.client_name.charAt(0).toUpperCase()}</div>
        <div class="client-details">
          <div class="client-name">${escapeHtml(invoice.client_name)}</div>
          <div class="invoice-due">Due ${new Date(invoice.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}</div>
        </div>
      </div>
      <div class="invoice-number">${invoice.number}</div>
      <div class="invoice-amount">$${invoice.amount.toLocaleString()}</div>
      <div>
        <select onchange="updateInvoiceStatus(${invoice.id}, this.value)" onclick="event.stopPropagation()" class="status-select">
          <option value="draft" ${invoice.status === 'draft' ? 'selected' : ''}>Draft</option>
          <option value="sent" ${invoice.status === 'sent' ? 'selected' : ''}>Sent</option>
          <option value="paid" ${invoice.status === 'paid' ? 'selected' : ''}>Paid</option>
          <option value="overdue" ${invoice.status === 'overdue' ? 'selected' : ''}>Overdue</option>
        </select>
      </div>
      <div class="invoice-actions">
        <button onclick="event.stopPropagation(); showInvoiceDetail(${invoice.id})" class="action-btn" title="View Details">
          <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5z"/>
          </svg>
        </button>
        ${invoice.status === 'draft' ? `
          <button onclick="event.stopPropagation(); sendInvoice(${invoice.id})" class="action-btn" title="Send via AI">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
              <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
            </svg>
          </button>
        ` : ''}
        ${invoice.status === 'sent' ? `
          <button onclick="event.stopPropagation(); markAsPaid(${invoice.id})" class="action-btn" title="Mark as Paid">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
              <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
          </button>
        ` : ''}
      </div>
    </div>
  `).join('');
}

function updateStats() {
  const totalRevenue = invoices.filter(i => i.status === 'paid').reduce((sum, i) => sum + i.amount, 0);
  const pendingAmount = invoices.filter(i => i.status === 'sent').reduce((sum, i) => sum + i.amount, 0);
  const overdueAmount = invoices.filter(i => i.status === 'overdue').reduce((sum, i) => sum + i.amount, 0);
  
  document.getElementById('revenueThisMonth').textContent = `$${totalRevenue.toLocaleString()}`;
  document.getElementById('totalCollected').textContent = `$${totalRevenue.toLocaleString()}`;
  document.getElementById('pendingAmount').textContent = `$${pendingAmount.toLocaleString()}`;
  document.getElementById('overdueAmount').textContent = `$${overdueAmount.toLocaleString()}`;
  
  document.getElementById('totalInvoices').textContent = invoices.length;
  document.getElementById('paidInvoices').textContent = invoices.filter(i => i.status === 'paid').length;
  document.getElementById('pendingInvoices').textContent = invoices.filter(i => i.status === 'sent').length;
  document.getElementById('overdueInvoices').textContent = invoices.filter(i => i.status === 'overdue').length;
  document.getElementById('draftInvoices').textContent = invoices.filter(i => i.status === 'draft').length;
  
  const overdueAlert = document.getElementById('overdueAlert');
  if (overdueAmount > 0) {
    overdueAlert.style.display = 'block';
    document.getElementById('overdueAlertText').textContent = `$${overdueAmount.toLocaleString()} overdue. AI agent has sent automatic follow-up reminders.`;
  } else {
    overdueAlert.style.display = 'none';
  }
}

function updateInvoiceStatus(invoiceId, status) {
  const invoice = invoices.find(i => i.id === invoiceId);
  if (invoice) {
    invoice.status = status;
    renderInvoices();
    updateStats();
    showToast(`Invoice marked as ${status}`, 'success');
  }
}

function sendInvoice(invoiceId) {
  const invoice = invoices.find(i => i.id === invoiceId);
  if (invoice) {
    invoice.status = 'sent';
    renderInvoices();
    updateStats();
    showToast(`Invoice ${invoice.number} sent! AI message: "Your invoice is ready for payment."`, 'success');
  }
}

function markAsPaid(invoiceId) {
  const invoice = invoices.find(i => i.id === invoiceId);
  if (invoice) {
    invoice.status = 'paid';
    renderInvoices();
    updateStats();
    showToast('Invoice marked as paid', 'success');
  }
}

function showCreateModal() {
  document.getElementById('createModal').classList.add('active');
  resetCreateForm();
}

function hideCreateModal() {
  document.getElementById('createModal').classList.remove('active');
}

function resetCreateForm() {
  document.getElementById('invoiceClient').value = '';
  document.getElementById('invoiceDueDate').value = '';
  document.getElementById('invoiceNotes').value = '';
  invoiceItems = [{ description: '', quantity: 1, unit_price: 0 }];
  renderInvoiceItems();
  updateInvoiceTotal();
}

function addInvoiceItem() {
  invoiceItems.push({ description: '', quantity: 1, unit_price: 0 });
  renderInvoiceItems();
}

function removeInvoiceItem(index) {
  if (invoiceItems.length > 1) {
    invoiceItems.splice(index, 1);
    renderInvoiceItems();
  }
}

function renderInvoiceItems() {
  const container = document.getElementById('invoiceItems');
  container.innerHTML = invoiceItems.map((item, i) => `
    <div class="item-row">
      <input type="text" class="form-input" placeholder="Description" value="${escapeHtml(item.description)}" onchange="updateInvoiceItem(${i}, 'description', this.value)">
      <input type="number" class="form-input" placeholder="Qty" min="1" value="${item.quantity}" onchange="updateInvoiceItem(${i}, 'quantity', Number(this.value))">
      <input type="number" class="form-input" placeholder="Price" min="0" value="${item.unit_price}" onchange="updateInvoiceItem(${i}, 'unit_price', Number(this.value))">
      <button onclick="removeInvoiceItem(${i})" class="remove-item-btn">
        <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
      </button>
    </div>
  `).join('');
}

function updateInvoiceItem(index, field, value) {
  invoiceItems[index][field] = value;
  updateInvoiceTotal();
}

function updateInvoiceTotal() {
  const total = invoiceItems.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
  document.getElementById('invoiceTotal').textContent = total.toLocaleString();
}

function createInvoice() {
  const clientId = document.getElementById('invoiceClient').value;
  const dueDate = document.getElementById('invoiceDueDate').value;
  
  if (!clientId || !dueDate) {
    showToast('Client and due date are required', 'error');
    return;
  }
  
  const hasValidItems = invoiceItems.some(item => item.description.trim());
  if (!hasValidItems) {
    showToast('At least one item with description is required', 'error');
    return;
  }
  
  const btn = document.getElementById('createInvoiceBtn');
  const originalText = btn.innerHTML;
  btn.innerHTML = '<svg width="16" height="16" fill="currentColor" class="animate-spin" viewBox="0 0 24 24"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8z"/></svg> Creating...';
  
  setTimeout(() => {
    const total = invoiceItems.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0);
    const newInvoice = {
      id: invoices.length + 1,
      number: `INV-${String(invoices.length + 1).padStart(3, '0')}`,
      client_name: 'New Client',
      amount: total,
      status: 'draft',
      due_date: dueDate,
      created_at: new Date().toISOString().split('T')[0],
      items: invoiceItems.filter(item => item.description.trim()),
      notes: document.getElementById('invoiceNotes').value.trim() || null
    };
    
    invoices.unshift(newInvoice);
    renderInvoices();
    updateStats();
    hideCreateModal();
    showToast('Invoice created successfully', 'success');
    btn.innerHTML = originalText;
  }, 800);
}

function showInvoiceDetail(invoiceId) {
  selectedInvoice = invoices.find(i => i.id === invoiceId);
  if (!selectedInvoice) return;
  
  document.getElementById('detailNumber').textContent = selectedInvoice.number;
  document.getElementById('detailClient').textContent = selectedInvoice.client_name;
  document.getElementById('detailStatus').textContent = selectedInvoice.status.toUpperCase();
  document.getElementById('detailStatus').style.background = selectedInvoice.status === 'paid' ? 'rgba(0,255,179,0.12)' : selectedInvoice.status === 'sent' ? 'rgba(0,229,255,0.12)' : 'rgba(255,176,32,0.12)';
  document.getElementById('detailStatus').style.color = selectedInvoice.status === 'paid' ? 'var(--c-green)' : selectedInvoice.status === 'sent' ? 'var(--c-cyan)' : 'var(--c-amber)';
  
  const itemsContainer = document.getElementById('detailItems');
  itemsContainer.innerHTML = selectedInvoice.items.map(item => `
    <div class="detail-item">
      <div>
        <div class="detail-item-desc">${escapeHtml(item.description)}</div>
        <div class="stat-sub">${item.quantity} × $${item.unit_price.toLocaleString()}</div>
      </div>
      <div class="detail-item-price">$${item.total.toLocaleString()}</div>
    </div>
  `).join('');
  
  document.getElementById('detailAmount').textContent = `$${selectedInvoice.amount.toLocaleString()}`;
  
  const notesContainer = document.getElementById('detailNotesContainer');
  if (selectedInvoice.notes) {
    notesContainer.style.display = 'block';
    document.getElementById('detailNotes').textContent = selectedInvoice.notes;
  } else {
    notesContainer.style.display = 'none';
  }
  
  const actionsContainer = document.getElementById('detailActions');
  if (selectedInvoice.status === 'draft') {
    actionsContainer.innerHTML = `
      <button onclick="sendInvoice(${selectedInvoice.id}); hideDetailModal();" class="btn-primary">Send via AI</button>
      <button onclick="hideDetailModal()" class="btn-secondary">Close</button>
    `;
  } else {
    actionsContainer.innerHTML = `<button onclick="hideDetailModal()" class="btn-secondary">Close</button>`;
  }
  
  document.getElementById('detailModal').classList.add('active');
}

function hideDetailModal() {
  document.getElementById('detailModal').classList.remove('active');
  selectedInvoice = null;
}

function sendReminder() {
  showToast('AI reminders sent to all overdue clients!', 'success');
}

function showToast(message, type = 'info') {
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}

// Initialize
renderInvoices();
renderInvoiceItems();
updateStats();
</script>
@endsection