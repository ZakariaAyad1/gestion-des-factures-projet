document.addEventListener('DOMContentLoaded', function() {
    fetch('../../Traitement/agents/consommation_annuelle_controller.php?action=getHistorique')
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const lastYear = Math.max(...data.map(item => item.annee));
                document.getElementById('last-year').textContent = lastYear;
                
                const thisMonth = new Date().getMonth() + 1;
                const monthImports = data.filter(item => {
                    const importDate = new Date(item.date_saisie);
                    return importDate.getMonth() + 1 === thisMonth;
                }).length;
                
                document.getElementById('month-imports').textContent = monthImports;
            }
        })
        .catch(error => console.error('Erreur:', error));
});