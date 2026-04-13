

## [ ] Step 2: Kill old servers
taskkill /F /IM python.exe

## [ ] Step 3: Restart server
cd twilio-webhook && venv\\Scripts\\activate && python -m uvicorn main:app --host 0.0.0.0 --port 8003 --reload

## [ ] Step 4: Verify health shows "twilio_sid":true

## [ ] Step 5: Test simulation POST returns empty TwiML + WhatsApp sent log

## [ ] Step 6: Get ngrok URL from http://localhost:4040

## [ ] Step 7: Set in Twilio Console sandbox webhook to ngrok/webhook HTTP POST

## [ ] Step 8: Send join code + test msg from WhatsApp

**Monitor server terminal logs for errors.**
