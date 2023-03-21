$(document).ready(function() {
	var tags = $('#tags');
  var tags2 = $('#tags2');
	var tableDT = $('#example').DataTable({
		dom: 'lfrtip',
    //sDom: '', // Hiding the datatables ui
		//bLengthChange: false,
    ordering: false,
		oLanguage: {
			sUrl: "https://cdn.datatables.net/plug-ins/1.10.15/i18n/English.json"
		},
		initComplete: initFilter
	});

	function initFilter() {
      var list = [];
      var list2 = {};
      var tableTag = tableDT.column(3);
      //console.log(tableTag.data());
    // get tags
      tableTag.data().each(function(value, index) {
        var re = /[$,]/;
        if ((re).test(value)) { 
          var val = value.split(re); //
           //console.log(val);
           //list.concat(val)
          list.push(val);
        } else {
          list.push(value);
        }
      }); 
      console.log(list);
    // count tags
      list.forEach(function(x) {
         list2[x] = (list2[x] || 0) + 1;
      });
      var list = []; // set null   
      // push data
      Object.keys(list2).forEach(function(key){
          list.push({city: key, count: list2[key]});
      });
      // sort data
      list.sort(function(a,b) {return (b.count > a.count) ? 1 : ((a.count > b.count) ? -1 : 0);} ); 
      // build <select>
      $.each(list, function(key, value) {   
        var $value = value.city, $text = value.city +' ('+value.count+')';
        tags.append( $("<option></option>").attr("value", $value).text($text) ); 
      });
      // change <select>
      tags.on('change', function() {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        tableTag
          .search( val ? '^'+val+'$' : '', true, false )
          .draw();
       });
      // build <li>
      $.each(list, function(key, value) {   
        var $value = value.city, $text = value.city +' ('+value.count+')';
        tags2.append( 
          $("<li><a href='#' data-value='"+$value+"'>"+$text+"</a></li>")
        );
      });
    tags2.find('a').on( 'click', function (e) {
        e.preventDefault();
        var val = $.fn.dataTable.util.escapeRegex($(this).attr('data-value'));
         tableTag
          .search( val ? '^'+val+'$' : '', true, false )
          .draw();
    } );

    $('#btToggleDisplay').on('click', function () {
        $("#example").toggleClass('cards')
        //$("#example thead, #example tfoot").toggle()
    })
    
  } //initTag


});