<?php
session_start();

// Cek login
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php?error=ID tidak valid");
    exit;
}

// Ambil data dari database
$query = "SELECT * FROM trades WHERE id = $id";
$result = mysqli_query($koneksi, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: index.php?error=Data tidak ditemukan");
    exit;
}

$data = mysqli_fetch_assoc($result);
$existing_photos = !empty($data['foto']) ? array_filter(array_map('trim', explode(',', $data['foto']))) : [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
  
  <title>EDIT TRADE • TRADING JOURNAL</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    body {
      background: linear-gradient(145deg, #0a0f1c 0%, #0f1a2f 100%);
      min-height: 100vh;
      padding: 20px 16px 90px 16px;
      color: #f8fafc;
    }

    body.modal-open {
      overflow: hidden;
    }

    .header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 32px;
      background: rgba(26, 35, 50, 0.6);
      backdrop-filter: blur(12px);
      padding: 16px 24px;
      border-radius: 32px;
      border: 1px solid rgba(255, 255, 255, 0.05);
    }

    h2 {
      font-size: 1.8rem;
      font-weight: 700;
      background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    h2 i {
      background: rgba(255, 255, 255, 0.1);
      padding: 12px;
      border-radius: 20px;
      color: #6366f1;
      -webkit-text-fill-color: initial;
    }

    .form-container {
      max-width: 800px;
      margin: 0 auto;
    }

    .card {
      background: rgba(26, 35, 50, 0.8);
      backdrop-filter: blur(12px);
      border-radius: 32px;
      padding: 40px;
      box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Session info - CLICKABLE */
    .session-info {
      background: rgba(99, 102, 241, 0.1);
      border-radius: 16px;
      padding: 12px 16px;
      margin-bottom: 24px;
      border-left: 4px solid #6366f1;
      font-size: 0.9rem;
      color: #94a3b8;
      display: flex;
      align-items: center;
      gap: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .session-info:hover {
      background: rgba(99, 102, 241, 0.2);
      transform: translateX(5px);
    }

    .session-info i {
      color: #6366f1;
      font-size: 1.2rem;
    }

    .form-group {
      margin-bottom: 24px;
    }

    label {
      display: block;
      margin-bottom: 10px;
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #94a3b8;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    label i {
      color: #6366f1;
    }

    input, select, textarea {
      width: 100%;
      padding: 14px 18px;
      background: rgba(45, 55, 72, 0.6);
      border: 2px solid #334155;
      border-radius: 20px;
      color: white;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: #6366f1;
      box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }

    input[type="date"] {
      color-scheme: dark;
    }

    select {
      appearance: none;
      background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 18px center;
      background-size: 16px;
      padding-right: 45px;
    }

    textarea {
      min-height: 100px;
      resize: vertical;
    }

    /* Existing Photos */
    .existing-photos {
      margin-bottom: 20px;
    }

    .photos-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 12px;
    }

    .photo-item {
      position: relative;
      width: 100px;
      height: 100px;
      border-radius: 16px;
      overflow: hidden;
      border: 2px solid #334155;
      background: rgba(0,0,0,0.3);
      cursor: pointer;
    }

    .photo-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .photo-item:hover img {
      transform: scale(1.05);
    }

    .remove-photo-btn {
      position: absolute;
      top: 5px;
      right: 5px;
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

    .remove-photo-btn:hover {
      background: #ef4444;
      transform: scale(1.05);
    }

    /* Upload Area */
    .upload-area {
      border: 2px dashed #334155;
      border-radius: 20px;
      padding: 30px 20px;
      text-align: center;
      background: rgba(45, 55, 72, 0.3);
      transition: all 0.3s ease;
      cursor: pointer;
      margin-top: 15px;
    }

    .upload-area.drag-over {
      border-color: #6366f1;
      background: rgba(99, 102, 241, 0.1);
    }

    .upload-area i {
      font-size: 2.5rem;
      color: #64748b;
      margin-bottom: 10px;
    }

    .upload-area p {
      color: #94a3b8;
      margin-bottom: 5px;
    }

    .upload-area small {
      color: #64748b;
      font-size: 0.75rem;
    }

    #file-input {
      display: none;
    }

    .preview-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-top: 20px;
    }

    .preview-item {
      position: relative;
      width: 100px;
      height: 100px;
      border-radius: 16px;
      overflow: hidden;
      border: 2px solid #334155;
      cursor: pointer;
    }

    .preview-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .remove-new-photo {
      position: absolute;
      top: 5px;
      right: 5px;
      background: rgba(239, 68, 68, 0.9);
      border: none;
      color: white;
      width: 28px;
      height: 28px;
      border-radius: 50%;
      cursor: pointer;
      font-size: 0.8rem;
      z-index: 10;
    }

    .btn-submit {
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      border: none;
      border-radius: 24px;
      color: white;
      font-weight: 700;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-top: 10px;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.5);
    }

    .error-message {
      padding: 12px 16px;
      border-radius: 16px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(239, 68, 68, 0.2);
      border-left: 4px solid #ef4444;
      color: #fecaca;
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

    .bottom-nav {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: calc(100% - 32px);
      max-width: 400px;
      background: rgba(26, 35, 50, 0.9);
      backdrop-filter: blur(20px);
      border-radius: 40px;
      padding: 8px 12px;
      display: flex;
      justify-content: space-around;
      z-index: 1000;
    }

    .nav-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 10px 16px;
      border-radius: 30px;
      color: #94a3b8;
      text-decoration: none;
      font-weight: 600;
      flex: 1;
    }

    .nav-item.active {
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      color: white;
    }

    .nav-item span {
      font-size: 1.3rem;
    }

    .nav-item p {
      font-size: 0.7rem;
      margin-top: 2px;
    }

    @media (max-width: 768px) {
      .card { padding: 24px; }
      h2 { font-size: 1.5rem; }
      .photos-grid { gap: 10px; }
      .photo-item, .preview-item { width: 80px; height: 80px; }
      .close-modal { width: 40px; height: 40px; font-size: 22px; top: 15px; right: 15px; }
      .zoom-controls { bottom: 90px; right: 15px; padding: 6px 10px; }
      .zoom-btn { width: 36px; height: 36px; font-size: 1rem; }
      .photo-indicator { bottom: 150px; }
    }

    @media (min-width: 769px) {
      .nav-item { flex-direction: row; gap: 8px; }
      .nav-item span { font-size: 1.2rem; }
      .zoom-controls { bottom: 30px; right: 30px; }
    }
  </style>
</head>
<body>

<div class="header">
  <h2><i class="fas fa-edit"></i> EDIT TRADE</h2>
</div>

<div class="form-container">
  <div class="card">
    <!-- Session Info Clickable -->
    <div class="session-info" id="sessionInfoBtn">
      <i class="fas fa-info-circle"></i>
      <span>SETIAP SESI MEMILIKI ANALISA BERBEDA</span>
      <i class="fas fa-chevron-right" style="margin-left: auto; font-size: 0.8rem;"></i>
    </div>

    <?php if (isset($_GET['error'])): ?>
      <div class="error-message"><i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form action="update.php" method="POST" enctype="multipart/form-data" id="editForm">
      <input type="hidden" name="id" value="<?= $data['id'] ?>">
      
      <div class="form-group">
        <label><i class="fas fa-calendar-alt"></i> TANGGAL TRADING</label>
        <input type="date" name="tanggal" required value="<?= $data['tanggal'] ?>">
      </div>

      <div class="form-group">
        <label><i class="fas fa-chart-line"></i> SESSION 1 (0–50%) - PAGI</label>
        <select name="session1" required>
          <option value="profit" <?= $data['session1'] == 'profit' ? 'selected' : '' ?>>📈 PROFIT</option>
          <option value="lose" <?= $data['session1'] == 'lose' ? 'selected' : '' ?>>📉 LOSS</option>
          <option value="skip" <?= $data['session1'] == 'skip' ? 'selected' : '' ?>>⏸️ SKIP</option>
        </select>
      </div>

      <div class="form-group">
        <label><i class="fas fa-chart-line"></i> SESSION 2 (0-100%) - SIANG</label>
        <select name="session2" required>
          <option value="profit" <?= $data['session2'] == 'profit' ? 'selected' : '' ?>>📈 PROFIT</option>
          <option value="lose" <?= $data['session2'] == 'lose' ? 'selected' : '' ?>>📉 LOSS</option>
          <option value="skip" <?= $data['session2'] == 'skip' ? 'selected' : '' ?>>⏸️ SKIP</option>
        </select>
      </div>

      <div class="form-group">
        <label><i class="fas fa-chart-line"></i> SESSION 3 (50-0%) - SORE</label>
        <select name="session3" required>
          <option value="profit" <?= $data['session3'] == 'profit' ? 'selected' : '' ?>>📈 PROFIT</option>
          <option value="lose" <?= $data['session3'] == 'lose' ? 'selected' : '' ?>>📉 LOSS</option>
          <option value="skip" <?= $data['session3'] == 'skip' ? 'selected' : '' ?>>⏸️ SKIP</option>
        </select>
      </div>

      <div class="form-group">
        <label><i class="fas fa-chart-line"></i> SESSION 4 (100-50%) - MALAM</label>
        <select name="session4" required>
          <option value="profit" <?= $data['session4'] == 'profit' ? 'selected' : '' ?>>📈 PROFIT</option>
          <option value="lose" <?= $data['session4'] == 'lose' ? 'selected' : '' ?>>📉 LOSS</option>
          <option value="skip" <?= $data['session4'] == 'skip' ? 'selected' : '' ?>>⏸️ SKIP</option>
        </select>
      </div>

      <!-- SESSION 5 -->
      <div class="form-group">
        <label><i class="fas fa-chart-line"></i> SESSION 5 (50-0%) - LARUT MALAM</label>
        <select name="session5" required>
          <option value="profit" <?= ($data['session5'] ?? 'skip') == 'profit' ? 'selected' : '' ?>>📈 PROFIT</option>
          <option value="lose" <?= ($data['session5'] ?? 'skip') == 'lose' ? 'selected' : '' ?>>📉 LOSS</option>
          <option value="skip" <?= ($data['session5'] ?? 'skip') == 'skip' ? 'selected' : '' ?>>⏸️ SKIP</option>
        </select>
      </div>

      <!-- Existing Photos -->
      <?php if (!empty($existing_photos)): ?>
      <div class="form-group">
        <label><i class="fas fa-images"></i> FOTO SAAT INI</label>
        <div class="existing-photos">
          <div class="photos-grid" id="existingPhotosGrid">
            <?php foreach ($existing_photos as $index => $photo): ?>
              <div class="photo-item" data-photo="<?= htmlspecialchars($photo) ?>" data-index="<?= $index ?>">
                <img src="<?= htmlspecialchars($photo) ?>" alt="Foto">
                <button type="button" class="remove-photo-btn" data-photo="<?= htmlspecialchars($photo) ?>">✕</button>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <input type="hidden" name="existing_photos" id="existingPhotos" value="<?= htmlspecialchars($data['foto']) ?>">
        <small style="color:#64748b; display:block; margin-top:8px;"><i class="fas fa-info-circle"></i> KLIK FOTO UNTUK MEMPERBESAR | KLIK ✕ UNTUK MENGHAPUS FOTO</small>
      </div>
      <?php endif; ?>

      <!-- Upload New Photos -->
      <div class="form-group">
        <label><i class="fas fa-plus-circle"></i> TAMBAH FOTO BARU (MAX 3, MAX 5MB)</label>
        <div class="upload-area" id="uploadArea">
          <i class="fas fa-cloud-upload-alt"></i>
          <p>DRAG & DROP FOTO DISINI ATAU KLIK UNTUK MEMILIH</p>
          <small>FORMAT: JPG, PNG, GIF | MAX 5MB PER FILE | MAX 3 FILE</small>
        </div>
        <input type="file" name="new_fotos[]" id="file-input" accept="image/*" multiple>
        <div class="preview-grid" id="previewGrid"></div>
        <small style="display: block; margin-top: 12px; color: #64748b;">
          <i class="fas fa-info-circle"></i> KLIK FOTO UNTUK MEMPERBESAR | GESER KANAN/KIRI UNTUK NAVIGASI | PINCH ZOOM
        </small>
      </div>

      <div class="form-group">
        <label><i class="fas fa-sticky-note"></i> CATATAN TRADING</label>
        <textarea name="note" placeholder="Tulis analisis, emosi, atau catatan penting lainnya..."><?= htmlspecialchars($data['note'] ?? '') ?></textarea>
      </div>

      <button type="submit" class="btn-submit" id="submitBtn">
        <i class="fas fa-save"></i> SIMPAN PERUBAHAN
      </button>
    </form>
  </div>
</div>

<div class="bottom-nav">
  <a href="index.php" class="nav-item"><span>🏠</span><p>HOME</p></a>
  <a href="tambah.php" class="nav-item"><span>📝</span><p>TAMBAH</p></a>
  <a href="statistik.php" class="nav-item"><span>📊</span><p>STATISTIK</p></a>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Fungsi untuk menampilkan popup info sesi
function showSessionInfo() {
    Swal.fire({
        title: '📊 ANALISA SETIAP SESI',
        html: `
            <div style="text-align: left;">
                <div style="margin-bottom: 15px; padding: 10px; background: rgba(99,102,241,0.1); border-radius: 12px;">
                    <strong style="color: #10b981;">🟢 SESSION 1 (0–50%) - PAGI</strong>
                    <p style="margin: 5px 0 0 0; font-size: 0.9rem;">Sesi pembuka, biasanya market masih mencari arah. Volatilitas sedang.</p>
                </div>
                <div style="margin-bottom: 15px; padding: 10px; background: rgba(99,102,241,0.1); border-radius: 12px;">
                    <strong style="color: #10b981;">🟢 SESSION 2 (0-100%) - SIANG</strong>
                    <p style="margin: 5px 0 0 0; font-size: 0.9rem;">Sesi utama, likuiditas tinggi. Momentum mulai terbentuk.</p>
                </div>
                <div style="margin-bottom: 15px; padding: 10px; background: rgba(99,102,241,0.1); border-radius: 12px;">
                    <strong style="color: #f59e0b;">🟡 SESSION 3 (50-0%) - SORE</strong>
                    <p style="margin: 5px 0 0 0; font-size: 0.9rem;">Potensi reversal, banyak trader mulai take profit.</p>
                </div>
                <div style="margin-bottom: 15px; padding: 10px; background: rgba(99,102,241,0.1); border-radius: 12px;">
                    <strong style="color: #ef4444;">🔴 SESSION 4 (100-50%) - MALAM</strong>
                    <p style="margin: 5px 0 0 0; font-size: 0.9rem;">Sesi Asia, cenderung ranging. Hati-hati dengan breakout palsu.</p>
                </div>
                <div style="margin-bottom: 5px; padding: 10px; background: rgba(99,102,241,0.1); border-radius: 12px;">
                    <strong style="color: #8b5cf6;">🟣 SESSION 5 (50-0%) - LARUT MALAM</strong>
                    <p style="margin: 5px 0 0 0; font-size: 0.9rem;">Sesi akhir, volume tipis. Risiko spread melebar.</p>
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
document.addEventListener('DOMContentLoaded', function() {
    const sessionInfoBtn = document.getElementById('sessionInfoBtn');
    if (sessionInfoBtn) {
        sessionInfoBtn.addEventListener('click', showSessionInfo);
    }
});

// =====================
// MODAL FOTO dengan ZOOM & SWIPE
// =====================
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
  
  // Setup click handlers untuk existing photos
  function setupPhotoClickHandlers() {
    document.querySelectorAll('#existingPhotosGrid .photo-item').forEach(item => {
      // Hapus handler lama jika ada
      if (item._clickHandler) {
        item.removeEventListener('click', item._clickHandler);
      }
      // Buat handler baru
      item._clickHandler = function(e) {
        // Jangan trigger jika yang diklik adalah tombol hapus
        if (e.target.classList.contains('remove-photo-btn')) {
          return;
        }
        const img = this.querySelector('img');
        if (img && img.src) {
          const photoUrl = img.src;
          // Kumpulkan semua foto yang ada
          const allPhotos = [];
          document.querySelectorAll('#existingPhotosGrid .photo-item img').forEach(photo => {
            if (photo.src) allPhotos.push(photo.src);
          });
          const currentIndex = allPhotos.indexOf(photoUrl);
          if (allPhotos.length > 0) {
            openModalWithPhotos(allPhotos, currentIndex >= 0 ? currentIndex : 0);
          }
        }
      };
      item.addEventListener('click', item._clickHandler);
    });
  }
  
  // Setup handler untuk preview foto baru
  function setupPreviewClickHandlers() {
    document.querySelectorAll('#previewGrid .preview-item').forEach((item, idx) => {
      if (item._clickHandler) {
        item.removeEventListener('click', item._clickHandler);
      }
      item._clickHandler = function(e) {
        if (e.target.classList.contains('remove-new-photo')) {
          return;
        }
        // Kumpulkan semua URL preview
        const previewUrls = [];
        document.querySelectorAll('#previewGrid .preview-item img').forEach(img => {
          if (img.src) previewUrls.push(img.src);
        });
        if (previewUrls.length > 0) {
          openModalWithPhotos(previewUrls, idx);
        }
      };
      item.addEventListener('click', item._clickHandler);
    });
  }
  
  // Observasi perubahan DOM untuk setup handler
  const observer = new MutationObserver(() => {
    setupPhotoClickHandlers();
    setupPreviewClickHandlers();
  });
  observer.observe(document.body, { childList: true, subtree: true });
  
  // Initial setup
  setupPhotoClickHandlers();
  setupPreviewClickHandlers();
})();

// =====================
// MULTI PHOTO UPLOAD
// =====================
(function() {
  const MAX_FILES = 3;
  const MAX_FILE_SIZE = 5 * 1024 * 1024;
  const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
  
  const fileInput = document.getElementById('file-input');
  const uploadArea = document.getElementById('uploadArea');
  const previewGrid = document.getElementById('previewGrid');
  const submitBtn = document.getElementById('submitBtn');
  const existingPhotosInput = document.getElementById('existingPhotos');
  
  let selectedFiles = [];
  let photosToRemove = [];
  let currentPreviewUrls = [];
  
  function validateFile(file) {
    if (!ALLOWED_TYPES.includes(file.type)) {
      return { valid: false, error: `Format ${file.type} tidak didukung` };
    }
    if (file.size > MAX_FILE_SIZE) {
      return { valid: false, error: `File ${file.name} melebihi 5MB` };
    }
    return { valid: true };
  }
  
  function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.marginTop = '10px';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i><span>${message}</span>`;
    uploadArea.parentNode.insertBefore(errorDiv, uploadArea.nextSibling);
    setTimeout(() => errorDiv.remove(), 4000);
  }
  
  function updatePreview() {
    previewGrid.innerHTML = '';
    currentPreviewUrls = [];
    
    if (selectedFiles.length === 0) {
      previewGrid.style.display = 'none';
      return;
    }
    
    previewGrid.style.display = 'flex';
    
    selectedFiles.forEach((file, index) => {
      const reader = new FileReader();
      const previewItem = document.createElement('div');
      previewItem.className = 'preview-item';
      
      const img = document.createElement('img');
      const removeBtn = document.createElement('button');
      removeBtn.className = 'remove-new-photo';
      removeBtn.innerHTML = '✕';
      removeBtn.onclick = (e) => {
        e.stopPropagation();
        selectedFiles.splice(index, 1);
        currentPreviewUrls.splice(index, 1);
        updatePreview();
        updateFileInput();
      };
      
      previewItem.appendChild(img);
      previewItem.appendChild(removeBtn);
      
      reader.onload = (e) => {
        img.src = e.target.result;
        currentPreviewUrls[index] = e.target.result;
      };
      reader.readAsDataURL(file);
      
      previewGrid.appendChild(previewItem);
    });
    
    updateFileInput();
    // Trigger setup handler untuk preview
    setTimeout(() => {
      if (window.setupPreviewClickHandlers) window.setupPreviewClickHandlers();
    }, 100);
  }
  
  function updateFileInput() {
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    fileInput.files = dataTransfer.files;
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
      showError(errors.join('\n'));
    }
    
    updatePreview();
  }
  
  // Remove existing photo
  document.querySelectorAll('.remove-photo-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      const photoPath = this.getAttribute('data-photo');
      if (photoPath) {
        photosToRemove.push(photoPath);
        this.closest('.photo-item').remove();
        updateExistingPhotosInput();
      }
    });
  });
  
  function updateExistingPhotosInput() {
    const remainingPhotos = [];
    document.querySelectorAll('#existingPhotosGrid .photo-item img').forEach(img => {
      if (img && img.src) {
        remainingPhotos.push(img.src);
      }
    });
    existingPhotosInput.value = remainingPhotos.join(',');
  }
  
  // Drag & Drop
  if (uploadArea) {
    uploadArea.addEventListener('click', () => fileInput.click());
    uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.classList.add('drag-over'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag-over'));
    uploadArea.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadArea.classList.remove('drag-over');
      const files = e.dataTransfer.files;
      if (files.length > 0) addFiles(files);
    });
  }
  
  if (fileInput) {
    fileInput.addEventListener('change', (e) => {
      if (e.target.files.length > 0) addFiles(e.target.files);
    });
  }
  
  // Form submit
  const form = document.getElementById('editForm');
  if (form) {
    form.addEventListener('submit', (e) => {
      if (selectedFiles.length > MAX_FILES) {
        e.preventDefault();
        showError(`Maksimal upload ${MAX_FILES} foto`);
        return;
      }
      
      for (const file of selectedFiles) {
        const validation = validateFile(file);
        if (!validation.valid) {
          e.preventDefault();
          showError(validation.error);
          return;
        }
      }
      
      const removedInput = document.createElement('input');
      removedInput.type = 'hidden';
      removedInput.name = 'removed_photos';
      removedInput.value = photosToRemove.join(',');
      form.appendChild(removedInput);
      
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-pulse"></i> Menyimpan...';
    });
  }
})();
</script>

</body>
</html>