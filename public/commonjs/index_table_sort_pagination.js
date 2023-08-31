function setSearchBy(val){
    $("#search_by").val(val)
  }
  $(document).ready(function(){
    $(document).on('focus','#search',function(event){
       $("#dropdown-menu").slideDown()
      });
      $(document).on('blur','#search',function(event){
       $("#dropdown-menu").slideUp()
      });

      $(document).on('click','.pagination a',function(event){
        var column_name = $('#hidden_column_name').val();
         var sort_type = $('#hidden_sort_type').val();
         var page = $('#hidden_page').val();
         var search_by = $("#search_by").val();
          event.preventDefault();
          $('li').removeClass('active');
          $(this).parent('li').addClass('active');
          var url = $(this).attr('href');
          var page = $(this).attr('href').split('page=')[1];
          getData(page, sort_type,column_name,'',search_by)
      });
  });
  var xhr = null;
  function getData(page, sort_type, sort_by, query='',search_by) {
    if(xhr !== null){
      xhr.abort();
      xhr = null;
 }    
     xhr= $.ajax({
          url : '?page=' +page+"&sortby="+sort_by+"&sorttype="+sort_type+"&query="+query+"&search_by="+search_by,
          type : 'get',
          dataType : 'html',
      }).done(function(data){
       
          $('#tbody').empty().html(data);
          location.hash = page;
      }).fail(function(jqXHR,ajaxOptions,thrownError){
         // alert('No response from server');
      });
  }
  function clear_icon()
{
$('#category_icon').html('');
$('#name_icon').html('');
}
$(document).on('keyup', '#search', function(){
    var query = $('#search').val();
   
    var column_name = $('#hidden_column_name').val();
    var sort_type = $('#hidden_sort_type').val();
    var page = $('#hidden_page').val();
    var search_by = $("#search_by").val();
    if(column_name!==undefined)
    getData(page, sort_type, column_name, query,search_by);
    
  });

$(document).on('click', '.sorting', function(){
var column_name = $(this).data('column_name');
var order_type = $(this).data('sorting_type');

var reverse_order = '';
if(order_type == 'asc')
{
  $(this).data('sorting_type', 'desc');
  reverse_order = 'desc';
//  == clear_icon();
  $('#'+column_name+'_icon').html('<i class="fa fa-angle-down"></i>');
}
if(order_type == 'desc')
{
  $(this).data('sorting_type', 'asc');
  reverse_order = 'asc';
 // clear_icon
  $('#'+column_name+'_icon').html('<i class="fa fa-angle-up"></i>');
}
  $('#hidden_column_name').val(column_name);
  $('#hidden_sort_type').val(reverse_order);
  var page = $('#hidden_page').val();
  var query = $('#serach').val();
  var search_by = $("#search_by").val();
  if(column_name!==undefined)
       getData(page, reverse_order, column_name, query,search_by);
});
