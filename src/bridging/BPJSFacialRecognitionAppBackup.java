package bridging;

import com.github.sarxos.webcam.Webcam;
import com.github.sarxos.webcam.WebcamDriver;
import com.github.sarxos.webcam.WebcamResolution;
import fungsi.koneksiDB;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.awt.image.BufferedImage;
import java.io.*;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;
import java.util.Base64;
import java.util.HashMap;
import java.util.Map;
import javax.imageio.ImageIO;
import javax.swing.*;
import javax.swing.border.TitledBorder;
import org.json.simple.JSONObject;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

// Webcam capture implementation (simplified version using Java Media Framework concepts)
/**
 * BPJS Facial Recognition Application Enterprise-grade healthcare integration
 * system for BPJS Kesehatan facial verification
 *
 * @author IT Manager - Hospital Healthcare System
 * @version 1.0
 * @since 2025
 */
public class BPJSFacialRecognitionAppBackup extends javax.swing.JDialog {

    // Application constants - following enterprise configuration patterns
    private static final String DEFAULT_BASE_URL = "https://frista.bpjs-kesehatan.go.id";
    private static final String DEFAULT_VERSION = "3.0.2";
    private static final Dimension CAMERA_DIMENSION = new Dimension(640, 480);
    private static final Dimension APP_DIMENSION = new Dimension(1000, 700);

    // Status codes mirroring PHP implementation
    public enum StatusCode {
        OK("OK"),
        UNREGISTERED("UNREGISTERED"),
        ALREADY_REGISTERED("ALREADY_REGISTERED"),
        INVALID_ID("INVALID_ID"),
        INVALID_ENCODING("INVALID_ENCODING"),
        INVALID_IMAGE("INVALID_IMAGE"),
        AUTH_FAILED("AUTH_FAILED"),
        SERVER_UNREACHABLE("SERVER_UNREACHABLE"),
        INTERNAL_SERVER_ERROR("INTERNAL_SERVER_ERROR"),
        INTEGRATION_ERROR("INTEGRATION_ERROR");

        private final String value;

        StatusCode(String value) {
            this.value = value;
        }

        public String getValue() {
            return value;
        }
    }

    // Core business logic components
    private FacialRecognitionService facialRecognitionService;

    // UI Components - organized by functional areas
    private JTextField nikTextField;
    private JPasswordField passwordField;
    private JTextField usernameField;
    private JButton captureButton;
    private JButton verifyButton;
    private JButton registerButton;
    private JLabel statusLabel;
    private JLabel cameraPanel;
    private JTextArea logArea;

    // Camera and image handling
    private BufferedImage currentImage;
    private CameraService cameraService;

    // Add new constructor
    public BPJSFacialRecognitionAppBackup(java.awt.Frame parent, boolean modal, String nik) {
        super(parent, modal);
        initializeUI();
        initializeServices();

        if (nik != null && !nik.isEmpty()) {
            nikTextField.setText(nik);
        }
        setupEventHandlers();

        usernameField.setVisible(false);
        passwordField.setVisible(false);
        startCamera();
    }

    public BPJSFacialRecognitionAppBackup() {
        initializeServices();
        initializeUI();
        setupEventHandlers();
    }

    /**
     * Initialize core business services Following dependency injection patterns
     * for enterprise applications
     */
    private void initializeServices() {
        try {
            this.facialRecognitionService = new FacialRecognitionService();
            this.cameraService = new CameraService();
            logMessage("Services initialized successfully");
        } catch (Exception e) {
            logMessage("Error initializing services: " + e.getMessage());
            JOptionPane.showMessageDialog(this,
                    "Failed to initialize camera services. Please check camera permissions.",
                    "Initialization Error",
                    JOptionPane.ERROR_MESSAGE);
        }
    }

    /**
     * Initialize user interface components Following Material Design principles
     * for healthcare applications
     */
    private void initializeUI() {
        setTitle("BPJS Facial Recognition System v1.0");
        setSize(APP_DIMENSION);
//        setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
        centerWindow();

        // Main layout using BorderLayout for professional appearance
        setLayout(new BorderLayout(10, 10));

        // Header panel with branding
        add(createHeaderPanel(), BorderLayout.NORTH);

        // Main content area
        add(createMainPanel(), BorderLayout.CENTER);

        // Status and log panel
        add(createStatusPanel(), BorderLayout.SOUTH);

        // Apply professional styling
        applyEnterpriseTheme();

        // Add window listener
        setDefaultCloseOperation(JFrame.DO_NOTHING_ON_CLOSE);
        addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent e) {
                closeApplication();
            }
        });
    }

    private void centerWindow() {
        Dimension screenSize = Toolkit.getDefaultToolkit().getScreenSize();
        int x = (screenSize.width - getWidth()) / 2;
        int y = (screenSize.height - getHeight()) / 2;
        setLocation(x, y);
    }

    private JPanel createHeaderPanel() {
        JPanel headerPanel = new JPanel(new FlowLayout(FlowLayout.CENTER));
        headerPanel.setBackground(new Color(0, 128, 64)); // BPJS Green

        JLabel titleLabel = new JLabel("BPJS Kesehatan - Sistem Pengenalan Wajah");
        titleLabel.setFont(new Font("Arial", Font.BOLD, 18));
        titleLabel.setForeground(Color.WHITE);
        headerPanel.add(titleLabel);

        return headerPanel;
    }

    private JPanel createMainPanel() {
        JPanel mainPanel = new JPanel(new BorderLayout(10, 10));
        mainPanel.setBorder(BorderFactory.createEmptyBorder(10, 10, 10, 10));

        // Left panel - Camera and capture
        mainPanel.add(createCameraPanel(), BorderLayout.WEST);

        // Right panel - Input fields and actions
        mainPanel.add(createControlPanel(), BorderLayout.EAST);

        return mainPanel;
    }

    private JPanel createCameraPanel() {
        JPanel cameraContainer = new JPanel(new BorderLayout());
        cameraContainer.setBorder(new TitledBorder("Live Camera Feed"));
        cameraContainer.setPreferredSize(new Dimension(660, 500));

        // Camera display area
        cameraPanel = new JLabel();
        cameraPanel.setPreferredSize(CAMERA_DIMENSION);
        cameraPanel.setBorder(BorderFactory.createLoweredBevelBorder());
        cameraPanel.setBackground(Color.BLACK);
        cameraPanel.setOpaque(true);
        cameraPanel.setHorizontalAlignment(SwingConstants.CENTER);
        cameraPanel.setText("Camera Feed - Click 'Start Camera' to begin");
        cameraPanel.setForeground(Color.WHITE);

        cameraContainer.add(cameraPanel, BorderLayout.CENTER);

        // Camera controls
        JPanel cameraControls = new JPanel(new FlowLayout());
        JButton startCameraButton = new JButton("Start Camera");
        captureButton = new JButton("Capture Photo");
        captureButton.setEnabled(false);

        startCameraButton.addActionListener(e -> startCamera());
        captureButton.addActionListener(e -> capturePhoto());

        cameraControls.add(startCameraButton);
        cameraControls.add(captureButton);
        cameraContainer.add(cameraControls, BorderLayout.SOUTH);

        return cameraContainer;
    }

    private JPanel createControlPanel() {
        JPanel controlPanel = new JPanel();
        controlPanel.setLayout(new BoxLayout(controlPanel, BoxLayout.Y_AXIS));
        controlPanel.setBorder(new TitledBorder("BPJS Authentication & Verification"));
        controlPanel.setPreferredSize(new Dimension(320, 500));

        // Authentication section
        JPanel authPanel = createAuthenticationPanel();

        // Patient identification section
        JPanel patientPanel = createPatientPanel();

        // Action buttons section
        JPanel actionPanel = createActionPanel();

        controlPanel.add(authPanel);
        controlPanel.add(Box.createRigidArea(new Dimension(0, 10)));
        controlPanel.add(patientPanel);
        controlPanel.add(Box.createRigidArea(new Dimension(0, 10)));
        controlPanel.add(actionPanel);
        controlPanel.add(Box.createVerticalGlue());

        return controlPanel;
    }

    private JPanel createAuthenticationPanel() {
        JPanel authPanel = new JPanel(new GridBagLayout());
        authPanel.setBorder(new TitledBorder("BPJS Credentials"));

        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(5, 5, 5, 5);
        gbc.fill = GridBagConstraints.HORIZONTAL;

        // Username field
        gbc.gridx = 0;
        gbc.gridy = 0;
        authPanel.add(new JLabel("Username:"), gbc);
        gbc.gridx = 1;
        usernameField = new JTextField(15);
        authPanel.add(usernameField, gbc);

        // Password field
        gbc.gridx = 0;
        gbc.gridy = 1;
        authPanel.add(new JLabel("Password:"), gbc);
        gbc.gridx = 1;
        passwordField = new JPasswordField(15);
        authPanel.add(passwordField, gbc);

        return authPanel;
    }

    private JPanel createPatientPanel() {
        JPanel patientPanel = new JPanel(new GridBagLayout());
        patientPanel.setBorder(new TitledBorder("Patient Information"));

        GridBagConstraints gbc = new GridBagConstraints();
        gbc.insets = new Insets(5, 5, 5, 5);
        gbc.fill = GridBagConstraints.HORIZONTAL;

        gbc.gridx = 0;
        gbc.gridy = 0;
        patientPanel.add(new JLabel("NIK/BPJS Number:"), gbc);
        gbc.gridx = 1;
        nikTextField = new JTextField(16);
        nikTextField.setToolTipText("Enter 13 or 16 digit NIK/BPJS number");
        patientPanel.add(nikTextField, gbc);

        return patientPanel;
    }

    private JPanel createActionPanel() {
        JPanel actionPanel = new JPanel(new GridLayout(2, 1, 5, 5));
        actionPanel.setBorder(new TitledBorder("Actions"));

        verifyButton = new JButton("Verify Face");
        verifyButton.setEnabled(false);
        verifyButton.setBackground(new Color(0, 128, 64));
        verifyButton.setForeground(Color.WHITE);

        registerButton = new JButton("Register Biometric");
        registerButton.setEnabled(false);
        registerButton.setBackground(new Color(64, 128, 192));
        registerButton.setForeground(Color.WHITE);

        actionPanel.add(verifyButton);
        actionPanel.add(registerButton);

        return actionPanel;
    }

    private JPanel createStatusPanel() {
        JPanel statusPanel = new JPanel(new BorderLayout());

        statusLabel = new JLabel("Ready - Please authenticate and capture photo");
        statusLabel.setBorder(BorderFactory.createEmptyBorder(5, 10, 5, 10));
        statusLabel.setOpaque(true);
        statusLabel.setBackground(new Color(240, 240, 240));

        // Log area for debugging and audit trail
        logArea = new JTextArea(5, 50);
        logArea.setEditable(false);
        logArea.setFont(new Font("Courier New", Font.PLAIN, 12));
        JScrollPane logScrollPane = new JScrollPane(logArea);
        logScrollPane.setBorder(new TitledBorder("System Log"));

        statusPanel.add(statusLabel, BorderLayout.NORTH);
        statusPanel.add(logScrollPane, BorderLayout.CENTER);

        return statusPanel;
    }

    private void applyEnterpriseTheme() {
        try {
            UIManager.setLookAndFeel(UIManager.getSystemLookAndFeelClassName());
            SwingUtilities.updateComponentTreeUI(this);
        } catch (Exception e) {
            logMessage("Could not apply system theme: " + e.getMessage());
        }
    }

    private void setupEventHandlers() {
        verifyButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                performFacialVerification();
            }
        });

        registerButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                performBiometricRegistration();
            }
        });
    }

    private void startCamera() {
        try {
            cameraService.startCamera();
            captureButton.setEnabled(true);
            cameraPanel.setText("Camera Active - Ready to capture");
            logMessage("Camera started successfully");

            // Start camera feed update timer
            Timer cameraTimer = new Timer(100, e -> updateCameraFeed());
            cameraTimer.start();

        } catch (Exception e) {
            logMessage("Error starting camera: " + e.getMessage());
            JOptionPane.showMessageDialog(this,
                    "Could not start camera: " + e.getMessage(),
                    "Camera Error",
                    JOptionPane.ERROR_MESSAGE);
        }
    }

    private void updateCameraFeed() {
        try {
            BufferedImage frame = cameraService.getCurrentFrame();
            if (frame != null) {
                // Scale image to fit display
                Image scaledImage = frame.getScaledInstance(
                        cameraPanel.getWidth(),
                        cameraPanel.getHeight(),
                        Image.SCALE_FAST);
                cameraPanel.setIcon(new ImageIcon(scaledImage));
                cameraPanel.setText("");
            }
        } catch (Exception e) {
            // Silent fail for camera feed updates
        }
    }

    private void capturePhoto() {
        try {
            currentImage = cameraService.captureFrame();
            if (currentImage != null) {
                logMessage("Photo captured successfully");
                statusLabel.setText("Photo captured - Ready for verification");
                enableVerificationButtons();
            } else {
                logMessage("Failed to capture photo");
                statusLabel.setText("Failed to capture photo");
            }
        } catch (Exception e) {
            logMessage("Error capturing photo: " + e.getMessage());
            statusLabel.setText("Error capturing photo");
        }
    }

    private void enableVerificationButtons() {
        boolean hasImage = (currentImage != null);
        boolean hasCredentials = !usernameField.getText().trim().isEmpty()
                && passwordField.getPassword().length > 0;
        boolean hasNik = !nikTextField.getText().trim().isEmpty();

        verifyButton.setEnabled(hasImage && hasCredentials && hasNik);
        registerButton.setEnabled(hasImage && hasCredentials && hasNik);
    }

    private void performFacialVerification() {
        if (currentImage == null) {
            JOptionPane.showMessageDialog(this, "Please capture a photo first",
                    "No Photo", JOptionPane.WARNING_MESSAGE);
            return;
        }

        String nik = nikTextField.getText().trim();
        String username = koneksiDB.USERFINGERPRINTBPJS();
        String password = koneksiDB.PASSWORDFINGERPRINTBPJS();

        // Show progress dialog
        JDialog progressDialog = createProgressDialog("Verifying with BPJS server...");

        // Perform verification in background thread
        SwingWorker<Map<String, Object>, Void> worker = new SwingWorker<Map<String, Object>, Void>() {
            @Override
            protected Map<String, Object> doInBackground() throws Exception {
                try {
                    facialRecognitionService.init(username, password);
                    String base64Image = imageToBase64(currentImage);
                    return facialRecognitionService.verify(nik, base64Image);
                } catch (Exception e) {
                    Map<String, Object> errorResult = new HashMap<>();
                    errorResult.put("status", StatusCode.INTERNAL_SERVER_ERROR.getValue());
                    errorResult.put("message", "Verification failed: " + e.getMessage());
                    return errorResult;
                }
            }

            @Override
            protected void done() {
                progressDialog.dispose();
                try {
                    Map<String, Object> result = get();
                    handleVerificationResult(result);
                } catch (Exception e) {
                    logMessage("Verification error: " + e.getMessage());
                    statusLabel.setText("Verification failed");
                }
            }
        };

        worker.execute();
        progressDialog.setVisible(true);
    }

    private void performBiometricRegistration() {
        if (currentImage == null) {
            JOptionPane.showMessageDialog(this, "Please capture a photo first",
                    "No Photo", JOptionPane.WARNING_MESSAGE);
            return;
        }

        String nik = nikTextField.getText().trim();
        String username = usernameField.getText().trim();
        String password = new String(passwordField.getPassword());

        JDialog progressDialog = createProgressDialog("Registering biometric data...");

        SwingWorker<Map<String, Object>, Void> worker = new SwingWorker<Map<String, Object>, Void>() {
            @Override
            protected Map<String, Object> doInBackground() throws Exception {
                try {
                    facialRecognitionService.init(username, password);
                    String base64Image = imageToBase64(currentImage);
                    return facialRecognitionService.register(nik, base64Image);
                } catch (Exception e) {
                    Map<String, Object> errorResult = new HashMap<>();
                    errorResult.put("status", StatusCode.INTERNAL_SERVER_ERROR.getValue());
                    errorResult.put("message", "Registration failed: " + e.getMessage());
                    return errorResult;
                }
            }

            @Override
            protected void done() {
                progressDialog.dispose();
                try {
                    Map<String, Object> result = get();
                    handleRegistrationResult(result);
                } catch (Exception e) {
                    logMessage("Registration error: " + e.getMessage());
                    statusLabel.setText("Registration failed");
                }
            }
        };

        worker.execute();
        progressDialog.setVisible(true);
    }

    private JDialog createProgressDialog(String message) {
        JDialog dialog = new JDialog(this, "Processing", true);
        dialog.setDefaultCloseOperation(JDialog.DO_NOTHING_ON_CLOSE);
        dialog.setSize(300, 100);
        dialog.setLocationRelativeTo(this);

        JPanel panel = new JPanel(new BorderLayout());
        panel.add(new JLabel(message, SwingConstants.CENTER), BorderLayout.CENTER);

        JProgressBar progressBar = new JProgressBar();
        progressBar.setIndeterminate(true);
        panel.add(progressBar, BorderLayout.SOUTH);

        dialog.add(panel);
        return dialog;
    }

    private void handleVerificationResult(Map<String, Object> result) {
        String status = (String) result.get("status");
        String message = (String) result.get("message");

        logMessage("Verification result: " + status + " - " + message);

        if (StatusCode.OK.getValue().equals(status)) {
            statusLabel.setText("✓ Verification successful");
            statusLabel.setBackground(new Color(200, 255, 200));
            JOptionPane.showMessageDialog(this,
                    "Facial verification successful!\n" + message,
                    "Verification Success",
                    JOptionPane.INFORMATION_MESSAGE);

            // Add delay and then close
            new Timer(2000, new ActionListener() { // 2 second delay
                @Override
                public void actionPerformed(ActionEvent e) {
                    ((Timer) e.getSource()).stop();
                    closeApplication();
                }
            }).start();
        } else {
            statusLabel.setText("✗ Verification failed: " + status);
            statusLabel.setBackground(new Color(255, 200, 200));
            JOptionPane.showMessageDialog(this,
                    "Facial verification failed:\n" + message,
                    "Verification Failed",
                    JOptionPane.ERROR_MESSAGE);
        }
    }

    private void closeApplication() {
        // Close camera if active
        if (cameraService != null && cameraService.isActive()) {
            cameraService.stopCamera();
        }
        // Dispose the frame
        dispose();
    }

    private void handleRegistrationResult(Map<String, Object> result) {
        String status = (String) result.get("status");
        String message = (String) result.get("message");

        logMessage("Registration result: " + status + " - " + message);

        if (StatusCode.OK.getValue().equals(status)) {
            statusLabel.setText("✓ Registration successful");
            statusLabel.setBackground(new Color(200, 255, 200));
            JOptionPane.showMessageDialog(this,
                    "Biometric registration successful!\n" + message,
                    "Registration Success",
                    JOptionPane.INFORMATION_MESSAGE);
        } else {
            statusLabel.setText("✗ Registration failed: " + status);
            statusLabel.setBackground(new Color(255, 200, 200));
            JOptionPane.showMessageDialog(this,
                    "Biometric registration failed:\n" + message,
                    "Registration Failed",
                    JOptionPane.ERROR_MESSAGE);
        }
    }

    private String imageToBase64(BufferedImage image) throws IOException {
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        ImageIO.write(image, "jpg", baos);
        byte[] imageBytes = baos.toByteArray();
        return Base64.getEncoder().encodeToString(imageBytes);
    }

    private void logMessage(String message) {
        // Ensure logArea exists before trying to append
        if (logArea != null) {
            String timestamp = new java.util.Date().toString();
            logArea.append("[" + timestamp + "] " + message + "\n");
            logArea.setCaretPosition(logArea.getDocument().getLength());
        } else {
            // Fallback to console output if logArea isn't ready
            System.out.println("[INIT] " + message);
        }
    }

    /**
     * Core facial recognition service - mirrors PHP functionality
     * Enterprise-grade service with proper error handling and logging
     */
    private static class FacialRecognitionService {

        private String baseUrl = DEFAULT_BASE_URL;
        private String version = DEFAULT_VERSION;
        private String token;

        public void init(String username, String password) throws Exception {
            this.token = authenticate(username, password);
            if (this.token == null) {
                throw new Exception("Authentication failed");
            }
        }

        private String authenticate(String username, String password) throws Exception {
            URL url = new URL(baseUrl + "/frista-api/user/login/rs");
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();
            conn.setRequestMethod("POST");
            conn.setRequestProperty("Content-Type", "application/json");
            conn.setDoOutput(true);

            // Create JSON payload
            JSONObject authData = new JSONObject();
            authData.put("username", username);
            authData.put("password", password);
            authData.put("version", version);

            try (OutputStream os = conn.getOutputStream()) {
                byte[] input = authData.toJSONString().getBytes(StandardCharsets.UTF_8);
                os.write(input, 0, input.length);
            }

            int responseCode = conn.getResponseCode();
            if (responseCode == 200) {
                try (BufferedReader br = new BufferedReader(
                        new InputStreamReader(conn.getInputStream(), StandardCharsets.UTF_8))) {

                    StringBuilder response = new StringBuilder();
                    String responseLine;
                    while ((responseLine = br.readLine()) != null) {
                        response.append(responseLine.trim());
                    }

                    JSONParser parser = new JSONParser();
                    JSONObject jsonResponse = (JSONObject) parser.parse(response.toString());
                    return (String) jsonResponse.get("token");
                }
            }

            return null;
        }

        public Map<String, Object> verify(String id, String base64Image) throws Exception {
            Map<String, Object> result = new HashMap<>();

            if (token == null) {
                result.put("status", StatusCode.AUTH_FAILED.getValue());
                result.put("message", "Gagal autentikasi ke server BPJS");
                return result;
            }

            // Validate ID
            if (!id.matches("\\d{13}|\\d{16}")) {
                result.put("status", StatusCode.INVALID_ID.getValue());
                result.put("message", "Nomor identitas harus 13 atau 16 digit angka");
                return result;
            }

            // For verification, we would need to convert base64 image to face encoding
            // This is a simplified version - in reality, you'd use face recognition libraries
            double[] encoding = generateMockEncoding(base64Image);

            URL url = new URL(baseUrl + "/frista-api/face/match2");
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();
            conn.setRequestMethod("POST");
            conn.setRequestProperty("Content-Type", "application/json");
            conn.setRequestProperty("Authorization", "Bearer " + token);
            conn.setDoOutput(true);

            JSONObject payload = new JSONObject();
            payload.put("id", id);
            payload.put("encoding", encoding);

            try (OutputStream os = conn.getOutputStream()) {
                byte[] input = payload.toJSONString().getBytes(StandardCharsets.UTF_8);
                os.write(input, 0, input.length);
            }

            return processApiResponse(conn);
        }

        public Map<String, Object> register(String id, String base64Image) throws Exception {
            Map<String, Object> result = new HashMap<>();

            if (token == null) {
                result.put("status", StatusCode.AUTH_FAILED.getValue());
                result.put("message", "Gagal autentikasi ke server BPJS");
                return result;
            }

            // Create multipart form data for image upload
            String boundary = "----WebKitFormBoundary" + System.currentTimeMillis();

            URL url = new URL(baseUrl + "/frista-api/face/upload");
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();
            conn.setRequestMethod("POST");
            conn.setRequestProperty("Content-Type", "multipart/form-data; boundary=" + boundary);
            conn.setRequestProperty("Authorization", "Bearer " + token);
            conn.setDoOutput(true);

            try (OutputStream os = conn.getOutputStream(); PrintWriter writer = new PrintWriter(new OutputStreamWriter(os, StandardCharsets.UTF_8))) {

                // Add ID field
                writer.append("--" + boundary).append("\r\n");
                writer.append("Content-Disposition: form-data; name=\"id\"").append("\r\n");
                writer.append("\r\n");
                writer.append(id).append("\r\n");

                // Add file field
                writer.append("--" + boundary).append("\r\n");
                writer.append("Content-Disposition: form-data; name=\"file\"; filename=\"photo.jpg\"").append("\r\n");
                writer.append("Content-Type: image/jpeg").append("\r\n");
                writer.append("\r\n");
                writer.flush();

                // Write image data
                byte[] imageBytes = Base64.getDecoder().decode(base64Image);
                os.write(imageBytes);

                writer.append("\r\n");
                writer.append("--" + boundary + "--").append("\r\n");
                writer.flush();
            }

            return processApiResponse(conn);
        }

        private Map<String, Object> processApiResponse(HttpURLConnection conn) throws Exception {
            Map<String, Object> result = new HashMap<>();

            int responseCode = conn.getResponseCode();
            String responseBody;

            if (responseCode >= 200 && responseCode < 300) {
                responseBody = readResponse(conn.getInputStream());
            } else {
                responseBody = readResponse(conn.getErrorStream());
            }

            try {
                JSONParser parser = new JSONParser();
                JSONObject jsonResponse = (JSONObject) parser.parse(responseBody);

                Boolean status = (Boolean) jsonResponse.get("status");
                String message = (String) jsonResponse.get("message");
                Long code = (Long) jsonResponse.get("code");

                if (status != null && status) {
                    result.put("status", StatusCode.OK.getValue());
                    result.put("message", "Pengenalan wajah berhasil");
                } else {
                    if (message != null && message.toLowerCase().contains("telah terdaftar hari ini")) {
                        result.put("status", StatusCode.ALREADY_REGISTERED.getValue());
                        result.put("message", "Peserta telah terdaftar hari ini");
                    } else if (code != null && code == 0) {
                        result.put("status", StatusCode.UNREGISTERED.getValue());
                        result.put("message", "Fitur registerBiometrics belum diimplementasikan");
                    } else {
                        result.put("status", StatusCode.INTEGRATION_ERROR.getValue());
                        result.put("message", message != null ? message : "Integration error");
                    }
                }
            } catch (ParseException e) {
                result.put("status", StatusCode.INTERNAL_SERVER_ERROR.getValue());
                result.put("message", "Invalid response from server");
            }

            return result;
        }

        private String readResponse(InputStream inputStream) throws IOException {
            if (inputStream == null) {
                return "";
            }

            try (BufferedReader br = new BufferedReader(
                    new InputStreamReader(inputStream, StandardCharsets.UTF_8))) {

                StringBuilder response = new StringBuilder();
                String responseLine;
                while ((responseLine = br.readLine()) != null) {
                    response.append(responseLine.trim());
                }
                return response.toString();
            }
        }

        /**
         * Mock encoding generation for demo purposes In production, use actual
         * face recognition libraries like OpenCV, dlib, etc.
         */
        private double[] generateMockEncoding(String base64Image) {
            double[] encoding = new double[128];
            java.util.Random random = new java.util.Random(base64Image.hashCode());
            for (int i = 0; i < 128; i++) {
                encoding[i] = random.nextGaussian();
            }
            return encoding;
        }
    }

    private static class CameraService {

        private Webcam webcam;
        private volatile boolean cameraActive = false;

        public void startCamera() throws Exception {
            try {
                // Platform detection and driver setup
                setupPlatformDriver();

                java.util.List<Webcam> webcams = Webcam.getWebcams();
                if (webcams.isEmpty()) {
                    throw new Exception("No webcams detected");
                }

                // Print available webcams to log
                System.out.println("Available webcams:");
                for (Webcam w : webcams) {
                    System.out.println("- " + w.getName());
                }
                // Get webcam with timeout protection
                webcam = getWebcamSafely();
                if (webcam == null) {
                    throw new Exception("No webcam detected on this system");
                }

                // Try to open camera with different resolutions
                openCameraWithResolution();

                cameraActive = true;
                System.out.println("Camera initialized successfully: " + webcam.getName());

            } catch (Exception e) {
                cleanup();
                throw new Exception("Camera initialization failed: " + e.getMessage(), e);
            }
        }

        private void setupPlatformDriver() {
            String osName = System.getProperty("os.name").toLowerCase();

            try {
                if (osName.contains("linux")) {
                    // Try to load V4L4J driver for Linux
                    try {
                        Class<?> v4l4jDriverClass = Class.forName("com.github.sarxos.webcam.ds.v4l4j.V4l4jDriver");
                        WebcamDriver driver = (WebcamDriver) v4l4jDriverClass.getDeclaredConstructor().newInstance();
                        Webcam.setDriver(driver);
                        System.out.println("Using V4L4J driver for Linux");
                    } catch (ClassNotFoundException e) {
                        System.out.println("V4L4J driver not available, using default driver");
                    }
                } else if (osName.contains("win")) {
                    // Try to load DirectShow driver for Windows
                    try {
                        Class<?> dshowDriverClass = Class.forName("com.github.sarxos.webcam.ds.directshow.WebcamDirectShowDriver");
                        WebcamDriver driver = (WebcamDriver) dshowDriverClass.getDeclaredConstructor().newInstance();
                        Webcam.setDriver(driver);
                        System.out.println("Using DirectShow driver for Windows");
                    } catch (ClassNotFoundException e) {
                        System.out.println("DirectShow driver not available, using default driver");
                    }
                }

                // Configure webcam discovery
                Webcam.setAutoOpenMode(true);

            } catch (Exception e) {
                System.out.println("Using default webcam driver: " + e.getMessage());
                // Default driver will be used - this is usually fine
            }
        }

        private Webcam getWebcamSafely() throws Exception {
            try {
                // Use a simple approach - get default webcam
                Webcam defaultWebcam = Webcam.getDefault();
                if (defaultWebcam != null) {
                    System.out.println("Found default webcam: " + defaultWebcam.getName());
                    return defaultWebcam;
                }

                // If no default, try to get from list
                java.util.List<Webcam> webcams = Webcam.getWebcams();
                if (!webcams.isEmpty()) {
                    System.out.println("Found " + webcams.size() + " webcam(s)");
                    for (Webcam cam : webcams) {
                        System.out.println("- " + cam.getName());
                    }
                    return webcams.get(0);
                }

                return null;

            } catch (Exception e) {
                throw new Exception("Failed to detect webcam: " + e.getMessage());
            }
        }

        private void openCameraWithResolution() throws Exception {
            // Try different resolutions in order of preference
            Dimension[] resolutions = {
                new Dimension(640, 480), // VGA - most compatible
                new Dimension(320, 240), // QVGA - fallback
                new Dimension(800, 600), // SVGA
                WebcamResolution.VGA.getSize(),
                WebcamResolution.QVGA.getSize()
            };

            Exception lastException = null;

            for (Dimension resolution : resolutions) {
                try {
                    System.out.println("Trying resolution: " + resolution.width + "x" + resolution.height);

                    // Set the resolution
                    webcam.setViewSize(resolution);

                    // Try to open the webcam
                    webcam.open();

                    // Check if it actually opened
                    if (webcam.isOpen()) {
                        // Try to get a test image
                        BufferedImage testImage = webcam.getImage();
                        if (testImage != null) {
                            System.out.println("Successfully opened camera with resolution: "
                                    + resolution.width + "x" + resolution.height);
                            return; // Success!
                        }
                    }

                } catch (Exception e) {
                    lastException = e;
                    System.err.println("Failed with resolution " + resolution.width + "x" + resolution.height
                            + ": " + e.getMessage());

                    // Make sure to close if it was partially opened
                    if (webcam != null && webcam.isOpen()) {
                        try {
                            webcam.close();
                        } catch (Exception closeEx) {
                            // Ignore close errors
                        }
                    }
                }
            }

            throw new Exception("Could not open camera with any resolution. Last error: "
                    + (lastException != null ? lastException.getMessage() : "Unknown"));
        }

        public BufferedImage getCurrentFrame() {
            if (!cameraActive || webcam == null || !webcam.isOpen()) {
                return null;
            }

            try {
                return webcam.getImage();
            } catch (Exception e) {
                System.err.println("Error getting camera frame: " + e.getMessage());
                return null;
            }
        }

        public BufferedImage captureFrame() throws Exception {
            if (!cameraActive || webcam == null || !webcam.isOpen()) {
                throw new Exception("Camera not active");
            }

            try {
                BufferedImage image = webcam.getImage();
                if (image == null) {
                    throw new Exception("Failed to capture image from camera");
                }
                return image;
            } catch (Exception e) {
                throw new Exception("Camera capture failed: " + e.getMessage());
            }
        }

        public void stopCamera() {
            cameraActive = false;
            cleanup();
        }

        private void cleanup() {
            if (webcam != null && webcam.isOpen()) {
                try {
                    webcam.close();
                } catch (Exception e) {
                    System.err.println("Error closing webcam: " + e.getMessage());
                }
            }
        }

        public boolean isActive() {
            return cameraActive && webcam != null && webcam.isOpen();
        }
    }

//    private static class CameraService {
//
//        private Webcam webcam;
//        private WebcamPanel webcamPanel;
//
//        public void startCamera() throws Exception {
//            try {
//                // Platform detection
//                String os = System.getProperty("os.name").toLowerCase();
//                boolean isWindows = os.contains("win");
//                boolean isLinux = os.contains("linux");
//
//                // Platform-specific driver setup
//                if (isLinux) {
//                    Webcam.setDriver(new V4l4jDriver());
//                } else if (isWindows) {
//                    Webcam.setDriver(new DShowDriver());
//                }
//
//                webcam = Webcam.getDefault();
//                if (webcam == null) {
//                    throw new Exception("No webcam detected");
//                }
//
//                // Resolution attempts with timeout
//                Dimension[] resolutions = {
//                    new Dimension(640, 480), // VGA
//                    new Dimension(800, 600),
//                    new Dimension(320, 240),
//                    WebcamResolution.HD.getSize()
//                };
//
//                for (Dimension res : resolutions) {
//                    try {
//                        webcam.setViewSize(res);
//                        webcam.open(true);  // true = non-daemon mode
//
//                        // Verify camera is actually working
//                        if (webcam.getImage() != null) {
//                            return;  // Success
//                        }
//                    } catch (Exception e) {
//                        System.err.println("Failed with resolution " + res.width + "x" + res.height + ": " + e.getMessage());
//                        if (webcam.isOpen()) {
//                            webcam.close();
//                        }
//                    }
//                }
//
//                throw new Exception("All resolution attempts failed");
//
//            } catch (Exception e) {
//                throw new Exception(getPlatformSpecificErrorMessage() + "\nError: " + e.getMessage(), e);
//            }
//
//        }
//
//        public BufferedImage getCurrentFrame() {
//            if (webcam == null || !webcam.isOpen()) {
//                return null;
//            }
//            return webcam.getImage();
//        }
//
//        private String getPlatformSpecificErrorMessage() {
//            String os = System.getProperty("os.name");
//            String arch = System.getProperty("os.arch");
//
//            StringBuilder msg = new StringBuilder("Camera initialization failed on ")
//                    .append(os).append(" (").append(arch).append(")\n\n")
//                    .append("TROUBLESHOOTING STEPS:\n");
//
//            if (os.toLowerCase().contains("win")) {
//                msg.append("1. Check Windows Camera Privacy Settings:\n")
//                        .append("   - Settings > Privacy > Camera > Allow desktop apps\n")
//                        .append("2. Update webcam drivers:\n")
//                        .append("   - Device Manager > Cameras > Update driver\n")
//                        .append("3. Close other camera applications (Zoom, Teams, etc.)\n");
//            } else {
//                msg.append("1. Install v4l-utils:\n")
//                        .append("   sudo apt-get install v4l-utils\n")
//                        .append("2. Check permissions:\n")
//                        .append("   ls -l /dev/video*\n")
//                        .append("3. Verify camera detection:\n")
//                        .append("   v4l2-ctl --list-devices\n");
//            }
//
//            return msg.toString();
//        }
//
//        public BufferedImage captureFrame() throws Exception {
//            if (webcam == null || !webcam.isOpen()) {
//                throw new Exception("Camera not active");
//            }
//            return webcam.getImage();
//        }
//
//        public void stopCamera() {
//            if (webcam != null && webcam.isOpen()) {
//                webcam.close();
//            }
//        }
//
//        public boolean isActive() {
//            return webcam != null && webcam.isOpen();
//        }
//    }
    /**
     * Application entry point
     */
    public static void main(String[] args) {
        // Set system properties for better UI experience
        System.setProperty("java.awt.headless", "false");
        System.setProperty("awt.useSystemAAFontSettings", "on");
        System.setProperty("swing.aatext", "true");

        // Ensure GUI runs on Event Dispatch Thread
        SwingUtilities.invokeLater(() -> {
            try {
                // Create and show application
                BPJSFacialRecognitionAppBackup app = new BPJSFacialRecognitionAppBackup();
                app.setVisible(true);

                // Add shutdown hook for cleanup
                Runtime.getRuntime().addShutdownHook(new Thread(() -> {
                    if (app.cameraService != null && app.cameraService.isActive()) {
                        app.cameraService.stopCamera();
                    }
                }));

            } catch (Exception e) {
                e.printStackTrace();
                JOptionPane.showMessageDialog(null,
                        "Failed to start BPJS Facial Recognition System:\n" + e.getMessage(),
                        "Startup Error",
                        JOptionPane.ERROR_MESSAGE);
                System.exit(1);
            }
        });
    }
}
