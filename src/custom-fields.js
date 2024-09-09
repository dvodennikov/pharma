import './site.css';

$('xxxxxxxxxxxxxxbutton#add-custom-fields').click(function(e) {
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

$('button#add-custom-fields').click(function(e) {
	e.preventDefault();
	addItem('#custom-fields', '/document-type/get-new-custom-field?', 'button.delete-custom-field');
})

$('button#add-drug').click(function(e) {
	e.preventDefault();
	drugId = $('select#receipt-drug-drug_id').val();
	quantity = $('input#receipt-drug-quantity').val();
	console.log(drugId);
	addItem('#drugs', '/receipt/get-new-drug?drug_id=' + drugId + '&quantity=' + quantity, 'button.delete-drug');
})

function addItem(container, url, deleteButton) {
	//e.preventDefault();
	var count = $(container).children('div.control-group').length;
	
	$.ajax({
		url: url + '&idx=' + count,
	}).done(function(html) {
		console.log(html);
		var customFields = $(container);
		$(customFields).append(html);
		
		$(customFields).find(deleteButton).last().click(function(e) {
			e.preventDefault();
			
			$(this).parent('div.control-group').remove();
			updateIndex(customFields);
		});
		
		updateIndex(customFields);
	});

}

$('select.document-type').on('change', function() {
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

function getSelectOptions(url, select) {
	$.ajax({
		url: url
	}).done(function(html) {
		console.log(html);
		var personList = $(select);
		$(personList).html(html);
	});
}

$('#drugs').find('button.delete-drug').on('click', function(e) {
	e.preventDefault();
	$(this).parent('div.control-group').detach();
	updateIndex('#drugs');
})

$('#custom-fields').find('button.delete-custom-field').on('click', function(e) {
	e.preventDefault();
	$(this).parent('div.control-group').detach();
	updateIndex('#custom-fields');
})

$('#person_search').on('change', function() {
	getSelectOptions('/receipt/get-persons?person_search=' + this.value + '&' + window.location.search.substr(1), 'select#receipt-person_id');
})

$('#drug_search').on('change', function() {
	getSelectOptions('/receipt/get-search-drugs?drug_search=' + this.value + '&' + window.location.search.substr(1), 'select#receipt-drug-drug_id');
})
