<?php
// Mulai session
session_start();

// Cek login menggunakan session yang sama dengan file lain
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit();
}

// Include database connection
require_once 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
  
  <title>TAMBAH TRADE • Multi Foto • Trading Journal</title>
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <!-- Google Fonts - Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <style>
    /* ======================
       VARIABLES & RESET
    ====================== */
    :root {
      --primary: #6366f1;
      --primary-dark: #4f46e5;
      --secondary: #8b5cf6;
      --success: #10b981;
      --danger: #ef4444;
      --warning: #f59e0b;
      --dark: #0b1120;
      --dark-card: #1a2332;
      --dark-light: #2d3748;
      --text-primary: #f8fafc;
      --text-secondary: #94a3b8;
      --text-muted: #64748b;
      --border-color: #334155;
      --gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
      --shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
      --shadow-lg: 0 30px 60px -15px rgba(0, 0, 0, 0.6);
      --blur: blur(12px);
      --radius-sm: 16px;
      --radius-md: 24px;
      --radius-lg: 32px;
      --radius-full: 9999px;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(145deg, #0a0f1c 0%, #0f1a2f 100%);
      color: var(--text-primary);
      min-height: 100vh;
      padding: 20px 16px 90px 16px;
      line-height: 1.6;
      position: relative;
      animation: fadeIn 0.3s ease;
    }

    body.modal-open {
      overflow: hidden;
    }

    /* Animated background */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.1) 0%, transparent 50%),
                  radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
      pointer-events: none;
      z-index: -1;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; }
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Header */
    .header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 32px;
      background: rgba(26, 35, 50, 0.6);
      backdrop-filter: var(--blur);
      -webkit-backdrop-filter: var(--blur);
      padding: 16px 24px;
      border-radius: var(--radius-lg);
      border: 1px solid rgba(255, 255, 255, 0.05);
      box-shadow: var(--shadow-sm);
      animation: slideDown 0.5s ease forwards;
    }

    h2 {
      font-size: 1.8rem;
      font-weight: 700;
      background: var(--gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      letter-spacing: -0.5px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    h2 i {
      background: rgba(255, 255, 255, 0.1);
      padding: 12px;
      border-radius: 20px;
      color: var(--primary);
      font-size: 1.4rem;
      -webkit-text-fill-color: initial;
    }

    /* Form Card */
    .form-container {
      max-width: 800px;
      margin: 0 auto;
    }

    .card {
      background: rgba(26, 35, 50, 0.8);
      backdrop-filter: var(--blur);
      -webkit-backdrop-filter: var(--blur);
      border-radius: var(--radius-lg);
      padding: 40px;
      box-shadow: var(--shadow-lg);
      border: 1px solid rgba(255, 255, 255, 0.05);
      margin-bottom: 24px;
      animation: fadeInUp 0.5s ease forwards;
    }

    /* Form Groups */
    .form-group {
      margin-bottom: 28px;
      position: relative;
      animation: fadeInUp 0.5s ease forwards;
    }

    .form-group:nth-child(1) { animation-delay: 0.05s; }
    .form-group:nth-child(2) { animation-delay: 0.1s; }
    .form-group:nth-child(3) { animation-delay: 0.15s; }
    .form-group:nth-child(4) { animation-delay: 0.2s; }
    .form-group:nth-child(5) { animation-delay: 0.25s; }
    .form-group:nth-child(6) { animation-delay: 0.3s; }
    .form-group:nth-child(7) { animation-delay: 0.35s; }
    .form-group:nth-child(8) { animation-delay: 0.4s; }

    label {
      display: block;
      margin-bottom: 12px;
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--text-secondary);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    label i {
      color: var(--primary);
      font-size: 1rem;
      width: 20px;
    }

    /* Input Fields */
    input, select, textarea {
      width: 100%;
      padding: 16px 20px;
      background: rgba(45, 55, 72, 0.6);
      border: 2px solid var(--border-color);
      border-radius: var(--radius-md);
      color: var(--text-primary);
      font-size: 1rem;
      font-family: 'Inter', sans-serif;
      transition: all 0.3s ease;
    }

    input:hover, select:hover, textarea:hover {
      border-color: var(--primary);
      background: rgba(45, 55, 72, 0.8);
    }

    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
      background: rgba(45, 55, 72, 0.9);
    }

    /* Date input specific */
    input[type="date"] {
      color-scheme: dark;
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
      filter: invert(1);
      opacity: 0.5;
      cursor: pointer;
    }

    input[type="date"]::-webkit-calendar-picker-indicator:hover {
      opacity: 1;
    }

    /* Select dropdown */
    select {
      appearance: none;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 20px center;
      background-size: 16px;
      padding-right: 50px;
    }

    select option {
      background: var(--dark-card);
      color: var(--text-primary);
    }

    /* Textarea */
    textarea {
      min-height: 100px;
      resize: vertical;
    }

    /* Upload Area */
    .upload-area {
      border: 2px dashed var(--border-color);
      border-radius: var(--radius-md);
      padding: 30px 20px;
      text-align: center;
      background: rgba(45, 55, 72, 0.3);
      transition: all 0.3s ease;
      cursor: pointer;
      margin-bottom: 20px;
    }

    .upload-area.drag-over {
      border-color: var(--primary);
      background: rgba(99, 102, 241, 0.1);
      transform: scale(0.98);
    }

    .upload-area i {
      font-size: 3rem;
      color: var(--text-muted);
      margin-bottom: 12px;
    }

    .upload-area p {
      color: var(--text-secondary);
      margin-bottom: 8px;
    }

    .upload-area small {
      color: var(--text-muted);
      font-size: 0.8rem;
    }

    /* Hidden file input */
    #file-input {
      display: none;
    }

    /* Preview Grid - Sama seperti index.php */
    .preview-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
      gap: 15px;
      margin-top: 20px;
    }

    .multiple-photo-thumb {
      position: relative;
      cursor: pointer;
      display: inline-block;
    }

    .foto-thumb {
      width: 100%;
      height: 120px;
      border-radius: var(--radius-md);
      object-fit: cover;
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
      border: 2px solid var(--primary);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .foto-thumb:hover {
      transform: scale(1.05) rotate(2deg);
      border-color: var(--secondary);
    }

    .photo-count-badge {
      position: absolute;
      bottom: -5px;
      right: -5px;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      color: white;
      border-radius: 20px;
      padding: 2px 8px;
      font-size: 0.7rem;
      font-weight: bold;
    }

    .remove-file-btn {
      position: absolute;
      top: -10px;
      right: -10px;
      background: rgba(239, 68, 68, 0.9);
      border: none;
      color: white;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      cursor: pointer;
      font-size: 0.8rem;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
      z-index: 10;
    }

    .remove-file-btn:hover {
      background: var(--danger);
      transform: scale(1.1);
    }

    .preview-item {
      position: relative;
    }

    /* Counter info */
    .photo-counter {
      display: inline-block;
      margin-top: 8px;
      padding: 4px 12px;
      background: rgba(99, 102, 241, 0.2);
      border-radius: var(--radius-full);
      font-size: 0.75rem;
      color: var(--primary);
    }

    /* Session info - CLICKABLE */
    .session-info {
      background: rgba(99, 102, 241, 0.1);
      border-radius: var(--radius-sm);
      padding: 12px 16px;
      margin-bottom: 24px;
      border-left: 4px solid var(--primary);
      font-size: 0.9rem;
      color: var(--text-secondary);
      display: flex;
      align-items: center;
      gap: 12px;
      animation: fadeInUp 0.5s ease forwards;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .session-info:hover {
      background: rgba(99, 102, 241, 0.2);
      transform: translateX(5px);
    }

    .session-info i {
      color: var(--primary);
      font-size: 1.2rem;
    }

    /* Submit Button */
    .btn-submit {
      width: 100%;
      padding: 18px;
      background: var(--gradient);
      border: none;
      border-radius: var(--radius-md);
      color: white;
      font-weight: 700;
      font-size: 1.1rem;
      text-transform: uppercase;
      letter-spacing: 1px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      position: relative;
      overflow: hidden;
      margin-top: 10px;
      animation: fadeInUp 0.5s ease forwards;
      animation-delay: 0.45s;
    }

    .btn-submit::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.5s ease;
    }

    .btn-submit:hover {
      transform: translateY(-3px);
      box-shadow: 0 20px 30px -5px rgba(99, 102, 241, 0.7);
    }

    .btn-submit:hover::before {
      left: 100%;
    }

    .btn-submit:active {
      transform: scale(0.98);
    }

    .btn-submit i {
      font-size: 1.2rem;
    }

    .btn-submit:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    /* Modal Foto */
    .modal {
      display: none;
      position: fixed;
      z-index: 2000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.95);
      backdrop-filter: blur(15px);
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      position: relative;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .modal-img {
      max-width: 95%;
      max-height: 85%;
      width: auto;
      height: auto;
      border-radius: 20px;
      box-shadow: 0 30px 60px rgba(0,0,0,0.5);
      border: 4px solid rgba(255,255,255,0.2);
      object-fit: contain;
      transition: transform 0.1s ease;
      cursor: grab;
    }

    .modal-img:active {
      cursor: grabbing;
    }

    .close-modal {
      position: fixed;
      top: 20px;
      right: 20px;
      background: rgba(255,255,255,0.2);
      border: none;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
      color: white;
      z-index: 2001;
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
      backdrop-filter: blur(10px);
    }

    .close-modal:hover {
      background: rgba(255,255,255,0.4);
      transform: scale(1.05);
    }

    .zoom-controls {
      position: fixed;
      bottom: 100px;
      right: 20px;
      display: flex;
      gap: 10px;
      background: rgba(0,0,0,0.6);
      backdrop-filter: blur(10px);
      padding: 8px 12px;
      border-radius: 50px;
      z-index: 2001;
    }

    .zoom-btn {
      background: rgba(255,255,255,0.2);
      border: none;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      font-size: 1.2rem;
      font-weight: bold;
      cursor: pointer;
      color: white;
      transition: all 0.2s ease;
    }

    .zoom-btn:active {
      transform: scale(0.95);
    }

    .photo-indicator {
      position: fixed;
      bottom: 170px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(0,0,0,0.7);
      backdrop-filter: blur(10px);
      color: white;
      padding: 6px 14px;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 600;
      z-index: 2002;
      pointer-events: none;
      white-space: nowrap;
    }

    /* Bottom Navigation */
    .bottom-nav {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: calc(100% - 32px);
      max-width: 400px;
      background: rgba(26, 35, 50, 0.9);
      backdrop-filter: var(--blur);
      -webkit-backdrop-filter: var(--blur);
      border-radius: var(--radius-full);
      padding: 8px 12px;
      display: flex;
      justify-content: space-around;
      box-shadow: 0 25px 50px -12px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      z-index: 1000;
      opacity: 0;
      animation: bottomNavFadeIn 0.4s ease forwards;
      animation-delay: 0.2s;
    }

    @keyframes bottomNavFadeIn {
      0% {
        opacity: 0;
        transform: translateX(-50%) translateY(10px);
      }
      100% {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
      }
    }

    .nav-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 12px 16px;
      border-radius: var(--radius-full);
      color: var(--text-secondary);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      font-size: 0.8rem;
      flex: 1;
      gap: 4px;
    }

    .nav-item span {
      font-size: 1.4rem;
      transition: transform 0.3s ease;
    }

    .nav-item p {
      margin: 0;
      font-size: 0.7rem;
    }

    .nav-item:hover span {
      transform: translateY(-2px);
    }

    .nav-item.active {
      background: var(--gradient);
      color: white;
      box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.5);
    }

    .nav-item.active span {
      color: white;
    }

    /* Responsive */
    @media (max-width: 768px) {
      body {
        padding: 16px 16px 90px 16px;
      }

      .header {
        flex-direction: column;
        gap: 15px;
        padding: 16px;
      }

      h2 {
        font-size: 1.5rem;
      }

      h2 i {
        padding: 10px;
        font-size: 1.2rem;
      }

      .card {
        padding: 24px;
      }

      .session-info {
        flex-direction: column;
        text-align: center;
        gap: 8px;
      }

      .preview-grid {
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 12px;
      }
      
      .foto-thumb {
        height: 100px;
      }

      .bottom-nav {
        width: calc(100% - 24px);
        padding: 6px 8px;
      }

      .nav-item {
        padding: 10px 12px;
      }

      .nav-item span {
        font-size: 1.3rem;
      }

      .close-modal {
        width: 40px;
        height: 40px;
        font-size: 22px;
        top: 15px;
        right: 15px;
      }
      
      .zoom-controls {
        bottom: 90px;
        right: 15px;
        padding: 6px 10px;
      }
      
      .zoom-btn {
        width: 36px;
        height: 36px;
        font-size: 1rem;
      }
      
      .photo-indicator {
        bottom: 150px;
      }
    }

    @media (min-width: 769px) {
      .bottom-nav {
        width: auto;
        min-width: 400px;
      }
      
      .nav-item {
        flex-direction: row;
        gap: 8px;
        font-size: 0.95rem;
      }
      
      .nav-item span {
        font-size: 1.2rem;
        margin-bottom: 0;
      }
      
      .nav-item p {
        font-size: 0.9rem;
      }
      
      .zoom-controls {
        bottom: 30px;
        right: 30px;
      }
    }
  </style>
</head>
<body>

<div class="header">
  <h2>
    <i class="fas fa-plus-circle"></i> 
    TAMBAH TRADE
  </h2>
</div>

<div class="form-container">
  <div class="card">
    <div class="session-info" id="sessionInfoBtn">
      <i class="fas fa-info-circle"></i>
      <span>SETIAP SESI MEMILIKI ANALISA BERBEDA</span>
      <i class="fas fa-chevron-right" style="margin-left: auto; font-size: 0.8rem;"></i>
    </div>

    <form action="simpan.php" method="POST" enctype="multipart/form-data" id="tradeForm">
      <!-- Tanggal -->
      <div class="form-group">
        <label>
          <i class="fas fa-calendar-alt"></i>
          TANGGAL TRADING
        </label>
        <input type="date" name="tanggal" required value="<?= date('Y-m-d') ?>">
      </div>

      <!-- Session 1 -->
      <div class="form-group">
        <label>
          <i class="fas fa-chart-line"></i>
          SESSION 1 (0–50%)
        </label>
        <select name="session1" required>
          <option value="skip" selected>⏸️ SKIP (Default)</option>
          <option value="profit">📈 PROFIT</option>
          <option value="lose">📉 LOSS</option>
        </select>
      </div>

      <!-- Session 2 -->
      <div class="form-group">
        <label>
          <i class="fas fa-chart-line"></i>
          SESSION 2 (0-100%)
        </label>
        <select name="session2" required>
          <option value="skip" selected>⏸️ SKIP (Default)</option>
          <option value="profit">📈 PROFIT</option>
          <option value="lose">📉 LOSS</option>
        </select>
      </div>

      <!-- Session 3 -->
      <div class="form-group">
        <label>
          <i class="fas fa-chart-line"></i>
          SESSION 3 (50-0%)
        </label>
        <select name="session3" required>
          <option value="skip" selected>⏸️ SKIP (Default)</option>
          <option value="profit">📈 PROFIT</option>
          <option value="lose">📉 LOSS</option>
        </select>
      </div>

      <!-- Session 4 -->
      <div class="form-group">
        <label>
          <i class="fas fa-chart-line"></i>
          SESSION 4 (100-50%)
        </label>
        <select name="session4" required>
          <option value="skip" selected>⏸️ SKIP (Default)</option>
          <option value="profit">📈 PROFIT</option>
          <option value="lose">📉 LOSS</option>
        </select>
      </div>

      <!-- Session 5 -->
      <div class="form-group">
        <label>
          <i class="fas fa-chart-line"></i>
          SESSION 5 (100-50%)
        </label>
        <select name="session5" required>
          <option value="skip" selected>⏸️ SKIP (Default)</option>
          <option value="profit">📈 PROFIT</option>
          <option value="lose">📉 LOSS</option>
        </select>
      </div>

      <!-- MULTI FOTO UPLOAD AREA -->
      <div class="form-group">
        <label>
          <i class="fas fa-images"></i>
          SCREENSHOT CHART (MAX 3 FOTO)
        </label>
        
        <!-- Drag & Drop Area -->
        <div class="upload-area" id="uploadArea">
          <i class="fas fa-cloud-upload-alt"></i>
          <p>DRAG & DROP FOTO DISINI ATAU KLIK UNTUK MEMILIH</p>
          <small>FORMAT: JPG, PNG, JPEG, GIF | MAX 5MB PER FILE | MAX 3 FILE</small>
        </div>
        
        <input type="file" name="fotos[]" id="file-input" accept="image/*" multiple>
        
        <!-- Counter Info -->
        <div id="photoCounter" class="photo-counter">0 / 3 FOTO</div>
        
        <!-- Preview Grid -->
        <div class="preview-grid" id="previewGrid"></div>
        
        <small style="display: block; margin-top: 12px; color: var(--text-muted);">
          <i class="fas fa-info-circle"></i> KLIK FOTO UNTUK MEMPERBESAR | GESER KANAN/KIRI UNTUK NAVIGASI | PINCH ZOOM
        </small>
      </div>

      <!-- Catatan -->
      <div class="form-group">
        <label>
          <i class="fas fa-sticky-note"></i>
          CATATAN TRADING
        </label>
        <textarea name="note" placeholder="Tulis analisis, emosi, atau catatan penting lainnya..."></textarea>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn-submit" id="submitBtn">
        <i class="fas fa-save"></i>
        SIMPAN DATA TRADING
      </button>
    </form>
  </div>
</div>

<!-- Bottom Navigation -->
<div class="bottom-nav">
  <a href="index.php" class="nav-item">
    <span>🏠</span>
    <p>HOME</p>
  </a>
  <a href="tambah.php" class="nav-item active">
    <span>📝</span>
    <p>TAMBAH</p>
  </a>
  <a href="statistik.php" class="nav-item">
    <span>📊</span>
    <p>STATISTIK</p>
  </a>
</div>

<!-- Modal Foto -->
<div id="modal" class="modal">
  <div class="modal-content">
    <button class="close-modal" id="closeModalBtn">✕</button>
    <img id="modal-img" class="modal-img" alt="Preview">
    <div class="zoom-controls">
      <button class="zoom-btn" id="zoomOutBtn">−</button>
      <button class="zoom-btn reset-zoom-btn" id="resetZoomBtn">⟳</button>
      <button class="zoom-btn" id="zoomInBtn">+</button>
    </div>
  </div>
</div>

<script>
// Fungsi untuk menampilkan popup info sesi
function showSessionInfo() {
    Swal.fire({
        title: '📊 ANALISA SETIAP SESI',
        html: `
            <div style="text-align: left;">
                <div style="margin-bottom: 15px; padding: 10px; background: rgba(99,102,241,0.1); border-radius: 12px;">
                    <strong style="color: #10b981;">🟢 SESSION 1 (0–50%)</strong>
                    <p style="margin: 5px 0 0 0; font-size: 0.9rem;">Entry saat candle break dan retest target profit 50%</p>
                </div>
                <div style="margin-bottom: 15px; padding: 10px; background: rgba(99,102,241,0.1); border-radius: 12px;">
                    <strong style="color: #10b981;">🟢 SESSION 2 (0-100%)</strong>
                    <p style="margin: 5px 0 0 0; font-size: 0.9rem;">Entry saat candle break dan retest target profit 100%</p>
                </div>
                <div style="margin-bottom: 15px; padding: 10px; background: rgba(99,102,241,0.1); border-radius: 12px;">
                    <strong style="color: #f59e0b;">🟡 SESSION 3 (50-0%)</strong>
                    <p style="margin: 5px 0 0 0; font-size: 0.9rem;">Entry saat candle melewati target profit 50%</p>
                </div>
                <div style="margin-bottom: 15px; padding: 10px; background: rgba(99,102,241,0.1); border-radius: 12px;">
                    <strong style="color: #ef4444;">🔴 SESSION 4 (100-50%)</strong>
                    <p style="margin: 5px 0 0 0; font-size: 0.9rem;">Entry saat candle menyentuh target profit 100%</p>
                </div>
                <div style="margin-bottom: 5px; padding: 10px; background: rgba(99,102,241,0.1); border-radius: 12px;">
                    <strong style="color: #8b5cf6;">🟣 SESSION 5 (100-50%)</strong>
                    <p style="margin: 5px 0 0 0; font-size: 0.9rem;">Entry saat candle sudah melewati target profit 150%</p>
                </div>
            </div>
        `,
        icon: 'info',
        confirmButtonColor: '#6366f1',
        background: '#1a2332',
        color: '#f8fafc',
        confirmButtonText: 'Saya Mengerti',
        width: '90%',
        maxWidth: '500px'
    });
}

// Event listener untuk klik session info
document.getElementById('sessionInfoBtn').addEventListener('click', showSessionInfo);

// Cek apakah ada parameter dari simpan.php
document.addEventListener('DOMContentLoaded', function() {
    // Ambil parameter dari URL
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');
    const error = urlParams.get('error');
    
    // Tampilkan alert jika ada parameter success - LANGSUNG REDIRECT KE INDEX
    if (success) {
        Swal.fire({
            title: 'Berhasil!',
            text: success,
            icon: 'success',
            confirmButtonColor: '#6366f1',
            background: '#1a2332',
            color: '#f8fafc',
            confirmButtonText: 'OK',
            timer: 2000,
            showConfirmButton: true
        }).then(() => {
            window.location.href = 'index.php';
        });
    }
    
    // Tampilkan alert jika ada parameter error
    if (error) {
        Swal.fire({
            title: 'Gagal!',
            text: error,
            icon: 'error',
            confirmButtonColor: '#ef4444',
            background: '#1a2332',
            color: '#f8fafc',
            confirmButtonText: 'OK'
        }).then(() => {
            const newUrl = window.location.pathname;
            window.history.replaceState({}, document.title, newUrl);
        });
    }
    
    // Smooth page transition
    document.body.style.animation = 'fadeIn 0.3s ease';
    
    // Smooth transition saat pindah halaman
    const links = document.querySelectorAll('a:not([target="_blank"]):not([href^="http"]):not([href*="logout"])');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.target === '_blank' || this.href.startsWith('javascript:') || this.href.startsWith('#')) {
                return;
            }
            if (this.href.includes('logout')) {
                return;
            }
            
            e.preventDefault();
            const href = this.href;
            
            document.body.style.animation = 'fadeOut 0.2s ease forwards';
            
            setTimeout(() => {
                window.location.href = href;
            }, 200);
        });
    });
});

// Multi photo upload with preview
(function() {
    const MAX_FILES = 3;
    const MAX_FILE_SIZE = 5 * 1024 * 1024;
    const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
    
    const fileInput = document.getElementById('file-input');
    const uploadArea = document.getElementById('uploadArea');
    const previewGrid = document.getElementById('previewGrid');
    const submitBtn = document.getElementById('submitBtn');
    const photoCounter = document.getElementById('photoCounter');
    
    let selectedFiles = [];
    let currentImageUrls = [];
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function validateFile(file) {
        if (!ALLOWED_TYPES.includes(file.type)) {
            return { valid: false, error: `Format ${file.type} tidak didukung. Gunakan JPG, PNG, atau GIF.` };
        }
        if (file.size > MAX_FILE_SIZE) {
            return { valid: false, error: `File ${file.name} melebihi 5MB (${formatFileSize(file.size)})` };
        }
        return { valid: true };
    }
    
    function showAlert(message, type = 'error') {
        Swal.fire({
            title: type === 'error' ? 'Perhatian!' : 'Informasi',
            text: message,
            icon: type,
            confirmButtonColor: type === 'error' ? '#ef4444' : '#6366f1',
            background: '#1a2332',
            color: '#f8fafc',
            timer: 3000,
            showConfirmButton: true,
            confirmButtonText: 'OK'
        });
    }
    
    function updatePhotoCounter() {
        photoCounter.textContent = `${selectedFiles.length} / ${MAX_FILES} FOTO`;
        if (selectedFiles.length === MAX_FILES) {
            photoCounter.style.color = '#f59e0b';
            photoCounter.style.background = 'rgba(245, 158, 11, 0.2)';
        } else {
            photoCounter.style.color = 'var(--primary)';
            photoCounter.style.background = 'rgba(99, 102, 241, 0.2)';
        }
    }
    
    function updatePreview() {
        previewGrid.innerHTML = '';
        currentImageUrls = [];
        
        if (selectedFiles.length === 0) {
            previewGrid.style.display = 'none';
            updatePhotoCounter();
            return;
        }
        
        previewGrid.style.display = 'grid';
        
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            const previewItem = document.createElement('div');
            previewItem.className = 'preview-item';
            
            const thumbDiv = document.createElement('div');
            thumbDiv.className = 'multiple-photo-thumb';
            thumbDiv.setAttribute('data-photos', '');
            thumbDiv.setAttribute('data-index', index);
            
            const img = document.createElement('img');
            img.className = 'foto-thumb';
            img.alt = file.name;
            
            const removeBtn = document.createElement('button');
            removeBtn.className = 'remove-file-btn';
            removeBtn.innerHTML = '✕';
            removeBtn.title = 'Hapus foto';
            removeBtn.onclick = (e) => {
                e.stopPropagation();
                removeFile(index);
            };
            
            thumbDiv.appendChild(img);
            thumbDiv.appendChild(removeBtn);
            previewItem.appendChild(thumbDiv);
            
            // Klik untuk melihat foto besar
            thumbDiv.onclick = (e) => {
                if (e.target !== removeBtn) {
                    openModalWithPhotos(currentImageUrls, index);
                }
            };
            
            reader.onload = (e) => {
                img.src = e.target.result;
                currentImageUrls[index] = e.target.result;
                thumbDiv.setAttribute('data-photos', JSON.stringify([e.target.result]));
            };
            reader.readAsDataURL(file);
            
            previewGrid.appendChild(previewItem);
        });
        
        updatePhotoCounter();
        updateFileInput();
    }
    
    function removeFile(index) {
        selectedFiles.splice(index, 1);
        currentImageUrls.splice(index, 1);
        updatePreview();
    }
    
    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        fileInput.files = dataTransfer.files;
        validateAndToggleSubmit();
    }
    
    function addFiles(files) {
        const newFiles = Array.from(files);
        const errors = [];
        
        for (const file of newFiles) {
            const validation = validateFile(file);
            if (!validation.valid) {
                errors.push(validation.error);
                continue;
            }
            
            if (selectedFiles.length >= MAX_FILES) {
                errors.push(`Maksimal ${MAX_FILES} foto. Foto ${file.name} tidak ditambahkan.`);
                continue;
            }
            
            const isDuplicate = selectedFiles.some(existingFile => 
                existingFile.name === file.name && existingFile.size === file.size
            );
            
            if (isDuplicate) {
                errors.push(`File ${file.name} sudah ditambahkan.`);
                continue;
            }
            
            selectedFiles.push(file);
        }
        
        if (errors.length > 0) {
            showAlert(errors.join('\n'), 'error');
        }
        
        updatePreview();
    }
    
    function validateAndToggleSubmit() {
        if (selectedFiles.length > MAX_FILES) {
            submitBtn.disabled = true;
            submitBtn.title = `Maksimal ${MAX_FILES} foto`;
        } else {
            submitBtn.disabled = false;
            submitBtn.title = '';
        }
    }
    
    // Event listeners untuk upload
    uploadArea.addEventListener('click', () => {
        fileInput.click();
    });
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('drag-over');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('drag-over');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            addFiles(files);
        }
    });
    
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            addFiles(e.target.files);
        }
    });
    
    const form = document.getElementById('tradeForm');
    form.addEventListener('submit', function(e) {
        if (selectedFiles.length > MAX_FILES) {
            e.preventDefault();
            showAlert(`Maksimal upload ${MAX_FILES} foto. Saat ini ${selectedFiles.length} foto.`, 'error');
            return;
        }
        
        for (const file of selectedFiles) {
            const validation = validateFile(file);
            if (!validation.valid) {
                e.preventDefault();
                showAlert(validation.error, 'error');
                return;
            }
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Menyimpan...';
    });
    
    updatePreview();
})();

// Modal Foto dengan Zoom & Swipe
(function() {
  const modal = document.getElementById("modal");
  const modalImg = document.getElementById("modal-img");
  const closeBtn = document.getElementById("closeModalBtn");
  
  let scale = 1, translateX = 0, translateY = 0, isDragging = false, startX = 0, startY = 0;
  let currentPhotoList = [], currentPhotoIndex = 0;
  let touchStartX = 0, touchStartTime = 0, isSwiping = false;
  let initialPinchDistance = null, initialPinchScale = 1;
  
  function applyTransform() {
    modalImg.style.transform = `translate(${translateX}px, ${translateY}px) scale(${scale})`;
  }
  
  function resetZoomAndPan() { scale = 1; translateX = 0; translateY = 0; applyTransform(); }
  function zoomIn() { scale = Math.min(scale + 0.25, 4); applyTransform(); }
  function zoomOut() { scale = Math.max(scale - 0.25, 1); if(scale === 1) { translateX = 0; translateY = 0; } applyTransform(); }
  
  function loadImage(src) { modalImg.src = src; resetZoomAndPan(); }
  
  function nextPhoto() {
    if(currentPhotoList.length > 1) {
      currentPhotoIndex = (currentPhotoIndex + 1) % currentPhotoList.length;
      loadImage(currentPhotoList[currentPhotoIndex]);
      showPhotoIndicator();
    }
  }
  
  function prevPhoto() {
    if(currentPhotoList.length > 1) {
      currentPhotoIndex = (currentPhotoIndex - 1 + currentPhotoList.length) % currentPhotoList.length;
      loadImage(currentPhotoList[currentPhotoIndex]);
      showPhotoIndicator();
    }
  }
  
  function showPhotoIndicator() {
    if(currentPhotoList.length <= 1) return;
    const existing = document.querySelector('.photo-indicator');
    if(existing) existing.remove();
    const indicator = document.createElement('div');
    indicator.className = 'photo-indicator';
    indicator.innerHTML = `${currentPhotoIndex + 1} / ${currentPhotoList.length}`;
    document.body.appendChild(indicator);
    setTimeout(() => indicator.remove(), 1500);
  }
  
  function showSwipeHint() {
    if(!localStorage.getItem('swipe_hint_shown') && currentPhotoList.length > 1) {
      const hint = document.createElement('div');
      hint.className = 'photo-indicator';
      hint.innerHTML = '👈 GESER KIRI/KANAN 👉';
      hint.style.bottom = '220px';
      document.body.appendChild(hint);
      localStorage.setItem('swipe_hint_shown', 'true');
      setTimeout(() => hint.remove(), 2500);
    }
  }
  
  function closeModal() {
    modal.style.display = 'none';
    document.body.classList.remove('modal-open');
    resetZoomAndPan();
  }
  
  function openModalWithPhotos(photoUrls, initialIndex = 0) {
    if(!photoUrls || photoUrls.length === 0) return;
    currentPhotoList = photoUrls;
    currentPhotoIndex = Math.min(initialIndex, currentPhotoList.length - 1);
    loadImage(currentPhotoList[currentPhotoIndex]);
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');
    setTimeout(() => { showPhotoIndicator(); showSwipeHint(); }, 500);
  }
  
  function getTouchDistance(touches) {
    const dx = touches[0].clientX - touches[1].clientX;
    const dy = touches[0].clientY - touches[1].clientY;
    return Math.sqrt(dx*dx + dy*dy);
  }
  
  // Event listeners
  modal.addEventListener('click', function(e) {
    if(e.target === modal || e.target.classList.contains('modal-content')) closeModal();
  });
  
  closeBtn.addEventListener('click', e => { e.stopPropagation(); closeModal(); });
  
  // Mouse drag
  modalImg.addEventListener('mousedown', e => {
    if(scale > 1) {
      e.preventDefault();
      isDragging = true;
      startX = e.clientX - translateX;
      startY = e.clientY - translateY;
      modalImg.style.transition = 'none';
      modalImg.style.cursor = 'grabbing';
    }
  });
  
  window.addEventListener('mousemove', e => {
    if(isDragging && scale > 1) {
      translateX = e.clientX - startX;
      translateY = e.clientY - startY;
      applyTransform();
    }
  });
  
  window.addEventListener('mouseup', () => {
    if(isDragging) {
      isDragging = false;
      modalImg.style.cursor = 'grab';
      modalImg.style.transition = '';
    }
  });
  
  // Touch events
  modalImg.addEventListener('touchstart', e => {
    if(e.touches.length === 1) {
      if(scale > 1) {
        e.preventDefault();
        isDragging = true;
        startX = e.touches[0].clientX - translateX;
        startY = e.touches[0].clientY - translateY;
        modalImg.style.transition = 'none';
        isSwiping = false;
      } else {
        isSwiping = true;
        touchStartX = e.touches[0].clientX;
        touchStartTime = Date.now();
      }
    } else if(e.touches.length === 2) {
      e.preventDefault();
      initialPinchDistance = getTouchDistance(e.touches);
      initialPinchScale = scale;
      isDragging = false;
      isSwiping = false;
    }
  });
  
  modalImg.addEventListener('touchmove', e => {
    if(e.touches.length === 1) {
      if(isDragging && scale > 1) {
        e.preventDefault();
        translateX = e.touches[0].clientX - startX;
        translateY = e.touches[0].clientY - startY;
        applyTransform();
      } else if(isSwiping && scale === 1 && currentPhotoList.length > 1) {
        const diffX = e.touches[0].clientX - touchStartX;
        if(Math.abs(diffX) > 10) e.preventDefault();
      }
    } else if(e.touches.length === 2 && initialPinchDistance) {
      e.preventDefault();
      const newDistance = getTouchDistance(e.touches);
      let newScale = initialPinchScale * (newDistance / initialPinchDistance);
      newScale = Math.min(Math.max(1, newScale), 4);
      scale = newScale;
      if(scale === 1) { translateX = 0; translateY = 0; }
      applyTransform();
      if(scale > 1) isSwiping = false;
    }
  });
  
  modalImg.addEventListener('touchend', e => {
    if(isDragging) {
      isDragging = false;
      modalImg.style.transition = '';
    }
    if(isSwiping && scale === 1 && currentPhotoList.length > 1 && e.changedTouches[0]) {
      const diffX = e.changedTouches[0].clientX - touchStartX;
      const diffTime = Date.now() - touchStartTime;
      if(Math.abs(diffX) > 50 && diffTime < 300) {
        if(diffX > 0) prevPhoto();
        else nextPhoto();
      }
    }
    isSwiping = false;
    initialPinchDistance = null;
  });
  
  // Wheel zoom
  modalImg.addEventListener('wheel', e => {
    e.preventDefault();
    let newScale = scale + (e.deltaY > 0 ? -0.05 : 0.05);
    newScale = Math.min(Math.max(1, newScale), 4);
    scale = newScale;
    if(scale === 1) { translateX = 0; translateY = 0; }
    applyTransform();
  });
  
  // Keyboard controls
  document.addEventListener('keydown', e => {
    if(modal.style.display === 'flex') {
      if(e.key === 'ArrowLeft') { e.preventDefault(); prevPhoto(); }
      else if(e.key === 'ArrowRight') { e.preventDefault(); nextPhoto(); }
      else if(e.key === 'Escape') { closeModal(); }
      else if(e.key === '+' || e.key === '=') { e.preventDefault(); zoomIn(); }
      else if(e.key === '-' || e.key === '_') { e.preventDefault(); zoomOut(); }
      else if(e.key === '0' || e.key === 'r') { e.preventDefault(); resetZoomAndPan(); }
    }
  });
  
  // Zoom buttons
  document.getElementById('zoomInBtn').addEventListener('click', e => { e.stopPropagation(); zoomIn(); });
  document.getElementById('zoomOutBtn').addEventListener('click', e => { e.stopPropagation(); zoomOut(); });
  document.getElementById('resetZoomBtn').addEventListener('click', e => { e.stopPropagation(); resetZoomAndPan(); });
  
  modalImg.style.cursor = 'grab';
  
  // Expose function globally
  window.openModalWithPhotos = openModalWithPhotos;
})();
</script>

</body>
</html>