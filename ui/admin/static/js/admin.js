function init() {
  initManagersGrid();
}

function initManagersGrid() {
  var templates = {
    addManagerDialog: Handlebars.compile( $('#managers-add-dialog').html() )
  };

  console.log(templates)

  var $grid = $('#managers-grid');

  $grid.on('click', '.mg-remove', function() {
    $(this).parent('tr').remove();
  });

  $grid.on('dblclick', 'tbody tr', function() {
    var $row = $(this);
    var _id = $row.data('_id');

    $.getJSON('/admin/managers/' + _id).then(function(data) {
      data.edit = true;
      var html = templates.addManagerDialog(data);
      $(html).modal();
    }).fail(function() {
      // alert('fail');
    });
  });

  $('#add-manager-btn').click(function() {
    var html = templates.addManagerDialog();
    $(html).modal();
  });
}
