document.addEventListener('DOMContentLoaded', function() {
    const importForm = document.getElementById('importForm');
    const resultsDiv = document.getElementById('importResults');

    importForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const fileInput = document.getElementById('fileInput');
        if (!fileInput.files.length) {
            showResult('Veuillez sélectionner un fichier', 'error');
            return;
        }

        const formData = new FormData();
        formData.append('file', fileInput.files[0]);

        showResult('Import en cours...', 'loading');

        fetch('../../Traitement/agents/consommation_annuelle_controller.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let html = `<div class="success">${data.imported} importations réussies</div>`;
                if (data.errors.length) {
                    html += `<div class="errors"><h4>Erreurs (${data.errors.length})</h4><ul>`;
                    data.errors.forEach(err => {
                        html += `<li>${err}</li>`;
                    });
                    html += `</ul></div>`;
                }
                showResult(html, 'success');
            } else {
                showResult(data.message || 'Erreur lors de l\'import', 'error');
            }
        })
        .catch(error => {
            showResult('Erreur de connexion', 'error');
            console.error('Error:', error);
        });
    });

    function showResult(message, type) {
        resultsDiv.innerHTML = `<div class="alert ${type}">${message}</div>`;
    }
});