$(document).ready(function() {
	
	if($("#cart-list")) {
		$.ajax({
			url: '/cart-list',
			methos: 'get',
			success: function(resp) {
				
				$("#cart-list").html(resp);
			}
		});
	}
	
	$('.quantity').live("blur", function() {
		$("#cartcontent").fadeOut("fast");

		var url = '/cart-content/'+$(this).attr("id").replace("p",'')+'/'+$(this).val();

		$.ajax({
			url: url,
			method: "get",
			success: function(resp) {
				$("#cartcontent").html(resp);
				$("#cartcontent").fadeIn("slow");		
			}
		});
	});

	$(".buytype").live("change", function() {
		$("#cartcontent").fadeOut("fast");

		var url = '/cart-buytype/'+$(this).attr("id").replace("p",'')+'/'+$(this).val();

		$.ajax({
			url: url,
			method: "get",
			success: function(resp) {
				$("#cartcontent").html(resp);
				$("#cartcontent").fadeIn("slow");		
			}
		});		
	});

	$("#step3").click(function() {
		
		$.ajax({
			url: "/cart/delivery",
			method: "post",
			data: {name: $("#name").val(), surname: $("#surname").val(), address: $("#address").val(), post: $("#post").val()}
		});	

		return false;
	});


	$("#nakup").click(function() {
		var payment = false;

		for (var i=0; i < document.paymentform.payment.length; i++) {
		   if (document.paymentform.payment[i].checked) {
		      payment = document.paymentform.payment[i].value;
		   }
		}

		if(payment) {
			switch (payment.toLowerCase()) {
				case 'upn':
					$("#nakup").attr("href", "/nakup/upn");
					break;
				case 'moneta':
					$("#nakup").attr("href", "/nakup/moneta");
					break;
				case 'account':
					$("#nakup").attr("href", "/nakup/account");
					break;
			}
		} else {
			$("#payment-err").html("SELECT_PAYMENT_TYPE");
			$("#payment-err").show();
			return false;
		}
	});

	$("#step2").click(function() {
		$("#delivery-form").submit();
		return false;
	});
});