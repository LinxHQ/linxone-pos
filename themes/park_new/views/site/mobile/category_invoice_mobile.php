<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.6/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
</head>
<body>
<?php
    use app\models\ListSetup;
    $ListSetup = new ListSetup();
    $dropdow_revenue_type = $ListSetup->getRevenue();
?>

<div class="container">


  <div class="list-group">
    <?php foreach($dropdow_revenue_type as $value){ ?>
        <a href="#" class="list-group-item list-group-item-action"><?php echo $value;?></a>
    <?php } ?>
    
    
  </div>
</div>

</body>
</html>
