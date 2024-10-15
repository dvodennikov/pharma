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
  
  this.container = $("#" + id);
  this.input = $('<input name="' + (('fieldName' in options)?options.fieldName:id) + '" type="hidden">');

  console.log(options);
  this.select = '<div class="search-select">';
  this.select += '<div class="selected-value-container">' + (('selectHint' in options)?options.selectHint:'Choose option') + '</div>';
  this.select += '<div class="select-list">';
  this.select += '<div class="search-field-container"><input class="search-field" type="text" placeholder="' + 
                 (('searchHint' in options)?options.searchHint:'Search') + 
                 '"></input><span class="search-field-close-button">x</span></div>';
  if (Array.isArray(options.values)) {
    this.select += '<div class="values-list-container"><ul class="search-select-list">';
    for (i = 0; i < options.values.length; i++) {
      if (('value' in options.values[i]) && ('title' in options.values[i]))
        this.select += '<li data-value="' + options.values[i].value + '">' + options.values[i].title + '</li>';
    }
    this.select += '</ul></div>';
  }
  this.select += '</div>';//select-list end
  this.select = $(this.select + '</div>');
  this.selectList = $(this.select).children('.select-list');
  this.selectedValueContainer = $(this.select).children('.selected-value-container');
  this.searchFieldContainer = $(this.selectList).children('.search-field-container');
  this.searchField = $(this.searchFieldContainer).children('.search-field');
  this.valuesListContainer = $(this.selectList).children('.values-list-container');

  /*$(this.searchFieldContainer).css({
    'display': 'none',
    'position': 'absolute',
    //'left': $(this.select).position().left,
    //'top': ($(this.select).position().top - $(this.select).height() - 10),
    'min-width': $(this.select).width()
  });*/
  $(this.selectList).css({
    'display': 'none',
    'position': 'absolute',
    'min-width': $(this.select).width()
  });
  console.log('top' + $(this.select).position().top + ' - ' + $(this.select).height());
  $(this.container).append(this.input);
  $(this.container).append(this.select);
  self = this;

  this.hideSearchField = function() {
    console.log(!$(self.select).hasClass('active'));
    if (!$(self.select).hasClass('active'))
      return;

    $(self.select).removeClass('active');
    //$(self.searchFieldContainer).css('display', 'none');
    $(self.selectList).css('display', 'none');
  };


  this.showSearchField = function() {
    if ($(self.select).hasClass('active'))
      return;

    $(self.select).addClass('active');
    //$(self.searchFieldContainer).css('display', 'block');
    $(self.selectList).css('display', 'block');
    console.log(self.select);
    console.log('catch onFocus');
  };

  this.toggleShowSelect = function() {
    if ($(self.select).hasClass('active')) {
      self.hideSearchField();
    } else {
      self.showSearchField();
    }
  }

  this.selectOption = function() {
    self.input.attr('value', $(this).attr('data-value'));
    self.selectedValueContainer.html($(this).html());
    self.hideSearchField();
    $(this).siblings().removeClass('active');
    $(this).addClass('active');
  }

  //$(this.select).on('focusin', this.showSearchField);
  $(this.selectedValueContainer).on('click', this.toggleShowSelect);
  //$(this.select).on('focusout', this.hideSearchField);
  $(this.searchFieldContainer).find('.search-field-close-button').on('click', this.hideSearchField);
  //$(window).on('click', function(e) { if ($(e.target).parent('#custom-select').first()) { self.hideSearchField; }});
  $(this.valuesListContainer).find('li').on('click', this.selectOption);

  $(this.searchField).on('change', function() {
    console.log(this);
    $.ajax({
      url: 'http://pharma.localhost/searchselect/json.json',
      method: 'GET',
      dataType: 'json'
    }).done(function(data) {
      console.log(data);
      html = '';
      for (i = 0; i < data.values.length; i++) {
        html += '<li data-value=' + data.values[i].value + '>' + data.values[i].title + '</li>';
      }

      console.log(html);
      $(self.valuesListContainer).find('ul').html(html);
      $(self.valuesListContainer).find('li').on('click', self.selectOption);
    }).fail(function(jqHRX, textStatus) {
      console.log('AJAX request failed: ' + textStatus);
    }).always(function() {
      console.log('AJAX request done');
    });
  });

  return this;
}

