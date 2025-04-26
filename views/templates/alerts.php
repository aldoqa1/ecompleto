<?php 
    if($alerts){
        foreach($alerts as $types => $messages){
            foreach($messages as $message): ?>
            
                <div class="<?php echo $types; ?>"><?php echo $message; ?></div>
            
            <?php endforeach;
        }
    }
?>

