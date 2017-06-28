<div class="card">
    <div class="card-header">
        <h4>This is title</h4>
    </div>
    <div class="card-block">
        <div class="quiz-content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 25%; height: 3px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-sm-10">
                        <h5>This is the question</h5>
                    </div>
                    <div class="col-sm-2">
                        <div class="countdown">
                            <div class="countdown-number"></div>
                            <svg>
                                <circle r="18" cx="20" cy="20"></circle>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-center">
        <small class="text-muted">This is presented by <a href="www.smartgnan.com" target="_blank">www.smartgnan.com</a></small>
    </div>
</div>

<script>
    var countdownNumberEl = document.getElementsByClassName('countdown-number')[0];
    var countdown = 30;

    countdownNumberEl.textContent = countdown+'s';

    setInterval(function() {
      countdown = --countdown < 0 ? 30 : countdown;

      countdownNumberEl.textContent = countdown+'s';
    }, 1000);
</script>

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
