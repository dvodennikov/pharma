function SearchSelect(id, url, options = {}) {
  if (!id) {
	  console.log('Search select error: no id specified for select container');
	  
	  return null;
  }
  
  this.id = id;
  
  if (!url) {
	  console.log('Search select error: no URL specified for AJAX query');
	  
	  return null;
  }
  
  this.url = url;
  
  this.class = '';
  if (options.class)
    if (typeof options.class === 'string')
      this.class = options.class;
  
  this.searchParam = 'search';
  if (options.searchParam) 
	if (typeof options.searchParam === 'string')
	  this.searchParam = options.searchParam;
  
  this.minCharsSearch = 3;
  if (options.minCharsSearch)
    if ((typeof options.minCharsSearch ==='number') && 
        (options.minCharsSearch > 0) &&
        (options.minCharsSearch < 10)) {
	  this.minCharsSearch = options.minCharsSearch;
    } else if ((typeof options.minCharsSearch === 'string') && 
               (match = options.minCharsSearch.match(/\d/))) {
      this.minCharsSearch = match;
    }
    
  this.searchDelay = 0;
  if (options.searchDelay)
    if ((typeof options.searchDelay === 'number') &&
        (options.searchDelay >= 0)) {
	  this.searchDelay = options.searchDelay;
	} else if ((typeof options.searchDelay === 'string') &&
               (match = options.searchDelay.match(/\d+/))) {
	  this.searchDelay = match;
	}
  
  this.container = $("#" + id);
  this.label = $('<label for="search-' + this.id + '">' + (('label' in options)?options.label:'') + '</label>');
  this.input = $('<input id="search-' + this.id + '" name="' + (('fieldName' in options)?options.fieldName:id) + 
                 '" type="hidden"' + (('value' in options)?(' value="' + options.value + '"'):'') + '>');

  this.select = '<div class="search-select">';
  this.select += '<div class="selected-value-container ' + this.class + '">' + 
                 (('caption' in options)?options.caption:'Choose option') + 
                 '</div>';
  this.select += '<div class="select-list">';
  this.select += '<div class="search-field-container"><span class="search-field-close-button">x</span>' +
                 '<input class="search-field" type="text" placeholder="' + 
                 (('searchHint' in options)?options.searchHint:'Search') + 
                 '"></input></div>';
  this.select += '<div class="values-list-container"><ul class="search-select-list">';
  
  if (Array.isArray(options.values)) {
    for (i = 0; i < options.values.length; i++) {
      if (('value' in options.values[i]) && ('title' in options.values[i]))
        this.select += '<li data-value="' + options.values[i].value + '">' + options.values[i].title + '</li>';
    }
  }
  
  this.select += '</ul></div>';
  this.select += '</div>';//select-list end
  this.select = $(this.select + '</div>');
  this.selectList = $(this.select).children('.select-list');
  this.selectedValueContainer = $(this.select).children('.selected-value-container');
  this.searchFieldContainer = $(this.selectList).children('.search-field-container');
  this.searchField = $(this.searchFieldContainer).children('.search-field');
  this.valuesListContainer = $(this.selectList).children('.values-list-container');

  $(this.selectList).css({
    'display': 'none',
    'position': 'absolute',
    'min-width': $(this.select).css('width')
  });
  
  this.mimic = function(mimic) {
	let copycat;
	let form = $(self.select).parents('form');
    if (typeof mimic === 'string') {
	  copycat = $(form).children(mimic).last();
	  
	  if ((typeof copycat !== 'object') ||
		  (copycat.length == 0)) {
	    console.log('no copycat found: ' + copycat);
	    //copycat = $('select').first();
	    //Use first select if no mimic found
	    let example = $('<select style="display: none"></select>');
	    let match;
		if (match = mimic.match(/\.([^.\s#]+)/)) {
		  $(example).addClass(match[1]);
		} else if (match = mimic.match(/\#([^.\s#]+)/)) {
		  $(example).attr('id', match[1]);
		}
		
		$(form).append(example);
		
	    copycat = self.mimic(true);
	  }
	} else if (mimic === true) {
		copycat = $(form).children('select').last();
		
		/*if ((typeof mimic !== 'object') ||
		    (mimic.length === 0)) {
		  $(self.select).parents('form').append('<select class="form-control" style="display: none"></select>');
		  
		  copycat = $('select').first();
		}*/
	} else {
	  $(self.select).addClass('search-select-default');
		
      return;

	}
	
	if ((typeof copycat === 'object') &&
	    (copycat.length > 0)) {
	  $(self.selectedValueContainer).css('margin', $(copycat).css('margin'));
	  $(self.selectedValueContainer).css('padding', $(copycat).css('padding'));
	  $(self.selectedValueContainer).css('color', $(copycat).css('color'));
	  $(self.selectedValueContainer).css('background', $(copycat).css('background'));
	  $(self.selectedValueContainer).css('font', $(copycat).css('font'));
	  $(self.selectedValueContainer).css('font-size', $(copycat).css('font-size'));
	  $(self.selectedValueContainer).css('border', $(copycat).css('border'));
	  $(self.selectedValueContainer).css('border-radius', $(copycat).css('border-radius'));
	} else {
	  $(self.select).addClass('search-select-default');
	}
  };
  
  $(this.container).append(this.label);
  $(this.container).append(this.input);
  $(this.container).append(this.select);
  let self = this;

  if ('mimic' in options)
    this.mimic(options.mimic);

  this.hideSearchField = function() {
    if (!$(self.select).hasClass('active'))
      return;

    $(self.select).removeClass('active');
    $(self.selectList).css('display', 'none');
  };


  this.showSearchField = function() {
    if ($(self.select).hasClass('active'))
      return;

    $(self.select).addClass('active');
    $(self.selectList).css('display', 'block');
    $(self.selectList).css('width', $(self.select).css('width'));
    $(self.searchField).focus();
  };

  this.toggleShowSelect = function(e) {
	e.stopPropagation();
	
    if ($(self.select).hasClass('active')) {
      self.hideSearchField();
    } else {
      self.showSearchField();
    }
  }

  this.selectOption = function(e) {
	e.stopPropagation();

    self.input.attr('value', $(this).attr('data-value'));
    self.selectedValueContainer.html($(this).html());
    self.hideSearchField();
    $(this).siblings().removeClass('active');
    $(this).addClass('active');
  }
  
  this.search = function () {
    $.ajax({
      url: self.url + '?' + self.searchParam + '=' + $(self.searchField).val(),
      method: 'GET',
      dataType: 'json',
      contentType: 'application/json'
    }).done(function(data) {
      html = '';
      for (i = 0; i < data.values.length; i++) {
        html += '<li data-value=' + data.values[i].value + '>' + data.values[i].title + '</li>';
      }

      $(self.valuesListContainer).find('ul').html(html);
      $(self.valuesListContainer).find('li').on('click', self.selectOption);
    }).fail(function(jqHRX, textStatus) {
      console.log('AJAX request failed: ' + textStatus);
    }).always(function() {
      console.log('AJAX request done');
    });
  }

  $(this.selectedValueContainer).on('click', this.toggleShowSelect);
  $(this.searchFieldContainer).find('.search-field-close-button').on('click', this.hideSearchField);
  $(this.valuesListContainer).find('li').on('click', this.selectOption);

  $(this.searchField).on('input', function(e) {
	e.preventDefault();
    
    if ($(self.searchField).val().length >= self.minCharsSearch) {
	  let searchString = $(self.searchField).val();
	  setTimeout(function() {
		if ($(self.searchField).val() == searchString)
          self.search();
      }, self.searchDelay);
    }
  });
  
  $(this.searchField).on('keydown', function(e) {
    if (e.key === "Escape") {
	  self.hideSearchField();
	  
	  return;
	}
  });
  
  $(document).children('html').on('click', function(e) {
	if ($(e.target).parent('#' + self.id).length === 0)
	  if ($(self.select).hasClass('active'))
	    self.hideSearchField();
  });
  
  //prevent closing selectList on it's or childs click
  $(this.selectList).on('click', function(e) {
	e.stopPropagation();
  });

  return this;
}

