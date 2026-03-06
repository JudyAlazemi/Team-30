(function(){
  const fab = document.getElementById("chatFab");
  const panel = document.getElementById("chatPanel");
  const closeBtn = document.getElementById("chatClose");
  const body = document.getElementById("chatBody");
  const quick = document.getElementById("chatQuick");

  const footer = panel.querySelector(".chat-footer");
  if (footer) footer.style.display = "none";

  //  Q&A
  const QA = [
    {
      q: "Can I cancel or edit my order?",
      a: "If your order hasn’t been processed yet, we may be able to cancel it. Please go to My Orders and contact support with your order number as soon as possible."
    },
    {
      q: "How long does delivery take?",
      a: "UK delivery is usually 2–5 working days (depending on the option selected at checkout). You’ll get tracking once it ships."
    },
    {
      q: "Do you ship internationally?",
      a: "Yes we ship worldwide. Shipping fees and delivery estimates appear at checkout after you enter your address."
    },
    {
      q: "How do I start a return?",
      a: "Go to My Orders → Select order → Start a return. If you can’t see the option, message support with your order number."
    },
    {
      q: "Do you offer perfume recommendations?",
      a: "Pick a vibe below and I’ll recommend a direction."
    }
  ];

  // Recommendations
  const SCENT_CHOICES = ["Sweet & vanilla", "Fresh & clean", "Woody & smoky", "Musky", "Back"];

  const RECS = {
    "Sweet & vanilla": "Sweet & vanilla: warm gourmand scents. Look for notes like vanilla, caramel, tonka, amber.",
    "Fresh & clean": "Fresh & clean: airy, shower-fresh scents. Look for bergamot, citrus, white musk, clean florals.",
    "Woody & smoky": "Woody & smoky: deep and intense. Look for oud, cedarwood, sandalwood, incense, leather.",
    "Musky": "Musky: soft skin scents. Look for white musk, powdery notes, iris, clean woods."
  };

  let mode = "default"; 

  function addMsg(text, who="bot"){
    const div = document.createElement("div");
    div.className = `msg ${who}`;
    div.textContent = text;
    body.appendChild(div);
    body.scrollTop = body.scrollHeight;
  }

  function renderChips(chips){
    quick.innerHTML = "";
    chips.forEach(label => {
      const b = document.createElement("button");
      b.type = "button";
      b.className = "chip";
      b.textContent = label;
      b.addEventListener("click", () => handleChip(label));
      quick.appendChild(b);
    });
  }

  function handleChip(label){
    if (mode === "recommend") {
      if (label === "Back") {
        mode = "default";
        addMsg("Back to quick questions", "bot");
        renderChips(QA.map(x => x.q));
        return;
      }

      addMsg(label, "user");
      addMsg(RECS[label] || "Pick one of the options, or press Back.", "bot");
      renderChips(SCENT_CHOICES);
      return;
    }

    addMsg(label, "user");

    const match = QA.find(x => x.q === label);
    if (!match){
      addMsg("Please choose one of the questions below.", "bot");
      renderChips(QA.map(x => x.q));
      return;
    }

    addMsg(match.a, "bot");

    if (label.toLowerCase().includes("perfume recommendations")) {
      mode = "recommend";
      addMsg("What vibe are you going for?", "bot");
      renderChips(SCENT_CHOICES);
    } else {
      // stay in default mode, keep FAQs visible
      renderChips(QA.map(x => x.q));
    }
  }

  function openChat(){
    panel.classList.add("is-open");
    localStorage.setItem("sabilChatOpen", "1");
  }

  function closeChat(){
    panel.classList.remove("is-open");
    localStorage.setItem("sabilChatOpen", "0");
    fab.focus();
  }

  function toggleChat(){
    panel.classList.contains("is-open") ? closeChat() : openChat();
  }

  fab.addEventListener("click", toggleChat);
  closeBtn.addEventListener("click", closeChat);

  // Click outside to close
  document.addEventListener("mousedown", (e) => {
    if(!panel.classList.contains("is-open")) return;
    if(panel.contains(e.target) || fab.contains(e.target)) return;
    closeChat();
  });

  // Initial content
  addMsg("Hi! I’m Sabil’s quick help bot. Pick a question below :)", "bot");
  renderChips(QA.map(x => x.q));

  // Remember open state
  if(localStorage.getItem("sabilChatOpen") === "1"){
    openChat();
  }
})();