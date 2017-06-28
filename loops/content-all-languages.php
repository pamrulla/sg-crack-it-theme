<?php 
$terms = get_terms( 'language' );
$next_row = 0;
$classesColor = array('primary', 'success', 'info', 'warning', 'danger');

if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
    
    $cnt = count($terms) / 2;
    if($cnt > 3){
        $cnt = 3;
    }
    else if($cnt <= 1){
        $cnt = 2;
    }
    
    $colorIdx = 0;
    
    foreach ( $terms as $term ) { 
        if( $next_row % $cnt == 0)
        {
            if( $next_row <> 0){
                echo '</div>';
            }
            echo '<br> <div class="card-deck">';
        }?>
        <div class="card card-outline-<?php echo $classesColor[$colorIdx%count($classesColor)]; ?>">
          <h4 class="card-header text-<?php echo $classesColor[$colorIdx%count($classesColor)]; ?>">
            <?php echo $term->name; ?>
          </h4>
          <div class="card-block">
            <blockquote class="card-blockquote">
              <p class="card-text"><?php echo $term->description; ?></p>
            </blockquote>
          </div>
          <div class="card-footer text-muted text-right">
            <a href="<?php echo get_term_link($term->name, 'language'); ?>" class="btn btn-<?php echo $classesColor[$colorIdx%count($classesColor)]; ?>">Go</a>
          </div>
            <?php $next_row = $next_row + 1 ?>
        </div>
    <?php $colorIdx++; }
    echo '</div>';
} 
