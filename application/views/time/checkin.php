<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Take a Photo</title>

  <style>
    :root {
      --bg: #0b1020;
      --card: rgba(20, 27, 48, 0.92);
      --text: #f8fafc;
      --muted: #aab4c8;
      --primary: #5b8cff;
      --primary-dark: #3d70df;
      --danger: #ef5350;
      --success: #20c997;
      --border: rgba(255, 255, 255, 0.10);
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      min-height: 100vh;
      padding: 24px 16px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: Inter, Arial, sans-serif;
      color: var(--text);
      background:
        radial-gradient(circle at top left, rgba(91, 140, 255, .18), transparent 35%),
        radial-gradient(circle at bottom right, rgba(32, 201, 151, .12), transparent 35%),
        var(--bg);
    }

    .camera-card {
      width: min(100%, 760px);
      padding: 28px;
      border: 1px solid var(--border);
      border-radius: 28px;
      background: var(--card);
      box-shadow: 0 25px 70px rgba(0, 0, 0, .35);
      backdrop-filter: blur(18px);
    }

    .header {
      text-align: center;
      margin-bottom: 24px;
    }

    .camera-icon {
      width: 58px;
      height: 58px;
      margin: 0 auto 14px;
      display: grid;
      place-items: center;
      border-radius: 18px;
      font-size: 28px;
      background: rgba(91, 140, 255, .14);
      border: 1px solid rgba(91, 140, 255, .25);
    }

    h1 {
      margin: 0;
      font-size: clamp(28px, 6vw, 44px);
      letter-spacing: -.03em;
    }

    .subtitle {
      margin: 8px auto 0;
      max-width: 520px;
      color: var(--muted);
      font-size: 15px;
      line-height: 1.6;
    }

    .preview-wrap {
      position: relative;
      width: 100%;
      aspect-ratio: 4 / 3;
      overflow: hidden;
      border-radius: 22px;
      background: #05070d;
      border: 1px solid var(--border);
      box-shadow: inset 0 0 0 1px rgba(255,255,255,.03);
    }

    video {
      width: 100%;
      height: 100%;
      display: block;
      object-fit: cover;
      transform: scaleX(-1);
    }

    /* Camera corner guides */
    .camera-frame {
      position: absolute;
      inset: 20px;
      pointer-events: none;
      border: 2px solid rgba(255,255,255,.35);
      border-radius: 18px;
      opacity: .8;
    }

    .camera-frame::before,
    .camera-frame::after {
      content: "";
      position: absolute;
      inset: -2px;
      border-radius: 18px;
      border-top: 3px solid rgba(255,255,255,.85);
      border-bottom: 3px solid rgba(255,255,255,.85);
      clip-path: polygon(
        0 0, 20% 0, 20% 5px, 0 5px,
        0 95%, 20% 95%, 20% 100%, 0 100%,
        100% 100%, 80% 100%, 80% 95%, 100% 95%,
        100% 5%, 80% 5%, 80% 0, 100% 0
      );
    }

    .camera-label {
      position: absolute;
      left: 18px;
      top: 18px;
      padding: 7px 11px;
      border-radius: 999px;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: #fff;
      background: rgba(0,0,0,.45);
      backdrop-filter: blur(8px);
    }

    .countdown {
      position: absolute;
      inset: 0;
      display: none;
      place-items: center;
      color: #fff;
      font-size: clamp(90px, 20vw, 180px);
      font-weight: 800;
      text-shadow: 0 5px 30px rgba(0,0,0,.8);
      background: rgba(0,0,0,.20);
      backdrop-filter: blur(2px);
    }

    .countdown.show {
      display: grid;
      animation: countdownFade .2s ease;
    }

    @keyframes countdownFade {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    .button {
      width: 100%;
      margin-top: 18px;
      padding: 17px 20px;
      border: 0;
      border-radius: 14px;
      color: #fff;
      background: linear-gradient(135deg, var(--primary), var(--primary-dark));
      font-size: 17px;
      font-weight: 750;
      cursor: pointer;
      box-shadow: 0 10px 25px rgba(61, 112, 223, .25);
      transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
    }

    .button:hover {
      transform: translateY(-1px);
      box-shadow: 0 14px 30px rgba(61, 112, 223, .35);
    }

    .button:active {
      transform: translateY(1px);
    }

    .button:disabled {
      opacity: .5;
      cursor: not-allowed;
      transform: none;
    }

    .status {
      min-height: 22px;
      margin-top: 12px;
      text-align: center;
      color: var(--muted);
      font-size: 13px;
    }

    .status.ready {
      color: var(--success);
    }

    .status.error {
      color: #ff7774;
    }

    .captured {
      display: none;
      margin-top: 30px;
      padding-top: 26px;
      border-top: 1px solid var(--border);
    }

    .captured.show {
      display: block;
      animation: slideUp .35s ease;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(12px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .captured h2 {
      margin: 0 0 16px;
      text-align: center;
      font-size: 21px;
    }

    .captured-image-wrap {
      width: min(100%, 420px);
      margin: 0 auto;
      overflow: hidden;
      border-radius: 18px;
      border: 1px solid var(--border);
      background: #05070d;
      box-shadow: 0 15px 35px rgba(0,0,0,.25);
    }

    .captured img {
      display: block;
      width: 100%;
      height: auto;
    }

    .actions {
      display: flex;
      gap: 12px;
      width: min(100%, 520px);
      margin: 16px auto 0;
    }

    .actions button {
      flex: 1;
      padding: 14px 16px;
      border: 0;
      border-radius: 12px;
      color: #fff;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      transition: transform .2s ease, opacity .2s ease;
    }

    .actions button:hover {
      transform: translateY(-1px);
    }

    .retake {
      background: var(--danger);
    }

    .download {
      background: var(--success);
    }

    canvas {
      display: none;
    }

    @media (max-width: 560px) {
      body {
        padding: 12px;
        align-items: flex-start;
      }

      .camera-card {
        margin-top: 8px;
        padding: 20px 16px;
        border-radius: 22px;
      }

      .preview-wrap {
        border-radius: 18px;
      }

      .camera-frame {
        inset: 12px;
        border-radius: 14px;
      }

      .actions {
        flex-direction: column;
      }
    }
  </style>
</head>

<body>

  <main class="camera-card">

    <header class="header">
      <div class="camera-icon">📷</div>
      <h1>Take a Photo</h1>
      <p class="subtitle">
        Position yourself inside the frame. Your photo will be captured automatically after 5 seconds.
      </p>
    </header>

    <div class="preview-wrap">
      <video id="cameraVideo" autoplay playsinline muted></video>

      <div class="camera-frame"></div>
      <div class="camera-label">● Camera Ready</div>

      <div id="countdown" class="countdown"></div>
    </div>

    <button id="captureButton" class="button" type="button">
      📸 Take Photo — 5 Seconds
    </button>

    <div id="status" class="status">
      Starting camera...
    </div>

    <canvas id="photoCanvas"></canvas>

    <section id="capturedSection" class="captured">
      <h2>Your Photo</h2>

      <div class="captured-image-wrap">
        <img id="capturedImage" alt="Captured photo">
      </div>

      <div class="actions">
        <button id="retakeButton" class="retake" type="button">
          ↻ Retake
        </button>

        <button id="downloadButton" class="download" type="button">
          ↓ Download
        </button>
      </div>
    </section>

  </main>

  <script>
    const video = document.getElementById("cameraVideo");
    const canvas = document.getElementById("photoCanvas");
    const countdown = document.getElementById("countdown");
    const captureButton = document.getElementById("captureButton");
    const retakeButton = document.getElementById("retakeButton");
    const downloadButton = document.getElementById("downloadButton");
    const capturedSection = document.getElementById("capturedSection");
    const capturedImage = document.getElementById("capturedImage");
    const status = document.getElementById("status");

    let cameraStream = null;
    let capturedDataUrl = null;
    let countdownRunning = false;

    async function startCamera() {
      try {
        status.classList.remove("error");
        status.textContent = "Requesting camera access...";

        cameraStream = await navigator.mediaDevices.getUserMedia({
          video: {
            facingMode: { ideal: "user" },
            width: { ideal: 1280 },
            height: { ideal: 960 }
          },
          audio: false
        });

        video.srcObject = cameraStream;

        status.classList.add("ready");
        status.textContent = "Camera ready. You're good to go.";
      } catch (error) {
        console.error(error);

        status.classList.remove("ready");
        status.classList.add("error");

        if (error.name === "NotAllowedError") {
          status.textContent =
            "Camera permission was denied. Please allow camera access and reload.";
        } else if (error.name === "NotFoundError") {
          status.textContent =
            "No camera was found on this device.";
        } else {
          status.textContent =
            "Unable to access the camera. Use HTTPS or localhost and try again.";
        }

        captureButton.disabled = true;
      }
    }

    function wait(ms) {
      return new Promise(resolve => setTimeout(resolve, ms));
    }

    async function startCountdown() {
      if (countdownRunning) return;

      countdownRunning = true;
      captureButton.disabled = true;
      countdown.classList.add("show");

      for (let i = 5; i >= 1; i--) {
        countdown.textContent = i;
        await wait(1000);
      }

      countdown.textContent = "📸";
      await wait(300);

      capturePhoto();

      countdown.classList.remove("show");
      countdownRunning = false;
      captureButton.disabled = false;
    }

    function capturePhoto() {
      const width = video.videoWidth;
      const height = video.videoHeight;

      if (!width || !height) {
        status.classList.add("error");
        status.textContent = "Camera is not ready yet. Please try again.";
        return;
      }

      canvas.width = width;
      canvas.height = height;

      const context = canvas.getContext("2d");

      // Mirror the photo so it matches the front-camera preview.
      context.translate(width, 0);
      context.scale(-1, 1);
      context.drawImage(video, 0, 0, width, height);

      capturedDataUrl = canvas.toDataURL("image/jpeg", 0.92);

      capturedImage.src = capturedDataUrl;
      capturedSection.classList.add("show");

      status.classList.remove("error");
      status.classList.add("ready");
      status.textContent = "Photo captured successfully.";
    }

    function retakePhoto() {
      capturedSection.classList.remove("show");
      capturedImage.removeAttribute("src");
      capturedDataUrl = null;

      status.classList.remove("error");
      status.classList.add("ready");
      status.textContent = "Camera ready. Take another photo.";
    }

    function downloadPhoto() {
      if (!capturedDataUrl) return;

      const link = document.createElement("a");

      link.href = capturedDataUrl;
      link.download = `photo-${Date.now()}.jpg`;

      document.body.appendChild(link);
      link.click();
      link.remove();
    }

    captureButton.addEventListener("click", startCountdown);
    retakeButton.addEventListener("click", retakePhoto);
    downloadButton.addEventListener("click", downloadPhoto);

    window.addEventListener("beforeunload", () => {
      if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
      }
    });

    if (
      !navigator.mediaDevices ||
      !navigator.mediaDevices.getUserMedia
    ) {
      status.classList.add("error");
      status.textContent =
        "Your browser does not support camera access.";

      captureButton.disabled = true;
    } else {
      startCamera();
    }
  </script>

</body>
</html>
