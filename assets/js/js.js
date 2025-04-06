// === CHART.JS (Graphique de consommation) === 
if (document.getElementById('consumptionChart')) {
    const ctx = document.getElementById('consumptionChart').getContext('2d');

    const consumptionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months || [], // Assure-toi que 'months' est défini avant
            datasets: [{
                label: 'Consommation Mensuelle (kWh)',
                data: monthlyConsumptions || [], // Assure-toi que 'monthlyConsumptions' est défini avant
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { grid: { display: false } },
                y: { grid: { display: false } }
            }
        }
    });
}
// === GESTION CLIENTS : Suppression ===
document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll('.deleteBtn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const clientId = this.dataset.id;
            console.log("Client ID à supprimer :", clientId);  // Vérification de l'ID

            if (confirm("Êtes-vous sûr de vouloir supprimer ce client ?")) {
                fetch('../../Traitement/Fournisseurs/gestion_clients_controller.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id=' + encodeURIComponent(clientId)
                })
                .then(response => response.text())
                .then(result => {
                    console.log(result); // Vérification de la réponse du serveur
                    if (result.trim() === 'success') {
                        // Supprimer visuellement la ligne du tableau
                        const row = this.closest('tr');
                        row.remove();
                    } else {
                        alert("Erreur lors de la suppression. Réponse : " + result); // Afficher la réponse du serveur
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
            }
        });
    });
});




// === GESTION CLIENTS : Redirection vers page de modification (si pas modale) ===
document.querySelectorAll('.editBtn').forEach(btn => {
    btn.addEventListener('click', function () {
        const row = this.closest('tr');
        const clientId = row.dataset.id;

        // Rediriger vers la page de modification
        window.location.href = `modifier-client.php?id=${clientId}`;
    });
});

// === MODALE AJOUT CLIENT ===
// === FORMULAIRE D'AJOUT CLIENT ===
document.addEventListener("DOMContentLoaded", function () {
    const addForm = document.getElementById("addClientForm");

    if (addForm) {
        addForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const nom = document.getElementById("nom").value;
            const prenom = document.getElementById("prenom").value;
            const email = document.getElementById("email").value;
            const adresse = document.getElementById("adresse").value;
            const mot_de_passe = document.getElementById("mot_de_passe").value;

            fetch("../../Traitement/Fournisseurs/fournisseur_controller.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `nom=${encodeURIComponent(nom)}&prenom=${encodeURIComponent(prenom)}&email=${encodeURIComponent(email)}&adresse=${encodeURIComponent(adresse)}&mot_de_passe=${encodeURIComponent(mot_de_passe)}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    alert("✅ Client ajouté avec succès !");
                    // Redirection vers la page de gestion des clients
                    window.location.href = "gestion-client.php";
                } else {
                    alert("❌ Erreur lors de l'ajout : " + (data.message || "Erreur inconnue"));
                }
            })
            .catch(err => {
                alert("❌ Une erreur réseau est survenue.");
                console.error(err);
            });
        });
    }
});



// === MODALE MODIFICATION CLIENT ===
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("editClientModal");
    const closeModal = document.querySelector(".close");
    const form = document.getElementById("editClientForm");

    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const row = this.closest('tr');
            const clientId = row.dataset.id;
            const nomPrenom = row.cells[0].innerText.split(' ');
            const adresse = row.cells[1].innerText;

            document.getElementById("client_id").value = clientId;
            document.getElementById("nom").value = nomPrenom[0];
            document.getElementById("prenom").value = nomPrenom[1];
            document.getElementById("adresse").value = adresse;

            modal.style.display = "flex";
        });
    });

    if (closeModal && modal) {
        closeModal.addEventListener("click", function () {
            modal.style.display = "none";
        });
    }

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    if (form) {
        form.addEventListener("submit", function (event) {
            event.preventDefault();

            const clientId = document.getElementById("client_id").value;
            const nom = document.getElementById("nom").value;
            const prenom = document.getElementById("prenom").value;
            const adresse = document.getElementById("adresse").value;

            fetch('../../Traitement/Fournisseurs/gestion_Clients_Controller.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=modifier&client_id=${clientId}&nom=${nom}&prenom=${prenom}&adresse=${adresse}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const row = document.querySelector(`tr[data-id='${clientId}']`);
                    row.cells[0].innerText = `${nom} ${prenom}`;
                    row.cells[1].innerText = adresse;

                    modal.style.display = "none";
                    alert("✅ Client modifié avec succès !");
                } else {
                    alert('Erreur lors de la modification');
                }
            })
            .catch(err => alert('Erreur de connexion'));
        });
    }
});

// === FORMULAIRE DE MODIFICATION VIA PAGE DÉDIÉE (modifier-client.php) ===
document.addEventListener("DOMContentLoaded", function () {
    const formModifier = document.getElementById("form-modifier-client");
    const message = document.getElementById("message-succes");

    if (formModifier) {
        formModifier.addEventListener("submit", function (e) {
            e.preventDefault(); // Empêche le rechargement de la page

            const formData = new FormData(formModifier);

            fetch("../../Traitement/Clients/Modifier-Client-Action.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    message.style.display = "block";
                    message.style.color = "green";
                    message.innerText = "✅ Modifications enregistrées avec succès !";

                    // Optionnel : cacher le message après 3s
                    setTimeout(() => {
                        message.style.display = "none";
                    }, 3000);
                } else {
                    message.style.display = "block";
                    message.style.color = "red";
                    message.innerText = "❌ Une erreur est survenue.";
                }
            })
            .catch(err => {
                console.error(err);
                message.style.display = "block";
                message.style.color = "red";
                message.innerText = "❌ Une erreur réseau est survenue.";
            });
        });
    }
});