<h1>Khan</h1>
<div class="custom-controls-stacked">
    <label class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" id="check1" name="check-1">
      <span class="custom-control-indicator"></span>
      <span class="custom-control-description">Check 1</span>
    </label>
    <label class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" id="check2" name="check-2">
      <span class="custom-control-indicator"></span>
      <span class="custom-control-description">Check 2</span>
    </label>
    <label class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" id="check3" name="check-3">
      <span class="custom-control-indicator"></span>
      <span class="custom-control-description">Check 3</span>
    </label>
    <label class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" id="check4" name="check-4">
      <span class="custom-control-indicator"></span>
      <span class="custom-control-description">Check 4</span>
    </label>
</div>
<div class="custom-controls-stacked">
  <label class="custom-control custom-radio">
    <input id="radio1" name="radio-1" type="radio" class="custom-control-input">
    <span class="custom-control-indicator"></span>
    <span class="custom-control-description">Option 1</span>
  </label>
  <label class="custom-control custom-radio">
    <input id="radio2" name="radio-1" type="radio" class="custom-control-input">
    <span class="custom-control-indicator"></span>
    <span class="custom-control-description">Option 2</span>
  </label>
  <label class="custom-control custom-radio">
    <input id="radio3" name="radio-1" type="radio" class="custom-control-input">
    <span class="custom-control-indicator"></span>
    <span class="custom-control-description" >Option 3</span>
  </label>
  <label class="custom-control custom-radio">
    <input id="radio4" name="radio-1" type="radio" class="custom-control-input">
    <span class="custom-control-indicator"></span>
    <span class="custom-control-description">Option 4</span>
  </label>
</div>

<div class="container-fluid">
	<div class="row sorting">
		<div class="col-md-12">
			<div class="card card-outline-primary forsorting">
				<div class="card-block ">
					Panel content 1
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="card card-outline-primary forsorting">
				<div class="card-block ">
					Panel content 2
				</div>
			</div>
		</div>
		<div class="col-md-12">
			<div class="card card-outline-primary forsorting">
				<div class="card-block">
					Panel content 3
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    jQuery(function($){
    $('.sorting').sortable({
	   connectWith: ".card",
	   handle: ".card-block",
	   placeholder: "card-placeholder",
      start: function(e, ui){
         ui.placeholder.width(ui.item.find('.card').width());
         ui.placeholder.height(ui.item.find('.card').height());
         ui.placeholder.addClass(ui.item.attr("class"));
       }
    });

    $('.card').on('mousedown', function(){
        console.log("Mousedoen");
        $(this).css( 'cursor', 'move' );
    }).on('mouseup', function(){
        $(this).css( 'cursor', 'auto' );
    });;
    });
</script>
