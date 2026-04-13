<audio id="notificationSound"
       src="<?= base_url('uploads/audio/nadaorderan_n1nc1372.mp3'); ?>"
       preload="auto">
</audio>

<button id="enableSoundBtn"
        style="position:fixed;bottom:20px;right:20px;z-index:9999;">
  🔔 Aktifkan Suara
</button>

<!-- Socket.IO client (WAJIB) -->
<script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b>Version</b> 1.0.0
    </div>
</footer>
<script>
  const audio = document.getElementById("notificationSound");
  const enableBtn = document.getElementById("enableSoundBtn");

  let soundEnabled = false;
  let audioUnlocked = false;   // 🔑 INI PENTING
  let audioCtx = null;

  let CURRENT_BRANCH_ID = 'Sahira Hotel Paledang';

  console.log("🟡 [ADMIN] Script loaded");
  console.log("🟡 [ADMIN] Branch:", CURRENT_BRANCH_ID);

  // ===============================
  // 🔔 ENABLE AUDIO (VALID USER GESTURE)
  // ===============================
  enableBtn.addEventListener("click", async () => {
    try {
      // 🔓 Create AudioContext ONLY on user gesture
      audioCtx = new (window.AudioContext || window.webkitAudioContext)();

      const source = audioCtx.createMediaElementSource(audio);
      source.connect(audioCtx.destination);

      audio.muted = false;
      audio.currentTime = 0;

      await audio.play();   // ✅ allowed
      audio.pause();        // ⬅️ unlock trick
      audio.currentTime = 0;

      soundEnabled = true;
      audioUnlocked = true;

      enableBtn.style.display = "none";

      console.log("✅ [ADMIN] AUDIO FULLY UNLOCKED");
    } catch (err) {
      console.error("❌ Audio unlock failed:", err);
    }
  });

  // ===============================
  // 🔌 SOCKET.IO CONNECT
  // ===============================
  console.log("🟡 [ADMIN] Connecting Socket.IO...");

  const socket = io("https://api.sahirahotelsgroup.com", {
    auth: {
      role: "admin",
      branch_id: CURRENT_BRANCH_ID
    }
  });

  socket.on("connect", () => {
    console.log("✅ [ADMIN][SOCKET] CONNECTED");
  });

  socket.on("connect_error", err => {
    console.error("❌ [ADMIN][SOCKET] ERROR:", err.message);
  });

  socket.onAny((event, ...args) => {
    console.log("📥 [SOCKET]", event, args);
  });

  // ===============================
  // 📩 ADMIN NOTIFICATION
  // ===============================
  socket.on("admin-notification", (data) => {
    console.log("📩 [ADMIN] admin-notification RECEIVED", data);

    const msgBranch = data?.payload?.branch_id;
    if (msgBranch && msgBranch !== CURRENT_BRANCH_ID) return;

    // 🔔 Browser Notification
    if (Notification.permission !== "granted") {
      Notification.requestPermission();
    }

    if (Notification.permission === "granted") {
      new Notification(data.notification?.title || "Notifikasi", {
        body: data.notification?.body || "",
        icon: "/favicon.png"
      });
    }

    // 🔊 PLAY SOUND (ONLY IF UNLOCKED)
    if (soundEnabled && audioUnlocked) {
      console.log("🔊 [ADMIN] Playing notification sound");

      audio.pause();
      audio.currentTime = 0;
      audio.loop = false;

      audio.play().catch(err => {
        console.warn("⚠️ Audio play blocked:", err);
      });
    } else {
      console.warn("🔇 Audio not unlocked yet");
    }

    // ROUTING
    switch (data.type) {
      case "room-service":
        handleFnbOrder(data.notification, data.payload);
        break;
      case "cancel":
        handlePayment(data.notification, data.payload);
        break;
    }
  });

  // ===============================
  // 🍽 FNB HANDLER
  // ===============================
  function handleFnbOrder(notification, payload) {
    let arrayPayload = [];

    try {
      arrayPayload = JSON.parse(payload.data.value);
      if (arrayPayload.products) arrayPayload = arrayPayload.products;
    } catch {
      return;
    }

    let menuItemsHTML = "";
    arrayPayload.forEach(item => {
      menuItemsHTML += `
        <b>${item.item_name}</b> x ${item.qty}<br>
        <small>Note: ${item.extra_note || "-"}</small><br><br>
      `;
    });

    Swal.fire({
      title: "🍽 New Order",
      html: `${menuItemsHTML}`,
      icon: "info",
      confirmButtonText: "Close",
      willClose: stopAudio
    });
  }

  // ===============================
  // 💳 PAYMENT HANDLER
  // ===============================
  function handlePayment(notification, payload) {
    let paymentData = {};
    try {
      paymentData = JSON.parse(payload.data.value);
    } catch {
      return;
    }

    const amount = paymentData.total_pay || 0;
    const formattedAmount = new Intl.NumberFormat("id-ID", {
      style: "currency",
      currency: "IDR",
      minimumFractionDigits: 0
    }).format(amount);

    Swal.fire({
      title: notification?.title || "Update Pembayaran",
      html: `
        <p>Order ID: <b>${paymentData.orderid}</b></p>
        <p>Status: <b>${paymentData.pay_status}</b></p>
        <p>Jumlah: <b>${formattedAmount}</b></p>
      `,
      icon: "info",
      confirmButtonText: "Tutup",
      willClose: stopAudio
    });
  }

  function stopAudio() {
    if (soundEnabled && audioUnlocked) {
      audio.pause();
      audio.currentTime = 0;
    }
    speechSynthesis.cancel();
  }
</script>
