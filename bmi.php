<?php
// 1. Legge nome, peso e altezza inviati dal form tramite $_POST
$nome = $_POST['nome'];
$peso = $_POST['peso'];
$altezza_cm = $_POST['altezza'];

// 2. Converte l'altezza da centimetri a metri (es. 178 cm -> 1.78 m)
$altezza_m = $altezza_cm / 100;

// 3. Calcola il BMI con la formula: peso / (altezza in metri)^2
$bmi = $peso / ($altezza_m * $altezza_m);

// Arrotonda il BMI a 2 cifre decimali (es. 22.09)
$bmi_formattato = number_format($bmi, 2);

// 4. Determina la fascia e la classe CSS corrispondente in base al valore del BMI
if ($bmi < 18.5) {
    $fascia = "Sottopeso";
    $classe_card = "sottopeso";
} elseif ($bmi <= 24.9) {
    $fascia = "Normopeso";
    $classe_card = "normopeso";
} elseif ($bmi <= 29.9) {
    $fascia = "Sovrappeso";
    $classe_card = "sovrappeso";
} else {
    $fascia = "Obesità";
    $classe_card = "obesita";
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultato BMI</title>
    <!-- Collegamento allo stesso file CSS comune -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <main>
        <!-- Titolo principale della pagina -->
        <h1>Il tuo BMI</h1>

        <!-- La card possiede due classi: "card" + la classe specifica della fascia (es. "card normopeso") -->
        <div class="card <?php echo $classe_card; ?>">
            
            <!-- Nome inserito -->
            <div class="card-nome"><?php echo $nome; ?></div>
            
            <!-- Dati inseriti: Peso e Altezza -->
            <div class="card-dati">Peso <?php echo $peso; ?> kg · Altezza <?php echo $altezza_cm; ?> cm</div>
            
            <!-- Valore del BMI calcolato con 2 decimali e font a 48px -->
            <div class="card-bmi"><?php echo $bmi_formattato; ?></div>
            
            <!-- Nome della fascia in maiuscolo -->
            <div class="card-fascia"><?php echo $fascia; ?></div>
            
        </div>

        <!-- Link "Calcola di nuovo" diretto verso form.html -->
        <a href="index.html" class="link-riprova">Calcola di nuovo</a>
    </main>

</body>
</html>