// Validation du formulaire de réservation
document.addEventListener('DOMContentLoaded', function() {
    const reservationForm = document.querySelector('.reservation-form form');
    
    if(reservationForm) {
        reservationForm.addEventListener('submit', function(e) {
            const depart = document.getElementById('depart').value;
            const arrivee = document.getElementById('arrivee').value;
            const date = document.getElementById('date').value;
            const places = document.getElementById('places').value;
            
            if(depart === arrivee) {
                e.preventDefault();
                alert('La ville de départ et d\'arrivée doivent être différentes');
                return false;
            }
            
            if(places < 1 || places > 10) {
                e.preventDefault();
                alert('Le nombre de places doit être entre 1 et 10');
                return false;
            }
        });
    }
    
    // Validation du numéro de téléphone
    const telephoneInput = document.getElementById('telephone');
    if(telephoneInput) {
        telephoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9+]/g, '');
        });
    }
    
    // Animation des cartes d'agences
    const agenceCards = document.querySelectorAll('.agence-card');
    agenceCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#e67e22';
            this.style.color = 'white';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.backgroundColor = 'white';
            this.style.color = '#e67e22';
        });
    });
});

// Fonction pour imprimer le ticket
function imprimerTicket() {
    window.print();
}

// Fonction pour générer un code QR (simplifié)
function genererQRCode(text) {
    // Dans une vraie application, utiliser une bibliothèque comme qrcode.js
    console.log('QR Code généré pour: ' + text);
    alert('QR Code: ' + text);
}

// Validation des dates
function validerDate() {
    const dateInput = document.getElementById('date');
    if(dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
        
        dateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const currentDate = new Date();
            currentDate.setHours(0,0,0,0);
            
            if(selectedDate < currentDate) {
                alert('La date de voyage ne peut pas être dans le passé');
                this.value = '';
            }
        });
    }
}

// Appeler la validation des dates
validerDate();

// Gestionnaire pour la réservation en agence
function reserverEnAgence() {
    if(confirm('Confirmer la réservation en agence ?')) {
        // Logique de réservation en agence
        alert('Réservation en agence enregistrée');
    }
}

// Rafraîchir la liste des trajets (pour admin)
function rafraichirTrajets() {
    location.reload();
}

// Exporter les réservations en CSV (pour admin)
function exporterReservations() {
    let csv = 'Référence,Nom,Prénom,Téléphone,Trajet,Date,Places,Montant,Statut\n';
    
    // Récupérer les données du tableau
    const rows = document.querySelectorAll('#reservations-table tbody tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td');
        let rowData = [];
        cols.forEach(col => {
            rowData.push('"' + col.textContent.trim() + '"');
        });
        csv += rowData.join(',') + '\n';
    });
    
    // Télécharger le fichier
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', 'reservations_' + new Date().toISOString().split('T')[0] + '.csv');
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}