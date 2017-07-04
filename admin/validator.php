<?php 

function sgcrackit_register_validator_menu_page() {
    $count = 0;
    global $progressTable;
    
    global $wpdb;
    
    $sqlSelect = "SELECT COUNT(*) as c FROM $progressTable WHERE isCompleted = 1 ";
    $result = $wpdb->get_results($sqlSelect);
    
    $count = $result[0]->c;
    
    $question_page = add_menu_page(
        'Validator',
        sprintf( __( 'Validator %s' ), "<span class='update-plugins count-$count'><span class='update-count'>" . number_format_i18n($count) . "</span></span>" ),
        'manage_options',
        'validator',
        'sgcrackit_render_validator_page',
        '',
        6
    );
    
    add_action('admin_print_styles-' . $question_page, 'sgcrackit_load_admin_scripts_validator');
}
add_action( 'admin_menu', 'sgcrackit_register_validator_menu_page' );

function sgcrackit_load_admin_scripts_validator() {
    wp_register_style('bootstrap-css', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css', false, '4.0.0-alpha.6', null);
	wp_enqueue_style('bootstrap-css');
    
    wp_register_style('font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0', null);
	wp_enqueue_style('font-awesome-css');
    
    wp_register_script('sgcrackit-admin-js', get_template_directory_uri() . '/theme/js/admin.js', false, null, true);
	wp_enqueue_script('sgcrackit-admin-js');
}

function sgcrackit_render_validator_page() {
    if(isset($_GET['quizId']) && isset($_GET['prgId'])) {
        sgcrackit_render_validator_page_single_quiz();
        return;
    }
?>
    <div class="container">
        <br>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Quiz</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="quiz-list">
                    
                  </tbody>
                </table>
            </div>
        </div>
    </div>
<?php }

function sgcrackit_render_validator_page_single_quiz() { ?>
        <script>
            var quizId = <?php echo $_GET['quizId']; ?>;
            var prgId = <?php echo $_GET['prgId']; ?>;
             jQuery(document).ready(function() {
                getPendingFullQuiz(quizId, prgId);  
            });
        </script>
        <br>
        <div id="validation-of-desc">
            <div class="row">
                <div class="col-sm-4 text-success">
                    <span>Automated Right Answers: </span>
                </div>
                <div class="col-sm-2 text-success">
                    <span id="auto-right-ans">0</span>
                </div>
                <div class="col-sm-4 text-danger">
                    <span>Automated Wrong Answers: </span>
                </div>
                <div class="col-sm-2 text-danger">
                    <span id="auto-wrong-ans">0</span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 text-success">
                    <span>Manual Right Answers: </span>
                </div>
                <div class="col-sm-2 text-success">
                    <span id="manual-right-ans">0</span>
                </div>
                <div class="col-sm-4 text-danger">
                    <span>Manual Wrong Answers: </span>
                </div>
                <div class="col-sm-2 text-danger">
                    <span id="manual-wrong-ans">0</span>
                </div>
            </div>
            <div class="row text-info">
                <div class="col-sm-4">
                    <span>Pending Questions: </span>
                </div>
                <div class="col-sm-2">
                    <span id="pending-questions">0</span>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-10">
                    <div class="card" style="max-width:100%">
                      <div class="card-block">
                        <h6 class="card-title" id="manual-question"></h6>
                          <hr>
                        <p class="card-text"  id="manual-answer"></p>
                      </div>
                    </div>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-danger" onclick="updateValidation(false);">Wrong Answer</button>
                    <br><br>
                    <button class="btn btn-success" onclick="updateValidation(true);">Validated Answer</button>
                </div>
            </div>
        </div>
        <div id="final-validation" style="display:none">
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card" style="max-width:100%">
                      <div class="card-block">
                        <h6 class="card-title" id="manual-question">Final Summary</h6>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="alert alert-success" role="alert">
                                    Right Answers: <strong id="total-right"></strong>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="alert alert-danger" role="alert">
                                    Wrong Answers: <strong id="total-wrong"></strong>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="alert alert-info" role="alert">
                                    Total Score: <strong id="total-score"></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                        <?php wp_editor( '', 'editor-id', $settings = array() ); ?>
                        </div>
                      </div>
                        <div class="card-footer text-muted text-right">
                            <button class="btn btn-success" onclick="onSubmittingQuizValidation();">Submit Validation</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php }