<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Payments system test">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/build/css/app.css">
     
    <title>Payments app</title>

</head>
<body>

    <?php include_once __DIR__ . "/templates/header.php"; ?>

    <div class="app">
        <?php echo $content; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script> 
    
    <?php
        echo $script ?? '';
    ?>

    <?php include_once __DIR__ . "/templates/footer.php"; ?>

</body>
</html>