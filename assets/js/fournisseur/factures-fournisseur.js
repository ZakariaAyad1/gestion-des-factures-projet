$(document).ready(function () {
    // Mise à jour de l'état de la facture
    $(".etat-select").change(function () {
        var factureId = $(this).data("id");
        var newEtat = $(this).val();
        var row = $(this).closest("tr");

        $.ajax({
            type: "POST",
            url: "../../Traitement/fournisseurs/facture.php",
            data: { facture_id: factureId, etat: newEtat },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    row.find(".etat").text(newEtat);  // Mise à jour de l'état dans la table
                    alert("État de la facture mis à jour !");
                } else {
                    alert("Erreur lors de la mise à jour !");
                }
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX:", error);
                alert("Une erreur est survenue lors de la requête AJAX.");
            }
        });
    });

    // Génération du PDF de la facture
    $(".generate-pdf").click(function () {
        var factureId = $(this).data("id");
        $.ajax({
            type: "POST",
            url: "../../Traitement/fournisseurs/generate_invoice.php",
            data: { facture_id: factureId },
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response.url) {
                    window.open(response.url, '_blank');  // Ouvre le PDF généré dans un nouvel onglet
                } else {
                    alert("Erreur: Impossible de générer la facture.");
                }
            },
            error: function (xhr, status, error) {
                console.error("Erreur AJAX:", error);
                
            }
        });
    });
});
