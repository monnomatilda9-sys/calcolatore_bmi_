<?php
// Inizializziamo le variabili per evitare errori
$is_post = $_SERVER['REQUEST_METHOD'] === 'POST';
$errore = "";
$bmi = 0;
$categoria = "";
$classe_css = ""; // Nuova variabile per la classe della card
$nome = "";

// Se il form è stato inviato (metodo POST), elaboriamo i dati
if ($is_post) {
    $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
    $peso = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
    // Riceviamo direttamente l'altezza in metri
    $altezza_m = isset($_POST['altezza']) ? floatval($_POST['altezza']) : 0;

    // Validazione lato server
    if (empty($nome) || $peso <= 0 || $altezza_m <= 0) {
        $errore = "Inserisci tutti i dati correttamente prima di procedere.";
    } else {
        // L'altezza è già in metri, calcoliamo il quadrato
        $altezza_quadrato = pow($altezza_m, 2);

        if ($altezza_quadrato > 0) {
            $bmi = $peso / $altezza_quadrato;

            // Classificazione e assegnazione classi CSS basate sull'immagine
            if ($bmi < 18.5) {
                $categoria = "SOTTOPESO";
                $classe_css = "sottopeso";
            } elseif ($bmi < 25.0) {
                $categoria = "NORMOPESO";
                $classe_css = "normopeso";
            } elseif ($bmi < 30.0) {
                $categoria = "SOVRAPPESO";
                $classe_css = "sovrappeso";
            } else {
                $categoria = "OBESITÀ";
                $classe_css = "obesita";
            }
        } else {
            $errore = "Impossibile calcolare il BMI con i dati forniti.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calcolatore BMI</title>
  <style>
    /* Reset di base dei margini e box model */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: sans-serif;
      background-color: #F1F3F5;
      color: #1F2328;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .container {
      width: 100%;
      min-width: 250px;
      max-width: 400px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h1 {
      font-size: 1.8rem;
      margin-bottom: 20px;
      text-align: center;
    }

    /* Stile base della card */
    .card {
      background-color: #FFFFFF;
      width: 100%;
      padding: 30px 24px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      /* Impostiamo un bordo trasparente di default per evitare salti di layout */
      border: 3px solid transparent; 
    }

    /* Form Styles */
    .form-group {
      display: flex;
      flex-direction: column;
      margin-bottom: 16px;
    }

    .form-group.last-group {
      margin-bottom: 24px;
    }

    label {
      width: 100%;
      font-weight: 600;
      font-size: 0.95rem;
      margin-bottom: 6px;
    }

    input[type="text"],
    input[type="number"] {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #D0D7DE;
      border-radius: 6px;
      font-size: 1rem;
      font-family: sans-serif;
      color: #1F2328;
      outline: none;
      transition: border-color 0.2s ease;
    }

    input[type="text"]:focus,
    input[type="number"]:focus {
      border-color: #1F2328;
    }

    .btn-submit {
      width: 100%;
      background-color: #1F2328;
      color: #FFFFFF;
      border: none;
      padding: 12px;
      font-size: 1rem;
      font-weight: bold;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .btn-submit:hover {
      background-color: #32383E;
    }

    /* --- STILI SPECIFICI PER I RISULTATI (Basati sull'immagine) --- */
    .result-box {
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    /* Valore Numerico BMI */
    .bmi-value {
      font-size: 3.5rem;
      font-weight: 900;
      color: #1F2328; /* Colore scuro come nell'immagine */
      line-height: 1;
      margin-top: 15px;
      margin-bottom: 5px;
    }

    /* Testo Categoria */
    .categoria-text {
      font-size: 1.1rem;
      font-weight: bold;
      letter-spacing: 1px;
      margin-bottom: 25px;
    }

    /* Classi Dinamiche per i Colori */
    .card.sottopeso {
      background-color: #E3EEF9;
      border-color: #2F6FB3;
    }
    .card.sottopeso .categoria-text { color: #2F6FB3; }

    .card.normopeso {
      background-color: #E3F4E6;
      border-color: #256D34;
    }
    .card.normopeso .categoria-text { color: #256D34; }

    .card.sovrappeso {
      background-color: #FCEBD7;
      border-color: #9A4F06;
    }
    .card.sovrappeso .categoria-text { color: #9A4F06; }

    .card.obesita {
      background-color: #FBE2E2;
      border-color: #B32D2D;
    }
    .card.obesita .categoria-text { color: #B32D2D; }

    /* Pulsante Indietro */
    .btn-back {
      display: inline-block;
      text-decoration: none;
      color: #FFFFFF;
      background-color: #1F2328;
      padding: 10px 18px;
      border-radius: 8px;
      font-weight: bold;
      transition: background-color 0.2s;
    }
    
    .btn-back:hover {
      background-color: #32383E;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1><?php echo $is_post && empty($errore) ? "Risultato del Calcolo" : "Calcolatore BMI"; ?></h1>

    <?php if ($is_post && empty($errore)): ?>
      <!-- SCHERMATA DEI RISULTATI -->
      <!-- Aggiungiamo dinamicamente la classe CSS per lo stile (es: .card.sottopeso) -->
      <div class="card result-box <?php echo $classe_css; ?>">
        <p>Ciao <strong><?php echo htmlspecialchars($nome); ?></strong></p>
        
        <!-- Valore grande -->
        <div class="bmi-value"><?php echo number_format($bmi, 2); ?></div>
        <!-- Nome della categoria colorato -->
        <div class="categoria-text"><?php echo $categoria; ?></div>
        
        <a href="" class="btn-back">Ricalcola</a>
      </div>

    <?php else: ?>
      <!-- SCHERMATA DEL FORM -->
      <div class="card">
        <?php if (!empty($errore)): ?>
          <p style="color: #D9383A; font-weight: bold; margin-bottom: 15px; text-align: center;"><?php echo $errore; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
          
          <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" placeholder="Inserisci il tuo nome" required>
          </div>

          <div class="form-group">
            <label for="peso">Peso (kg)</label>
            <input type="number" id="peso" name="peso" step="0.1" min="1" placeholder="Es. 70.5" required>
          </div>

          <div class="form-group last-group">
            <label for="altezza">Altezza (m)</label>
            <input type="number" id="altezza" name="altezza" step="0.01" min="0.5" placeholder="Es. 1.75" required>
          </div>

          <button type="submit" class="btn-submit">Calcola</button>

        </form>
      </div>
    <?php endif; ?>

  </div>

</body>
</html>