<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?= $this->escape($this->pageTitle); ?></title>

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="/css/main.css">

</head>
<body>
<?= $this->yieldView(); ?>
</body>
<script src="js/dom7.min.js" type="text/javascript"></script>
<script src="js/script.js" type="text/javascript"></script>
</html>