// ============================================================
// CALCULATOR BMI – JavaScript
// Lab 3 – Tehnologii Web
// ============================================================

function calculeazaBMI(event) {
    // Prevenim reîncărcarea paginii
    event.preventDefault();

    // Preluăm valorile din formular
    var nume     = document.getElementById("nume").value.trim();
    var greutate = parseFloat(document.getElementById("greutate").value);
    var inaltime = parseFloat(document.getElementById("inaltime").value);
    var varsta   = parseInt(document.getElementById("varsta").value);
    var sex      = document.getElementById("sex").value;

    var rezultatDiv = document.getElementById("rezultat");

    // Validare câmpuri
    if (nume === "" || isNaN(greutate) || isNaN(inaltime) || isNaN(varsta) || sex === "") {
        rezultatDiv.className = "rezultat eroare";
        rezultatDiv.innerHTML = "⚠️ Te rugăm să completezi toate câmpurile corect!";
        rezultatDiv.classList.remove("hidden");
        return;
    }

    if (greutate < 20 || greutate > 300) {
        rezultatDiv.className = "rezultat eroare";
        rezultatDiv.innerHTML = "⚠️ Greutatea trebuie să fie între 20 și 300 kg!";
        rezultatDiv.classList.remove("hidden");
        return;
    }

    if (inaltime < 100 || inaltime > 250) {
        rezultatDiv.className = "rezultat eroare";
        rezultatDiv.innerHTML = "⚠️ Înălțimea trebuie să fie între 100 și 250 cm!";
        rezultatDiv.classList.remove("hidden");
        return;
    }

    // Calculul BMI
    var inaltimeMetri = inaltime / 100;
    var bmi = greutate / (inaltimeMetri * inaltimeMetri);
    var bmiRotunjit = bmi.toFixed(1);

    // Determinarea categoriei
    var categorie = "";
    var emoji     = "";
    var sfat      = "";
    var clasa     = "";

    if (bmi < 18.5) {
        categorie = "Subponderal";
        emoji     = "🔵";
        sfat      = "Încearcă să mărești aportul caloric și să faci exerciții de forță.";
        clasa     = "subponderal";
    } else if (bmi < 25) {
        categorie = "Greutate normală";
        emoji     = "🟢";
        sfat      = "Felicitări! Menține stilul de viață activ și alimentația echilibrată.";
        clasa     = "normal";
    } else if (bmi < 30) {
        categorie = "Supraponderal";
        emoji     = "🟡";
        sfat      = "Recomandăm mai multă activitate fizică și o dietă mai echilibrată.";
        clasa     = "supraponderal";
    } else {
        categorie = "Obezitate";
        emoji     = "🔴";
        sfat      = "Consultă un medic sau nutriționist pentru un plan personalizat.";
        clasa     = "obez";
    }

    // Greutatea ideală (formula Devine)
    var greutateIdeala;
    if (sex === "masculin") {
        greutateIdeala = 50 + 2.3 * ((inaltime - 152.4) / 2.54);
    } else {
        greutateIdeala = 45.5 + 2.3 * ((inaltime - 152.4) / 2.54);
    }
    greutateIdeala = Math.max(greutateIdeala, 40).toFixed(1);

    // Afișarea rezultatului
    rezultatDiv.className = "rezultat " + clasa;
    rezultatDiv.innerHTML =
        "<p>Salut, <strong>" + nume + "</strong>! Iată rezultatele tale:</p>" +
        "<div class='bmi-valoare'>" + emoji + " " + bmiRotunjit + "</div>" +
        "<div class='bmi-categorie'>" + categorie + "</div>" +
        "<p>Vârstă: <strong>" + varsta + " ani</strong> | Sex: <strong>" + sex + "</strong></p>" +
        "<p>Greutate ideală estimată: <strong>~" + greutateIdeala + " kg</strong></p>" +
        "<p class='bmi-sfat'>💡 " + sfat + "</p>";

    rezultatDiv.classList.remove("hidden");

    // Scroll automat la rezultat
    rezultatDiv.scrollIntoView({ behavior: "smooth", block: "center" });
}

// Resetare formular
function reseteazaForm() {
    document.getElementById("nume").value     = "";
    document.getElementById("greutate").value = "";
    document.getElementById("inaltime").value = "";
    document.getElementById("varsta").value   = "";
    document.getElementById("sex").value      = "";

    var rezultatDiv = document.getElementById("rezultat");
    rezultatDiv.className = "rezultat hidden";
    rezultatDiv.innerHTML = "";
}
