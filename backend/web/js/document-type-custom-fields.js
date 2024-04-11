$('button#add-custom-fields').click(function(e) {
	e.preventDefault();
	var count = $('#custom-fields').children('div.control-group').length;
	
	$.ajax({
		url: '/document-type/get-new-custom-field?idx=' + count,
	}).done(function(html) {
		console.log(html);
		var customFields = $('div#custom-fields');
		$(customFields).append(html);
		
		$(customFields).find('button.delete-custom-field').last().click(function(e) {
			e.preventDefault();
			
			$(this).parent('div.control-group').remove();
			updateIndex(customFields);
		});
		
		updateIndex(customFields);
	});
})

$('select.document-type').on('change', function() {
	console.log('on change');
	$.ajax({
		url: '/document/get-custom-fields?id=' + this.value
	}).done(function(html) {
		console.log(html);
		var customFields = $('div#custom-fields');
		$(customFields).children('div').detach();
		$(customFields).append(html);
	});
})

function updateIndex(customFields) {
	$(customFields).find('div.control-group').each(function(idx) {
			
			$(this).find('input[type="text"]').each(function() {
				updatePropertyIndex(this, 'id', idx);
				updatePropertyIndex(this, 'name', idx);
			});
			
			$(this).find('label').each(function() {
				updatePropertyIndex(this, 'for', idx);
			});
		});
}

function updatePropertyIndex(element, property, idx) {
	console.log(idx + ': ' + $(element).prop(property));
	$(element).prop(property, $(element).prop(property).replace(/\[\d+\]/, '[' + idx + ']'));
}
