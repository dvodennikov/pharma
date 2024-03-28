$('button#add-custom-fields').click(function(e) {
	e.preventDefault();
	var count = $('#custom-fields').children('div.control-group').length;
	
	$.ajax({
		url: '/document-type/get-custom-fields?idx=' + count,
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
