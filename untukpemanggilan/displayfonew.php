<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrian</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            overflow: hidden;
        }

        .main-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        /* Header */
        .header {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 20px;
            margin-bottom: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            text-align: center;
        }

        .header h1 {
            color: #2d6e5e;
            font-size: 2.5em;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .header .hospital-name {
            color: #4a9b8e;
            font-size: 1.4em;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header .datetime {
            color: #7f8c8d;
            font-size: 1.2em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* Main Display Area */
        .display-area {
            flex: 1;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        /* Current Queue Section */
        .current-queue {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .current-queue::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(74, 155, 142, 0.05) 0%, transparent 70%);
            animation: pulse-bg 3s ease-in-out infinite;
        }

        @keyframes pulse-bg {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.05); opacity: 0.5; }
        }

        .queue-label {
            color: #7f8c8d;
            font-size: 2em;
            font-weight: 600;
            margin-bottom: 20px;
            z-index: 1;
            opacity: 0;
            animation: fade-in-down 0.8s ease-out forwards;
        }

        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .queue-number-display {
            background: linear-gradient(135deg, #d9534f 0%, #c9302c 100%);
            padding: 50px 80px;
            border-radius: 25px;
            box-shadow: 0 15px 50px rgba(217, 83, 79, 0.4);
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
            opacity: 0;
            animation: number-enter 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            animation-delay: 0.3s;
        }

        @keyframes number-enter {
            0% {
                transform: scale(0.7) translateY(30px);
                opacity: 0;
            }
            60% {
                transform: scale(1.05) translateY(-5px);
            }
            100% {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        .queue-number {
            color: white;
            font-size: 8em;
            font-weight: 900;
            letter-spacing: 8px;
            text-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            display: inline-block;
            animation: number-glow 2s ease-in-out infinite;
        }

        @keyframes number-glow {
            0%, 100% {
                text-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            }
            50% {
                text-shadow: 0 5px 30px rgba(0, 0, 0, 0.5), 
                             0 0 20px rgba(255, 255, 255, 0.3);
            }
        }

        .queue-location {
            background: linear-gradient(135deg, #4a9b8e 0%, #2d6e5e 100%);
            padding: 20px 40px;
            border-radius: 15px;
            color: white;
            font-size: 1.8em;
            font-weight: 700;
            z-index: 1;
            box-shadow: 0 10px 30px rgba(74, 155, 142, 0.3);
            opacity: 0;
            animation: fade-in-up 0.8s ease-out forwards;
            animation-delay: 0.8s;
        }

        @keyframes fade-in-up {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .waiting-state {
            text-align: center;
            color: #95a5a6;
            z-index: 1;
            opacity: 0;
            animation: fade-in 1s ease-out forwards;
        }

        @keyframes fade-in {
            0% {
                opacity: 0;
                transform: scale(0.95);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .waiting-state i {
            font-size: 6em;
            margin-bottom: 20px;
            opacity: 0.3;
            animation: waiting-pulse 3s ease-in-out infinite;
        }

        @keyframes waiting-pulse {
            0%, 100% { 
                opacity: 0.3; 
                transform: scale(1);
            }
            50% { 
                opacity: 0.5; 
                transform: scale(1.03);
            }
        }

        .waiting-state h3 {
            font-size: 2em;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .waiting-state p {
            font-size: 1.3em;
        }

        /* Queue List Section */
        .queue-list {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .queue-list-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 3px solid #ecf0f1;
        }

        .queue-list-header h3 {
            color: #2d6e5e;
            font-size: 1.6em;
            font-weight: 700;
        }

        .queue-list-header i {
            color: #4a9b8e;
            font-size: 1.5em;
        }

        .queue-items {
            flex: 1;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #4a9b8e #ecf0f1;
        }

        .queue-items::-webkit-scrollbar {
            width: 8px;
        }

        .queue-items::-webkit-scrollbar-track {
            background: #ecf0f1;
            border-radius: 10px;
        }

        .queue-items::-webkit-scrollbar-thumb {
            background: #4a9b8e;
            border-radius: 10px;
        }

        .queue-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            animation: item-slide-in 0.6s ease;
            opacity: 0;
            animation-fill-mode: forwards;
        }

        @keyframes item-slide-in {
            from {
                transform: translateX(30px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .queue-item:hover {
            background: #e8f4f3;
            transform: translateX(-8px) scale(1.02);
            box-shadow: 0 4px 20px rgba(74, 155, 142, 0.15);
        }

        .queue-item-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .queue-item-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3em;
            color: white;
        }

        .icon-cs { background: linear-gradient(45deg, #4a9b8e, #2d6e5e); }
        .icon-loket { background: linear-gradient(45deg, #5cb85c, #449d44); }
        .icon-appointment { background: linear-gradient(45deg, #f0ad4e, #ec971f); }
        .icon-ranap { background: linear-gradient(45deg, #d9534f, #c9302c); }

        .queue-item-info h4 {
            color: #2c3e50;
            font-size: 1.1em;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .queue-item-info p {
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .queue-item-number {
            background: linear-gradient(135deg, #4a9b8e, #2d6e5e);
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            font-size: 1.5em;
            font-weight: 800;
            box-shadow: 0 4px 15px rgba(74, 155, 142, 0.3);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .queue-item:hover .queue-item-number {
            transform: scale(1.08);
            box-shadow: 0 6px 25px rgba(74, 155, 142, 0.4);
        }

        /* Status Indicator */
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-left: 10px;
            animation: pulse 2s infinite;
        }

        .status-online {
            background: #4a9b8e;
            box-shadow: 0 0 10px rgba(74, 155, 142, 0.5);
        }

        .status-offline {
            background: #e74c3c;
            box-shadow: 0 0 10px rgba(231, 76, 60, 0.5);
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
                box-shadow: 0 0 10px currentColor;
            }
            50% {
                opacity: 0.7;
                transform: scale(1.15);
                box-shadow: 0 0 20px currentColor;
            }
        }

        /* Loading Animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(45, 110, 94, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.5s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        .loading-spinner {
            width: 70px;
            height: 70px;
            border: 6px solid rgba(255, 255, 255, 0.2);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1.5s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .display-area {
                grid-template-columns: 1fr;
            }
            
            .queue-number {
                font-size: 6em;
            }
            
            .queue-list {
                max-height: 400px;
            }
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.8em;
            }
            
            .queue-number {
                font-size: 4em;
            }
            
            .queue-number-display {
                padding: 30px 50px;
            }
        }
    </style>
</head>
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="main-container">
        <div class="header">
            <h1><i class="fas fa-hospital"></i> Display Antrian</h1>
            <div class="hospital-name">RS Indriati Boyolali</div>
            <div class="datetime">
                <i class="fas fa-calendar-alt"></i>
                <span id="currentDateTime"></span>
                <span class="status-indicator status-online" id="connectionStatus"></span>
            </div>
        </div>

        <div class="display-area">
            <!-- Current Queue Display -->
            <div class="current-queue" id="currentQueueSection">
                <div class="waiting-state">
                    <i class="fas fa-clock"></i>
                    <h3>Menunggu Panggilan</h3>
                    <p>Silakan menunggu nomor antrian Anda dipanggil</p>
                </div>
            </div>

            <!-- Queue List -->
            <div class="queue-list">
                <div class="queue-list-header">
                    <i class="fas fa-list"></i>
                    <h3>Antrian Aktif</h3>
                </div>
                <div class="queue-items" id="queueItems">
                    <!-- Queue items will be populated here -->
                </div>
            </div>
        </div>
    </div>

    <audio id="bellSound" src="suara/open.wav" preload="auto"></audio>

    <script>
        // Configuration
        const POLL_INTERVAL = 2500; // Poll every 2.5 seconds (slower for elegant transitions)
        const API_ENDPOINT = 'antrianfrontoffice.php?ajax=getQueueStatus';
        
        let previousQueueData = {};
        let isFirstLoad = true;

        // Type configuration
        const typeConfig = {
            'CS': {
                icon: 'fas fa-headset',
                class: 'icon-cs',
                location: 'Customer Service'
            },
            'Loket': {
                icon: 'fas fa-window-maximize',
                class: 'icon-loket',
                location: 'Loket Pendaftaran'
            },
            'Loket by Appointment': {
                icon: 'fas fa-calendar-check',
                class: 'icon-appointment',
                location: 'Loket Pendaftaran'
            },
            'Ranap': {
                icon: 'fas fa-bed',
                class: 'icon-ranap',
                location: 'Loket Rawat Inap'
            }
        };

        // Update date and time
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('currentDateTime').textContent = 
                now.toLocaleDateString('id-ID', options);
        }

        // Fetch queue data
        async function fetchQueueData() {
            try {
                const response = await fetch(API_ENDPOINT);
                if (!response.ok) throw new Error('Network response failed');
                
                const data = await response.json();
                updateConnectionStatus(true);
                return data;
            } catch (error) {
                console.error('Error fetching queue data:', error);
                updateConnectionStatus(false);
                return null;
            }
        }

        // Update connection status
        function updateConnectionStatus(isOnline) {
            const statusElement = document.getElementById('connectionStatus');
            statusElement.className = `status-indicator ${isOnline ? 'status-online' : 'status-offline'}`;
        }

        // Check if there's a new queue call
        function checkForNewCall(newData) {
            let hasNewCall = false;
            let newCallType = null;
            let newCallNumber = null;

            for (const [type, data] of Object.entries(newData)) {
                const oldCurrent = previousQueueData[type]?.current;
                const newCurrent = data.current;

                // Check if current number changed and is not null
                if (newCurrent && newCurrent !== oldCurrent) {
                    hasNewCall = true;
                    newCallType = type;
                    newCallNumber = data.current;
                    break;
                }
            }

            return { hasNewCall, newCallType, newCallNumber };
        }

        // Store the last displayed queue to maintain display
        let lastDisplayedQueue = null;

        // Update current queue display
        function updateCurrentQueueDisplay(queueData) {
            const container = document.getElementById('currentQueueSection');
            
            // Find the most recently called queue (highest priority to newest change)
            let latestCall = null;
            let latestType = null;
            let hasCurrentCall = false;

            // Check if any queue has a current number
            for (const [type, data] of Object.entries(queueData)) {
                if (data.current) {
                    hasCurrentCall = true;
                    // If this is different from what we're showing, or we have no display yet
                    const currentDisplay = `${type}-${data.current}`;
                    if (!lastDisplayedQueue || lastDisplayedQueue !== currentDisplay) {
                        latestCall = data;
                        latestType = type;
                        lastDisplayedQueue = currentDisplay;
                        break;
                    }
                }
            }

            // If no new call detected but we have an active display, keep showing it
            if (!latestCall && lastDisplayedQueue && hasCurrentCall) {
                const [savedType, savedNumber] = lastDisplayedQueue.split('-');
                const savedData = queueData[savedType];
                if (savedData && savedData.current == savedNumber) {
                    latestCall = savedData;
                    latestType = savedType;
                }
            }

            if (latestCall && latestType) {
                const config = typeConfig[latestType];
                const formattedNumber = latestCall.prefix + String(latestCall.current).padStart(3, '0');
                
                container.innerHTML = `
                    <div class="queue-label">
                        <i class="${config.icon}"></i> Nomor Antrian
                    </div>
                    <div class="queue-number-display">
                        <div class="queue-number">${formattedNumber}</div>
                    </div>
                    <div class="queue-location">
                        <i class="fas fa-arrow-right"></i> ${config.location}
                    </div>
                `;
            } else {
                // Show waiting state
                lastDisplayedQueue = null;
                container.innerHTML = `
                    <div class="waiting-state">
                        <i class="fas fa-clock"></i>
                        <h3>Menunggu Panggilan</h3>
                        <p>Silakan menunggu nomor antrian Anda dipanggil</p>
                    </div>
                `;
            }
        }

        // Update queue list
        function updateQueueList(queueData) {
            const container = document.getElementById('queueItems');
            let html = '';
            let delayIndex = 0;

            for (const [type, data] of Object.entries(queueData)) {
                if (data.current) {
                    const config = typeConfig[type];
                    const formattedNumber = data.prefix + String(data.current).padStart(3, '0');
                    
                    html += `
                        <div class="queue-item" style="animation-delay: ${delayIndex * 0.15}s;">
                            <div class="queue-item-left">
                                <div class="queue-item-icon ${config.class}">
                                    <i class="${config.icon}"></i>
                                </div>
                                <div class="queue-item-info">
                                    <h4>${type}</h4>
                                    <p>${config.location}</p>
                                </div>
                            </div>
                            <div class="queue-item-number">${formattedNumber}</div>
                        </div>
                    `;
                    delayIndex++;
                }
            }

            if (!html) {
                html = `
                    <div style="text-align: center; padding: 40px; color: #95a5a6; animation: fade-in 0.8s ease-out;">
                        <i class="fas fa-inbox" style="font-size: 3em; margin-bottom: 15px; opacity: 0.3;"></i>
                        <p style="font-size: 1.2em;">Belum ada antrian aktif</p>
                    </div>
                `;
            }

            container.innerHTML = html;
        }

        // Play notification sound
        function playNotificationSound() {
            const bell = document.getElementById('bellSound');
            bell.play().catch(err => console.log('Audio play failed:', err));
        }

        // Main update function
        async function updateDisplay() {
            const queueData = await fetchQueueData();
            
            if (!queueData) return;

            // Check for new calls (skip on first load)
            if (!isFirstLoad) {
                const { hasNewCall, newCallType, newCallNumber } = checkForNewCall(queueData);
                
                if (hasNewCall) {
                    playNotificationSound();
                    console.log(`New call detected: ${newCallType} - ${newCallNumber}`);
                }
            }

            // Update displays
            updateCurrentQueueDisplay(queueData);
            updateQueueList(queueData);

            // Store current data for next comparison
            previousQueueData = JSON.parse(JSON.stringify(queueData));
            isFirstLoad = false;
        }

        // Initialize
        function init() {
            updateDateTime();
            setInterval(updateDateTime, 1000);
            
            updateDisplay();
            setInterval(updateDisplay, POLL_INTERVAL);
        }

        // Start when page loads
        window.addEventListener('DOMContentLoaded', init);

        // Reload when page becomes visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                updateDisplay();
            }
        });
    </script>
</body>
</html>