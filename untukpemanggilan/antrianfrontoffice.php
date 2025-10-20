<?php
require_once('conf/conf.php');

date_default_timezone_set("Asia/Jakarta");
$tanggal = date("Y-m-d");
$jamNow  = date("H:i:s");

$typeMap = [
    'CS' => 'B',
    'Loket' => 'A',
    'Loket by Appointment' => 'C',
    'Ranap' => 'D',
];

// AJAX handler for real-time updates
if (isset($_GET['ajax']) && $_GET['ajax'] == 'getQueueStatus') {
    header('Content-Type: application/json');
    $queueStatus = [];

    foreach ($typeMap as $type => $prefix) {
        // Get current called number
        $currentQuery = "SELECT noantrian, start_time FROM antrian_loket 
                        WHERE type='$type' AND postdate='$tanggal' AND end_time != '00:00:00' 
                        ORDER BY start_time DESC LIMIT 1";
        $currentResult = mysqli_fetch_array(bukaquery($currentQuery));
        $currentNumber = $currentResult ? $currentResult['noantrian'] : null;

        // Get next number
        $nextQuery = "SELECT noantrian FROM antrian_loket 
                     WHERE type='$type' AND postdate='$tanggal' AND end_time='00:00:00' 
                     ORDER BY start_time ASC LIMIT 1";
        $nextResult = mysqli_fetch_array(bukaquery($nextQuery));
        $nextNumber = $nextResult ? $nextResult['noantrian'] : null;

        // Get previous number for rewind
        $prevQuery = "SELECT noantrian FROM antrian_loket 
                     WHERE type='$type' AND postdate='$tanggal' AND end_time != '00:00:00' 
                     ORDER BY start_time DESC LIMIT 1";
        $prevResult = mysqli_fetch_array(bukaquery($prevQuery));
        $prevNumber = $prevResult ? $prevResult['noantrian'] : null;

        // ADDED: Get remaining queue count (end_time = '00:00:00')
        $remainingQuery = "SELECT COUNT(*) as count FROM antrian_loket 
                          WHERE type='$type' AND postdate='$tanggal' AND end_time='00:00:00'";
        $remainingResult = mysqli_fetch_array(bukaquery($remainingQuery));
        $remainingCount = $remainingResult ? $remainingResult['count'] : 0;

        $queueStatus[$type] = [
            'current' => $currentNumber,
            'next' => $nextNumber,
            'previous' => $prevNumber,
            'prefix' => $prefix,
            'remaining' => $remainingCount // Added remaining count
        ];
    }

    echo json_encode($queueStatus);
    exit;
}

// Update call status
if (isset($_GET['play']) && isset($_GET['type']) && isset($_GET['no'])) {
    $type = $_GET['type'];
    $no   = $_GET['no'];
    bukaquery("UPDATE antrian_loket SET end_time='$jamNow' WHERE type='$type' AND noantrian='$no' AND postdate='$tanggal'");

    // Return JSON for AJAX requests
    if (isset($_GET['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'action' => 'play', 'type' => $type, 'no' => $no]);
        exit;
    }

    echo "<script>
        window.onload = function() {
            panggilAntrianFO('$type', '$no');
        };
    </script>";
}

if (isset($_GET['rewind']) && isset($_GET['type']) && isset($_GET['no'])) {
    $type = $_GET['type'];
    $no = $_GET['no'];

    // Set the current number back to pending and find the previous one
    bukaquery("UPDATE antrian_loket SET end_time='00:00:00' WHERE type='$type' AND noantrian='$no' AND postdate='$tanggal'");

    // Get the previous number to display
    $prevQuery = "SELECT noantrian FROM antrian_loket 
                 WHERE type='$type' AND postdate='$tanggal' AND end_time != '00:00:00' 
                 AND noantrian < '$no' ORDER BY noantrian DESC LIMIT 1";
    $prevResult = mysqli_fetch_array(bukaquery($prevQuery));

    if (isset($_GET['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'action' => 'rewind',
            'type' => $type,
            'no' => $no,
            'previous' => $prevResult ? $prevResult['noantrian'] : null
        ]);
        exit;
    }

    header("Location: antrianfrontoffice.php");
    exit;
}

if (isset($_GET['next']) && isset($_GET['type'])) {
    $type = $_GET['type'];
    $kd = getOne("SELECT kd FROM antrian_loket WHERE type='$type' AND postdate='$tanggal' AND end_time='00:00:00' ORDER BY start_time ASC LIMIT 1");
    if (!empty($kd)) {
        bukaquery("UPDATE antrian_loket SET end_time='$jamNow' WHERE kd='$kd'");
    }

    if (isset($_GET['ajax'])) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'action' => 'next', 'type' => $type]);
        exit;
    }

    header("Location: antrianfrontoffice.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<?php $setting =  mysqli_fetch_array(bukaquery("select nama_instansi,alamat_instansi,kabupaten,propinsi,kontak,email,logo from setting"));
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemanggil Antrian V2</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4a9b8e 0%, #2d6e5e 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            text-align: center;
        }

        .header h1 {
            color: #2d6e5e;
            font-size: 2.2em;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        .header .hospital-name {
            color: #4a9b8e;
            font-size: 1.3em;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .header .date {
            color: #7f8c8d;
            font-size: 1.1em;
        }

        .main-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-bottom: 25px;
        }

        .queue-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .queue-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .queue-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .queue-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
        }

        .queue-header h3 {
            color: #2d6e5e;
            font-size: 1.4em;
            font-weight: 600;
            margin-left: 10px;
        }

        .queue-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5em;
            color: white;
        }

        .queue-icon.cs {
            background: linear-gradient(45deg, #4a9b8e, #2d6e5e);
        }

        .queue-icon.loket {
            background: linear-gradient(45deg, #5cb85c, #449d44);
        }

        .queue-icon.appointment {
            background: linear-gradient(45deg, #f0ad4e, #ec971f);
        }

        .queue-icon.ranap {
            background: linear-gradient(45deg, #d9534f, #c9302c);
        }

        .queue-numbers {
            margin-bottom: 20px;
        }

        .number-display {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .number-item {
            text-align: center;
            flex: 1;
            padding: 15px;
            border-radius: 10px;
            margin: 0 5px;
        }

        .current-number {
            background: linear-gradient(45deg, #d9534f, #c9302c);
            color: white;
        }

        .next-number {
            background: linear-gradient(45deg, #4a9b8e, #2d6e5e);
            color: white;
        }

        .number-item h4 {
            font-size: 0.9em;
            margin-bottom: 5px;
            opacity: 0.9;
        }

        .number-item .number {
            font-size: 1.8em;
            font-weight: bold;
        }

        .control-buttons {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .btn {
            flex: 1;
            padding: 12px 15px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-rewind {
            background: linear-gradient(45deg, #f0ad4e, #ec971f);
        }

        .btn-play {
            background: linear-gradient(45deg, #4a9b8e, #2d6e5e);
        }

        .btn-next {
            background: linear-gradient(45deg, #5cb85c, #449d44);
        }

        .btn-recall {
            background: linear-gradient(45deg, #5bc0de, #31b0d5);
        }

        .control-panel {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            height: fit-content;
        }

        .control-panel h3 {
            color: #2d6e5e;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .text-announcement {
            margin-bottom: 20px;
        }

        .text-announcement textarea {
            width: 100%;
            min-height: 120px;
            padding: 15px;
            border: 2px solid #ecf0f1;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
            transition: border-color 0.3s ease;
        }

        .text-announcement textarea:focus {
            outline: none;
            border-color: #4a9b8e;
            box-shadow: 0 0 0 3px rgba(74, 155, 142, 0.1);
        }

        .btn-announce {
            width: 100%;
            background: linear-gradient(45deg, #4a9b8e, #2d6e5e);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-announce:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(74, 155, 142, 0.3);
        }

        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-left: 10px;
            animation: pulse 2s infinite;
        }

        .status-online {
            background: #4a9b8e;
        }

        .status-offline {
            background: #e74c3c;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* ADDED: Style for remaining queue counter */
        .remaining-counter {
            text-align: center;
            margin-top: 10px;
            font-size: 0.9em;
            color: #7f8c8d;
            padding: 5px;
            background: rgba(0, 0, 0, 0.03);
            border-radius: 8px;
        }

        .remaining-counter .count {
            font-weight: bold;
            color: #2d6e5e;
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }

            .queue-section {
                grid-template-columns: 1fr;
            }

            .control-buttons {
                flex-direction: column;
                gap: 8px;
            }

            .btn {
                min-height: 44px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-hospital" style="color: #4a9b8e;"></i> Sistem Manajemen Antrian</h1>
            <div class="hospital-name"><?php echo $setting["nama_instansi"] ?></div>
            <div class="date"><?= date("l, d F Y") ?> <span class="status-indicator status-online" id="connectionStatus"></span></div>
        </div>

        <div class="main-content">
            <div class="queue-section" id="queueSection">
                <!-- Queue cards will be populated by JavaScript -->
            </div>

            <div class="control-panel">
                <h3><i class="fas fa-microphone"></i> Pengumuman Teks</h3>
                <div class="text-announcement">
                    <textarea id="announcementText" placeholder="Ketik pengumuman di sini... (akan dikonversi ke huruf kecil untuk pengucapan yang lebih baik). Gunakan pemisah koma (,) untuk memberikan jeda."></textarea>
                </div>
                <button class="btn-announce" onclick="announceText()">
                    <i class="fas fa-volume-up"></i> Umumkan Teks
                </button>
            </div>
        </div>
    </div>

    <!-- Audio elements -->
    <audio id="suarabelawal" src="suara/open.wav" preload="auto"></audio>
    <audio id="suarabelakhir" src="suara/close.wav" preload="auto"></audio>

    <script src="https://cdn.socket.io/4.6.0/socket.io.min.js"></script>
    <script>
        // const socket = io('http://192.168.15.115:3000', {
        //     transports: ['websocket', 'polling', 'flashsocket']
        // });

        const typeConfig = {
            'CS': {
                icon: 'fas fa-headset',
                class: 'cs'
            },
            'Loket': {
                icon: 'fas fa-window-maximize',
                class: 'loket'
            },
            'Loket by Appointment': {
                icon: 'fas fa-calendar-check',
                class: 'appointment'
            },
            'Ranap': {
                icon: 'fas fa-bed',
                class: 'ranap'
            }
        };

        let currentQueueData = {};
        let isLoading = false;



        // Load queue data
        async function loadQueueData() {
            if (isLoading) return;

            try {
                isLoading = true;
                const response = await fetch('?ajax=getQueueStatus');
                const data = await response.json();
                currentQueueData = data;
                updateQueueDisplay();
            } catch (error) {
                console.error('Error loading queue data:', error);
            } finally {
                isLoading = false;
            }
        }

        // Update queue display
        function updateQueueDisplay() {
            const queueSection = document.getElementById('queueSection');
            queueSection.innerHTML = '';

            Object.entries(currentQueueData).forEach(([type, data]) => {
                const config = typeConfig[type];
                const card = createQueueCard(type, data, config);
                queueSection.appendChild(card);
            });
        }

        // Create queue card
        function createQueueCard(type, data, config) {
            const card = document.createElement('div');
            // ADDED: Remaining queue counter HTML
            const remainingHtml = data.remaining > 0 ?
                `<div class="remaining-counter">Antrian tersisa: <span class="count">${data.remaining}</span></div>` :
                '';
            card.className = 'queue-card';
            card.innerHTML = `
                <div class="queue-header">
                    <div class="queue-icon ${config.class}">
                        <i class="${config.icon}"></i>
                    </div>
                    <h3>${type} (${data.prefix})</h3>
                </div>
                <div class="queue-numbers">
                    <div class="number-display">
                        <div class="number-item current-number">
                            <h4>Sedang Dipanggil</h4>
                            <div class="number">${data.current ? data.prefix + String(data.current).padStart(3, '0') : '--'}</div>
                        </div>
                        <div class="number-item next-number">
                            <h4>Selanjutnya</h4>
                            <div class="number">${data.next ? data.prefix + String(data.next).padStart(3, '0') : '--'}</div>
                        </div>
                    </div>
                    ${remainingHtml} <!-- ADDED: Remaining counter -->
                </div>
                <div class="control-buttons">
                    ${data.current ? `
                        <button class="btn btn-recall" onclick="recallCurrent('${type}', '${data.current}')">
                            <i class="fas fa-redo"></i> Panggil Ulang
                        </button>
                        <button class="btn btn-rewind" onclick="performAction('rewind', '${type}', '${data.current}')">
                            <i class="fas fa-undo"></i> Ulangi
                        </button>
                    ` : ''}
                    ${data.next ? `
                        <button class="btn btn-play" onclick="performAction('play', '${type}', '${data.next}')">
                            <i class="fas fa-play"></i> Panggil
                        </button>
                        <button class="btn btn-next" onclick="performAction('next', '${type}')">
                            <i class="fas fa-forward"></i> Lewati
                        </button>
                    ` : data.current ? '' : `<p style="text-align: center; color: #7f8c8d; font-style: italic;">Tidak ada antrian tersedia</p>`}
                </div>
            `;
            return card;
        }

        // Recall current number (no database changes)
        function recallCurrent(type, no) {
            if (!no || isLoading) return;

            // Just call the audio function without any database operations
            panggilAntrianFO(type, no);
        }

        // Perform queue actions
        async function performAction(action, type, no = '') {
            if (isLoading) return;

            try {
                isLoading = true;
                document.body.classList.add('loading');

                let url = `?ajax=1&${action}=1&type=${encodeURIComponent(type)}`;
                if (no) url += `&no=${encodeURIComponent(no)}`;

                const response = await fetch(url);
                const result = await response.json();

                if (result.status === 'success') {
                    if (action === 'play') {
                        panggilAntrianFO(type, no);
                    }

                    // Wait a bit for database to update, then reload
                    setTimeout(loadQueueData, 500);
                }
            } catch (error) {
                console.error('Error performing action:', error);
            } finally {
                isLoading = false;
                document.body.classList.remove('loading');
            }
        }

        // Call queue function with audio
        function panggilAntrianFO(type, no) {
            const prefixMap = {
                "CS": "B",
                "Loket": "A",
                "Loket by Appointment": "C",
                "Ranap": "D"
            };

            const speechMap = {
                "A": "Loket Pendaftaran",
                "B": "Customer Service",
                "C": "Loket Pendaftaran",
                "D": "Loket Pendaftaran Rawat Inap"
            };
            const prefix = prefixMap[type];
            const nomor = prefixMap[type] + String(no).padStart(3, '');
            const audio1 = document.getElementById("suarabelawal");
            const audio2 = document.getElementById("suarabelakhir");

            // Play opening sound
            audio1.play();

            audio1.onended = () => {
                // Display on screen
                DisplayNomorAntrian(nomor, prefix);

                // console.log(`Panggil antrian: ${nomor} di ${type}`);
                // Speak the number
                const utter = new SpeechSynthesisUtterance(`Antrian pendaftaran, nomor urut, ${nomor}, silakan menuju ${speechMap[prefix].toLowerCase()}`);
                utter.lang = 'id-ID';
                utter.rate = 1.0;
                utter.volume = 1;

                window.speechSynthesis.speak(utter);
                utter.onend = () => {
                    audio2.play(); // Closing sound
                };
            };
        }

        function DisplayNomorAntrian(NoPanggil, Loket) {
            const socket = io('http://192.168.15.115:3000', {
                transports: ['websocket', 'polling', 'flashsocket']
            });

            // Initialize connection status
            socket.on('connect', () => {

                // console.log('Connected to Socket.io server');
            });
            socket.on('connect', () => {
                console.log('Connected to Socket.io server');
                document.getElementById('connectionStatus').className = 'status-indicator status-online';
                // Triggered when the socket connects
                socket.emit('DisplayAdmisi', {
                    NoAntrianAdmisi: NoPanggil,
                    LoketAdmisi: Loket
                });
            });
        }

        // Announce custom text
        function announceText() {
            const textarea = document.getElementById('announcementText');
            const text = textarea.value.trim();

            if (!text) {
                alert('Mohon masukkan teks untuk diumumkan');
                return;
            }

            const audio1 = document.getElementById("suarabelawal");
            const audio2 = document.getElementById("suarabelakhir");

            audio1.play();

            audio1.onended = () => {
                const utter = new SpeechSynthesisUtterance(text.toLowerCase());
                utter.lang = 'id-ID';
                utter.rate = 1.0;
                utter.volume = 1;

                window.speechSynthesis.speak(utter);

                utter.onend = () => {
                    audio2.play();
                };
            };
        }

        // Auto-refresh every 3 seconds
        setInterval(loadQueueData, 3000);

        // Initial load
        loadQueueData();

        // Load when page becomes visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                loadQueueData();
            }
        });
    </script>
</body>

</html>