<div class ="message">
<ul>
<?php 
foreach($_REQUEST['message'] as $message)
    {
      echo "<li>$message</li>";
    }
?>
</ul></div>
