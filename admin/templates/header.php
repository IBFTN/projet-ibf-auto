<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'IBF Motors Admin' ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; color: #333; }
        
        /* Header & Navigation */
        .header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .header h1 { font-size: 22px; font-weight: 700; }
        .nav-links { display: flex; gap: 15px; align-items: center; }
        .nav-links a { 
            color: rgba(255,255,255,0.8); 
            text-decoration: none; 
            padding: 8px 15px; 
            border-radius: 6px; 
            font-size: 14px;
            font-weight: 500;
            transition: 0.3s;
        }
        .nav-links a:hover, .nav-links a.active { color: white; background: rgba(255,255,255,0.1); }
        .btn-logout { 
            background: #dc3545; 
            color: white; 
            padding: 8px 20px; 
            text-decoration: none; 
            border-radius: 6px; 
            font-size: 14px;
            font-weight: 600;
        }
        .btn-logout:hover { background: #c82333; }

        .container { max-width: 1400px; margin: 30px auto; padding: 0 20px; }
        
        /* Common Components */
        .section { background: white; border-radius: 12px; padding: 25px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .section-header h2 { font-size: 22px; color: #1a1a2e; }
        
        .btn { display: inline-block; padding: 10px 20px; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: 0.3s; }
        .btn-primary { background: #007bff; color: white; }
        .btn-primary:hover { background: #0069d9; }
        .btn-success { background: #28a745; color: white; }
        .btn-success:hover { background: #218838; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-danger:hover { background: #c82333; }
        .btn-back { color: #666; text-decoration: none; font-size: 14px; display: inline-block; margin-bottom: 15px; }
        .btn-back:hover { color: #007bff; }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #f0f0f0; }
        th { background: #f8f9fa; color: #555; font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
        tr:hover { background: #fafafa; }
        
        .badge { padding: 5px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-primary { background: #cce5ff; color: #004085; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 25px; font-size: 14px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        .car-img-small { width: 100px; height: 65px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        
        /* Form Styling */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #444; }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; transition: border-color 0.3s;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #007bff; box-shadow: 0 0 0 3px rgba(0,123,255,0.1); }
        .form-group input[readonly] { background: #f8f9fa; color: #777; }
    </style>
</head>
<body>
    <?php if (!isset($noHeader) || !$noHeader): ?>
    <div class="header">
        <h1>IBF Motors Admin</h1>
        <div class="nav-links">
            <a href="<?= $adminRoot ?? '' ?>dashboard.php" class="<?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>">Tableau de Bord</a>
            <a href="<?= $adminRoot ?? '' ?>cars/index.php" class="<?= ($activePage ?? '') === 'cars' ? 'active' : '' ?>">Voitures</a>
            <a href="<?= $adminRoot ?? '' ?>orders/index.php" class="<?= ($activePage ?? '') === 'orders' ? 'active' : '' ?>">Commandes</a>
            <a href="<?= $adminRoot ?? '' ?>contacts/index.php" class="<?= ($activePage ?? '') === 'contacts' ? 'active' : '' ?>">Messages</a>
            <a href="<?= $adminRoot ?? '' ?>../index.html" target="_blank" style="margin-left: 20px; opacity: 0.6;">Voir le Site →</a>
        </div>
        <a href="<?= $adminRoot ?? '' ?>logout.php" class="btn-logout">Déconnexion</a>
    </div>
    <?php endif; ?>
    <div class="container">
