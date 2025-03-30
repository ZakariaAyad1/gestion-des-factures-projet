// assets/js/agent_history.js
document.addEventListener('DOMContentLoaded', function() {
    // Variables pour la pagination
    let currentPage = 1;
    const itemsPerPage = 10;
    let allData = [];

    // Éléments DOM
    const yearFilter = document.getElementById('yearFilter');
    const historyTable = document.getElementById('historyTable').getElementsByTagName('tbody')[0];
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const pageInfo = document.getElementById('pageInfo');

    // Charger les données initiales
    loadData();

    // Écouteurs d'événements
    yearFilter.addEventListener('change', function() {
        currentPage = 1;
        renderTable();
        updatePagination();
    });

    prevBtn.addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            renderTable();
            updatePagination();
        }
    });

    nextBtn.addEventListener('click', function() {
        if (currentPage < Math.ceil(filterData().length / itemsPerPage)) {
            currentPage++;
            renderTable();
            updatePagination();
        }
    });

    // Fonction pour charger les données depuis le serveur
    function loadData() {
        fetch('../../Traitement/agents/consommation_annuelle_controller.php?action=getHistorique')
            .then(response => response.json())
            .then(data => {
                allData = data;
                populateYearFilter();
                renderTable();
                updatePagination();
            })
            .catch(error => {
                console.error('Erreur:', error);
                showError('Erreur lors du chargement des données');
            });
    }

    // Remplir le filtre d'année
    function populateYearFilter() {
        const years = [...new Set(allData.map(item => item.annee))];
        years.sort((a, b) => b - a);
        
        const yearFilter = document.getElementById('yearFilter');
        // Garder seulement l'option "Toutes" et ajouter les années
        yearFilter.innerHTML = '<option value="">Toutes</option>';
        
        years.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearFilter.appendChild(option);
        });
    }

    // Filtrer les données selon les critères
    function filterData() {
        const selectedYear = yearFilter.value;
        
        if (!selectedYear) return allData;
        
        return allData.filter(item => item.annee === selectedYear);
    }

    // Afficher les données dans le tableau
    function renderTable() {
        const filteredData = filterData();
        const startIndex = (currentPage - 1) * itemsPerPage;
        const paginatedData = filteredData.slice(startIndex, startIndex + itemsPerPage);
        
        historyTable.innerHTML = '';
        
        if (paginatedData.length === 0) {
            const row = historyTable.insertRow();
            const cell = row.insertCell();
            cell.colSpan = 4;
            cell.textContent = 'Aucune donnée disponible';
            cell.className = 'no-data';
            return;
        }
        
        paginatedData.forEach(item => {
            const row = historyTable.insertRow();
            
            // Nom du client
            const clientCell = row.insertCell();
            clientCell.textContent = `${item.prenom} ${item.nom}`;
            
            // Année
            const yearCell = row.insertCell();
            yearCell.textContent = item.annee;
            
            // Consommation
            const consoCell = row.insertCell();
            consoCell.textContent = item.consommation;
            
            // Date d'import
            const dateCell = row.insertCell();
            const importDate = new Date(item.date_saisie);
            dateCell.textContent = importDate.toLocaleDateString('fr-FR');
        });
    }

    // Mettre à jour la pagination
    function updatePagination() {
        const filteredData = filterData();
        const totalPages = Math.ceil(filteredData.length / itemsPerPage);
        
        pageInfo.textContent = `Page ${currentPage} sur ${totalPages}`;
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages || totalPages === 0;
    }

    // Afficher une erreur
    function showError(message) {
        const tbody = document.getElementById('historyTable').getElementsByTagName('tbody')[0];
        tbody.innerHTML = `<tr><td colspan="4" class="error">${message}</td></tr>`;
    }
});