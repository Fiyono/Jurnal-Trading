<?php require_once 'session.php'; ?>
<?php require_once 'koneksi.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
  
  <title>JURNAL TRADING</title>
  
  <link rel="icon" href="/assets/favicon.png" type="image/png">  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
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

    body.modal-open {
      overflow: hidden;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 28px;
      flex-wrap: wrap;
      gap: 12px;
      background: rgba(26, 35, 50, 0.6);
      backdrop-filter: var(--blur);
      padding: 16px 24px;
      border-radius: var(--radius-lg);
      border: 1px solid rgba(255,255,255,0.05);
      box-shadow: var(--shadow-sm);
      animation: slideDown 0.5s ease forwards;
    }

    .header h2 {
      font-size: 1.9rem;
      font-weight: 700;
      background: var(--gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      letter-spacing: -0.5px;
      display: flex;
      align-items: center;
      gap: 12px;
      text-transform: uppercase;
    }

    .header h2 i {
      background: rgba(255,255,255,0.1);
      padding: 12px;
      border-radius: 20px;
      color: var(--primary);
      font-size: 1.4rem;
      -webkit-text-fill-color: initial;
    }

    .btn-logout {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      padding: 12px 24px;
      border-radius: var(--radius-full);
      font-weight: 600;
      font-size: 0.95rem;
      color: white;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      transition: all 0.3s ease;
      backdrop-filter: var(--blur);
      text-transform: uppercase;
    }

    .btn-logout:hover {
      background: rgba(255,255,255,0.1);
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }

    .search-container {
      background: rgba(26, 35, 50, 0.8);
      backdrop-filter: var(--blur);
      border-radius: var(--radius-lg);
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: var(--shadow-lg);
      border: 1px solid rgba(255,255,255,0.05);
      animation: fadeInUp 0.5s ease forwards;
    }

    .search-form {
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
    }

    .search-input {
      flex: 1;
      min-width: 250px;
      padding: 15px 20px;
      border: 2px solid var(--border-color);
      border-radius: var(--radius-md);
      font-size: 1rem;
      outline: none;
      background: rgba(45, 55, 72, 0.6);
      color: var(--text-primary);
      transition: all 0.3s ease;
      text-transform: uppercase;
    }

    .search-input:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
    }

    .search-input::placeholder {
      text-transform: none;
    }

    .search-btn {
      background: var(--gradient);
      color: white;
      border: none;
      padding: 15px 30px;
      border-radius: var(--radius-md);
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-transform: uppercase;
    }

    .search-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
    }

    .reset-btn {
      background: rgba(45, 55, 72, 0.8);
      color: var(--text-secondary);
      border: 2px solid var(--border-color);
      padding: 15px 30px;
      border-radius: var(--radius-md);
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-transform: uppercase;
    }

    .reset-btn:hover {
      background: rgba(45, 55, 72, 1);
      border-color: var(--primary);
      color: white;
    }

    .limit-selector {
      display: flex;
      align-items: center;
      gap: 15px;
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px solid var(--border-color);
      flex-wrap: wrap;
    }

    .limit-label {
      color: var(--text-secondary);
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
    }

    .limit-select {
      background: rgba(45, 55, 72, 0.8);
      color: var(--text-primary);
      border: 2px solid var(--border-color);
      padding: 10px 20px;
      border-radius: var(--radius-md);
      font-size: 0.9rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      outline: none;
      text-transform: uppercase;
    }

    .limit-select:hover, .limit-select:focus {
      border-color: var(--primary);
      background: rgba(45, 55, 72, 1);
    }

    .limit-info {
      color: var(--text-muted);
      font-size: 0.85rem;
      margin-left: auto;
      text-transform: uppercase;
    }

    .day-filter {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 15px;
      margin-bottom: 25px;
      justify-content: center;
    }

    .day-chip {
      padding: 10px 20px;
      border-radius: var(--radius-full);
      background: rgba(45, 55, 72, 0.6);
      color: var(--text-secondary);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      border: 2px solid var(--border-color);
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-transform: uppercase;
    }

    .day-chip:hover, .day-chip.active {
      background: var(--gradient);
      color: white;
      border-color: transparent;
      transform: translateY(-2px);
    }

    .pagination-container {
      margin-bottom: 20px;
    }

    .pagination {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      margin-top: 30px;
      flex-wrap: wrap;
      animation: fadeInUp 0.5s ease forwards;
      animation-delay: 0.3s;
    }

    .pagination a, .pagination span {
      padding: 12px 20px;
      border-radius: var(--radius-full);
      background: rgba(45, 55, 72, 0.6);
      color: var(--text-secondary);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      border: 1px solid var(--border-color);
      text-transform: uppercase;
    }

    .pagination a:hover, .pagination .active {
      background: var(--gradient);
      color: white;
      border-color: transparent;
    }

    .pagination .disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .pagination-info {
      text-align: center;
      margin-top: 15px;
      color: var(--text-secondary);
      font-size: 0.9rem;
      margin-bottom: 20px;
      text-transform: uppercase;
    }

    .desktop-table {
      display: block;
      background: rgba(26, 35, 50, 0.8);
      backdrop-filter: var(--blur);
      border-radius: var(--radius-lg);
      padding: 24px;
      box-shadow: var(--shadow-lg);
      border: 1px solid rgba(255,255,255,0.05);
      margin-bottom: 20px;
      overflow-x: auto;
      animation: fadeInUp 0.5s ease forwards;
    }

    .trading-table {
      width: 100%;
      border-collapse: collapse;
      min-width: 1000px;
    }

    .trading-table th, .trading-table td {
      padding: 20px 16px;
      border-bottom: 1px solid var(--border-color);
      vertical-align: middle;
      color: var(--text-primary);
      text-transform: uppercase;
    }

    .trading-table th {
      font-weight: 600;
      color: var(--text-secondary);
      text-transform: uppercase;
      background: rgba(45, 55, 72, 0.4);
      border-bottom: 2px solid var(--border-color);
    }

    .foto-thumb {
      width: 60px;
      height: 60px;
      border-radius: var(--radius-md);
      object-fit: cover;
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
      border: 2px solid var(--primary);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .foto-thumb:hover {
      transform: scale(1.1) rotate(2deg);
      border-color: var(--secondary);
    }

    .session-badge {
      display: inline-block;
      padding: 8px 20px;
      border-radius: var(--radius-full);
      font-weight: 700;
      font-size: 0.9rem;
      text-transform: uppercase;
      min-width: 85px;
      text-align: center;
    }

    .badge-profit { background: linear-gradient(135deg, #10b981, #059669); color: white; }
    .badge-lose { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
    .badge-neutral { background: linear-gradient(135deg, #64748b, #475569); color: white; }

    .note-cell {
      max-width: 200px;
      white-space: normal;
      word-wrap: break-word;
      line-height: 1.4;
    }
    
    .note-text {
      font-size: 0.85rem;
      color: var(--text-secondary);
      font-style: italic;
      text-transform: none;
    }
    
    .note-empty {
      opacity: 0.4;
      font-size: 0.8rem;
    }

    .btn-edit, .btn-delete {
      padding: 8px 15px;
      border-radius: var(--radius-full);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 0.85rem;
      font-weight: 600;
      transition: all 0.3s ease;
      text-transform: uppercase;
    }

    .btn-edit {
      background: var(--gradient);
      color: white;
    }

    .btn-edit:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(99, 102, 241, 0.4);
    }

    .btn-delete {
      background: rgba(239, 68, 68, 0.2);
      border: 1px solid var(--danger);
      color: #fecaca;
      cursor: pointer;
    }

    .btn-delete:hover {
      background: rgba(239, 68, 68, 0.4);
      transform: scale(1.05);
    }

    .action-buttons {
      display: flex;
      gap: 12px;
      align-items: center;
      justify-content: center;
    }

    .mobile-cards {
      display: none;
    }

    .trading-card {
      background: rgba(26, 35, 50, 0.8);
      backdrop-filter: var(--blur);
      border-radius: var(--radius-lg);
      padding: 20px;
      margin-bottom: 16px;
      box-shadow: var(--shadow-lg);
      border: 1px solid rgba(255,255,255,0.05);
      transition: all 0.3s ease;
      animation: fadeInUp 0.5s ease forwards;
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 16px;
      padding-bottom: 16px;
      border-bottom: 2px dashed var(--border-color);
    }

    .card-date {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--text-primary);
      display: flex;
      align-items: center;
      gap: 8px;
      text-transform: uppercase;
    }

    .card-content {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
      margin-bottom: 16px;
    }

    .card-session {
      background: rgba(45, 55, 72, 0.4);
      padding: 12px;
      border-radius: var(--radius-md);
      text-align: center;
    }

    .card-session-label {
      font-size: 0.8rem;
      color: var(--text-secondary);
      margin-bottom: 4px;
      text-transform: uppercase;
    }

    .card-note {
      margin-top: 16px;
      padding-top: 16px;
      border-top: 2px dashed var(--border-color);
    }
    
    .card-note-label {
      font-size: 0.75rem;
      color: var(--text-secondary);
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 6px;
      text-transform: uppercase;
    }
    
    .card-note-text {
      font-size: 0.85rem;
      color: var(--text-secondary);
      font-style: italic;
      line-height: 1.5;
      text-transform: none;
      background: rgba(45, 55, 72, 0.3);
      padding: 12px;
      border-radius: var(--radius-md);
    }

    .card-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 16px;
      padding-top: 16px;
      border-top: 2px dashed var(--border-color);
    }

    .btn-edit-mobile, .btn-delete-mobile {
      flex: 1;
      text-align: center;
      padding: 10px;
      border-radius: var(--radius-full);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      text-transform: uppercase;
    }

    .btn-edit-mobile {
      background: var(--gradient);
      color: white;
    }

    .btn-delete-mobile {
      background: rgba(239, 68, 68, 0.2);
      border: 1px solid var(--danger);
      color: #fecaca;
      cursor: pointer;
    }

    .empty-state {
      text-align: center;
      padding: 60px 20px;
      color: var(--text-secondary);
      text-transform: uppercase;
    }

    .search-info {
      background: rgba(99, 102, 241, 0.2);
      color: var(--text-primary);
      padding: 12px 20px;
      border-radius: var(--radius-full);
      margin-bottom: 20px;
      text-align: center;
      backdrop-filter: var(--blur);
      animation: fadeInUp 0.5s ease forwards;
      text-transform: uppercase;
    }

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
      text-transform: uppercase;
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

    .multiple-photo-thumb {
      position: relative;
      cursor: pointer;
      display: inline-block;
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

    @media (max-width: 768px) {
      body {
        padding: 16px 16px 90px 16px;
      }
      
      .desktop-table {
        display: none;
      }
      
      .mobile-cards {
        display: block;
      }
      
      .header {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .btn-logout { 
        width: 100%; 
        justify-content: center; 
      }
      
      .search-form { 
        flex-direction: column; 
      }
      
      .search-input, .search-btn, .reset-btn { 
        width: 100%; 
      }
      
      .limit-selector { 
        flex-direction: column; 
        align-items: stretch; 
      }
      
      .limit-info { 
        margin-left: 0; 
        text-align: center; 
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
    <i class="fas fa-chart-line"></i> 
    JURNAL TRADING
  </h2>
  <a href="logout.php" class="btn-logout">
    <i class="fas fa-sign-out-alt"></i> 
    <span>LOGOUT</span>
  </a>
</div>

<div class="search-container">
  <form method="GET" action="" class="search-form">
    <input type="text" 
           name="search" 
           class="search-input" 
           placeholder="CARI BERDASARKAN TANGGAL ATAU HARI..." 
           value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
    <button type="submit" class="search-btn">
      <i class="fas fa-search"></i> CARI
    </button>
    <?php if (isset($_GET['search']) && $_GET['search'] != ''): ?>
      <a href="index.php" class="reset-btn">
        <i class="fas fa-times"></i> RESET
      </a>
    <?php endif; ?>
  </form>

  <div class="limit-selector">
    <span class="limit-label"><i class="fas fa-eye"></i> TAMPILKAN:</span>
    <select class="limit-select" id="limitSelect" onchange="changeLimit()">
      <option value="10" <?= (isset($_GET['limit']) && $_GET['limit'] == '10') ? 'selected' : '' ?>>10 DATA</option>
      <option value="25" <?= (isset($_GET['limit']) && $_GET['limit'] == '25') ? 'selected' : '' ?>>25 DATA</option>
      <option value="50" <?= (isset($_GET['limit']) && $_GET['limit'] == '50') ? 'selected' : '' ?>>50 DATA</option>
      <option value="100" <?= (isset($_GET['limit']) && $_GET['limit'] == '100') ? 'selected' : '' ?>>100 DATA</option>
    </select>
    <span class="limit-info"><i class="fas fa-info-circle"></i> MENAMPILKAN DATA PER HALAMAN</span>
  </div>

  <div class="day-filter">
    <?php
    $days = [
      'SENIN' => 'Monday',
      'SELASA' => 'Tuesday',
      'RABU' => 'Wednesday',
      'KAMIS' => 'Thursday',
      'JUMAT' => 'Friday'
    ];
    
    $current_day = isset($_GET['day']) ? $_GET['day'] : '';
    
    foreach ($days as $day_id => $day_en) {
        $active = ($current_day == $day_en) ? 'active' : '';
        $params = $_GET;
        $params['day'] = $day_en;
        $params['page'] = 1;
        $url = '?' . http_build_query($params);
        echo "<a href='$url' class='day-chip $active'><i class='far fa-calendar-alt'></i> $day_id</a>";
    }
    
    if (!empty($current_day)) {
        echo "<a href='index.php' class='day-chip'><i class='fas fa-times'></i> RESET HARI</a>";
    }
    ?>
  </div>
</div>

<?php
function formatSession($value) {
    if ($value == 'profit') {
        return 'PROFIT';
    } elseif ($value == 'lose') {
        return 'LOSS';
    } elseif ($value == 'skip') {
        return 'SKIP';
    }
    return strtoupper($value);
}

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$allowed_limits = [10, 25, 50, 100];
if (!in_array($limit, $allowed_limits)) $limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$day = isset($_GET['day']) ? trim($_GET['day']) : '';

$where_conditions = [];

if (!empty($search)) {
    $search_escaped = mysqli_real_escape_string($koneksi, $search);
    $hari_indo = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
    $search_lower = strtolower($search);
    
    if (in_array($search_lower, $hari_indo)) {
        $day_map = ['senin'=>'Monday','selasa'=>'Tuesday','rabu'=>'Wednesday','kamis'=>'Thursday','jumat'=>'Friday'];
        $where_conditions[] = "DAYNAME(tanggal) = '{$day_map[$search_lower]}'";
    } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $search)) {
        $where_conditions[] = "tanggal = '$search_escaped'";
    } elseif (preg_match('/^\d{2}-\d{2}-\d{4}$/', $search)) {
        $parts = explode('-', $search);
        $where_conditions[] = "tanggal = '{$parts[2]}-{$parts[1]}-{$parts[0]}'";
    } else {
        $where_conditions[] = "tanggal LIKE '%$search_escaped%'";
    }
}

if (!empty($day)) {
    $day_escaped = mysqli_real_escape_string($koneksi, $day);
    $where_conditions[] = "DAYNAME(tanggal) = '$day_escaped'";
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

$count_query = "SELECT COUNT(*) as total FROM trades $where_clause";
$count_result = mysqli_query($koneksi, $count_query);
$total_data = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_data / $limit);

$query = "SELECT * FROM trades $where_clause ORDER BY tanggal DESC, id DESC LIMIT $offset, $limit";
$data = mysqli_query($koneksi, $query);

$hari_indo = [
  'Sunday' => 'MINGGU',
  'Monday' => 'SENIN',
  'Tuesday' => 'SELASA',
  'Wednesday' => 'RABU',
  'Thursday' => 'KAMIS',
  'Friday' => 'JUMAT',
  'Saturday' => 'SABTU'
];

if (!empty($search) || !empty($day)) {
    echo '<div class="search-info">';
    echo '<i class="fas fa-info-circle"></i> ';
    if (!empty($search)) echo "HASIL PENCARIAN: <strong>" . strtoupper(htmlspecialchars($search)) . "</strong> ";
    if (!empty($day)) echo "FILTER HARI: <strong>" . array_search($day, $hari_indo) . "</strong> ";
    echo "(" . $total_data . " DATA DITEMUKAN)";
    echo '</div>';
}

$success_msg = isset($_GET['success']) ? $_GET['success'] : '';
$error_msg = isset($_GET['error']) ? $_GET['error'] : '';

if (!empty($success_msg) || !empty($error_msg)) {
    if (!empty($success_msg)) {
        $message = addslashes($success_msg);
        echo '<script>
            Swal.fire({
                title: "BERHASIL!",
                text: "' . $message . '",
                icon: "success",
                timer: 2000,
                showConfirmButton: false,
                background: "#1a2332",
                color: "#f8fafc"
            }).then(() => {
                const url = new URL(window.location.href);
                url.searchParams.delete("success");
                url.searchParams.delete("error");
                window.history.replaceState({}, document.title, url.pathname + url.search);
            });
        </script>';
    } elseif (!empty($error_msg)) {
        $message = addslashes($error_msg);
        echo '<script>
            Swal.fire({
                title: "GAGAL!",
                text: "' . $message . '",
                icon: "error",
                background: "#1a2332",
                color: "#f8fafc",
                confirmButtonText: "OK"
            }).then(() => {
                const url = new URL(window.location.href);
                url.searchParams.delete("success");
                url.searchParams.delete("error");
                window.history.replaceState({}, document.title, url.pathname + url.search);
            });
        </script>';
    }
}
?>

<!-- DESKTOP VIEW - Table -->
<div class="desktop-table">
  <table class="trading-table">
    <thead>
      <tr>
        <th>TANGGAL</th>
        <th>FOTO</th>
        <th>SESI 1</th>
        <th>SESI 2</th>
        <th>SESI 3</th>
        <th>SESI 4</th>
        <th>SESI 5</th>
        <th>CATATAN</th>
        <th>AKSI</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($data) > 0) {
        while ($d = mysqli_fetch_array($data)) {
          $timestamp = strtotime($d['tanggal']);
          $tanggal_indonesia = $hari_indo[date('l', $timestamp)] . ', ' . date('d-m-Y', $timestamp);
          $photos = !empty($d['foto']) ? array_filter(array_map('trim', explode(',', $d['foto']))) : [];
          $photo_count = count($photos);
          $note = !empty($d['note']) ? htmlspecialchars($d['note']) : '';
          
          $s1 = formatSession($d['session1'] ?? 'skip');
          $s2 = formatSession($d['session2'] ?? 'skip');
          $s3 = formatSession($d['session3'] ?? 'skip');
          $s4 = formatSession($d['session4'] ?? 'skip');
          $s5 = formatSession($d['session5'] ?? 'skip');
          
          $badgeClass1 = $d['session1'] == 'profit' ? 'badge-profit' : ($d['session1'] == 'lose' ? 'badge-lose' : 'badge-neutral');
          $badgeClass2 = $d['session2'] == 'profit' ? 'badge-profit' : ($d['session2'] == 'lose' ? 'badge-lose' : 'badge-neutral');
          $badgeClass3 = $d['session3'] == 'profit' ? 'badge-profit' : ($d['session3'] == 'lose' ? 'badge-lose' : 'badge-neutral');
          $badgeClass4 = $d['session4'] == 'profit' ? 'badge-profit' : ($d['session4'] == 'lose' ? 'badge-lose' : 'badge-neutral');
          $badgeClass5 = $d['session5'] == 'profit' ? 'badge-profit' : ($d['session5'] == 'lose' ? 'badge-lose' : 'badge-neutral');
      ?>
        <tr>
          <td>
            <span style="font-weight:700; display: flex; align-items: center; gap: 8px;">
              <i class="far fa-calendar-alt" style="color: var(--primary);"></i>
              <?= strtoupper($tanggal_indonesia) ?>
            </span>
          </td>
          <td style="text-align:center; vertical-align:middle;">
            <?php if ($photo_count > 0) { ?>
              <div class="multiple-photo-thumb" data-photos='<?= htmlspecialchars(json_encode(array_values($photos)), ENT_QUOTES) ?>'>
                <img src="<?= htmlspecialchars($photos[0]) ?>" class="foto-thumb" alt="preview" loading="lazy">
                <?php if ($photo_count > 1): ?><span class="photo-count-badge">+<?= $photo_count-1 ?></span><?php endif; ?>
              </div>
            <?php } else { ?>
              <span style="opacity:0.3;"><i class="far fa-image fa-2x"></i></span>
            <?php } ?>
          </td>
          <td style="text-align: center;">
            <span class="session-badge <?= $badgeClass1 ?>"><?= $s1 ?></span>
          </td>
          <td style="text-align: center;">
            <span class="session-badge <?= $badgeClass2 ?>"><?= $s2 ?></span>
          </td>
          <td style="text-align: center;">
            <span class="session-badge <?= $badgeClass3 ?>"><?= $s3 ?></span>
          </td>
          <td style="text-align: center;">
            <span class="session-badge <?= $badgeClass4 ?>"><?= $s4 ?></span>
          </td>
          <td style="text-align: center;">
            <span class="session-badge <?= $badgeClass5 ?>"><?= $s5 ?></span>
          </td>
          <td class="note-cell">
            <?php if (!empty($note)): ?>
              <span class="note-text"><?= nl2br($note) ?></span>
            <?php else: ?>
              <span class="note-empty"><i class="fas fa-pen"></i> TIDAK ADA CATATAN</span>
            <?php endif; ?>
          </td>
          <td style="text-align: center;">
            <div class="action-buttons">
              <a href="edit.php?id=<?= $d['id'] ?>" class="btn-edit"><i class="fas fa-edit"></i> EDIT</a>
              <button class="btn-delete" onclick="confirmDelete(<?= $d['id'] ?>, '<?= addslashes($tanggal_indonesia) ?>')">
                <i class="fas fa-trash-alt"></i> HAPUS
              </button>
            </div>
          </td>
        </tr>
      <?php } } else { ?>
        <tr>
          <td colspan="9" class="empty-state">
            <i class="fas fa-search fa-3x"></i>
            <h3>TIDAK ADA DATA</h3>
          </td>
        </tr>
      <?php } ?>
    </tbody>
   </table>
</div>

<!-- MOBILE VIEW - Cards -->
<div class="mobile-cards">
  <?php
  mysqli_data_seek($data, 0);
  if (mysqli_num_rows($data) > 0) {
    while ($d = mysqli_fetch_array($data)) {
      $timestamp = strtotime($d['tanggal']);
      $tanggal_indonesia = $hari_indo[date('l', $timestamp)] . ', ' . date('d-m-Y', $timestamp);
      $photos = !empty($d['foto']) ? array_filter(array_map('trim', explode(',', $d['foto']))) : [];
      $photo_count = count($photos);
      $note = !empty($d['note']) ? htmlspecialchars($d['note']) : '';
      
      $s1 = formatSession($d['session1'] ?? 'skip');
      $s2 = formatSession($d['session2'] ?? 'skip');
      $s3 = formatSession($d['session3'] ?? 'skip');
      $s4 = formatSession($d['session4'] ?? 'skip');
      $s5 = formatSession($d['session5'] ?? 'skip');
      
      $badgeClass1 = $d['session1'] == 'profit' ? 'badge-profit' : ($d['session1'] == 'lose' ? 'badge-lose' : 'badge-neutral');
      $badgeClass2 = $d['session2'] == 'profit' ? 'badge-profit' : ($d['session2'] == 'lose' ? 'badge-lose' : 'badge-neutral');
      $badgeClass3 = $d['session3'] == 'profit' ? 'badge-profit' : ($d['session3'] == 'lose' ? 'badge-lose' : 'badge-neutral');
      $badgeClass4 = $d['session4'] == 'profit' ? 'badge-profit' : ($d['session4'] == 'lose' ? 'badge-lose' : 'badge-neutral');
      $badgeClass5 = $d['session5'] == 'profit' ? 'badge-profit' : ($d['session5'] == 'lose' ? 'badge-lose' : 'badge-neutral');
  ?>
  <div class="trading-card">
    <div class="card-header">
      <div class="card-date">
        <i class="far fa-calendar-alt"></i>
        <?= strtoupper($tanggal_indonesia) ?>
      </div>
      <?php if ($photo_count > 0) { ?>
        <div class="multiple-photo-thumb" data-photos='<?= htmlspecialchars(json_encode(array_values($photos)), ENT_QUOTES) ?>'>
          <img src="<?= htmlspecialchars($photos[0]) ?>" class="foto-thumb" alt="preview" loading="lazy">
          <?php if ($photo_count > 1): ?><span class="photo-count-badge">+<?= $photo_count-1 ?></span><?php endif; ?>
        </div>
      <?php } ?>
    </div>
    <div class="card-content">
      <div class="card-session">
        <div class="card-session-label">SESI 1</div>
        <div class="card-session-value">
          <span class="session-badge <?= $badgeClass1 ?>" style="padding:4px 12px; font-size:0.8rem;"><?= $s1 ?></span>
        </div>
      </div>
      <div class="card-session">
        <div class="card-session-label">SESI 2</div>
        <div class="card-session-value">
          <span class="session-badge <?= $badgeClass2 ?>" style="padding:4px 12px; font-size:0.8rem;"><?= $s2 ?></span>
        </div>
      </div>
      <div class="card-session">
        <div class="card-session-label">SESI 3</div>
        <div class="card-session-value">
          <span class="session-badge <?= $badgeClass3 ?>" style="padding:4px 12px; font-size:0.8rem;"><?= $s3 ?></span>
        </div>
      </div>
      <div class="card-session">
        <div class="card-session-label">SESI 4</div>
        <div class="card-session-value">
          <span class="session-badge <?= $badgeClass4 ?>" style="padding:4px 12px; font-size:0.8rem;"><?= $s4 ?></span>
        </div>
      </div>
      <div class="card-session">
        <div class="card-session-label">SESI 5</div>
        <div class="card-session-value">
          <span class="session-badge <?= $badgeClass5 ?>" style="padding:4px 12px; font-size:0.8rem;"><?= $s5 ?></span>
        </div>
      </div>
    </div>
    
    <?php if (!empty($note)): ?>
    <div class="card-note">
      <div class="card-note-label">
        <i class="fas fa-pen"></i> CATATAN
      </div>
      <div class="card-note-text">
        <?= nl2br($note) ?>
      </div>
    </div>
    <?php endif; ?>
    
    <div class="card-footer">
      <a href="edit.php?id=<?= $d['id'] ?>" class="btn-edit-mobile"><i class="fas fa-edit"></i> EDIT</a>
      <button class="btn-delete-mobile" onclick="confirmDelete(<?= $d['id'] ?>, '<?= addslashes($tanggal_indonesia) ?>')">
        <i class="fas fa-trash-alt"></i> HAPUS
      </button>
    </div>
  </div>
  <?php } } else { ?>
  <div class="trading-card" style="text-align:center;">
    <div style="padding:40px 20px;">
      <i class="fas fa-search" style="font-size:4rem; color:var(--text-muted); margin-bottom:20px;"></i>
      <h3 style="color:var(--text-secondary);">TIDAK ADA DATA</h3>
    </div>
  </div>
  <?php } ?>
</div>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
<div class="pagination-container">
  <div class="pagination">
    <?php
    function buildUrl($page) {
        $params = $_GET;
        $params['page'] = $page;
        unset($params['success']);
        unset($params['error']);
        return '?' . http_build_query($params);
    }
    ?>
    <?php if ($page > 1): ?>
      <a href="<?= buildUrl($page - 1) ?>"><i class="fas fa-chevron-left"></i> PREV</a>
    <?php else: ?>
      <span class="disabled"><i class="fas fa-chevron-left"></i> PREV</span>
    <?php endif; ?>

    <?php
    $start = max(1, $page - 2);
    $end = min($total_pages, $page + 2);
    
    if ($start > 1) {
        echo '<a href="' . buildUrl(1) . '">1</a>';
        if ($start > 2) echo '<span>...</span>';
    }
    
    for ($i = $start; $i <= $end; $i++) {
        if ($i == $page) {
            echo '<span class="active">' . $i . '</span>';
        } else {
            echo '<a href="' . buildUrl($i) . '">' . $i . '</a>';
        }
    }
    
    if ($end < $total_pages) {
        if ($end < $total_pages - 1) echo '<span>...</span>';
        echo '<a href="' . buildUrl($total_pages) . '">' . $total_pages . '</a>';
    }
    ?>

    <?php if ($page < $total_pages): ?>
      <a href="<?= buildUrl($page + 1) ?>">NEXT <i class="fas fa-chevron-right"></i></a>
    <?php else: ?>
      <span class="disabled">NEXT <i class="fas fa-chevron-right"></i></span>
    <?php endif; ?>
  </div>
  <div class="pagination-info">
    HALAMAN <?= $page ?> DARI <?= $total_pages ?> (TOTAL <?= $total_data ?> DATA | MENAMPILKAN <?= $limit ?> DATA PER HALAMAN)
  </div>
</div>
<?php endif; ?>

<div class="bottom-nav">
  <a href="index.php" class="nav-item active">
    <span>🏠</span>
    <p>HOME</p>
  </a>
  <a href="tambah.php" class="nav-item">
    <span>📝</span>
    <p>TAMBAH</p>
  </a>
  <a href="statistik.php" class="nav-item">
    <span>📊</span>
    <p>STATISTIK</p>
  </a>
</div>

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
function changeLimit() {
    var limit = document.getElementById('limitSelect').value;
    var urlParams = new URLSearchParams(window.location.search);
    urlParams.set('limit', limit);
    urlParams.set('page', 1);
    urlParams.delete('success');
    urlParams.delete('error');
    window.location.href = '?' + urlParams.toString();
}

function confirmDelete(id, tanggal) {
    Swal.fire({
        title: 'HAPUS DATA?',
        html: `APAKAH ANDA YAKIN INGIN MENGHAPUS DATA TRADING<br><strong>${tanggal.toUpperCase()}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'YA, HAPUS!',
        cancelButtonText: 'BATAL',
        background: '#1a2332',
        color: '#f8fafc'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `hapus.php?id=${id}`;
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.body.style.animation = 'fadeIn 0.3s ease';
    
    const links = document.querySelectorAll('a:not([target="_blank"]):not([href^="http"]):not([href*="logout"]):not([href*="hapus"])');
    
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
  
  function openModalWithPhotos(photoUrls) {
    if(!photoUrls || photoUrls.length === 0) return;
    currentPhotoList = photoUrls;
    currentPhotoIndex = 0;
    loadImage(currentPhotoList[0]);
    modal.style.display = 'flex';
    document.body.classList.add('modal-open');
    setTimeout(() => { showPhotoIndicator(); showSwipeHint(); }, 500);
  }
  
  function getTouchDistance(touches) {
    const dx = touches[0].clientX - touches[1].clientX;
    const dy = touches[0].clientY - touches[1].clientY;
    return Math.sqrt(dx*dx + dy*dy);
  }
  
  function setupPhotoThumbnails() {
    document.querySelectorAll(".multiple-photo-thumb").forEach(thumb => {
      if(thumb._clickHandler) thumb.removeEventListener('click', thumb._clickHandler);
      thumb._clickHandler = function(e) {
        e.stopPropagation();
        const photosAttr = this.getAttribute('data-photos');
        if(photosAttr) {
          try {
            const photos = JSON.parse(photosAttr);
            if(photos && photos.length) openModalWithPhotos(photos);
          } catch(e) { console.error(e); }
        }
      };
      thumb.addEventListener('click', thumb._clickHandler);
    });
  }
  
  modal.addEventListener('click', function(e) {
    if(e.target === modal || e.target.classList.contains('modal-content')) closeModal();
  });
  
  closeBtn.addEventListener('click', e => { e.stopPropagation(); closeModal(); });
  
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
  
  modalImg.addEventListener('wheel', e => {
    e.preventDefault();
    let newScale = scale + (e.deltaY > 0 ? -0.05 : 0.05);
    newScale = Math.min(Math.max(1, newScale), 4);
    scale = newScale;
    if(scale === 1) { translateX = 0; translateY = 0; }
    applyTransform();
  });
  
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
  
  document.getElementById('zoomInBtn').addEventListener('click', e => { e.stopPropagation(); zoomIn(); });
  document.getElementById('zoomOutBtn').addEventListener('click', e => { e.stopPropagation(); zoomOut(); });
  document.getElementById('resetZoomBtn').addEventListener('click', e => { e.stopPropagation(); resetZoomAndPan(); });
  
  modalImg.style.cursor = 'grab';
  setupPhotoThumbnails();
  new MutationObserver(() => setupPhotoThumbnails()).observe(document.body, { childList: true, subtree: true });
})();
</script>

</body>
</html>