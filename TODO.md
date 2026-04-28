

#   taskkill /F /IM python.exe
#   taskkill /IM ngrok.exe /F

#   new terminal 
#   cd D:\ai-agent-saas\twilio-webhook
#   python -m uvicorn main:app --host 0.0.0.0 --port 8003 


#   new terminal 
#   ngrok http 8003 

#   new terminal 
#   php artisan serve

<!-- 
 for login use this 


email = syedammar496539@gmail.com
password = 11331133 


-->









# 🤖 AI Agent SaaS Platform

A full-stack AI-powered SaaS platform with intelligent multi-channel communication and task automation capabilities.

## 📊 Overview

Enterprise-grade SaaS solution combining Laravel backend with FastAPI microservices for AI-driven client communication across WhatsApp, Slack, and automated Trello task management.

**Architecture:** Laravel 12 (API Backend) + FastAPI (Webhook Microservice) + Groq AI (Llama 3.3)

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 12, PHP 8.2, FastAPI, Python 3.8+ |
| **Frontend** | Blade Templates, TailwindCSS, Vite |
| **AI/ML** | Groq AI with Llama 3.3 70B |
| **Database** | SQLite (default), MySQL 8.0+ |
| **Communication** | Twilio WhatsApp Business API, Slack API |
| **Automation** | Trello REST API |
| **Tunneling** | Ngrok (development) |

## ✨ Features

- 🔐 **User Authentication** - Secure multi-tenant authentication system
- 👥 **Client Management** - CRM-style client database with activity tracking
- 🤖 **AI Agent** - Context-aware conversational AI powered by Llama 3.3
- 💬 **Multi-Channel Communication** - WhatsApp & Slack integration
- 📋 **Task Automation** - Auto-create Trello cards from conversations
- 📊 **Analytics Dashboard** - Real-time metrics and insights
- 🔔 **Real-time Notifications** - Webhook-based event handling

## 🚀 Installation

### Prerequisites

```bash
# Required Software
- PHP 8.2+
- Python 3.8+
- Composer 2.x
- Node.js 18+ & npm
- Ngrok
```

### Quick Start

```bash
# 1. Clone Repository
git clone https://github.com/your-username/ai-agent-saas.git
cd ai-agent-saas

# 2. Laravel Setup
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed

# 3. Python Webhook Setup
cd twilio-webhook
python -m venv venv
source venv/bin/activate  # Windows: venv\Scripts\activate
pip install -r requirements.txt
cp .env.example .env
cd ..

# 4. Frontend Build
npm install
npm run build
```

## ⚙️ Configuration

### Laravel Environment (.env)

```env
# Application
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite
# OR for MySQL:
# DB_CONNECTION=mysql
# DB_DATABASE=ai_agent_saas

# Twilio WhatsApp
TWILIO_ACCOUNT_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_WHATSAPP_NUMBER=whatsapp:+14155238886

# Slack
SLACK_BOT_TOKEN=xoxb-your-bot-token
SLACK_SIGNING_SECRET=your_signing_secret

# Trello
TRELLO_API_KEY=your_api_key
TRELLO_TOKEN=your_token
TRELLO_BOARD_ID=your_board_id

# Groq AI
GROQ_API_KEY=your_groq_api_key
GROQ_MODEL=llama-3.3-70b-versatile
```

### Python Environment (twilio-webhook/.env)

```env
WEBHOOK_SECRET=your_webhook_secret
LARAVEL_API_URL=http://localhost:8000/api
```

## 🏃 Running the Application

### Development Mode

```bash
# Terminal 1: Kill existing processes (Windows)
taskkill /F /IM python.exe
taskkill /IM ngrok.exe /F

# Terminal 2: Python Webhook Service
cd twilio-webhook
python -m uvicorn main:app --host 0.0.0.0 --port 8003

# Terminal 3: Ngrok Tunnel
ngrok http 8003
# Copy HTTPS URL → Update Twilio webhook settings

# Terminal 4: Laravel Server
php artisan serve
```

**Access Points:**
- Laravel App: `http://localhost:8000`
- Python API: `http://localhost:8003`
- Ngrok Dashboard: `http://localhost:4040`

### Production Mode

```bash
# Laravel
php artisan config:cache
php artisan route:cache
php artisan optimize

# Python (use Gunicorn)
gunicorn -w 4 -k uvicorn.workers.UvicornWorker main:app --bind 0.0.0.0:8003
```

## 📚 API Documentation

### Laravel Endpoints