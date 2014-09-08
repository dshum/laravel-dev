LT.Order = function() {
	var object = {};

	object.moveUp = function() {
		$('select[name=list]').each(function() {
			if(this.selectedIndex > 0) {
				var i = this.selectedIndex;

				var t = this.options[i].text;
				this.options[i].text = this.options[i - 1].text;
				this.options[i - 1].text = t;

				var v = this.options[i].value;
				this.options[i].value = this.options[i - 1].value;
				this.options[i - 1].value = v;

				this.options[i].selected = false;
				this.options[i - 1].selected = true;

				var order1 = $('input:hidden[name="orderList['+this.options[i].value+']"]');
				var order2 = $('input:hidden[name="orderList['+this.options[i - 1].value+']"]');

				var v1 = order1.val();
				var v2 = order2.val();

				order1.val(v2);
				order2.val(v1);
			}
		});
	};

	object.moveDown = function() {
		$('select[name=list]').each(function() {
			if(this.selectedIndex < (this.options.length - 1) && this.selectedIndex != -1) {
				var i = this.selectedIndex;

				var t = this.options[i].text;
				this.options[i].text = this.options[i + 1].text;
				this.options[i + 1].text = t;

				var v = this.options[i].value;
				this.options[i].value = this.options[i + 1].value;
				this.options[i + 1].value = v;

				this.options[i].selected = false;
				this.options[i + 1].selected = true;

				var order1 = $('input:hidden[name="orderList['+this.options[i].value+']"]');
				var order2 = $('input:hidden[name="orderList['+this.options[i + 1].value+']"]');

				var v1 = order1.val();
				var v2 = order2.val();

				order1.val(v2);
				order2.val(v1);
			}
		});
	};

	object.moveFirst = function() {
		$('select[name=list]').each(function() {
			if(this.selectedIndex > 0) {
				for(var i = this.selectedIndex; i > 0; i--) {
					object.moveUp();
				}
			}
		});
	};

	object.moveLast = function() {
		$('select[name=list]').each(function() {
			if(this.selectedIndex > -1) {
				for(var i = this.selectedIndex; i < (this.options.length - 1); i++) {
					object.moveDown();
				}
			}
		});
	};

	return object;
}();

$(function() {
	$('#order-first').click(function(){
		LT.Order.moveFirst();
	});

	$('#order-up').click(function(){
		LT.Order.moveUp();
	});

	$('#order-down').click(function(){
		LT.Order.moveDown();
	});

	$('#order-last').click(function(){
		LT.Order.moveLast();
	});
	
	$('#orderForm').submit(function(event) {
		$.blockUI();

		$(this).ajaxSubmit({
			url: this.action,
			dataType: 'json',
			success: function(data) {
//				alert(data);
				if (data.logout) {
					document.location.href = LT.adminUrl;
				}
				$.unblockUI();
			}
		});
		
		event.preventDefault();
	});
});

