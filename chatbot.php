<!-- Chat Widget -->
<link rel="stylesheet" href="assets/css/chatbot.css" />
<script defer src="assets/js/chatbot.js"></script>

<button class="chat-fab" id="chatFab" aria-label="Open chat">
  <!--chat icon -->
  <svg width="22" height="22" viewBox="0 0 24 24" aria-hidden="true">
    <path fill="currentColor"
      d="M12 3c-5 0-9 3.58-9 8c0 2.1 1.02 4.05 2.74 5.52L5 21l4.03-1.83C10 19.72 11 20 12 20c5 0 9-3.58 9-8s-4-9-9-9Zm-5 9h10v2H7v-2Zm0-4h10v2H7V8Z" />
  </svg>
</button>

<section class="chat-panel" id="chatPanel" aria-label="Sabil Help Chat" role="dialog" aria-modal="false">
  <header class="chat-header">
    <div class="chat-title">
      <div class="chat-brand">SABIL</div>
    </div>

    <button class="chat-close" id="chatClose" aria-label="Close chat">
      <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
        <path fill="currentColor"
          d="M18.3 5.71 12 12l6.3 6.29-1.41 1.42L10.59 13.4 4.29 19.71 2.88 18.29 9.17 12 2.88 5.71 4.29 4.29 10.59 10.6l6.3-6.31z" />
      </svg>
    </button>
  </header>

  <div class="chat-body" id="chatBody">
    <!-- Messages get injected here -->
  </div>

  <div class="chat-quick" id="chatQuick">
    <!-- Quick question chips get injected here -->
  </div>

  
</section>