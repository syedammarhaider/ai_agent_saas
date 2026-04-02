{{-- resources/views/settings.blade.php --}}
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

.settings-page {
  font-family: var(--ff-body);
  background: var(--c-bg);
  color: var(--c-text);
  min-height: 100vh;
  padding: 28px;
  position: relative;
  overflow-x: hidden;
}

.settings-page::before {
  content: '';
  position: fixed;
  inset: 0;
  z-index: 0;
  pointer-events: none;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
  background-size: 180px;
  opacity: 0.7;
}

.settings-page::after {
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

.settings-page > * {
  position: relative;
  z-index: 1;
}

/* Settings Layout */
.settings-layout {
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 24px;
}

@media (max-width: 768px) {
  .settings-layout {
    grid-template-columns: 1fr;
  }
  .settings-page {
    padding: 20px;
  }
}

/* Sidebar Navigation */
.settings-sidebar {
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: 20px;
  padding: 20px;
  height: fit-content;
  position: sticky;
  top: 24px;
}

.settings-nav {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  border-radius: 14px;
  cursor: pointer;
  transition: all 0.2s;
  color: var(--c-muted);
}

.nav-item:hover {
  background: var(--c-glass);
  color: var(--c-text);
}

.nav-item.active {
  background: var(--c-cyan-dim);
  color: var(--c-cyan);
  border: 1px solid rgba(0,229,255,0.2);
}

.nav-icon {
  width: 20px;
  height: 20px;
}

.nav-label {
  font-size: 13px;
  font-weight: 500;
}

/* Main Content */
.settings-main {
  background: var(--c-surface);
  border: 1px solid var(--c-border);
  border-radius: 20px;
  padding: 28px;
}

.settings-section {
  display: none;
  animation: fadeIn 0.3s var(--ease-out);
}

.settings-section.active {
  display: block;
}

.section-header {
  margin-bottom: 28px;
  padding-bottom: 20px;
  border-bottom: 1px solid var(--c-border);
}

.section-title {
  font-family: var(--ff-display);
  font-size: 20px;
  font-weight: 700;
  margin-bottom: 8px;
}

.section-desc {
  font-size: 13px;
  color: var(--c-muted);
}

/* Cards */
.settings-card {
  background: var(--c-glass);
  border: 1px solid var(--c-border);
  border-radius: 16px;
  padding: 20px;
  margin-bottom: 20px;
}

.card-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
  flex-wrap: wrap;
  gap: 12px;
}

.card-title {
  font-size: 15px;
  font-weight: 600;
}

.card-desc {
  font-size: 12px;
  color: var(--c-muted);
  margin-top: 4px;
}

/* Forms */
.form-group {
  margin-bottom: 20px;
}

.form-label {
  font-size: 12px;
  font-weight: 600;
  color: var(--c-muted);
  margin-bottom: 8px;
  display: block;
}

.form-input, .form-select, .form-textarea {
  width: 100%;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--c-border);
  border-radius: 12px;
  padding: 12px 16px;
  font-size: 13px;
  color: var(--c-text);
  transition: all 0.2s;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
  outline: none;
  border-color: var(--c-cyan);
  box-shadow: 0 0 0 3px rgba(0,229,255,0.1);
}

/* Toggle Switch */
.toggle-switch {
  position: relative;
  display: inline-block;
  width: 48px;
  height: 24px;
}

.toggle-input {
  opacity: 0;
  width: 0;
  height: 0;
}

.toggle-slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: var(--c-border);
  transition: 0.3s;
  border-radius: 24px;
}

.toggle-slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: 0.3s;
  border-radius: 50%;
}

.toggle-input:checked + .toggle-slider {
  background-color: var(--c-cyan);
}

.toggle-input:checked + .toggle-slider:before {
  transform: translateX(24px);
}

/* Avatar Upload */
.avatar-section {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-bottom: 24px;
  flex-wrap: wrap;
}

.avatar-preview {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--c-cyan), var(--c-purple));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 32px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
}

.avatar-preview:hover {
  transform: scale(1.05);
}

.avatar-upload-btn {
  padding: 8px 16px;
  background: var(--c-glass);
  border: 1px solid var(--c-border);
  border-radius: 10px;
  font-size: 12px;
  cursor: pointer;
  transition: all 0.2s;
}

.avatar-upload-btn:hover {
  border-color: var(--c-border2);
  background: rgba(255,255,255,0.05);
}

/* API Keys */
.api-key-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 16px;
  background: var(--c-glass);
  border: 1px solid var(--c-border);
  border-radius: 12px;
  margin-bottom: 12px;
  flex-wrap: wrap;
  gap: 12px;
}

.api-key-info {
  flex: 1;
}

.api-key-name {
  font-size: 13px;
  font-weight: 600;
  margin-bottom: 4px;
}

.api-key-date {
  font-size: 10px;
  color: var(--c-muted);
}

.api-key-value {
  font-family: var(--ff-display);
  font-size: 11px;
  color: var(--c-cyan);
  background: var(--c-surface);
  padding: 4px 8px;
  border-radius: 6px;
  margin-top: 6px;
  display: inline-block;
}

.api-key-actions {
  display: flex;
  gap: 8px;
}

/* Integration Cards Grid */
.integrations-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 16px;
}

.integration-card {
  background: var(--c-glass);
  border: 1px solid var(--c-border);
  border-radius: 16px;
  padding: 16px;
  transition: all 0.2s;
}

.integration-card:hover {
  border-color: var(--c-border2);
  transform: translateY(-2px);
}

.integration-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 12px;
}

.integration-icon {
  width: 48px;
  height: 48px;
  border-radius: 12px;
  background: var(--c-surface);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
}

.integration-status {
  font-size: 11px;
  padding: 4px 10px;
  border-radius: 20px;
  font-weight: 600;
}

.status-connected {
  background: rgba(0,255,179,0.12);
  color: var(--c-green);
}

.status-disconnected {
  background: rgba(255,255,255,0.05);
  color: var(--c-muted);
}

.integration-name {
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 4px;
}

.integration-desc {
  font-size: 11px;
  color: var(--c-muted);
  margin-bottom: 12px;
}

.integration-webhook {
  background: var(--c-surface);
  border-radius: 8px;
  padding: 8px;
  font-size: 10px;
  font-family: monospace;
  margin: 12px 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

/* Danger Zone */
.danger-zone {
  border: 1px solid rgba(255,77,109,0.3);
  background: rgba(255,77,109,0.03);
  border-radius: 16px;
  padding: 20px;
  margin-top: 20px;
}

.danger-title {
  color: var(--c-red);
  font-size: 14px;
  font-weight: 600;
  margin-bottom: 8px;
}

.danger-desc {
  font-size: 12px;
  color: var(--c-muted);
  margin-bottom: 16px;
}

/* Buttons */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  border-radius: 10px;
  font-size: 12px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
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
  background: var(--c-glass);
  border: 1px solid var(--c-border);
  color: var(--c-text);
}

.btn-secondary:hover {
  border-color: var(--c-border2);
  background: rgba(255,255,255,0.05);
}

.btn-danger {
  background: rgba(255,77,109,0.1);
  border: 1px solid rgba(255,77,109,0.3);
  color: var(--c-red);
}

.btn-danger:hover {
  background: rgba(255,77,109,0.2);
}

.btn-block {
  width: 100%;
  justify-content: center;
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
  max-width: 480px;
  width: 90%;
  animation: fadeIn 0.3s var(--ease-out);
}

.modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

.modal-title {
  font-size: 18px;
  font-weight: 700;
}

.modal-close {
  background: none;
  border: none;
  color: var(--c-muted);
  cursor: pointer;
}

.modal-actions {
  display: flex;
  gap: 12px;
  margin-top: 24px;
}

/* Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
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
  animation: fadeIn 0.3s var(--ease-out);
  background: var(--c-cyan);
  color: #05070f;
}

.toast-success {
  background: var(--c-green);
}

.toast-error {
  background: var(--c-red);
  color: white;
}

/* Logout Button */
.logout-btn {
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px solid var(--c-border);
}

/* Activity Log */
.activity-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  border-bottom: 1px solid var(--c-border);
}

.activity-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  background: var(--c-cyan-dim);
  display: flex;
  align-items: center;
  justify-content: center;
}

.activity-content {
  flex: 1;
}

.activity-text {
  font-size: 12px;
  margin-bottom: 4px;
}

.activity-time {
  font-size: 10px;
  color: var(--c-muted);
}
</style>

<div class="settings-page">
  <div class="settings-layout">
    <!-- Sidebar Navigation -->
    <div class="settings-sidebar">
      <div class="settings-nav">
        <div class="nav-item active" data-section="profile">
          <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
          <span class="nav-label">Profile</span>
        </div>
        <div class="nav-item" data-section="account">
          <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/>
          </svg>
          <span class="nav-label">Security</span>
        </div>
        <div class="nav-item" data-section="integrations">
          <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58z"/>
          </svg>
          <span class="nav-label">Integrations</span>
        </div>
        <div class="nav-item" data-section="notifications">
          <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.93 6 11v5l-2 2v1h16v-1l-2-2z"/>
          </svg>
          <span class="nav-label">Notifications</span>
        </div>
        <div class="nav-item" data-section="api">
          <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
          <span class="nav-label">API Keys</span>
        </div>
        <div class="nav-item" data-section="billing">
          <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
          </svg>
          <span class="nav-label">Billing</span>
        </div>
        <div class="nav-item" data-section="activity">
          <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
          </svg>
          <span class="nav-label">Activity Log</span>
        </div>
        <div class="nav-item" data-section="danger">
          <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
          </svg>
          <span class="nav-label">Danger Zone</span>
        </div>
      </div>
      
      <!-- Logout Button -->
      <div class="logout-btn">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn btn-danger btn-block">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
            </svg>
            Logout
          </button>
        </form>
      </div>
    </div>

    <!-- Main Content -->
    <div class="settings-main">
      <!-- Profile Section -->
      <div id="profile-section" class="settings-section active">
        <div class="section-header">
          <h2 class="section-title">Profile Settings</h2>
          <p class="section-desc">Manage your personal information and preferences</p>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">Profile Picture</h3>
              <p class="card-desc">Update your avatar</p>
            </div>
          </div>
          <div class="avatar-section">
            <div class="avatar-preview" id="avatarPreview">JD</div>
            <div>
              <button onclick="triggerAvatarUpload()" class="avatar-upload-btn">Upload New</button>
              <input type="file" id="avatarUpload" style="display: none;" accept="image/*" onchange="uploadAvatar(this)">
              <p class="card-desc" style="margin-top: 8px;">JPG, PNG or GIF. Max 2MB.</p>
            </div>
          </div>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">Personal Information</h3>
              <p class="card-desc">Update your personal details</p>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-input" id="fullName" value="{{ Auth::user()->name ?? 'John Doe' }}" placeholder="Enter your full name">
          </div>
          <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" class="form-input" id="email" value="{{ Auth::user()->email ?? 'john@example.com' }}" placeholder="Enter your email">
          </div>
          <div class="form-group">
            <label class="form-label">Company</label>
            <input type="text" class="form-input" id="company" value="Acme Corporation" placeholder="Enter your company">
          </div>
          <div class="form-group">
            <label class="form-label">Job Title</label>
            <input type="text" class="form-input" id="jobTitle" value="Product Manager" placeholder="Enter your job title">
          </div>
          <div class="form-group">
            <label class="form-label">Timezone</label>
            <select class="form-select" id="timezone">
              <option value="UTC">UTC</option>
              <option value="America/New_York">America/New York</option>
              <option value="America/Los_Angeles">America/Los Angeles</option>
              <option value="Europe/London">Europe/London</option>
              <option value="Asia/Dubai">Asia/Dubai</option>
              <option value="Asia/Tokyo">Asia/Tokyo</option>
            </select>
          </div>
          <button onclick="saveProfile()" class="btn btn-primary">Save Changes</button>
        </div>
      </div>

      <!-- Account Security Section -->
      <div id="account-section" class="settings-section">
        <div class="section-header">
          <h2 class="section-title">Security Settings</h2>
          <p class="section-desc">Manage your password and security preferences</p>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">Change Password</h3>
              <p class="card-desc">Update your password to keep your account secure</p>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Current Password</label>
            <input type="password" class="form-input" id="currentPassword" placeholder="Enter current password">
          </div>
          <div class="form-group">
            <label class="form-label">New Password</label>
            <input type="password" class="form-input" id="newPassword" placeholder="Enter new password">
            <div class="password-strength" id="passwordStrength" style="margin-top: 8px;"></div>
          </div>
          <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <input type="password" class="form-input" id="confirmPassword" placeholder="Confirm new password">
          </div>
          <button onclick="changePassword()" class="btn btn-primary">Update Password</button>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">Two-Factor Authentication</h3>
              <p class="card-desc">Add an extra layer of security to your account</p>
            </div>
          </div>
          <div style="display: flex; align-items: center; justify-content: space-between;">
            <div>
              <div style="font-size: 13px; margin-bottom: 4px;">2FA Status: <span id="twoFactorStatus" style="color: var(--c-muted);">Disabled</span></div>
              <div style="font-size: 11px; color: var(--c-muted);">Protect your account with two-factor authentication</div>
            </div>
            <button onclick="enable2FA()" class="btn btn-secondary">Enable 2FA</button>
          </div>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">Active Sessions</h3>
              <p class="card-desc">Manage your active sessions across devices</p>
            </div>
          </div>
          <div id="sessionsList">
            <div class="api-key-item">
              <div class="api-key-info">
                <div class="api-key-name">Chrome on Windows</div>
                <div class="api-key-date">Active now · IP: 192.168.1.1 · Location: New York, US</div>
              </div>
              <button onclick="terminateSession(this)" class="btn-secondary" style="padding: 4px 12px;">Terminate</button>
            </div>
            <div class="api-key-item">
              <div class="api-key-info">
                <div class="api-key-name">Safari on iPhone</div>
                <div class="api-key-date">Last active: 2 hours ago · IP: 10.0.0.1 · Location: Los Angeles, US</div>
              </div>
              <button onclick="terminateSession(this)" class="btn-secondary" style="padding: 4px 12px;">Terminate</button>
            </div>
          </div>
          <button onclick="terminateAllSessions()" class="btn-danger" style="margin-top: 12px;">Terminate All Other Sessions</button>
        </div>
      </div>

      <!-- Integrations Section -->
      <div id="integrations-section" class="settings-section">
        <div class="section-header">
          <h2 class="section-title">Integrations</h2>
          <p class="section-desc">Connect your favorite tools and services</p>
          <div style="display: flex; gap: 24px; margin-top: 16px;">
            <div>
              <div class="stat-value" id="connectedCount" style="font-size: 28px;">3</div>
              <div class="stat-label">Connected</div>
            </div>
            <div>
              <div class="stat-value" style="font-size: 28px;">11</div>
              <div class="stat-label">Available</div>
            </div>
          </div>
        </div>

        <!-- n8n Banner -->
        <div class="settings-card" style="background: rgba(255,176,32,0.05); border-color: rgba(255,176,32,0.2); margin-bottom: 24px;">
          <div style="display: flex; gap: 16px;">
            <div style="font-size: 32px;">🔗</div>
            <div style="flex: 1;">
              <div style="font-weight: 600; margin-bottom: 6px; font-size: 14px;">n8n Integration</div>
              <div style="font-size: 12px; color: var(--c-muted); margin-bottom: 12px;">Send messages from any n8n workflow to AgentFlow using the inbound webhook. The AI will automatically process, reply, and create tasks.</div>
              <div class="integration-webhook">
                <code style="font-size: 10px;">POST http://localhost:8000/api/webhooks/inbound</code>
                <button onclick="copyToClipboard('http://localhost:8000/api/webhooks/inbound')" class="btn-secondary" style="padding: 4px 12px;">Copy</button>
              </div>
              <div class="card-desc" style="margin-top: 8px;">Payload: <code style="color: var(--c-cyan);">{"client_name": "...", "platform": "whatsapp", "message": "...", "client_email": "..."}</code></div>
            </div>
          </div>
        </div>

        <div class="integrations-grid" id="integrationsContainer"></div>
      </div>

      <!-- Notifications Section -->
      <div id="notifications-section" class="settings-section">
        <div class="section-header">
          <h2 class="section-title">Notification Preferences</h2>
          <p class="section-desc">Choose how you want to be notified</p>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">Email Notifications</h3>
              <p class="card-desc">Receive email updates about your account activity</p>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" id="emailNotifications" checked class="toggle-input">
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">Push Notifications</h3>
              <p class="card-desc">Get real-time notifications in your browser</p>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" id="pushNotifications" class="toggle-input">
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">Weekly Digest</h3>
              <p class="card-desc">Receive a weekly summary of your account activity</p>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" id="weeklyDigest" checked class="toggle-input">
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">Marketing Communications</h3>
              <p class="card-desc">Receive product updates, tips, and special offers</p>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" id="marketingEmails" class="toggle-input">
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">AI Response Notifications</h3>
              <p class="card-desc">Get notified when AI responds to client messages</p>
            </div>
            <label class="toggle-switch">
              <input type="checkbox" id="aiResponses" checked class="toggle-input">
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>

        <button onclick="saveNotificationSettings()" class="btn btn-primary">Save Preferences</button>
      </div>

      <!-- API Keys Section -->
      <div id="api-section" class="settings-section">
        <div class="section-header">
          <h2 class="section-title">API Keys</h2>
          <p class="section-desc">Manage API keys for programmatic access to AgentFlow</p>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">API Keys</h3>
              <p class="card-desc">Your API keys allow you to access the AgentFlow API. Keep them secure.</p>
            </div>
            <button onclick="createAPIKey()" class="btn btn-primary">Create New Key</button>
          </div>
          <div id="apiKeysList">
            <div class="api-key-item">
              <div class="api-key-info">
                <div class="api-key-name">Production Key</div>
                <div class="api-key-date">Created: Jan 15, 2024 · Last used: 2 days ago · Requests: 1,247</div>
                <div class="api-key-value">••••••••••••••••••••</div>
              </div>
              <div class="api-key-actions">
                <button onclick="copyAPIKey(this)" class="btn-secondary" style="padding: 4px 12px;">Copy</button>
                <button onclick="revokeAPIKey(this)" class="btn-danger" style="padding: 4px 12px;">Revoke</button>
              </div>
            </div>
            <div class="api-key-item">
              <div class="api-key-info">
                <div class="api-key-name">Development Key</div>
                <div class="api-key-date">Created: Feb 1, 2024 · Last used: Never · Requests: 0</div>
                <div class="api-key-value">••••••••••••••••••••</div>
              </div>
              <div class="api-key-actions">
                <button onclick="copyAPIKey(this)" class="btn-secondary" style="padding: 4px 12px;">Copy</button>
                <button onclick="revokeAPIKey(this)" class="btn-danger" style="padding: 4px 12px;">Revoke</button>
              </div>
            </div>
          </div>
          <div class="card-desc" style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--c-border);">
            <strong>API Documentation:</strong> <a href="#" style="color: var(--c-cyan);">https://docs.agentflow.com/api</a>
          </div>
        </div>
      </div>

      <!-- Billing Section -->
      <div id="billing-section" class="settings-section">
        <div class="section-header">
          <h2 class="section-title">Billing & Subscription</h2>
          <p class="section-desc">Manage your subscription and payment methods</p>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">Current Plan</h3>
              <p class="card-desc">You're on the Pro plan</p>
            </div>
            <span class="status-connected" style="padding: 4px 12px;">Active</span>
          </div>
          <div style="margin: 20px 0;">
            <div style="font-size: 36px; font-weight: 700; color: var(--c-cyan);">$29<span style="font-size: 14px;">/month</span></div>
            <div style="font-size: 12px; color: var(--c-muted); margin-top: 6px;">Billed annually ($348/year) - Save 20%</div>
          </div>
          <div style="display: flex; gap: 12px;">
            <button onclick="manageSubscription()" class="btn btn-primary">Manage Subscription</button>
            <button onclick="viewPlans()" class="btn btn-secondary">View Plans</button>
          </div>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">Payment Method</h3>
              <p class="card-desc">Manage your payment methods</p>
            </div>
          </div>
          <div class="api-key-item">
            <div class="api-key-info">
              <div class="api-key-name">Visa ending in 4242</div>
              <div class="api-key-date">Expires 12/2025 · Default payment method</div>
            </div>
            <button onclick="updatePaymentMethod()" class="btn-secondary" style="padding: 4px 12px;">Update</button>
          </div>
          <button onclick="addPaymentMethod()" class="btn-secondary" style="margin-top: 12px;">+ Add New Payment Method</button>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">Billing History</h3>
              <p class="card-desc">View and download past invoices</p>
            </div>
          </div>
          <div class="api-key-item">
            <div class="api-key-info">
              <div class="api-key-name">February 2024</div>
              <div class="api-key-date">Paid on Feb 1, 2024 · $29.00</div>
            </div>
            <button onclick="downloadInvoice()" class="btn-secondary" style="padding: 4px 12px;">Download PDF</button>
          </div>
          <div class="api-key-item">
            <div class="api-key-info">
              <div class="api-key-name">January 2024</div>
              <div class="api-key-date">Paid on Jan 1, 2024 · $29.00</div>
            </div>
            <button onclick="downloadInvoice()" class="btn-secondary" style="padding: 4px 12px;">Download PDF</button>
          </div>
          <div class="api-key-item">
            <div class="api-key-info">
              <div class="api-key-name">December 2023</div>
              <div class="api-key-date">Paid on Dec 1, 2023 · $29.00</div>
            </div>
            <button onclick="downloadInvoice()" class="btn-secondary" style="padding: 4px 12px;">Download PDF</button>
          </div>
        </div>
      </div>

      <!-- Activity Log Section -->
      <div id="activity-section" class="settings-section">
        <div class="section-header">
          <h2 class="section-title">Activity Log</h2>
          <p class="section-desc">Track all actions and events in your account</p>
        </div>

        <div class="settings-card">
          <div class="card-header">
            <div>
              <h3 class="card-title">Recent Activity</h3>
              <p class="card-desc">Last 30 days of activity</p>
            </div>
            <button onclick="exportActivityLog()" class="btn-secondary">Export Log</button>
          </div>
          <div id="activityLog">
            <div class="activity-item">
              <div class="activity-icon">🔐</div>
              <div class="activity-content">
                <div class="activity-text">Login from new device - Chrome on Windows</div>
                <div class="activity-time">2 hours ago · IP: 192.168.1.1</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-icon">⚙️</div>
              <div class="activity-content">
                <div class="activity-text">Integration connected: Slack</div>
                <div class="activity-time">Yesterday at 3:45 PM</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-icon">📄</div>
              <div class="activity-content">
                <div class="activity-text">Invoice #INV-009 created</div>
                <div class="activity-time">Yesterday at 10:30 AM</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-icon">👤</div>
              <div class="activity-content">
                <div class="activity-text">Profile information updated</div>
                <div class="activity-time">2 days ago</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-icon">🤖</div>
              <div class="activity-content">
                <div class="activity-text">AI model changed to Gemini 2.0 Flash</div>
                <div class="activity-time">3 days ago</div>
              </div>
            </div>
            <div class="activity-item">
              <div class="activity-icon">💬</div>
              <div class="activity-content">
                <div class="activity-text">127 messages processed by AI agent</div>
                <div class="activity-time">Today</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Danger Zone Section -->
      <div id="danger-section" class="settings-section">
        <div class="section-header">
          <h2 class="section-title">Danger Zone</h2>
          <p class="section-desc">Irreversible account actions - proceed with caution</p>
        </div>

        <div class="danger-zone">
          <div class="danger-title">Delete Account</div>
          <div class="danger-desc">Permanently delete your account and all associated data. This action cannot be undone. All conversations, clients, invoices, and settings will be lost.</div>
          <button onclick="showDeleteConfirm()" class="btn-danger" style="padding: 10px 20px;">Delete Account</button>
        </div>

        <div class="danger-zone" style="margin-top: 16px;">
          <div class="danger-title">Export All Data</div>
          <div class="danger-desc">Download a complete export of all your data including clients, conversations, invoices, and settings.</div>
          <div style="display: flex; gap: 12px; margin-top: 12px;">
            <button onclick="exportData('json')" class="btn-secondary">Export as JSON</button>
            <button onclick="exportData('csv')" class="btn-secondary">Export as CSV</button>
            <button onclick="exportData('pdf')" class="btn-secondary">Export as PDF</button>
          </div>
        </div>

        <div class="danger-zone" style="margin-top: 16px;">
          <div class="danger-title">Clear All Data</div>
          <div class="danger-desc">Remove all conversations, clients, and billing data. This action cannot be undone. Your account settings and integrations will remain.</div>
          <button onclick="clearAllData()" class="btn-danger" style="padding: 10px 20px;">Clear All Data</button>
        </div>

        <div class="danger-zone" style="margin-top: 16px;">
          <div class="danger-title">Deactivate Account</div>
          <div class="danger-desc">Temporarily deactivate your account. You can reactivate it later by contacting support.</div>
          <button onclick="deactivateAccount()" class="btn-secondary" style="padding: 10px 20px;">Deactivate Account</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Create API Key Modal -->
<div id="apiKeyModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title">Create API Key</h3>
      <button class="modal-close" onclick="closeModal('apiKeyModal')">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
      </button>
    </div>
    <div class="form-group">
      <label class="form-label">Key Name</label>
      <input type="text" id="apiKeyName" class="form-input" placeholder="e.g., Production Key">
    </div>
    <div class="form-group">
      <label class="form-label">Expiration</label>
      <select class="form-select" id="apiKeyExpiry">
        <option value="never">Never expires</option>
        <option value="30">30 days</option>
        <option value="90">90 days</option>
        <option value="365">1 year</option>
      </select>
    </div>
    <div class="modal-actions">
      <button onclick="generateAPIKey()" class="btn-primary">Create</button>
      <button onclick="closeModal('apiKeyModal')" class="btn-secondary">Cancel</button>
    </div>
  </div>
</div>

<!-- Confirm Delete Modal -->
<div id="deleteConfirmModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-header">
      <h3 class="modal-title">Delete Account</h3>
      <button class="modal-close" onclick="closeModal('deleteConfirmModal')">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
        </svg>
      </button>
    </div>
    <p style="margin-bottom: 20px; color: var(--c-red);">⚠️ Warning: This action is permanent and cannot be undone.</p>
    <p style="margin-bottom: 20px;">Are you sure you want to delete your account? All your data will be permanently removed.</p>
    <div class="form-group">
      <label class="form-label">Type "DELETE ACCOUNT" to confirm</label>
      <input type="text" id="deleteConfirmText" class="form-input" placeholder="DELETE ACCOUNT">
    </div>
    <div class="modal-actions">
      <button onclick="deleteAccount()" class="btn-danger">Permanently Delete</button>
      <button onclick="closeModal('deleteConfirmModal')" class="btn-secondary">Cancel</button>
    </div>
  </div>
</div>

<script>
// Integration Data
let integrations = [
  { id: 'twilio', name: 'Twilio', type: 'messaging', status: 'connected', icon: '📱', connectedDate: '2 days ago', webhook: 'http://localhost:8000/api/webhooks/twilio/sms', description: 'SMS and voice messaging platform' },
  { id: 'slack', name: 'Slack', type: 'messaging', status: 'disconnected', icon: '⚡', webhook: 'http://localhost:8000/api/webhooks/slack', description: 'Team communication hub' },
  { id: 'jira', name: 'Jira', type: 'project_management', status: 'disconnected', icon: '🔷', webhook: 'http://localhost:8000/api/webhooks/jira', description: 'Project and issue tracking' },
  { id: 'stripe', name: 'Stripe', type: 'billing', status: 'connected', icon: '💳', connectedDate: '1 week ago', webhook: 'http://localhost:8000/api/webhooks/stripe', description: 'Payment processing platform' },
  { id: 'asana', name: 'Asana', type: 'project_management', status: 'disconnected', icon: '📋', webhook: 'http://localhost:8000/api/webhooks/asana', description: 'Work management platform' },
  { id: 'trello', name: 'Trello', type: 'project_management', status: 'disconnected', icon: '📌', webhook: 'http://localhost:8000/api/webhooks/trello', description: 'Visual project management' },
  { id: 'sendgrid', name: 'SendGrid', type: 'messaging', status: 'disconnected', icon: '📧', webhook: 'http://localhost:8000/api/webhooks/sendgrid', description: 'Email delivery service' },
  { id: 'salesforce', name: 'Salesforce', type: 'crm', status: 'disconnected', icon: '🏢', webhook: 'http://localhost:8000/api/webhooks/salesforce', description: 'Customer relationship management' }
];

let currentSection = 'profile';

// Navigation
document.querySelectorAll('.nav-item').forEach(item => {
  item.addEventListener('click', () => {
    const section = item.dataset.section;
    switchSection(section);
  });
});

function switchSection(section) {
  currentSection = section;
  
  document.querySelectorAll('.nav-item').forEach(item => {
    item.classList.remove('active');
    if (item.dataset.section === section) {
      item.classList.add('active');
    }
  });
  
  document.querySelectorAll('.settings-section').forEach(sec => {
    sec.classList.remove('active');
  });
  document.getElementById(`${section}-section`).classList.add('active');
  
  if (section === 'integrations') {
    renderIntegrations();
  }
}

// Render Integrations
function renderIntegrations() {
  const container = document.getElementById('integrationsContainer');
  container.innerHTML = integrations.map(integration => `
    <div class="integration-card">
      <div class="integration-header">
        <div class="integration-icon">${integration.icon}</div>
        <span class="integration-status ${integration.status === 'connected' ? 'status-connected' : 'status-disconnected'}">
          ${integration.status}
        </span>
      </div>
      <div class="integration-name">${integration.name}</div>
      <div class="integration-desc">${integration.description} ${integration.status === 'connected' ? `· Connected ${integration.connectedDate}` : ''}</div>
      ${integration.webhook ? `
        <div class="integration-webhook">
          <code style="font-size: 9px;">${integration.webhook.substring(0, 40)}...</code>
          <button onclick="copyToClipboard('${integration.webhook}')" class="btn-secondary" style="padding: 2px 8px;">Copy</button>
        </div>
      ` : ''}
      <button onclick="${integration.status === 'connected' ? `disconnectIntegration('${integration.id}')` : `connectIntegration('${integration.id}')`}" 
              class="btn ${integration.status === 'connected' ? 'btn-secondary' : 'btn-primary'}" 
              style="width: 100%; margin-top: 12px;">
        ${integration.status === 'connected' ? 'Disconnect' : 'Connect'}
      </button>
    </div>
  `).join('');
  
  updateConnectedCount();
}

function updateConnectedCount() {
  const connectedCount = integrations.filter(i => i.status === 'connected').length;
  document.getElementById('connectedCount').textContent = connectedCount;
}

function connectIntegration(id) {
  const integration = integrations.find(i => i.id === id);
  if (integration) {
    integration.status = 'connected';
    integration.connectedDate = 'Just now';
    renderIntegrations();
    showToast(`${integration.name} connected successfully!`, 'success');
  }
}

function disconnectIntegration(id) {
  const integration = integrations.find(i => i.id === id);
  if (integration) {
    integration.status = 'disconnected';
    renderIntegrations();
    showToast(`${integration.name} disconnected`, 'info');
  }
}

// Profile Functions
function saveProfile() {
  showToast('Profile updated successfully!', 'success');
}

function triggerAvatarUpload() {
  document.getElementById('avatarUpload').click();
}

function uploadAvatar(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = (e) => {
      const avatar = document.getElementById('avatarPreview');
      avatar.style.backgroundImage = `url(${e.target.result})`;
      avatar.style.backgroundSize = 'cover';
      avatar.style.backgroundPosition = 'center';
      avatar.textContent = '';
      showToast('Avatar uploaded successfully!', 'success');
    };
    reader.readAsDataURL(input.files[0]);
  }
}

// Security Functions
function checkPasswordStrength() {
  const password = document.getElementById('newPassword').value;
  const strengthDiv = document.getElementById('passwordStrength');
  if (!password) {
    strengthDiv.innerHTML = '';
    return;
  }
  
  let strength = 0;
  if (password.length >= 8) strength++;
  if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
  if (password.match(/\d/)) strength++;
  if (password.match(/[^a-zA-Z\d]/)) strength++;
  
  const strengthText = ['Weak', 'Fair', 'Good', 'Strong'];
  const strengthColor = ['var(--c-red)', 'var(--c-amber)', 'var(--c-cyan)', 'var(--c-green)'];
  
  strengthDiv.innerHTML = `<div style="color: ${strengthColor[strength]}; font-size: 10px;">Password strength: ${strengthText[strength]}</div>`;
}

document.getElementById('newPassword')?.addEventListener('input', checkPasswordStrength);

function changePassword() {
  const current = document.getElementById('currentPassword').value;
  const newPass = document.getElementById('newPassword').value;
  const confirm = document.getElementById('confirmPassword').value;
  
  if (!current || !newPass || !confirm) {
    showToast('Please fill all fields', 'error');
    return;
  }
  
  if (newPass !== confirm) {
    showToast('New passwords do not match', 'error');
    return;
  }
  
  if (newPass.length < 8) {
    showToast('Password must be at least 8 characters', 'error');
    return;
  }
  
  showToast('Password changed successfully!', 'success');
  document.getElementById('currentPassword').value = '';
  document.getElementById('newPassword').value = '';
  document.getElementById('confirmPassword').value = '';
}

function enable2FA() {
  showToast('2FA setup initiated. Check your email for instructions.', 'info');
}

function terminateSession(btn) {
  btn.closest('.api-key-item').remove();
  showToast('Session terminated', 'success');
}

function terminateAllSessions() {
  const sessions = document.querySelectorAll('#sessionsList .api-key-item');
  sessions.forEach(session => {
    if (session.querySelector('.api-key-name').textContent !== 'Chrome on Windows') {
      session.remove();
    }
  });
  showToast('All other sessions terminated', 'success');
}

// API Key Functions
function createAPIKey() {
  document.getElementById('apiKeyModal').classList.add('active');
}

function generateAPIKey() {
  const name = document.getElementById('apiKeyName').value;
  if (!name) {
    showToast('Please enter a key name', 'error');
    return;
  }
  
  const expiry = document.getElementById('apiKeyExpiry').value;
  const expiryText = expiry === 'never' ? 'Never expires' : `Expires in ${expiry} days`;
  
  const keysList = document.getElementById('apiKeysList');
  const keyHtml = `
    <div class="api-key-item">
      <div class="api-key-info">
        <div class="api-key-name">${name}</div>
        <div class="api-key-date">Created: ${new Date().toLocaleDateString()} · ${expiryText} · Requests: 0</div>
        <div class="api-key-value">${generateRandomKey()}</div>
      </div>
      <div class="api-key-actions">
        <button onclick="copyAPIKey(this)" class="btn-secondary" style="padding: 4px 12px;">Copy</button>
        <button onclick="revokeAPIKey(this)" class="btn-danger" style="padding: 4px 12px;">Revoke</button>
      </div>
    </div>
  `;
  keysList.insertAdjacentHTML('afterbegin', keyHtml);
  
  closeModal('apiKeyModal');
  document.getElementById('apiKeyName').value = '';
  showToast(`API key "${name}" created successfully!`, 'success');
}

function generateRandomKey() {
  return 'ag_' + Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
}

function copyAPIKey(btn) {
  const keyValue = btn.closest('.api-key-item').querySelector('.api-key-value').textContent;
  copyToClipboard(keyValue);
}

function revokeAPIKey(btn) {
  if (confirm('Are you sure you want to revoke this API key?')) {
    btn.closest('.api-key-item').remove();
    showToast('API key revoked', 'success');
  }
}

// Notification Functions
function saveNotificationSettings() {
  showToast('Notification preferences saved!', 'success');
}

// Billing Functions
function manageSubscription() {
  showToast('Redirecting to subscription portal...', 'info');
  setTimeout(() => showToast('Subscription portal opened', 'success'), 1000);
}

function viewPlans() {
  showToast('Viewing available plans...', 'info');
}

function updatePaymentMethod() {
  showToast('Payment method update initiated', 'info');
}

function addPaymentMethod() {
  showToast('Add new payment method form opened', 'info');
}

function downloadInvoice() {
  showToast('Invoice download started', 'success');
}

// Activity Functions
function exportActivityLog() {
  showToast('Activity log exported successfully!', 'success');
}

// Danger Zone Functions
function showDeleteConfirm() {
  document.getElementById('deleteConfirmModal').classList.add('active');
}

function deleteAccount() {
  const confirmText = document.getElementById('deleteConfirmText').value;
  if (confirmText === 'DELETE ACCOUNT') {
    showToast('Account deletion request submitted', 'error');
    closeModal('deleteConfirmModal');
  } else {
    showToast('Please type "DELETE ACCOUNT" to confirm', 'error');
  }
}

function exportData(format) {
  showToast(`Exporting data as ${format.toUpperCase()}...`, 'success');
  setTimeout(() => showToast('Data exported successfully!', 'success'), 1500);
}

function clearAllData() {
  if (confirm('⚠️ WARNING: This will permanently delete all your conversations, clients, and billing data. This action cannot be undone. Are you absolutely sure?')) {
    showToast('All data cleared', 'error');
  }
}

function deactivateAccount() {
  if (confirm('Are you sure you want to deactivate your account? You can reactivate it later by contacting support.')) {
    showToast('Account deactivated', 'info');
  }
}

// General Functions
function copyToClipboard(text) {
  navigator.clipboard.writeText(text);
  showToast('Copied to clipboard!', 'success');
}

function closeModal(modalId) {
  document.getElementById(modalId).classList.remove('active');
}

function showToast(message, type = 'info') {
  const toast = document.createElement('div');
  toast.className = `toast toast-${type}`;
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

// Initialize
renderIntegrations();
</script>
@endsection