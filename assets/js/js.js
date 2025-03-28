// Données fictives pour les consommations mensuelles (à remplacer par les vraies données de la base)
const monthlyConsumptions = [120, 150, 130, 100, 160, 180, 190, 170, 180, 200, 210, 220];

const ctx = document.getElementById('consumptionChart').getContext('2d');
const consumptionChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Consommation Mensuelle (kWh)',
            data: monthlyConsumptions,
            fill: false,
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                grid: {
                    display: false
                }
            }
        }
    }
});
 

/*client fournisseur admin*/
document.addEventListener("DOMContentLoaded", function () {
    const addClientBtn = document.getElementById("addClientBtn");
    const modal = document.getElementById("clientFormModal");
    const closeModal = document.querySelector(".close");
    const form = document.getElementById("clientForm");

    // Ouvrir la fenêtre modale
    addClientBtn.addEventListener("click", function () {
        modal.style.display = "flex";
    });

    // Fermer la fenêtre modale
    closeModal.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Fermer si on clique en dehors du formulaire
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // Simulation de soumission du formulaire (AJAX ou Backend)
    form.addEventListener("submit", function (event) {
        event.preventDefault();
        alert("✅ Client ajouté avec succès !");
        modal.style.display = "none";
    });
});

//add client par fournissseur 
// Récupération des éléments
const modal = document.getElementById("addClientModal");
const btn = document.getElementById("addClientBtn");
const closeBtn = document.querySelector(".close-btn");

// Lorsque le bouton "Ajouter un client" est cliqué, on ouvre la modale
btn.onclick = function() {
    modal.style.display = "flex";
}

// Lorsque la croix (close-btn) est cliquée, on ferme la modale
closeBtn.onclick = function() {
    modal.style.display = "none";
}

// Lorsque l'utilisateur clique en dehors de la modale, on ferme aussi la modale
window.onclick = function(event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
}  