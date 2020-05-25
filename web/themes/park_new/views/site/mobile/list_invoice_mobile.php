<!DOCTYPE html>
<?php 
use app\models\ListSetup;
$ListSetup = new ListSetup();
?>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
      .outstanding{
          background-color: #f0ad4e;
      }
  </style>
</head>
<body>
    <div class="container">                      
        <table class="table">
            <?php foreach ($dataProvider->models as $value){ ?>
                <tr>
                    <td><?php echo $value->invoice_no; ?></td>
                    <td><?php echo $ListSetup->getDisplayPrice($value->invoice_total_last_tax,2); ?></td>
                </tr>
                
            <?php } ?>
        </table>
    </div>

</body>
</html>
