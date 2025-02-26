<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="bg-white m-0 p-0 h-screen w-screen overflow-hidden">

    <?php
    $data = [
        'data' => "https://habitat-beauvais.smartquiz.fr/",
        'format' => 'png',
        'color' => '#dd002c',
        'scale' => 20,
    ];
    
    $qrcode = "https://yaqrgen.com/qrcode.png?" . http_build_query($data);
    ?>
    
    <div class="flex justify-center items-center h-full w-full">
        <img src="<?= $qrcode ?>" class="max-w-[90%] max-h-[90%] object-contain">
    </div>
</body>
</html>
