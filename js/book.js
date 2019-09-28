$(function(){
	$('#services').hide();
	$('#serviceinfo').hide();
	//$('#dates').hide();
	$('#timeslots').hide();
	$('.showing').hide();
	$('.confirm').hide();

	$('#categories span').click(function(){
		var num = this.id;
		$('#services').show();
		$('#s' + num).siblings().hide();
		$('#s' + num).show();
	});

	$('#services span').click(function(){
		var num = this.id;
		num = num.replace(/\D/g,'');
		$('#serviceinfo').show();
		$('#info' + num).siblings().hide();
		$('#info' + num).show();
	});

	/*$('#serviceinfo span').click(function(){
		$('#dates').show();
	})*/

	$('li').click(function(){
		var day = this.id;
		//alert(day);
		$('#timeslots').show();
		$('#' + day + '-2').siblings().hide();
		$('#' + day + '-2').show();
		//$('#days' + num).siblings().hide();
		//$('#info' + num).show();
	});

	//$('#' + day + '-2').click(function(){
		$('.button').click(function(){
		var info = this.id;
		//$('#' + info + '-show').siblings().hide();
		$('#' + info + '-show').show();
		$('.button').click(function(){
			//var info = this.id;
			$('#' + info + '-show').hide();
		})
		//alert(ok);
	});

		$('.showing').click(function(){
		var conf = this.id;
		conf = conf.replace(/\D/g,'');
		$('.confirm').show();
		$('#' + conf2 + '-confirm').siblings().hide();
		$('#' + conf2 + '-confirm').show();
		});

});