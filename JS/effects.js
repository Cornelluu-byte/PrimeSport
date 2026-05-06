// ============================================================
// EFECTE VIZUALE – JavaScript (Sarcina suplimentară)
// Se include în index.html, fotbal.html, fitness.html
// ============================================================

// 1. MESAJ DE BUN VENIT la încărcarea paginii (doar prima dată)
window.onload = function () {
    var titlu = document.querySelector("h1");
    var primeiraVizita = localStorage.getItem("arenaX_vizitat");

    if (titlu && !primeiraVizita) {
        var mesaj = document.createElement("div");
        mesaj.id = "mesaj-bun-venit";
        mesaj.style.cssText =
            "position:fixed; top:0; left:0; width:100%; height:100%;" +
            "background:rgba(0,0,0,0.85); display:flex; flex-direction:column;" +
            "justify-content:center; align-items:center; z-index:9999;" +
            "animation: fadeInBg 0.5s ease;";

        mesaj.innerHTML =
            "<h2 style='color:#00ffcc; font-size:36px; margin-bottom:15px;'>🏆 Bun venit la ARENA X!</h2>" +
            "<p style='color:white; font-size:18px; margin-bottom:25px;'>Locul unde sportul e stil de viață.</p>" +
            "<button onclick='inchideMesaj()' style='padding:12px 30px; background:#00ffcc; color:black;" +
            "border:none; border-radius:25px; font-size:16px; font-weight:bold; cursor:pointer;'>Intră pe site ▶</button>";

        document.body.appendChild(mesaj);
    }

    // 2. Animație de apariție pe toate imaginile
    animatieImagini();

    // 3. Pornește ceasul dacă există elementul
    pornesteCeas();
};

// Închide mesajul de bun venit și salvează în localStorage
function inchideMesaj() {
    var mesaj = document.getElementById("mesaj-bun-venit");
    if (mesaj) {
        mesaj.style.opacity = "0";
        mesaj.style.transition = "opacity 0.4s ease";
        setTimeout(function () { mesaj.remove(); }, 400);
    }
    // Salvează că utilizatorul a vizitat deja site-ul
    localStorage.setItem("arenaX_vizitat", "true");
}

// 2. ANIMATIE IMAGINI – apariție progresivă la scroll
function animatieImagini() {
    var imagini = document.querySelectorAll("img");

    // Stil inițial: invizibil
    imagini.forEach(function (img) {
        img.style.opacity    = "0";
        img.style.transform  = "translateY(30px)";
        img.style.transition = "opacity 0.6s ease, transform 0.6s ease";
    });

    // Funcție de verificare vizibilitate
    function verificaVizibilitate() {
        imagini.forEach(function (img) {
            var rect = img.getBoundingClientRect();
            if (rect.top < window.innerHeight - 50) {
                img.style.opacity   = "1";
                img.style.transform = "translateY(0)";
            }
        });
    }

    // Verifică la scroll și la încărcare
    window.addEventListener("scroll", verificaVizibilitate);
    verificaVizibilitate();
}

// 3. CEAS în timp real (dacă există un element cu id="ceas")
function pornesteCeas() {
    var ceasDiv = document.getElementById("ceas");
    if (!ceasDiv) return;

    function actualizeazaCeas() {
        var acum    = new Date();
        var ore     = String(acum.getHours()).padStart(2, "0");
        var minute  = String(acum.getMinutes()).padStart(2, "0");
        var secunde = String(acum.getSeconds()).padStart(2, "0");
        ceasDiv.textContent = "🕒 " + ore + ":" + minute + ":" + secunde;
    }

    actualizeazaCeas();
    setInterval(actualizeazaCeas, 1000);
}

// 4. EFECT DE HIGHLIGHT pe linkurile din meniu la hover
var linkuriMeniu = document.querySelectorAll("p a");
linkuriMeniu.forEach(function (link) {
    link.addEventListener("mouseenter", function () {
        this.style.letterSpacing = "1px";
    });
    link.addEventListener("mouseleave", function () {
        this.style.letterSpacing = "0";
    });
});