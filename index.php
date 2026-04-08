<?php
session_start();
require_once 'config/database.php';

$db = new Database();
$conn = $db->getConnection();

// Récupérer les villes pour les sélecteurs
$villes_query = "SELECT * FROM villes ORDER BY nom";
$villes_result = $conn->query($villes_query);

// Définition des tarifs
$tarifs = [
    ['depart' => 'NDJAMENA', 'arrivee' => 'SARH', 'prix' => 30000, 'duree' => '8h', 'distance' => '650 km'],
    ['depart' => 'NDJAMENA', 'arrivee' => 'KOUMRA', 'prix' => 29000, 'duree' => '7h30', 'distance' => '600 km'],
    ['depart' => 'NDJAMENA', 'arrivee' => 'DOBA', 'prix' => 27000, 'duree' => '6h30', 'distance' => '520 km'],
    ['depart' => 'NDJAMENA', 'arrivee' => 'MOUNDOU', 'prix' => 25000, 'duree' => '6h', 'distance' => '480 km'],
    ['depart' => 'NDJAMENA', 'arrivee' => 'KELO', 'prix' => 20000, 'duree' => '5h', 'distance' => '400 km'],
    ['depart' => 'NDJAMENA', 'arrivee' => 'BONGOR', 'prix' => 17000, 'duree' => '4h', 'distance' => '320 km'],
    ['depart' => 'NDJAMENA', 'arrivee' => 'GUELENDENG', 'prix' => 10500, 'duree' => '3h', 'distance' => '240 km'],
];

// Calculer les statistiques
$prix_min = min(array_column($tarifs, 'prix'));
$prix_max = max(array_column($tarifs, 'prix'));
$prix_moyen = array_sum(array_column($tarifs, 'prix')) / count($tarifs);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EXPRESS SUR VOYAGE - Réservation de voyages en ligne</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7fc;
        }

        header {
            background: #1a2a3a;
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo a {
            color: #2a5298;
            text-decoration: none;
            font-size: 24px;
            font-weight: bold;
        }

        nav ul {
            list-style: none;
            display: flex;
        }

        nav ul li {
            margin-left: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: #3b6cb0;
        }

        /* SECTION HERO */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('assets/images/img.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
            text-align: center;
            padding: 150px 20px 80px;
            margin-top: 60px;
            position: relative;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 30px;
        }

        .btn-hero {
            display: inline-block;
            background: #2a5298;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-hero:hover {
            background: #1e3c72;
            transform: translateY(-2px);
        }

        .reservation-form {
            background: white;
            padding: 40px;
            border-radius: 15px;
            max-width: 900px;
            margin: -50px auto 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .reservation-form h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2a5298;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42,82,152,0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #2a5298, #1e3c72);
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(42,82,152,0.3);
        }

        /* SECTION TARIFS - COMPACTE ET BLEUE */
        .tarifs-section {
            background: white;
            padding: 40px 20px;
        }

        .tarifs-section .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .tarifs-section h2 {
            text-align: center;
            margin-bottom: 12px;
            color: #2a5298;
            font-size: 28px;
        }

        .tarifs-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .tarifs-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-card .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #2a5298;
            margin-bottom: 3px;
        }

        .stat-card .stat-label {
            color: #666;
            font-size: 12px;
            margin-top: 3px;
        }

        .tarifs-table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }

        .tarifs-table {
            width: 100%;
            border-collapse: collapse;
        }

        .tarifs-table th {
            background: #f0f4f8;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #2a5298;
            border-bottom: 2px solid #e0e7ef;
        }

        .tarifs-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #eef2f6;
        }

        .tarifs-table tr:hover {
            background: #f8fafc;
        }

        .tarifs-table .price {
            font-size: 16px;
            font-weight: bold;
            color: #2a5298;
        }

        .btn-reserver {
            background: #2a5298;
            color: white;
            padding: 5px 12px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 11px;
            font-weight: 600;
            transition: background 0.2s;
        }

        .btn-reserver:hover {
            background: #1e3c72;
        }

        .tarifs-cards {
            display: none;
            grid-template-columns: 1fr;
            gap: 12px;
            margin-top: 15px;
        }

        .tarif-card {
            background: white;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 3px solid #2a5298;
        }

        .tarif-card h4 {
            color: #2a5298;
            margin-bottom: 3px;
            font-size: 14px;
        }

        .tarif-card .card-price {
            font-size: 16px;
            font-weight: bold;
            color: #2a5298;
        }

        .features {
            padding: 60px 20px;
            background: white;
        }

        .features .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .features h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #2a5298;
            font-size: 32px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .feature-card {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: transform 0.3s;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .feature-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .feature-card h3 {
            color: #2a5298;
            margin-bottom: 10px;
        }

        /* SECTION AGENCES AVEC IMAGE DE BUS */
        .agences {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 60px 20px;
        }

        .agences h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #2a5298;
            font-size: 32px;
        }

        .agences-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .agence-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            cursor: pointer;
            text-align: center;
        }

        .agence-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(42,82,152,0.2);
        }

        .agence-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-bottom: 3px solid #2a5298;
        }

        .agence-card h3 {
            color: #2a5298;
            margin: 15px 0 5px;
            font-size: 20px;
        }

        .agence-card p {
            color: #666;
            padding: 0 10px 15px;
            font-size: 13px;
        }

        footer {
            background: #1a2a3a;
            color: white;
            text-align: center;
            padding: 30px 20px;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 32px;
            }
            
            .hero p {
                font-size: 16px;
            }
            
            .reservation-form {
                margin: -30px 20px 30px;
                padding: 25px;
            }
            
            .features h2, .agences h2, .tarifs-section h2 {
                font-size: 24px;
            }
            
            .tarifs-table-container {
                display: none;
            }
            
            .tarifs-cards {
                display: grid;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.php">EXPRESS SUR VOYAGE</a>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">ACCUEIL</a></li>
                    <li><a href="agences.php">NOS AGENCES</a></li>
                    <li><a href="tarifs.php">💰 TARIFS</a></li>
                    <li><a href="client/login.php">CLIENT</a></li>
                    <li><a href="admin/login.php">ADMIN</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="hero">
        <h1>Voyagez en toute simplicité</h1>
        <p>Réservez vos billets de voyage en quelques clics</p>
        <a href="#reservation" class="btn-hero">Réserver maintenant →</a>
    </div>

    <div class="reservation-form" id="reservation">
        <h2>🔍 Rechercher un voyage</h2>
        <form action="client/recherche.php" method="GET">
            <div class="form-grid">
                <div class="form-group">
                    <label>Ville de départ</label>
                    <select name="depart" required>
                        <option value="">Sélectionnez</option>
                        <?php while($ville = $villes_result->fetch_assoc()): ?>
                        <option value="<?php echo $ville['id']; ?>"><?php echo $ville['nom']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Ville d'arrivée</label>
                    <select name="arrivee" required>
                        <option value="">Sélectionnez</option>
                        <?php 
                        $villes_result->data_seek(0);
                        while($ville = $villes_result->fetch_assoc()): 
                        ?>
                        <option value="<?php echo $ville['id']; ?>"><?php echo $ville['nom']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date de départ</label>
                    <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label>Nombre de places</label>
                    <input type="number" name="places" min="1" max="10" value="1" required>
                </div>
            </div>
            <button type="submit" class="btn-primary">Rechercher les trajets</button>
        </form>
    </div>

    <!-- SECTION AGENCES (déplacée en premier après le formulaire) -->
    <div class="agences">
        <h2>Nos agences</h2>
        <div class="agences-grid">
            <div class="agence-card">
                <img src="assets/images/img.jpg" alt="Bus Express Sur Voyage">
                <h3>NDJAMENA</h3>
                <p>Centre ville</p>
            </div>
            <div class="agence-card">
                <img src="assets/images/img.jpg" alt="Bus Express Sur Voyage">
                <h3>MOUNDOU</h3>
                <p>Avenue Mobutu</p>
            </div>
            <div class="agence-card">
                <img src="assets/images/img.jpg" alt="Bus Express Sur Voyage">
                <h3>SARH</h3>
                <p>Boulevard du Tchad</p>
            </div>
            <div class="agence-card">
                <img src="assets/images/img.jpg" alt="Bus Express Sur Voyage">
                <h3>DOBA</h3>
                <p>Quartier Commercial</p>
            </div>
            <div class="agence-card">
                <img src="assets/images/img.jpg" alt="Bus Express Sur Voyage">
                <h3>GUELENDENG</h3>
                <p>Place du Marché</p>
            </div>
            <div class="agence-card">
                <img src="assets/images/img.jpg" alt="Bus Express Sur Voyage">
                <h3>BONGOR</h3>
                <p>Place de l'Indépendance</p>
            </div>
            <div class="agence-card">
                <img src="assets/images/img.jpg" alt="Bus Express Sur Voyage">
                <h3>KELO</h3>
                <p>Rue Principale</p>
            </div>
            <div class="agence-card">
                <img src="assets/images/img.jpg" alt="Bus Express Sur Voyage">
                <h3>MONGO</h3>
                <p>Centre Ville</p>
            </div>
        </div>
    </div>

    <div class="features">
        <div class="container">
            <h2>Pourquoi voyager avec nous ?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🚌</div>
                    <h3>Flotte moderne</h3>
                    <p>Des bus climatisés et confortables pour vos voyages</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎫</div>
                    <h3>Réservation en ligne</h3>
                    <p>Réservez depuis chez vous en quelques minutes</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Ticket mobile</h3>
                    <p>Recevez votre ticket par SMS et email</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💳</div>
                    <h3>Paiement sécurisé</h3>
                    <p>Paiement à bord ou en ligne (Airtel Money / Virement)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION TARIFS (déplacée après les features) -->
    <div class="tarifs-section">
        <div class="container">
            <h2>💰 Tarifs de transport</h2>
            <p class="tarifs-subtitle">Au départ de N'Djamena vers toutes les destinations</p>

            <!-- Statistiques des tarifs -->
            <div class="tarifs-stats">
                <div class="stat-card">
                    <div class="stat-value"><?php echo number_format($prix_min, 0, ',', ' '); ?> F</div>
                    <div class="stat-label">Tarif minimum</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo number_format($prix_max, 0, ',', ' '); ?> F</div>
                    <div class="stat-label">Tarif maximum</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo number_format($prix_moyen, 0, ',', ' '); ?> F</div>
                    <div class="stat-label">Prix moyen</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?php echo count($tarifs); ?></div>
                    <div class="stat-label">Destinations</div>
                </div>
            </div>

            <!-- Tableau des tarifs (version desktop) -->
            <div class="tarifs-table-container">
                <table class="tarifs-table">
                    <thead>
                         <tr>
                            <th>Destination</th>
                            <th>Distance</th>
                            <th>Durée estimée</th>
                            <th>Tarif</th>
                            <th>Action</th>
                        </thead>
                    <tbody>
                        <?php foreach($tarifs as $t): ?>
                         <tr>
                            <td><strong><?php echo $t['depart']; ?> → <?php echo $t['arrivee']; ?></strong></td>
                            <td><?php echo $t['distance']; ?></td>
                            <td><?php echo $t['duree']; ?></td>
                            <td class="price"><?php echo number_format($t['prix'], 0, ',', ' '); ?> FCFA</td>
                            <td><a href="client/recherche.php?depart=<?php echo urlencode($t['depart']); ?>&arrivee=<?php echo urlencode($t['arrivee']); ?>" class="btn-reserver">Réserver</a></td>
                         </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Cartes des tarifs (version mobile) -->
            <div class="tarifs-cards">
                <?php foreach($tarifs as $t): ?>
                <div class="tarif-card">
                    <div>
                        <h4><?php echo $t['depart']; ?> → <?php echo $t['arrivee']; ?></h4>
                        <small><?php echo $t['distance']; ?> • <?php echo $t['duree']; ?></small>
                    </div>
                    <div class="card-price"><?php echo number_format($t['prix'], 0, ',', ' '); ?> F</div>
                </div>
                <?php endforeach; ?>
            </div>

            <div style="text-align: center; margin-top: 25px;">
                <a href="tarifs.php" class="btn-hero" style="display: inline-block; background: #2a5298;">Voir tous les tarifs détaillés →</a>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2026 express sur voyage - Tous droits réservés</p>
            <p>📍 Agences dans toutes les villes du Tchad</p>
            <p>📞 Service client: +235 68-10-83-47</p>
        </div>
    </footer>
</body>s
</html>