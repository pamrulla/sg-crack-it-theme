<?php 

function sgcrackit_register_questions_menu_page() {
    $question_page = add_menu_page(
        'Questions',
        'Questions',
        'manage_options',
        'questions',
        'sgcrackit_render_questions_page',
        '',
        6
    );
    
    add_action('admin_print_styles-' . $question_page, 'sgcrackit_load_admin_scripts');
}
add_action( 'admin_menu', 'sgcrackit_register_questions_menu_page' );

function sgcrackit_load_admin_scripts() {
    wp_register_style('bootstrap-css', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css', false, '4.0.0-alpha.6', null);
	wp_enqueue_style('bootstrap-css');
    
    wp_register_style('font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false, '4.7.0', null);
	wp_enqueue_style('font-awesome-css');
    
    wp_register_script('sgcrackit-admin-js', get_template_directory_uri() . '/theme/js/admin.js', false, null, true);
	wp_enqueue_script('sgcrackit-admin-js');
}

function sgcrackit_render_questions_page() {
    ?>
    <div class="container">
        <br>
        <div class="row">
            <div class="col-sm-4">
                <label class="custom-file">
                  <input type="file" id="fileImport" class="custom-file-input">
                  <span class="custom-file-control"></span>
                </label>
            </div>
            <div class="col-sm-4">
                <a href="#" class="btn btn-primary" onclick="sgcrackit_import_questions();">Import Questions  <i id="loader" class="fa fa-spinner fa-spin"></i></a>
            </div>
            <div class="col-sm-4" id="import-status">
                
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-4">
                <select class="form-control" id="select-level" name="select-level">
                  <option selected>Select Level</option> 
                    <?php
                        $terms = get_terms('level');
                        foreach( $terms as $term ){
                            echo '<option value="'. $term->name .'">'. $term->name .'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="col-sm-4">
                <select class="form-control"  id="select-language" name="select-language">
                  <option selected>Select Language</option>
                  <?php
                        $terms = get_terms('language');
                        foreach( $terms as $term ){
                            echo '<option value="'. $term->name .'">'. $term->name .'</option>';
                        }
                    ?>
                </select>
            </div>
            <div class="col-sm-4">
                <button class="btn btn-primary" onclick="fetchQuestionsList();">Go <i id="loader1" class="fa fa-spinner fa-spin"></i></button>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Question</th>
                      <th>Type</th>
                      <th>Options</th>
                      <th>Answers</th>
                    </tr>
                  </thead>
                  <tbody id="questions-list">
                    
                  </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
}