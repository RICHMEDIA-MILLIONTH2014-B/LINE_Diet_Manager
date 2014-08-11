<!DOCTYPE HTML>
<html lang="en-US">
<head>
  <meta charset="UTF-8">
  <?php echo $this->Html->css('display');?>
  <title></title>
</head>
<body>
<header>
	<div class="title">LINE ダイエット マネージャー</div>
</header>
<div class="talk">

</div>
<footer>
	<form class="form-horizontal" name="frm">
	  <div class="control-group">
		<div class="controls">
		  <input id="value" type="text" value=""/>
		  <button id="update" type="button">Sent</button>
		</div>
	  </div>
	  <div class="control-group">
		<div class="controls">
		</div>
	  </div>
	</form>
</footer>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript">
$("#update").click(function() {

    var data = {
        value: $("#value").val(),
    };
	$('.talk').append('<p class=\'right\'>' + $("#value").val() + '</p>');

    $.ajax({
        type:"POST",
        url:"/Internship/RICHMEDIA/api/posts/post/28683fre/",
        data:JSON.stringify(data),
        contentType: 'application/json',
        dataType: "json",
        success: function(json_data) {
			$('.talk').append('<p class=\'left\'>' + json_data.Message.value + '</p>');
			document.frm.reset();
			footerStart(".bottom");
        },
        error: function() {
            alert("Error");
        },
    });
});

function footerStart(selector){
    $('html,body').animate({scrollTop: $(selector).offset().top},'slow');
}$(".talk").append("<p class='bottom'></p>");

</script>

</body>
</html>