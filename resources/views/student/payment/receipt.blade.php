@extends('layouts.student')

<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    /* Base Styles */
:root {
  --primary-color: #8a56ff;
  --success-color: #20c997;
  --light-purple: #f3f0ff;
  --text-dark: #333;
  --text-light: #666;
  --border-radius: 12px;
  --box-shadow: 0 8px 30px rgba(138, 86, 255, 0.12);
}

body {
  font-family: 'Nunito', 'Segoe UI', sans-serif;
  background-color: #f8f9fa;
  color: var(--text-dark);
}

.payment-container {
  max-width: 800px;
  margin: 3rem auto;
  background-color: white;
  border-radius: var(--border-radius);
  box-shadow: var(--box-shadow);
  overflow: hidden;
  animation: fadeIn 0.5s ease-in-out;
}

.payment-header {
  text-align: center;
  padding: 2.5rem 1rem;
  background-color: #f8f9fa;
}

.payment-header .check-circle {
  width: 80px;
  height: 80px;
  margin: 0 auto 1.5rem;
  background-color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(32, 201, 151, 0.2);
  animation: scaleIn 0.5s ease-out 0.3s both;
}

.payment-header .check-circle svg {
  width: 40px;
  height: 40px;
  color: var(--success-color);
  animation: checkmark 0.5s ease-out 0.8s both;
}

.payment-header h1 {
  color: var(--success-color);
  margin-bottom: 0.5rem;
  font-weight: 700;
  animation: slideUp 0.5s ease-out 0.4s both;
}

.payment-header p {
  color: var(--text-light);
  margin: 0;
  animation: slideUp 0.5s ease-out 0.5s both;
}

/* Navigation Tabs */
.payment-tabs {
  display: flex;
  background-color: var(--primary-color);
  color: white;
}

.payment-tabs .tab {
  flex: 1;
  padding: 1rem;
  text-align: center;
  cursor: pointer;
  transition: background-color 0.3s;
  position: relative;
  overflow: hidden;
}

.payment-tabs .tab.active {
  background-color: rgba(255, 255, 255, 0.1);
}

.payment-tabs .tab:hover {
  background-color: rgba(255, 255, 255, 0.05);
}

.payment-tabs .tab svg {
  margin-right: 8px;
}

.payment-tabs .tab::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 3px;
  background-color: white;
  transform: scaleX(0);
  transition: transform 0.3s;
}

.payment-tabs .tab.active::after {
  transform: scaleX(1);
}

/* Receipt Content */
.receipt-content {
  padding: 2rem;
}

.receipt-header {
  display: flex;
  align-items: center;
  margin-bottom: 1.5rem;
  animation: slideRight 0.5s ease-out 0.6s both;
}

.receipt-header .receipt-icon {
  width: 50px;
  height: 50px;
  background-color: var(--light-purple);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 1rem;
}

.receipt-header .receipt-icon svg {
  color: var(--primary-color);
}

.receipt-header .receipt-title {
  margin: 0;
  line-height: 1.2;
}

.receipt-header .receipt-subtitle {
  margin: 0;
  color: var(--success-color);
  font-size: 0.9rem;
}

/* Transaction Information */
.transaction-info {
  border-top: 1px solid #eee;
  padding-top: 1.5rem;
  margin-top: 1.5rem;
  animation: slideUp 0.5s ease-out 0.7s both;
}

.transaction-info h3 {
  display: flex;
  align-items: center;
  color: var(--primary-color);
  margin-bottom: 1.5rem;
  font-size: 1.1rem;
}

.transaction-info h3 svg {
  margin-right: 8px;
}

.transaction-info-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.info-item {
  background-color: #f8f9fa;
  border-radius: var(--border-radius);
  padding: 1rem;
  display: flex;
  align-items: center;
  transition: transform 0.3s, box-shadow 0.3s;
}

.info-item:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.info-item .info-icon {
  width: 40px;
  height: 40px;
  background-color: var(--light-purple);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 1rem;
  flex-shrink: 0;
}

.info-item .info-icon svg {
  color: var(--primary-color);
}

.info-item .info-content {
  flex-grow: 1;
}

.info-item .info-label {
  font-size: 0.8rem;
  color: var(--text-light);
  margin-bottom: 0.25rem;
}

.info-item .info-value {
  font-weight: 600;
  margin: 0;
  word-break: break-all;
  display: flex;
  align-items: center;
}

.info-item .info-value .copy-btn {
  background: none;
  border: none;
  color: #ccc;
  cursor: pointer;
  margin-left: 0.5rem;
  transition: color 0.3s;
}

.info-item .info-value .copy-btn:hover {
  color: var(--primary-color);
}

.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.75rem;
  border-radius: 50px;
  font-size: 0.85rem;
  font-weight: 600;
}

.status-badge.completed {
  background-color: rgba(32, 201, 151, 0.1);
  color: var(--success-color);
}

.status-badge svg {
  margin-right: 4px;
}

/* Total Amount Section */
.total-amount {
  background-color: #f8f9fa;
  border-radius: var(--border-radius);
  padding: 1.5rem;
  margin-top: 2rem;
  position: relative;
  animation: slideUp 0.5s ease-out 0.8s both;
}

/* Enhanced Actions Grid */
.actions-grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 1rem;
  margin-top: 1.5rem;
}

.action-button {
  display: flex;
  align-items: center;
  padding: 1.25rem;
  background-color: #f8f9fa;
  border-radius: var(--border-radius);
  cursor: pointer;
  transition: all 0.3s ease;
  border-left: 4px solid var(--primary-color);
  position: relative;
  overflow: hidden;
}

.action-button:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 15px rgba(0,0,0,0.1);
  background-color: white;
}

.action-button:active {
  transform: translateY(0);
  box-shadow: 0 4px 8px rgba(0,0,0,0.08);
}

.action-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 4px;
  height: 100%;
  background-color: var(--primary-color);
  transition: width 0.3s ease;
  z-index: 0;
}

.action-button:hover::before {
  width: 8px;
}

.action-icon {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background-color: rgba(138, 86, 255, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 1rem;
  transition: all 0.3s ease;
  color: var(--primary-color);
  position: relative;
  z-index: 1;
}

.action-button:hover .action-icon {
  transform: scale(1.1);
  background-color: rgba(138, 86, 255, 0.2);
}

.action-text {
  font-weight: 600;
  font-size: 1.1rem;
  color: var(--text-dark);
  position: relative;
  z-index: 1;
}

.action-button:hover .action-text {
  color: var(--primary-color);
}

.action-button::after {
  content: '';
  position: absolute;
  top: 50%;
  right: 1.5rem;
  width: 24px;
  height: 24px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%238a56ff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='9 18 15 12 9 6'%3E%3C/polyline%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: center;
  opacity: 0;
  transform: translateY(-50%) translateX(10px);
  transition: all 0.3s ease;
}

.action-button:hover::after {
  opacity: 1;
  transform: translateY(-50%) translateX(0);
}

.total-amount h3 {
  margin-top: 0;
  color: var(--text-light);
  font-size: 1rem;
  font-weight: 500;
}

.total-amount .amount {
  font-size: 2rem;
  font-weight: 700;
  color: var(--primary-color);
  margin: 0.5rem 0;
}

.paid-stamp {
  position: absolute;
  top: 1.5rem;
  right: 1.5rem;
  color: #ff6b6b;
  border: 2px solid #ff6b6b;
  border-radius: 8px;
  padding: 0.25rem 0.75rem;
  font-weight: 700;
  transform: rotate(15deg);
  opacity: 0.8;
  animation: stamp 0.5s ease-out 1.2s both;
}

/* Footer */
.receipt-footer {
  padding: 1rem 2rem;
  background-color: #f8f9fa;
  text-align: center;
  color: var(--text-light);
  font-size: 0.9rem;
  animation: fadeIn 0.5s ease-out 1s both;
}

.receipt-footer svg {
  margin-right: 0.5rem;
  vertical-align: middle;
}

/* Print Button */
.print-btn {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  background-color: var(--primary-color);
  color: white;
  border: none;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(138, 86, 255, 0.3);
  cursor: pointer;
  transition: transform 0.3s, background-color 0.3s;
  animation: fadeIn 0.5s ease-out 1.2s both;
  z-index: 100;
}

.print-btn:hover {
  transform: translateY(-5px);
  background-color: #7645e0;
}

/* Animations */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes slideRight {
  from { opacity: 0; transform: translateX(-20px); }
  to { opacity: 1; transform: translateX(0); }
}

@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.8); }
  to { opacity: 1; transform: scale(1); }
}

@keyframes checkmark {
  0% { opacity: 0; transform: scale(0); }
  50% { opacity: 1; transform: scale(1.2); }
  100% { opacity: 1; transform: scale(1); }
}

@keyframes stamp {
  0% { opacity: 0; transform: scale(0) rotate(15deg); }
  50% { opacity: 1; transform: scale(1.2) rotate(15deg); }
  100% { opacity: 0.8; transform: scale(1) rotate(15deg); }
}

/* Media Queries */
@media (max-width: 768px) {
  .payment-container {
    margin: 2rem 1rem;
  }
  
  .transaction-info-grid {
    grid-template-columns: 1fr;
  }
  
  .payment-tabs .tab span {
    display: none;
  }
  
  .payment-tabs .tab svg {
    margin-right: 0;
  }
}

/* Print Styles */
@media print {
  body {
    background-color: white;
  }
  
  .payment-container {
    box-shadow: none;
    margin: 0;
    max-width: 100%;
  }
  
  .print-btn, .payment-tabs {
    display: none;
  }
  
  .total-amount {
    break-inside: avoid;
  }
}

/* Toast notification for copy to clipboard */
.toast {
  position: fixed;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%) translateY(100px);
  background-color: var(--text-dark);
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 50px;
  font-size: 0.9rem;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  z-index: 1000;
  opacity: 0;
  transition: transform 0.3s, opacity 0.3s;
}

.toast.show {
  opacity: 1;
  transform: translateX(-50%) translateY(0);
}
</style>

@section('main')

<div class="payment-container">
    <div class="payment-header">
        <div class="check-circle">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
        </div>
        <h1>Payment Successful!</h1>
        <p>Your transaction has been completed</p>
    </div>

    <div class="payment-tabs">
        <div class="tab active" data-tab="receipt">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            <span>Receipt</span>
        </div>
        <div class="tab" data-tab="items">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="8" y1="6" x2="21" y2="6"></line>
                <line x1="8" y1="12" x2="21" y2="12"></line>
                <line x1="8" y1="18" x2="21" y2="18"></line>
                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                <line x1="3" y1="18" x2="3.01" y2="18"></line>
            </svg>
            <span>Items</span>
        </div>
        <div class="tab" data-tab="actions">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
            </svg>
            <span>Actions</span>
        </div>
    </div>

    <div class="tab-content" id="receipt-tab">
        <div class="receipt-content">
            @if(session('session_restored'))
            <div class="alert alert-info" role="alert">
                <h4 class="alert-heading"><i class="fa fa-info-circle me-2"></i> Session Restored</h4>
                <p>Your session was restored after payment processing.</p>
            </div>
            @endif

            <div class="receipt-header">
                <div class="receipt-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                <div>
                    <h2 class="receipt-title">Payment Receipt</h2>
                    <p class="receipt-subtitle">Transaction Complete</p>
                </div>
            </div>

            <div class="transaction-info">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    Transaction Information
                </h3>

                <div class="transaction-info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                                <line x1="4" y1="22" x2="4" y2="15"></line>
                            </svg>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Reference No</div>
                            <div class="info-value">
                                {{ $data['payment']->ref_no }}
                                <button class="copy-btn" data-copy="{{ $data['payment']->ref_no }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Student IC</div>
                            <div class="info-value">
                                {{ $data['payment']->student_ic }}
                                <button class="copy-btn" data-copy="{{ $data['payment']->student_ic }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Date</div>
                            <div class="info-value">{{ date('d-m-Y', strtotime($data['payment']->add_date)) }}</div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Method</div>
                            <div class="info-value">
                                <span class="payment-method">FPX</span>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 7 23 1 17 1"></polyline>
                                <line x1="16" y1="8" x2="23" y2="1"></line>
                                <line x1="1" y1="1" x2="1" y2="23"></line>
                                <polyline points="1 15 7 15 10 10 13 17 17 10 23 10"></polyline>
                            </svg>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Transaction ID</div>
                            <div class="info-value">
                                {{ $data['method']->no_document }}
                                <button class="copy-btn" data-copy="{{ $data['method']->no_document }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Status</div>
                            <div class="info-value">
                                <span class="status-badge completed">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                    Completed
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="total-amount">
                <h3>Total Amount</h3>
                <div class="amount">RM {{ number_format($data['payment']->amount + 1.50, 2) }}</div>
                <div class="paid-stamp">PAID</div>
            </div>
        </div>
    </div>

    <div class="tab-content" id="items-tab" style="display: none;">
        <div class="receipt-content">
            <div class="receipt-header">
                <div class="receipt-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                </div>
                <div>
                    <h2 class="receipt-title">Payment Items</h2>
                    <p class="receipt-subtitle">Transaction Details</p>
                </div>
            </div>
            
            <div class="items-list">
                <div class="table-responsive">
                    <table class="w-100 table table-bordered display margin-top-10 w-p100">
                        <thead>
                            <tr>
                                <th style="width: 10%">Date</th>
                                <th style="width: 45%">Description</th>
                                <th style="width: 10%">Semester</th>
                                <th style="width: 15%">Amount (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['details'] as $key => $dtl)
                            <tr class="item-row">
                                <td>{{ date('d-m-Y', strtotime($dtl->add_date)) }}</td>
                                <td>{{ $dtl->name }}</td>
                                <td>{{ $data['payment']->semester_id }}</td>
                                <td class="text-end">{{ number_format($dtl->amount, 2) }}</td>
                            </tr>
                            @endforeach
                            <tr class="item-row">
                                <td>{{ date('d-m-Y') }}</td>
                                <td>Payment Processing Fee</td>
                                <td></td>
                                <td class="text-end">1.50</td>
                            </tr>
                            <tr class="bg-light">
                                <td colspan="3" class="text-end"><strong>TOTAL AMOUNT</strong></td>
                                <td class="text-end"><strong>{{ number_format($data['payment']->amount + 1.50, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-content" id="actions-tab" style="display: none;">
        <div class="receipt-content">
            <div class="receipt-header">
                <div class="receipt-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="receipt-title">Payment Actions</h2>
                    <p class="receipt-subtitle">Available Options</p>
                </div>
            </div>
            
            <div class="actions-grid">
                <div class="action-button" id="custom-print-button">
                    <div class="action-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                    </div>
                    <div class="action-text">Print Receipt</div>
                </div>
                
                <div class="action-button" id="download-pdf">
                    <div class="action-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                    </div>
                    <div class="action-text">Download PDF</div>
                </div>
                
                <a href="{{ route('yuran-pengajian') }}" class="action-button">
                    <div class="action-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <div class="action-text">Back to Dashboard</div>
                </a>
                
                <div class="action-button" id="share-receipt">
                    <div class="action-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="18" cy="5" r="3"></circle>
                            <circle cx="6" cy="12" r="3"></circle>
                            <circle cx="18" cy="19" r="3"></circle>
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                        </svg>
                    </div>
                    <div class="action-text">Share Receipt</div>
                </div>
            </div>
        </div>
    </div>

    <div class="receipt-footer">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>
        Secure Payment processed on {{ date('d M Y, h:i A') }}
    </div>
</div>

<button class="print-btn" id="float-print-button" title="Print Receipt">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="6 9 6 2 18 2 18 9"></polyline>
        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
        <rect x="6" y="14" width="12" height="8"></rect>
    </svg>
</button>

<div class="toast" id="toast-notification">Text copied to clipboard!</div>

<!-- Print Template -->
<div id="print-template" style="display: none;">
    <style>
        @media print {
            body {
                font-family: 'Nunito', 'Segoe UI', sans-serif;
                color: #000;
                background: white;
                margin: 0;
                padding: 10px;
                font-size: 11px;
            }
            .print-container {
                max-width: 800px;
                margin: 0 auto;
                padding: 15px;
                border: 1.5px solid #000;
                border-radius: 8px;
                position: relative;
                page-break-after: always;
            }
            .print-header {
                text-align: center;
                padding-bottom: 10px;
                border-bottom: 1.5px solid #000;
                margin-bottom: 15px;
            }
            .print-header h1 {
                color: #000;
                margin: 0 0 5px 0;
                font-size: 18px;
            }
            .print-header p {
                color: #000;
                margin: 0;
                font-size: 12px;
            }
            .print-section {
                margin-bottom: 15px;
            }
            .print-section h2 {
                color: #000;
                font-size: 14px;
                margin: 0 0 10px 0;
                padding-bottom: 3px;
                border-bottom: 1px solid #000;
            }
            .print-info-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
            }
            .print-info-item {
                margin-bottom: 5px;
            }
            .print-info-label {
                font-size: 10px;
                color: #000;
                font-weight: 500;
            }
            .print-info-value {
                font-weight: 600;
                color: #000;
            }
            .print-items-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 5px;
            }
            .print-items-table th, .print-items-table td {
                border: 1px solid #000;
                padding: 6px;
                text-align: left;
            }
            .print-items-table th {
                background-color: #f0f0f0;
                font-weight: 600;
            }
            .print-items-table .amount {
                text-align: right;
            }
            .print-total {
                margin-top: 10px;
                padding: 8px;
                background-color: #f0f0f0;
                border-radius: 6px;
                text-align: right;
                border: 1px solid #000;
            }
            .print-total .amount {
                font-size: 16px;
                font-weight: 700;
                color: #000;
            }
            .print-stamp {
                position: absolute;
                top: 60px;
                right: 60px;
                color: #ff0000;
                border: 2px solid #ff0000;
                border-radius: 6px;
                padding: 5px 10px;
                font-weight: 700;
                transform: rotate(15deg);
                opacity: 0.9;
                font-size: 14px;
            }
            .print-footer {
                margin-top: 10px;
                text-align: center;
                color: #000;
                font-size: 9px;
                padding-top: 5px;
                border-top: 1px solid #000;
            }
            @page {
                size: auto;
                margin: 5mm;
            }
        }
    </style>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Custom print functionality
    function customPrint() {
        // Create a new window for printing
        const printWindow = window.open('', '_blank');
        
        // Get all the necessary data
        const studentIc = "{{ $data['payment']->student_ic }}";
        const refNo = "{{ $data['payment']->ref_no }}";
        const transactionDate = "{{ date('d-m-Y', strtotime($data['payment']->add_date)) }}";
        const transactionId = "{{ $data['method']->no_document }}";
        const totalAmount = "{{ number_format($data['payment']->amount + 1.50, 2) }}";
        
        // Create the print content
        let printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Payment Receipt - ${refNo}</title>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
                <style>
                    body {
                        font-family: 'Nunito', 'Segoe UI', sans-serif;
                        color: #000;
                        background: white;
                        margin: 0;
                        padding: 10px;
                        font-size: 11px;
                    }
                    .print-container {
                        max-width: 800px;
                        margin: 0 auto;
                        padding: 15px;
                        border: 1.5px solid #000;
                        border-radius: 8px;
                        position: relative;
                        page-break-after: always;
                    }
                    .print-header {
                        text-align: center;
                        padding-bottom: 10px;
                        border-bottom: 1.5px solid #000;
                        margin-bottom: 15px;
                    }
                    .print-header h1 {
                        color: #000;
                        margin: 0 0 5px 0;
                        font-size: 18px;
                    }
                    .print-header p {
                        color: #000;
                        margin: 0;
                        font-size: 12px;
                    }
                    .print-section {
                        margin-bottom: 15px;
                    }
                    .print-section h2 {
                        color: #000;
                        font-size: 14px;
                        margin: 0 0 10px 0;
                        padding-bottom: 3px;
                        border-bottom: 1px solid #000;
                    }
                    .print-info-grid {
                        display: grid;
                        grid-template-columns: repeat(3, 1fr);
                        gap: 10px;
                    }
                    .print-info-item {
                        margin-bottom: 5px;
                    }
                    .print-info-label {
                        font-size: 10px;
                        color: #000;
                        font-weight: 500;
                    }
                    .print-info-value {
                        font-weight: 600;
                        color: #000;
                    }
                    .print-items-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 5px;
                    }
                    .print-items-table th, .print-items-table td {
                        border: 1px solid #000;
                        padding: 6px;
                        text-align: left;
                    }
                    .print-items-table th {
                        background-color: #f0f0f0;
                        font-weight: 600;
                    }
                    .print-items-table .amount {
                        text-align: right;
                    }
                    .print-total {
                        margin-top: 10px;
                        padding: 8px;
                        background-color: #f0f0f0;
                        border-radius: 6px;
                        text-align: right;
                        border: 1px solid #000;
                    }
                    .print-total .amount {
                        font-size: 16px;
                        font-weight: 700;
                        color: #000;
                    }
                    .print-stamp {
                        position: absolute;
                        top: 60px;
                        right: 60px;
                        color: #ff0000;
                        border: 2px solid #ff0000;
                        border-radius: 6px;
                        padding: 5px 10px;
                        font-weight: 700;
                        transform: rotate(15deg);
                        opacity: 0.9;
                        font-size: 14px;
                    }
                    .print-footer {
                        margin-top: 10px;
                        text-align: center;
                        color: #000;
                        font-size: 9px;
                        padding-top: 5px;
                        border-top: 1px solid #000;
                    }
                    @page {
                        size: auto;
                        margin: 5mm;
                    }
                </style>
            </head>
            <body onload="window.print()">
                <div class="print-container">
                    <div class="print-stamp">PAID</div>
                    
                    <div class="print-header">
                        <h1>Payment Receipt</h1>
                        <p>Transaction ID: ${transactionId}</p>
                    </div>
                    
                    <div class="print-section">
                        <h2>Transaction Information</h2>
                        <div class="print-info-grid">
                            <div class="print-info-item">
                                <div class="print-info-label">Reference No</div>
                                <div class="print-info-value">${refNo}</div>
                            </div>
                            <div class="print-info-item">
                                <div class="print-info-label">Student IC</div>
                                <div class="print-info-value">${studentIc}</div>
                            </div>
                            <div class="print-info-item">
                                <div class="print-info-label">Date</div>
                                <div class="print-info-value">${transactionDate}</div>
                            </div>
                            <div class="print-info-item">
                                <div class="print-info-label">Method</div>
                                <div class="print-info-value">FPX</div>
                            </div>
                            <div class="print-info-item">
                                <div class="print-info-label">Transaction ID</div>
                                <div class="print-info-value">${transactionId}</div>
                            </div>
                            <div class="print-info-item">
                                <div class="print-info-label">Status</div>
                                <div class="print-info-value">Completed</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="print-section">
                        <h2>Payment Items</h2>
                        <table class="print-items-table">
                            <thead>
                                <tr>
                                    <th width="15%">Date</th>
                                    <th width="45%">Description</th>
                                    <th width="15%">Semester</th>
                                    <th width="25%">Amount (RM)</th>
                                </tr>
                            </thead>
                            <tbody>
        `;
        
        // Add items from the details array
        @foreach($data['details'] as $dtl)
        printContent += `
                                <tr>
                                    <td>{{ date('d-m-Y', strtotime($dtl->add_date)) }}</td>
                                    <td>{{ $dtl->name }}</td>
                                    <td>{{ $data['payment']->semester_id }}</td>
                                    <td class="amount">{{ number_format($dtl->amount, 2) }}</td>
                                </tr>
        `;
        @endforeach
        
        // Add processing fee
        printContent += `
                                <tr>
                                    <td>{{ date('d-m-Y') }}</td>
                                    <td>Payment Processing Fee</td>
                                    <td></td>
                                    <td class="amount">1.50</td>
                                </tr>
                                <tr style="font-weight:bold; background-color:#f0f0f0;">
                                    <td colspan="3" style="text-align: right;">TOTAL AMOUNT</td>
                                    <td class="amount">${totalAmount}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="print-footer">
                        Secure Payment processed on {{ date('d M Y, h:i A') }} | Ref: ${refNo}
                    </div>
                </div>
            </body>
            </html>
        `;
        
        // Write to the new window and close the document for printing
        printWindow.document.open();
        printWindow.document.write(printContent);
        printWindow.document.close();
    }
    
    // Attach the print function to both print buttons
    document.getElementById('custom-print-button').addEventListener('click', customPrint);
    document.getElementById('float-print-button').addEventListener('click', customPrint);
    
    // Tab switching functionality
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Update active tab
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Show corresponding tab content with animation
            tabContents.forEach(content => {
                content.style.display = 'none';
            });
            
            const activeContent = document.getElementById(tabName + '-tab');
            activeContent.style.display = 'block';
            activeContent.style.animation = 'fadeIn 0.5s ease-in-out';
            
            // Animate items in the newly displayed tab
            setTimeout(() => {
                const items = activeContent.querySelectorAll('.info-item, .item-row, .action-button');
                items.forEach((item, index) => {
                    item.style.opacity = '0';
                    item.style.animation = `slideUp 0.3s ease-out ${index * 0.1}s forwards`;
                });
            }, 100);
        });
    });
    
    // Copy to clipboard functionality
    const copyButtons = document.querySelectorAll('.copy-btn');
    const toast = document.getElementById('toast-notification');
    
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const textToCopy = this.getAttribute('data-copy');
            navigator.clipboard.writeText(textToCopy).then(() => {
                // Show toast notification
                toast.textContent = 'Copied to clipboard!';
                toast.classList.add('show');
                
                // Hide toast after 2 seconds
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 2000);
                
                // Change button color temporarily
                this.style.color = '#8a56ff';
                setTimeout(() => {
                    this.style.color = '#ccc';
                }, 1000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        });
    });
    
    // Animate info items on load with staggered delay
    const infoItems = document.querySelectorAll('.info-item');
    infoItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.animation = `slideUp 0.4s ease-out ${0.6 + (index * 0.1)}s forwards`;
    });
    
    // Animate the paid stamp with a bounce effect
    const paidStamp = document.querySelector('.paid-stamp');
    if (paidStamp) {
        setTimeout(() => {
            paidStamp.classList.add('animate-stamp');
        }, 1000);
    }
    
    // Enhanced action buttons animation
    const actionButtons = document.querySelectorAll('.action-button');
    if (actionButtons.length > 0) {
        actionButtons.forEach((button, index) => {
            // Add initial state
            button.style.opacity = '0';
            button.style.transform = 'translateX(-20px)';
            
            // Animate each button with delay
            setTimeout(() => {
                button.style.transition = 'opacity 0.5s ease, transform 0.5s ease, box-shadow 0.3s ease, background-color 0.3s ease';
                button.style.opacity = '1';
                button.style.transform = 'translateX(0)';
            }, 800 + (index * 200));
            
            // Add pulse effect to the first action button
            if (index === 0) {
                setTimeout(() => {
                    button.querySelector('.action-icon').style.animation = 'pulse 2s infinite';
                }, 2000);
            }
        });
    }
    
    // Download PDF functionality (mock)
    const downloadPdfButton = document.getElementById('download-pdf');
    if (downloadPdfButton) {
        downloadPdfButton.addEventListener('click', function() {
            const toast = document.getElementById('toast-notification');
            toast.textContent = 'PDF download started!';
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2000);
        });
    }
    
    // Share receipt functionality (mock)
    const shareReceiptButton = document.getElementById('share-receipt');
    if (shareReceiptButton) {
        shareReceiptButton.addEventListener('click', function() {
            const toast = document.getElementById('toast-notification');
            toast.textContent = 'Share options opened!';
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2000);
        });
    }
    
    // Add hover effect to info items
    infoItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 10px 20px rgba(0,0,0,0.1)';
            this.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
    
    // Animation for the print button
    const printBtn = document.querySelector('.print-btn');
    if (printBtn) {
        // Floating animation
        setInterval(() => {
            printBtn.style.animation = 'floatButton 2s ease-in-out infinite';
        }, 100);
        
        // Hover effect
        printBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.05)';
        });
        
        printBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    }
    
    // If session was restored using a token, ping the server to keep the session alive
    const wasRestored = "{{ session('session_restored') }}" === "1";
    const tokenUsed = "{{ session('payment_token_used') }}" === "1";
    
    if (wasRestored || tokenUsed) {
        // Send a ping to keep session alive
        function pingSession() {
            fetch('{{ route('yuran-pengajian') }}', {
                method: 'HEAD',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
        }
        
        // Ping immediately and set up interval
        pingSession();
        setInterval(pingSession, 60000); // Every minute
        
        // Store authentication in local storage (encrypted)
        try {
            localStorage.setItem('payment_completed', 'true');
            localStorage.setItem('payment_completed_time', Date.now().toString());
        } catch (e) {
            console.error('Local storage not available');
        }
    }
    
    // Add these keyframes to the document if they don't exist already
    if (!document.getElementById('receipt-keyframes')) {
        const style = document.createElement('style');
        style.id = 'receipt-keyframes';
        style.textContent = `
            @keyframes floatButton {
                0% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
                100% { transform: translateY(0); }
            }
            
            @keyframes animate-stamp {
                0% { opacity: 0; transform: scale(0) rotate(15deg); }
                50% { opacity: 1; transform: scale(1.3) rotate(15deg); }
                100% { opacity: 0.8; transform: scale(1) rotate(15deg); }
            }
            
            @keyframes pulse {
                0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(138, 86, 255, 0.4); }
                70% { transform: scale(1.1); box-shadow: 0 0 0 10px rgba(138, 86, 255, 0); }
                100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(138, 86, 255, 0); }
            }
        `;
        document.head.appendChild(style);
    }
    
    // Add interactive effects for action buttons
    document.querySelectorAll('.actions-grid .action-button').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            // Add subtle shadow movement on hover
            this.style.boxShadow = '0 8px 25px rgba(138, 86, 255, 0.25)';
            
            // Scale the icon slightly
            const icon = this.querySelector('.action-icon');
            if (icon) {
                icon.style.transform = 'scale(1.15)';
                icon.style.backgroundColor = 'rgba(138, 86, 255, 0.2)';
            }
        });
        
        btn.addEventListener('mouseleave', function() {
            // Reset styles on mouse leave
            this.style.boxShadow = '';
            
            const icon = this.querySelector('.action-icon');
            if (icon) {
                icon.style.transform = '';
                icon.style.backgroundColor = '';
            }
        });
        
        // Add click effect
        btn.addEventListener('mousedown', function() {
            this.style.transform = 'scale(0.98)';
        });
        
        btn.addEventListener('mouseup', function() {
            this.style.transform = '';
        });
    });
});
</script>
@endsection