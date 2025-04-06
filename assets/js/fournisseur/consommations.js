document.addEventListener("DOMContentLoaded", function () {
    fetch("../../Traitement/fournisseurs/Consommation_fournisseur_controller.php")
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById("consommations-body");
            tbody.innerHTML = "";

            data.forEach(consommation => {
                const row = document.createElement("tr");

                row.innerHTML = `
                    <td>${consommation.nom} ${consommation.prenom}</td>
                    <td>${getMoisNom(consommation.mois)}</td>
                    <td>${consommation.annee}</td>
                    <td>${consommation.consommation} kWh</td>
                    <td>
    <a href="../../Traitement/${consommation.photo_compteur}" target="_blank">
        <img src="../../Traitement/${consommation.photo_compteur}" alt="Photo compteur" width="50" style="cursor: pointer;">
    </a>
</td>

                    <td><button class="valider" data-id="${consommation.consommation_id}" >✅</button></td>
                    <td><button class="modifier" data-id="${consommation.consommation_id}">✏</button></td>
                `;
                row.setAttribute("data-client-id", consommation.client_id);


                tbody.appendChild(row);
            });
        })
        .catch(error => console.error("Erreur lors du chargement des consommations :", error));
});

// Fonction pour obtenir le nom du mois en français
function getMoisNom(numeroMois) {
    const mois = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];
    return mois[numeroMois - 1];
}

document.getElementById("consommations-body").addEventListener("click", function (e) {
    if (e.target && e.target.classList.contains("valider")) {
        const consommationId = e.target.getAttribute("data-id");
        const row = e.target.closest("tr");
        const clientId = row.getAttribute("data-client-id");

        // Envoyer les données via une requête POST en JSON
        fetch("../../Traitement/fournisseurs/facture_fournisseur_controller.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                client_id: clientId,
                consommation_id: consommationId
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Facture insérée avec succès !");
                } else {
                    alert("Erreur lors de l'insertion de la facture : " + data.message);
                }
            })
            .catch(error => console.error("Erreur AJAX :", error));
    }
});










document.addEventListener("DOMContentLoaded", function () {
    const formModification = document.getElementById("form-modification");
    const modificationForm = document.getElementById("modification-form");

    document.getElementById("consommations-body").addEventListener("click", function (event) {
        if (event.target.classList.contains("modifier")) {
            const consommationId = event.target.getAttribute("data-id");
            console.log("ID récupéré :", consommationId); // Vérification

            // Récupérer les informations de la consommation via AJAX
            fetch(`../../Traitement/fournisseurs/get_consommation.php?consommation_id=${consommationId}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Réponse API :", data); // Vérification
                    document.getElementById("consommation_id").value = data.consommation_id;
                    document.getElementById("mois").value = data.mois;
                    document.getElementById("annee").value = data.annee;
                    document.getElementById("consommation").value = data.consommation;
                    const previewImage = document.getElementById("photo_preview");
                    if (data.photo_compteur) {
                        previewImage.src = `../../assets/uploads/Photo_compteur/${data.photo_compteur}`;
                        previewImage.style.display = "block"; // Affiche l'image si elle existe
                    } else {
                        previewImage.style.display = "none"; // Cache l'image s'il n'y en a pas
                    }

                    document.getElementById("form-modification").style.display = "block";
                })
                .catch(error => console.error("Erreur lors de la récupération des données :", error));
        }
    });


    // Envoi du formulaire pour mise à jour
    modificationForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(modificationForm);

        fetch("../../Traitement/fournisseurs/update_consommation.php", {
            method: "POST",
            body: formData
        }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Consommation mise à jour avec succès !");
                    location.reload(); // Recharger la page pour voir les modifications
                } else {
                    alert("Erreur lors de la mise à jour !");
                }
            });

    });

    // Bouton pour fermer le formulaire
    document.getElementById("fermer-form").addEventListener("click", function () {
        formModification.style.display = "none";
    });
});


