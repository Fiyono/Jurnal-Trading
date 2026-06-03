<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

// UPDATE: Set semua session yang NULL atau kosong menjadi 'skip'
for ($i = 1; $i <= 6; $i++) {
    mysqli_query($koneksi, "UPDATE trades SET session$i = 'skip' WHERE session$i IS NULL OR session$i = ''");
}

$data = mysqli_query($koneksi, "SELECT * FROM trades ORDER BY tanggal ASC");

// GLOBAL
$total_profit = 0;
$total_loss   = 0;
$win  = 0;
$loss = 0;

// SESSION - UNTUK 6 SESSIONS
$total = [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0];
$wins  = [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0];
$session_profit = [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0];
$session_lose = [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0];
$session_skip = [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0];

// Untuk total transaksi (profit+loss) per sesi
$total_transactions = [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0];

// Untuk menyimpan profit per sesi berdasarkan hari
$session_by_day = [
    1 => ['Senin'=>0, 'Selasa'=>0, 'Rabu'=>0, 'Kamis'=>0, 'Jumat'=>0],
    2 => ['Senin'=>0, 'Selasa'=>0, 'Rabu'=>0, 'Kamis'=>0, 'Jumat'=>0],
    3 => ['Senin'=>0, 'Selasa'=>0, 'Rabu'=>0, 'Kamis'=>0, 'Jumat'=>0],
    4 => ['Senin'=>0, 'Selasa'=>0, 'Rabu'=>0, 'Kamis'=>0, 'Jumat'=>0],
    5 => ['Senin'=>0, 'Selasa'=>0, 'Rabu'=>0, 'Kamis'=>0, 'Jumat'=>0],
    6 => ['Senin'=>0, 'Selasa'=>0, 'Rabu'=>0, 'Kamis'=>0, 'Jumat'=>0]
];

// HARIAN (INIT BIAR URUT) - DENGAN SESI 6
$hari_stats = [];
$day_order = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
foreach ($day_order as $day) {
    $hari_stats[$day] = [
        'profit' => 0,
        'lose' => 0,
        'total_profit' => 0
    ];
    // Inisialisasi untuk sesi 1-6
    for ($i = 1; $i <= 6; $i++) {
        $hari_stats[$day]["s{$i}_p"] = 0;
        $hari_stats[$day]["s{$i}_l"] = 0;
    }
}

// 🔥 Statistik untuk kombinasi sesi 1 dan 2
$session1_2_combinations = [
    'profit_profit' => 0,
    'profit_loss' => 0,
    'loss_loss' => 0,
    'total_valid' => 0
];

// 🔥 TRANSLATE HARI
$hari_indo = [
    'Sunday'=>'Minggu',
    'Monday'=>'Senin',
    'Tuesday'=>'Selasa',
    'Wednesday'=>'Rabu',
    'Thursday'=>'Kamis',
    'Friday'=>'Jumat',
    'Saturday'=>'Sabtu'
];

while ($d = mysqli_fetch_assoc($data)) {

    $day_profit = 0;

    // 🔥 HARI INDONESIA
    $hari_en = date('l', strtotime($d['tanggal']));
    $hari = isset($hari_indo[$hari_en]) ? $hari_indo[$hari_en] : $hari_en;
    
    // Hanya proses untuk hari Senin-Jumat
    if (in_array($hari, $day_order)) {
        
        // 🔥 Hitung kombinasi sesi 1 dan 2
        $s1 = $d["session1"];
        $s2 = $d["session2"];
        
        // Hanya hitung jika kedua sesi bukan skip
        if ($s1 != 'skip' && $s2 != 'skip') {
            $session1_2_combinations['total_valid']++;
            
            if ($s1 == 'profit' && $s2 == 'profit') {
                $session1_2_combinations['profit_profit']++;
            } elseif ($s1 == 'profit' && $s2 == 'lose') {
                $session1_2_combinations['profit_loss']++;
            } elseif ($s1 == 'lose' && $s2 == 'lose') {
                $session1_2_combinations['loss_loss']++;
            }
        }
        
        // LOOP UNTUK 6 SESSIONS
        for ($i=1; $i<=6; $i++) {
            $col = "session$i";
            $s = isset($d[$col]) ? $d[$col] : 'skip';
            
            // Jika NULL atau kosong, set sebagai skip
            if (empty($s)) {
                $s = 'skip';
            }

            if ($s == 'profit') {
                $day_profit += 1;
                $win++;
                $wins[$i]++;
                $total_transactions[$i]++;
                $session_profit[$i]++;
                $session_by_day[$i][$hari]++;
                $hari_stats[$hari]['profit']++;
                $hari_stats[$hari]['total_profit']++;
                $hari_stats[$hari]["s{$i}_p"]++;
            } 
            else if ($s == 'lose') {
                $day_profit -= 1;
                $loss++;
                $total_transactions[$i]++;
                $session_lose[$i]++;
                $hari_stats[$hari]['lose']++;
                $hari_stats[$hari]["s{$i}_l"]++;
            }
            else if ($s == 'skip') {
                $session_skip[$i]++;
            }
        }
        
        // GLOBAL
        if ($day_profit > 0) $total_profit += $day_profit;
        else if ($day_profit < 0) $total_loss += $day_profit;
    }
}

// TOTAL TRADE (hanya profit + loss, skip tidak dihitung)
$total_trade = $win + $loss;

// WINRATE GLOBAL
$winrate = $total_trade > 0 ? ($win / $total_trade) * 100 : 0;

// FUNCTION untuk menghitung winrate
function wr($w, $t) {
    return $t > 0 ? round(($w/$t)*100,1) : 0;
}

// 🔥 Hitung persentase untuk kombinasi sesi 1 & 2
$profit_profit_percent = $session1_2_combinations['total_valid'] > 0 
    ? round(($session1_2_combinations['profit_profit'] / $session1_2_combinations['total_valid']) * 100, 1) 
    : 0;
    
$profit_loss_percent = $session1_2_combinations['total_valid'] > 0 
    ? round(($session1_2_combinations['profit_loss'] / $session1_2_combinations['total_valid']) * 100, 1) 
    : 0;
    
$loss_loss_percent = $session1_2_combinations['total_valid'] > 0 
    ? round(($session1_2_combinations['loss_loss'] / $session1_2_combinations['total_valid']) * 100, 1) 
    : 0;

// 🔥 MENCARI HARI TERBAIK PER SESI (1-6)
$best_day_per_session = [];
$best_day_value_per_session = [];

for ($session = 1; $session <= 6; $session++) {
    $best_day = '-';
    $best_value = 0;
    
    foreach ($session_by_day[$session] as $hari => $profit_count) {
        if ($profit_count > $best_value) {
            $best_value = $profit_count;
            $best_day = $hari;
        }
    }
    
    $best_day_per_session[$session] = $best_day;
    $best_day_value_per_session[$session] = $best_value;
}

// 🔥 MENCARI SESSION DENGAN PROFIT TERBANYAK (1-6)
$best_session = 1;
$max_session_profit = $session_profit[1];

for ($i=2; $i<=6; $i++) {
    if ($session_profit[$i] > $max_session_profit) {
        $max_session_profit = $session_profit[$i];
        $best_session = $i;
    }
}

// 🔥 MENCARI HARI DENGAN PROFIT TERBANYAK
$best_day_profit = '-';
$max_day_profit = 0;

foreach ($hari_stats as $hari => $val) {
    if ($val['total_profit'] > $max_day_profit) {
        $max_day_profit = $val['total_profit'];
        $best_day_profit = $hari;
    }
}

// 🔥 MENCARI HARI DENGAN WINRATE TERTINGGI (minimal 3 transaksi)
$best_day_wr = '-';
$best_wr_value = 0;

foreach ($hari_stats as $hari => $val) {
    $t = $val['profit'] + $val['lose'];
    if ($t >= 3) {
        $wr_day = ($val['profit'] / $t) * 100;
        if ($wr_day > $best_wr_value) {
            $best_wr_value = $wr_day;
            $best_day_wr = $hari;
        }
    }
}

// 🔥 MENCARI HARI TERBURUK (BANYAK LOSS)
$worst_day = '-';
$max_loss = 0;

foreach ($hari_stats as $hari => $val) {
    if ($val['lose'] > $max_loss) {
        $max_loss = $val['lose'];
        $worst_day = $hari;
    }
}

// 🔥 HARI TERBAIK (BERDASARKAN WINRATE)
$best_day = '-';
$best_wr  = 0;

foreach ($hari_stats as $hari => $val) {
    $t = $val['profit'] + $val['lose'];
    $wr_day = $t > 0 ? ($val['profit'] / $t) * 100 : 0;

    if ($wr_day > $best_wr && $t > 0) {
        $best_wr = $wr_day;
        $best_day = $hari;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
  
  <title>STATISTIK TRADING</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
      --info: #3b82f6;
      --dark: #0b1120;
      --dark-card: #1a2332;
      --dark-light: #2d3748;
      --text-primary: #f8fafc;
      --text-secondary: #94a3b8;
      --text-muted: #64748b;
      --border-color: #334155;
      --gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
      --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
      --gradient-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      --gradient-info: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
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
      background: 
        radial-gradient(circle at 20% 30%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
        radial-gradient(circle at 80% 70%, rgba(139, 92, 246, 0.15) 0%, transparent 40%),
        radial-gradient(circle at 40% 80%, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
      pointer-events: none;
      z-index: -1;
      animation: gradientShift 15s ease infinite;
    }

    @keyframes gradientShift {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.8; }
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

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translate(-50%, 30px);
      }
      to {
        opacity: 1;
        transform: translate(-50%, 0);
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
      margin-bottom: 24px;
      background: rgba(26, 35, 50, 0.6);
      backdrop-filter: var(--blur);
      padding: 16px 20px;
      border-radius: var(--radius-lg);
      border: 1px solid rgba(255, 255, 255, 0.05);
      box-shadow: var(--shadow-sm);
      animation: slideDown 0.5s ease forwards;
    }

    h2 {
      font-size: 1.4rem;
      font-weight: 700;
      background: var(--gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      letter-spacing: -0.5px;
      display: flex;
      align-items: center;
      gap: 10px;
      text-transform: uppercase;
    }

    h2 i {
      background: rgba(255, 255, 255, 0.1);
      padding: 10px;
      border-radius: 16px;
      color: var(--primary);
      font-size: 1.2rem;
      -webkit-text-fill-color: initial;
    }

    .date-badge {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      padding: 8px 16px;
      border-radius: var(--radius-full);
      font-size: 0.75rem;
      color: var(--text-secondary);
      display: flex;
      align-items: center;
      gap: 6px;
      text-transform: uppercase;
    }

    /* Stats Cards */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      margin-bottom: 20px;
    }

    .stat-card {
      background: rgba(26, 35, 50, 0.7);
      backdrop-filter: var(--blur);
      border-radius: var(--radius-md);
      padding: 16px;
      border: 1px solid rgba(255, 255, 255, 0.05);
      box-shadow: var(--shadow-sm);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      animation: fadeInUp 0.5s ease forwards;
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
    }

    .stat-card.profit::before { background: var(--gradient-success); }
    .stat-card.loss::before { background: var(--gradient-danger); }
    .stat-card.winrate::before { background: var(--gradient-warning); }

    .stat-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-lg);
    }

    .stat-icon {
      width: 40px;
      height: 40px;
      border-radius: 16px;
      background: rgba(255, 255, 255, 0.05);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 12px;
    }

    .stat-icon i { font-size: 1.3rem; }
    .profit .stat-icon i { color: var(--success); }
    .loss .stat-icon i { color: var(--danger); }
    .winrate .stat-icon i { color: var(--warning); }

    .stat-label {
      font-size: 0.7rem;
      color: var(--text-secondary);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 6px;
    }

    .stat-value {
      font-size: 1.6rem;
      font-weight: 800;
      line-height: 1;
      margin-bottom: 4px;
    }

    .profit .stat-value { color: var(--success); }
    .loss .stat-value { color: var(--danger); }
    .winrate .stat-value { color: var(--warning); }

    .stat-desc {
      font-size: 0.65rem;
      color: var(--text-muted);
      text-transform: uppercase;
    }

    /* Session 1 & 2 Combination Cards */
    .combination-section {
      margin-bottom: 20px;
    }
    
    .combination-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
    }
    
    .combination-card {
      background: rgba(26, 35, 50, 0.7);
      backdrop-filter: var(--blur);
      border-radius: var(--radius-md);
      padding: 20px;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.05);
      transition: all 0.3s ease;
      animation: fadeInUp 0.5s ease forwards;
      position: relative;
      overflow: hidden;
    }
    
    .combination-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-lg);
    }
    
    .combination-card.profit-profit {
      border-left: 4px solid var(--success);
    }
    
    .combination-card.profit-loss {
      border-left: 4px solid var(--warning);
    }
    
    .combination-card.loss-loss {
      border-left: 4px solid var(--danger);
    }
    
    .combination-icon {
      font-size: 2rem;
      margin-bottom: 12px;
    }
    
    .combination-title {
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--text-secondary);
      margin-bottom: 8px;
      text-transform: uppercase;
    }
    
    .combination-value {
      font-size: 2rem;
      font-weight: 800;
      margin-bottom: 8px;
    }
    
    .combination-percent {
      font-size: 1rem;
      font-weight: 700;
      padding: 4px 12px;
      border-radius: var(--radius-full);
      display: inline-block;
    }
    
    .profit-profit .combination-value,
    .profit-profit .combination-percent { color: var(--success); }
    
    .profit-loss .combination-value,
    .profit-loss .combination-percent { color: var(--warning); }
    
    .loss-loss .combination-value,
    .loss-loss .combination-percent { color: var(--danger); }
    
    .combination-desc {
      font-size: 0.7rem;
      color: var(--text-muted);
      margin-top: 8px;
    }

    /* Session Day Cards */
    .session-day-grid {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 12px;
      margin-bottom: 20px;
    }

    .session-day-card {
      background: rgba(26, 35, 50, 0.7);
      backdrop-filter: var(--blur);
      border-radius: var(--radius-md);
      padding: 16px 12px;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.05);
      transition: all 0.3s ease;
      animation: fadeInUp 0.5s ease forwards;
      position: relative;
      overflow: hidden;
    }

    .session-day-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-lg);
      border-color: var(--primary);
    }

    .session-badge {
      position: absolute;
      top: 8px;
      right: 8px;
      background: var(--gradient);
      padding: 2px 8px;
      border-radius: var(--radius-full);
      font-size: 0.6rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    .session-title {
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 12px;
      background: var(--gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-transform: uppercase;
    }

    .best-day-info {
      margin: 12px 0;
    }

    .best-day-icon {
      font-size: 2rem;
      margin-bottom: 6px;
    }

    .best-day-name {
      font-size: 1rem;
      font-weight: 700;
      color: var(--success);
      margin-bottom: 6px;
      text-transform: uppercase;
    }

    .best-day-profit {
      font-size: 0.75rem;
      color: var(--text-secondary);
    }

    .best-day-profit span {
      font-size: 1rem;
      font-weight: 700;
      color: var(--success);
    }

    .progress-container {
      margin-top: 12px;
      padding-top: 12px;
      border-top: 1px solid var(--border-color);
    }

    .progress-label {
      font-size: 0.65rem;
      color: var(--text-muted);
      margin-bottom: 6px;
      text-transform: uppercase;
    }

    .progress-bar {
      width: 100%;
      height: 4px;
      background: var(--border-color);
      border-radius: var(--radius-full);
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      background: var(--gradient-success);
      border-radius: var(--radius-full);
      transition: width 0.3s ease;
    }

    /* Insight Cards */
    .insight-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      margin-bottom: 20px;
    }

    .insight-card {
      background: rgba(26, 35, 50, 0.7);
      backdrop-filter: var(--blur);
      border-radius: var(--radius-md);
      padding: 16px;
      border: 1px solid rgba(255, 255, 255, 0.05);
      transition: all 0.3s ease;
      animation: fadeInUp 0.5s ease forwards;
      position: relative;
      overflow: hidden;
    }

    .insight-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
    }

    .insight-card.best::before { background: var(--gradient-success); }
    .insight-card.worst::before { background: var(--gradient-danger); }

    .insight-card:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-lg);
    }

    .insight-icon {
      width: 40px;
      height: 40px;
      border-radius: 16px;
      background: rgba(255, 255, 255, 0.05);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 12px;
    }

    .insight-icon i { font-size: 1.3rem; }
    .insight-card.best .insight-icon i { color: var(--success); }
    .insight-card.worst .insight-icon i { color: var(--danger); }

    .insight-label {
      font-size: 0.65rem;
      color: var(--text-muted);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 4px;
    }

    .insight-title {
      font-size: 1rem;
      font-weight: 700;
      margin-bottom: 6px;
      text-transform: uppercase;
    }

    .insight-value {
      font-size: 1.4rem;
      font-weight: 800;
      margin-bottom: 4px;
    }

    .insight-card.best .insight-value { color: var(--success); }
    .insight-card.worst .insight-value { color: var(--danger); }

    .insight-desc {
      font-size: 0.6rem;
      color: var(--text-muted);
    }

    /* Session Grid */
    .section-title {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 24px 0 16px 0;
      animation: fadeInUp 0.5s ease forwards;
    }

    .section-title i {
      width: 32px;
      height: 32px;
      background: var(--gradient);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
    }

    .section-title h3 {
      font-size: 1.1rem;
      font-weight: 700;
      text-transform: uppercase;
    }

    .session-grid {
      display: grid;
      grid-template-columns: repeat(6, 1fr);
      gap: 12px;
      margin-bottom: 24px;
    }

    .session-item {
      background: rgba(26, 35, 50, 0.7);
      backdrop-filter: var(--blur);
      border-radius: var(--radius-md);
      padding: 16px;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.05);
      transition: all 0.3s ease;
      animation: fadeInUp 0.5s ease forwards;
    }

    .session-item.best-session {
      border: 2px solid var(--success);
      box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
    }

    .session-item:hover {
      transform: scale(1.02);
      border-color: var(--primary);
    }

    .session-number {
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--text-secondary);
      margin-bottom: 10px;
      text-transform: uppercase;
    }

    .session-value {
      font-size: 1.8rem;
      font-weight: 800;
      color: var(--primary);
      line-height: 1;
      margin-bottom: 8px;
    }

    .session-stats {
      font-size: 0.7rem;
      margin: 10px 0;
      display: flex;
      flex-direction: column;
      gap: 3px;
    }

    .profit-count {
      color: var(--success);
      font-weight: 600;
    }

    .lose-count {
      color: var(--danger);
      font-weight: 600;
    }

    .skip-count {
      color: var(--text-muted);
      font-weight: 600;
    }

    /* Desktop Table */
    .desktop-table {
      display: block;
      background: rgba(26, 35, 50, 0.7);
      backdrop-filter: var(--blur);
      border-radius: var(--radius-md);
      padding: 20px;
      border: 1px solid rgba(255, 255, 255, 0.05);
      margin-bottom: 20px;
      overflow-x: auto;
      animation: fadeInUp 0.5s ease forwards;
    }

    .desktop-table table {
      width: 100%;
      border-collapse: collapse;
      min-width: 1400px;
    }

    .desktop-table th {
      text-align: center;
      padding: 12px;
      font-weight: 600;
      color: var(--text-secondary);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      font-size: 0.7rem;
      border-bottom: 2px solid var(--border-color);
    }

    .desktop-table td {
      text-align: center;
      padding: 12px;
      border-bottom: 1px solid var(--border-color);
      font-weight: 500;
      font-size: 0.85rem;
    }

    .desktop-table tr:hover td {
      background: rgba(99, 102, 241, 0.1);
    }

    .desktop-table tr.best-row {
      background: rgba(16, 185, 129, 0.1);
    }

    .profit-text { color: var(--success); font-weight: 700; }
    .loss-text { color: var(--danger); font-weight: 700; }
    .neutral-text { color: var(--text-secondary); font-weight: 500; }

    /* Mobile Cards */
    .mobile-cards {
      display: none;
    }

    .day-card {
      background: rgba(26, 35, 50, 0.7);
      backdrop-filter: var(--blur);
      border-radius: var(--radius-md);
      padding: 16px;
      margin-bottom: 12px;
      border: 1px solid rgba(255, 255, 255, 0.05);
      transition: all 0.3s ease;
      animation: fadeInUp 0.5s ease forwards;
    }

    .day-card.best-day-card {
      border: 2px solid var(--success);
    }

    .day-card-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 12px;
      padding-bottom: 12px;
      border-bottom: 1px dashed var(--border-color);
    }

    .day-icon {
      width: 40px;
      height: 40px;
      background: var(--gradient);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2rem;
    }

    .day-title {
      font-size: 1rem;
      font-weight: 700;
      text-transform: uppercase;
    }

    .badge {
      margin-left: auto;
      padding: 3px 10px;
      border-radius: var(--radius-full);
      font-size: 0.6rem;
      font-weight: 600;
    }

    .badge.best { background: var(--success); color: white; text-transform: uppercase; }

    .day-session-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin: 12px 0;
    }

    .day-session-item {
      background: rgba(255, 255, 255, 0.03);
      border-radius: var(--radius-sm);
      padding: 8px;
      text-align: center;
    }

    .day-session-label {
      font-size: 0.6rem;
      color: var(--text-muted);
      margin-bottom: 4px;
      text-transform: uppercase;
    }

    .day-session-value {
      font-size: 0.85rem;
      font-weight: 700;
      display: flex;
      justify-content: center;
      gap: 12px;
    }

    .day-stats-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      margin-bottom: 12px;
    }

    .day-stat-item {
      text-align: center;
      padding: 8px;
      background: rgba(255, 255, 255, 0.03);
      border-radius: var(--radius-sm);
    }

    .day-stat-label {
      font-size: 0.6rem;
      color: var(--text-muted);
      text-transform: uppercase;
      margin-bottom: 6px;
    }

    .day-stat-value {
      font-size: 1rem;
      font-weight: 700;
    }

    .day-stat-value.profit { color: var(--success); }
    .day-stat-value.loss { color: var(--danger); }

    .day-wr {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 10px;
      font-size: 0.75rem;
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
      border-radius: var(--radius-full);
      padding: 8px 12px;
      display: flex;
      justify-content: space-around;
      box-shadow: 0 25px 50px -12px rgba(0,0,0,0.8), 0 0 0 1px rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      z-index: 1000;
      animation: slideUp 0.5s ease;
    }

    .nav-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 8px 12px;
      border-radius: var(--radius-full);
      color: var(--text-secondary);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      font-size: 0.7rem;
      flex: 1;
      gap: 3px;
      text-transform: uppercase;
    }

    .nav-item span {
      font-size: 1.2rem;
      transition: transform 0.3s ease;
    }

    .nav-item p {
      margin: 0;
      font-size: 0.6rem;
    }

    .nav-item:hover span {
      transform: translateY(-2px);
    }

    .nav-item.active {
      background: var(--gradient);
      color: white;
      box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.5);
    }

    /* Responsive */
    @media (max-width: 768px) {
      body {
        padding: 16px 12px 80px 12px;
      }
      
      .combination-grid {
        grid-template-columns: 1fr;
        gap: 10px;
      }
      
      .session-day-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
      }
      
      .insight-grid {
        grid-template-columns: 1fr;
        gap: 10px;
      }
      
      .session-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
      }
      
      .stats-grid {
        grid-template-columns: 1fr;
        gap: 10px;
      }
      
      .desktop-table {
        display: none;
      }
      
      .mobile-cards {
        display: block;
      }
      
      .header {
        padding: 12px 16px;
        margin-bottom: 16px;
      }
      
      h2 {
        font-size: 1.1rem;
      }
      
      h2 i {
        padding: 8px;
        font-size: 1rem;
      }
      
      .date-badge {
        padding: 6px 12px;
        font-size: 0.65rem;
      }
      
      .section-title {
        margin: 20px 0 12px 0;
      }
      
      .section-title h3 {
        font-size: 0.95rem;
      }
      
      .section-title i {
        width: 28px;
        height: 28px;
        font-size: 0.85rem;
      }
      
      .stat-value {
        font-size: 1.4rem;
      }
      
      .session-value {
        font-size: 1.5rem;
      }
      
      .session-title {
        font-size: 1rem;
      }
      
      .best-day-name {
        font-size: 0.85rem;
      }
      
      .best-day-icon {
        font-size: 1.6rem;
      }
      
      .insight-value {
        font-size: 1.2rem;
      }
      
      .insight-title {
        font-size: 0.85rem;
      }
      
      .combination-value {
        font-size: 1.6rem;
      }
      
      .day-session-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 480px) {
      .session-day-grid {
        grid-template-columns: 1fr;
      }
      
      .session-grid {
        grid-template-columns: 1fr;
      }
      
      .stats-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (min-width: 769px) {
      .desktop-table {
        display: block;
      }
      .mobile-cards {
        display: none;
      }
    }
  </style>
</head>
<body>

<div class="header">
  <h2>
    <i class="fas fa-chart-pie"></i> 
    ANALISIS STATISTIK
  </h2>
  <div class="date-badge">
    <i class="far fa-calendar-alt"></i>
    <?= date('d M Y') ?>
  </div>
</div>

<!-- Global Stats Cards -->
<div class="stats-grid">
  <div class="stat-card profit">
    <div class="stat-icon"><i class="fas fa-arrow-up"></i></div>
    <div class="stat-label">TOTAL PROFIT</div>
    <div class="stat-value"><?= $win ?></div>
    <div class="stat-desc">SESI PROFIT</div>
  </div>

  <div class="stat-card loss">
    <div class="stat-icon"><i class="fas fa-arrow-down"></i></div>
    <div class="stat-label">TOTAL LOSS</div>
    <div class="stat-value"><?= $loss ?></div>
    <div class="stat-desc">SESI LOSS</div>
  </div>

  <div class="stat-card winrate">
    <div class="stat-icon"><i class="fas fa-percent"></i></div>
    <div class="stat-label">WINRATE</div>
    <div class="stat-value"><?= number_format($winrate,1) ?>%</div>
    <div class="stat-desc">DARI <?= $total_trade ?> SESI (PROFIT+LOSS)</div>
  </div>
</div>

<!-- Session 1 & 2 Combination Analysis -->
<div class="section-title">
  <i class="fas fa-chart-line"></i>
  <h3>🎯 ANALISIS KOMBINASI SESI 1 & 2</h3>
</div>

<div class="combination-section">
  <div class="combination-grid">
    <div class="combination-card profit-profit">
      <div class="combination-icon">📈📈</div>
      <div class="combination-title">SESI 1 PROFIT & SESI 2 PROFIT</div>
      <div class="combination-value"><?= $session1_2_combinations['profit_profit'] ?> KALI</div>
      <div class="combination-percent"><?= $profit_profit_percent ?>%</div>
      <div class="combination-desc">DARI TOTAL <?= $session1_2_combinations['total_valid'] ?> TRANSAKSI</div>
    </div>
    
    <div class="combination-card profit-loss">
      <div class="combination-icon">📈📉</div>
      <div class="combination-title">SESI 1 PROFIT & SESI 2 LOSS</div>
      <div class="combination-value"><?= $session1_2_combinations['profit_loss'] ?> KALI</div>
      <div class="combination-percent"><?= $profit_loss_percent ?>%</div>
      <div class="combination-desc">DARI TOTAL <?= $session1_2_combinations['total_valid'] ?> TRANSAKSI</div>
    </div>
    
    <div class="combination-card loss-loss">
      <div class="combination-icon">📉📉</div>
      <div class="combination-title">SESI 1 LOSS & SESI 2 LOSS</div>
      <div class="combination-value"><?= $session1_2_combinations['loss_loss'] ?> KALI</div>
      <div class="combination-percent"><?= $loss_loss_percent ?>%</div>
      <div class="combination-desc">DARI TOTAL <?= $session1_2_combinations['total_valid'] ?> TRANSAKSI</div>
    </div>
  </div>
</div>

<!-- Best Day per Session -->
<div class="section-title">
  <i class="fas fa-calendar-alt"></i>
  <h3>🏆 HARI TERBAIK SETIAP SESI</h3>
</div>

<?php
$day_icons = [
  'senin'  => '📈',
  'selasa' => '📊',
  'rabu'   => '📉',
  'kamis'  => '💰',
  'jumat'  => '🎯'
];
?>

<div class="session-day-grid">
  <?php for ($session = 1; $session <= 6; $session++): 
    $best_hari = $best_day_per_session[$session];
    $profit_count = $best_day_value_per_session[$session];
    $total_profit_session = $session_profit[$session];
    $percentage = $total_profit_session > 0 ? ($profit_count / $total_profit_session) * 100 : 0;

    $hari_lower = strtolower($best_hari);
    $icon = isset($day_icons[$hari_lower]) ? $day_icons[$hari_lower] : '❓';
  ?>
  <div class="session-day-card">
    <div class="session-badge">SESI <?= $session ?></div>
    <div class="session-title">SESI <?= $session ?></div>

    <div class="best-day-info">
      <div class="best-day-icon"><?= $icon ?></div>

      <div class="best-day-name">
        <?= $best_hari != '-' ? mb_strtoupper($best_hari, 'UTF-8') : 'BELUM ADA DATA' ?>
      </div>

      <div class="best-day-profit">
        PROFIT : <span><?= $profit_count ?> KALI </span>
      </div>
    </div>

    <div class="progress-container">
      <div class="progress-label">
        <?= $profit_count ?> DARI <?= $total_profit_session ?> PROFIT SESI <?= $session ?>
      </div>

      <div class="progress-bar">
        <div class="progress-fill" style="width: <?= $percentage ?>%"></div>
      </div>
    </div>
  </div>
  <?php endfor; ?>
</div>

<!-- Insight Cards -->
<div class="insight-grid">
  <div class="insight-card best">
    <div class="insight-icon"><i class="fas fa-chart-line"></i></div>
    <div class="insight-label">🏆 SESI TERBAIK</div>
    <div class="insight-title">SESI <?= $best_session ?></div>
    <div class="insight-value"><?= $max_session_profit ?> PROFIT</div>
    <div class="insight-desc">JUMLAH PROFIT TERBANYAK</div>
  </div>
  
  <div class="insight-card best">
    <div class="insight-icon"><i class="fas fa-calendar-check"></i></div>
    <div class="insight-label">📅 HARI PROFIT TERBANYAK</div>
    <div class="insight-title">
      <?= $best_day_profit != '-' ? mb_strtoupper($best_day_profit, 'UTF-8') : 'BELUM ADA DATA' ?>
    </div>
    <div class="insight-value"><?= $max_day_profit ?> PROFIT</div>
    <div class="insight-desc">TOTAL PROFIT TERBANYAK</div>
  </div>
  
  <div class="insight-card best">
    <div class="insight-icon"><i class="fas fa-trophy"></i></div>
    <div class="insight-label">🎯 WINRATE TERTINGGI</div>
    <div class="insight-title">
      <?= $best_day_wr != '-' ? mb_strtoupper($best_day_wr, 'UTF-8') : 'BELUM ADA DATA' ?>
    </div>
    <div class="insight-value">
      <?= $best_day_wr != '-' ? number_format($best_wr_value,1) : '0' ?>%
    </div>
    <div class="insight-desc">MINIMAL 3 TRANSAKSI (PROFIT+LOSS)</div>
  </div>
  
  <div class="insight-card worst">
    <div class="insight-icon"><i class="fas fa-exclamation-triangle"></i></div>
    <div class="insight-label">⚠️ HARI LOSS TERBANYAK</div>
    <div class="insight-title">
      <?= $worst_day != '-' ? mb_strtoupper($worst_day, 'UTF-8') : 'BELUM ADA DATA' ?>
    </div>
    <div class="insight-value"><?= $max_loss ?> LOSS</div>
    <div class="insight-desc">WARNING !</div>
  </div>
</div>

<!-- Winrate per Session -->
<div class="section-title">
  <i class="fas fa-clock"></i>
  <h3>WINRATE PER SESI</h3>
</div>

<div class="session-grid">
  <?php for ($i = 1; $i <= 6; $i++): ?>
  <div class="session-item <?= $best_session == $i ? 'best-session' : '' ?>">
    <div class="session-number">SESI <?= $i ?></div>
    <div class="session-value"><?= wr($wins[$i], $total_transactions[$i]) ?>%</div>
    <div class="session-stats">
      <span class="profit-count">📈 PROFIT : <?= $session_profit[$i] ?? 0 ?></span>
      <span class="lose-count">📉 LOSS : <?= $session_lose[$i] ?? 0 ?></span>
      <span class="skip-count">⏭️ SKIP : <?= $session_skip[$i] ?? 0 ?></span>
    </div>
    <div class="progress-bar">
      <div class="progress-fill" style="width: <?= wr($wins[$i], $total_transactions[$i]) ?>%"></div>
    </div>
  </div>
  <?php endfor; ?>
</div>

<!-- Statistik per Hari dengan Sesi 1-6 -->
<div class="section-title">
  <i class="fas fa-calendar-week"></i>
  <h3>ANALISIS HARIAN LENGKAP (SESI 1-6)</h3>
</div>

<!-- DESKTOP TABLE -->
<div class="desktop-table">
  <table>
    <thead>
      <tr>
        <th rowspan="2">HARI</th>
        <th colspan="2">SESI 1</th>
        <th colspan="2">SESI 2</th>
        <th colspan="2">SESI 3</th>
        <th colspan="2">SESI 4</th>
        <th colspan="2">SESI 5</th>
        <th colspan="2">SESI 6</th>
        <th rowspan="2">TOTAL<br>PROFIT</th>
        <th rowspan="2">TOTAL<br>LOSS</th>
        <th rowspan="2">TOTAL<br>SESI</th>
        <th rowspan="2">WINRATE</th>
      </tr>
      <tr>
        <?php for ($i = 1; $i <= 6; $i++): ?>
        <th>✅</th><th>❌</th>
        <?php endfor; ?>
      </tr>
    </thead>
    <tbody>
      <?php 
      foreach ($day_order as $h): 
        $v = $hari_stats[$h];
        $t = $v['profit'] + $v['lose'];
        $wr_h = $t > 0 ? ($v['profit']/$t)*100 : 0;
        $is_best = (strtolower($h) == strtolower($best_day_profit));
      ?>
        <tr class="<?= $is_best ? 'best-row' : '' ?>">
          <td><strong><?= mb_strtoupper($h, 'UTF-8') ?></strong> <?= $is_best ? '🏆' : '' ?></td>
          <?php for ($i = 1; $i <= 6; $i++): ?>
          <td class="profit-text"><?= $v["s{$i}_p"] ?? 0 ?></td>
          <td class="loss-text"><?= $v["s{$i}_l"] ?? 0 ?></td>
          <?php endfor; ?>
          <td class="profit-text"><?= $v['profit'] ?></td>
          <td class="loss-text"><?= $v['lose'] ?></td>
          <td class="neutral-text"><?= $t ?></td>
          <td class="profit-text"><?= number_format($wr_h,1) ?>%</td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<!-- MOBILE CARDS -->
<div class="mobile-cards">
  <?php 
  foreach ($day_order as $h): 
    $v = $hari_stats[$h];
    $t = $v['profit'] + $v['lose'];
    $wr_h = $t > 0 ? ($v['profit']/$t)*100 : 0;
    $icon = isset($day_icons[strtolower($h)]) ? $day_icons[strtolower($h)] : '📅';
    $is_best = (strtolower($h) == strtolower($best_day_profit));
  ?>
  <div class="day-card <?= $is_best ? 'best-day-card' : '' ?>">
    <div class="day-card-header">
      <div class="day-icon"><?= $icon ?></div>
      <div class="day-title"><?= mb_strtoupper($h, 'UTF-8') ?></div>
      <?php if($is_best): ?><div class="badge best">TERBAIK</div><?php endif; ?>
    </div>

    <div class="day-session-grid">
      <?php for ($i = 1; $i <= 6; $i++): 
        $p = $v["s{$i}_p"] ?? 0;
        $l = $v["s{$i}_l"] ?? 0;
      ?>
      <div class="day-session-item">
        <div class="day-session-label">SESI <?= $i ?></div>
        <div class="day-session-value">
          <span class="profit-text">✅<?= $p ?></span>
          <span class="loss-text">❌<?= $l ?></span>
        </div>
      </div>
      <?php endfor; ?>
    </div>

    <div class="day-stats-grid">
      <div class="day-stat-item">
        <div class="day-stat-label">TOTAL PROFIT</div>
        <div class="day-stat-value profit">+<?= $v['profit'] ?></div>
      </div>
      <div class="day-stat-item">
        <div class="day-stat-label">TOTAL LOSS</div>
        <div class="day-stat-value loss">-<?= $v['lose'] ?></div>
      </div>
      <div class="day-stat-item">
        <div class="day-stat-label">TOTAL SESI</div>
        <div class="day-stat-value"><?= $t ?></div>
      </div>
    </div>

    <div class="day-wr">
      <span><i class="fas fa-chart-line"></i> WINRATE</span>
      <span class="profit-text"><?= number_format($wr_h,1) ?>%</span>
    </div>

    <div class="progress-bar" style="margin-top: 10px;">
      <div class="progress-fill" style="width: <?= $wr_h ?>%"></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Bottom Navigation -->
<div class="bottom-nav">
  <a href="index.php" class="nav-item"><span>🏠</span><p>HOME</p></a>
  <a href="tambah.php" class="nav-item"><span>📝</span><p>TAMBAH</p></a>
  <a href="statistik.php" class="nav-item active"><span>📊</span><p>STATISTIK</p></a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.body.style.animation = 'fadeIn 0.3s ease';
    
    const links = document.querySelectorAll('a:not([target="_blank"]):not([href^="http"]):not([href*="logout"]):not([href*="hapus"])');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            if (this.target === '_blank' || this.href.startsWith('javascript:') || this.href.startsWith('#')) {
                return;
            }
            if (this.href.includes('logout') || this.href.includes('hapus')) {
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
</script>

</body>
</html>