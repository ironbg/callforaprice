function checkIfEssentialInstallationChangesIsDoneCl()
{
    if (!$("#callforprice_settings_form").length) {
        return false;
    }

    if ($("input[name='changes_is_done']").val() == 1) {
        return false;
    }

    $.ajax({
        type: "POST",
        url: "index.php",
        dataType: 'json',
        data: {
            ajax: true,
            token: $('input[name=token_callforprice]').val(),
            controller: 'AdminCallForPrice',
            action: 'checkIfEssentialInstallationChangesIsDoneCl'
        },
        success: function (response) {
            if (response['installation_changes_remainder']) {
                $("body").append(response['installation_changes_remainder']);
                $("body").addClass("no-scroll");
            }
        },
        error: function() {
            alert('Ajax Request Has Failed!');
        }
    });
}

var CallforpriceSettingsCheckboxTable = {
    showAll: function(table_id) {
        $(table_id + " .checkbox_table").closest("tr").show();
    },
    showChecked: function(table_id) {
        var not_checked_manufacturers = table_id + " .checkbox_table:not(:checked)";

        $(not_checked_manufacturers).each(function() {
            $(this).closest("tr").hide();
        });
    },
    search: function(table_id, searched_value) {
        if (searched_value.length > 0) {
            var manufacturers_names = table_id + " tbody tr td label";
            $(manufacturers_names).each(function() {
                var search_item = $(this).text().trim();
                if (search_item.indexOf(searched_value) === -1) {
                    $(this).closest("tr").hide();
                } else {
                    $(this).closest("tr").show();
                }
            });
        } else {
            $(table_id + " tbody tr").show();
        }
    }
};

$(document).ready(function(){
    checkIfEssentialInstallationChangesIsDoneCl();

    var CodeMirrorActive = false;

    $(document).on('click', '#callforprice_settings_form .nav-tabs li a', function (e) {
        if ($(this).attr('href') == '#code_mirror' && !CodeMirrorActive) {
            var editor = CodeMirror.fromTextArea(document.getElementById("css_code"), {
                mode: "css",
                lineNumbers: "true",
                readOnly: false,
            });
            CodeMirrorActive = true;
        }
    });

    $(document).on("click", ".mpm-installation-changes-remainder > .close-btn", function() {
        $(".mpm-installation-changes-remainder-overlay").remove();
        $(".mpm-installation-changes-remainder").remove();
        $("body").removeClass("no-scroll");
    });

    $(document).on("click", ".manufacturer_list_callforprice #show_checked", function(e) {
        e.preventDefault();
        CallforpriceSettingsCheckboxTable.showChecked(".manufacturer_list_callforprice");
    });

    $(document).on("click", ".customer_group_list #show_checked", function(e) {
        e.preventDefault();
        CallforpriceSettingsCheckboxTable.showChecked(".customer_group_list");
    });

    $(document).on("click", ".manufacturer_list_callforprice #show_all", function(e) {
        e.preventDefault();
        CallforpriceSettingsCheckboxTable.showAll(".manufacturer_list_callforprice");
    });

    $(document).on("click", ".customer_group_list #show_all", function(e) {
        e.preventDefault();
        CallforpriceSettingsCheckboxTable.showAll(".customer_group_list");
    });

    $(document).on("propertychange change click keyup input paste", ".manufacturer_list_callforprice .search_checkbox_table", function(e) {
        CallforpriceSettingsCheckboxTable.search(".manufacturer_list_callforprice", $(this).val());
    });

    $(document).on("propertychange change click keyup input paste", ".customer_group_list .search_checkbox_table", function(e) {
        CallforpriceSettingsCheckboxTable.search(".customer_group_list", $(this).val());
    });

  $(document).on('click', '.btn-default-search', function(e){
    e.preventDefault();
    e.stopPropagation();

    var search_id = $('.item_one.key_list .search').val();
    var search_phone = $('.item_one.item_phone .search').val();
    var search_email = $('.item_one.item_email .search').val();
    var search_hour = $('.item_one.item_hour .search').val();
    var search_state = $('.item_one.item_state .search').val();
    var search_message = $('.item_one.item_message .search').val();
    var search_date = $('.item_one.item_date_add .search').val();
    var id_product = $('.item_one.item_id_product .search').val();
    var name = $('.item_one.item_name .search').val();
    var prod_name  = $('.item_one.item_prod_name .search').val();

    $.ajax({
      type: "POST",
      url: 'index.php?rand=' + new Date().getTime(),
      dataType: 'json',
      async: true,
      cache: false,
      data: {
        ajax	: true,
        token: $('input[name=token_callforprice]').val(),
        controller: 'AdminCallForPrice',
        fc: 'module',
        module : 'callforprice',
        action: 'search',
        search_id : search_id,
        search_phone : search_phone,
        search_email : search_email,
        search_hour : search_hour,
        search_state : search_state,
        search_message : search_message,
        search_date : search_date,
        id_product : id_product,
        name : name,
        prod_name : prod_name,
        p : 1,
      },
      beforeSend: function() {
        $('.progres_bar_ex').show();
      },
      success: function(json) {
        $('.progres_bar_ex').hide();
        if (json['error']) {

        }
        else{
          if(json['success']){

            $('.callBackList').replaceWith(json['success']);
          }
          else{
            $('.callBackList table tbody').remove();
          }

        }
      }
    });


  });



  $(document).on('click', '.callback_pagination a', function(e){
    e.preventDefault()

    var p = $(this).attr('href');

    var search_id = $('.item_one.key_list .search').val();
    var search_phone = $('.item_one.item_phone .search').val();
    var search_email = $('.item_one.item_email .search').val();
    var search_hour = $('.item_one.item_hour .search').val();
    var search_state = $('.item_one.item_state .search').val();
    var search_message = $('.item_one.item_message .search').val();
    var search_date = $('.item_one.item_date_add .search').val();
    var id_product = $('.item_one.item_id_product .search').val();
    var name = $('.item_one.item_name .search').val();
    var prod_name  = $('.item_one.item_prod_name .search').val();

    $.ajax({
      type: "POST",
      url: 'index.php?rand=' + new Date().getTime(),
      dataType: 'json',
      async: true,
      cache: false,
      data: {
        ajax	: true,
        token: $('input[name=token_callforprice]').val(),
        controller: 'AdminCallForPrice',
        fc: 'module',
        module : 'callforprice',
        action: 'pagination',
        p : p,
        search_id : search_id,
        search_phone : search_phone,
        search_email : search_email,
        search_hour : search_hour,
        search_state : search_state,
        search_message : search_message,
        search_date : search_date,
        id_product : id_product,
        prod_name : prod_name,
        name : name,
      },
      beforeSend: function() {
        $('.progres_bar_ex').show();
      },
      success: function(json) {
        $('.progres_bar_ex').hide();
        if (json['error']) {
          showErrorMessage(json['error']);
        }
        else{
          if(json['success']){

            $('.callBackList').replaceWith(json['success']);
          }
        }
      }
    });


  });
  $(document).on('click', '.item_one.item_delete i.delete', function(){

    var id = $(this).attr('data-id');

    $.ajax({
      type: "POST",
      url: 'index.php?rand=' + new Date().getTime(),
      dataType: 'json',
      async: true,
      cache: false,
      data: {
        ajax	: true,
        token: $('input[name=token_callforprice]').val(),
        controller: 'AdminCallForPrice',
        fc: 'module',
        module : 'callforprice',
        action: 'deleteItem',
        id : id,

      },
      success: function(json) {
        if (json['error']) {
          showErrorMessage(json['error']);
        }
        else{
          if(json['success']){
            $('.itemList.itemList_'+id).remove();
            showSuccessMessage(json['success']);
          }
        }
      }
    });
  });

  $(document).on('change', '.state_call_back', function(){

    var id = $(this).attr('data-id');
    var state = $(this).val();

    $.ajax({
      type: "POST",
      url: 'index.php?rand=' + new Date().getTime(),
      dataType: 'json',
      async: true,
      cache: false,
      data: {
        ajax	: true,
        token: $('input[name=token_callforprice]').val(),
        controller: 'AdminCallForPrice',
        fc: 'module',
        module : 'callforprice',
        action: 'changeState',
        id : id,
        state : state,

      },
      success: function(json) {
        if (json['error']) {
          showErrorMessage(json['error']);
        }
        else{
          if(json['success']){
            showSuccessMessage(json['success']);
          }
        }
      }
    });
  });




  if( $('.form-group input[name=show_delay]:checked').val() == '1' ){
    $('.form-group.delay_hidden').show();
  }
  else{
    $('.form-group.delay_hidden').hide();
  }

  $(document).on('change', '.form-group input[name=show_delay]', function(){
    if( $(this).val() == '1' ){
      $('.form-group.delay_hidden').show();
    }
    else{
      $('.form-group.delay_hidden').hide();
    }
  });

  showHideCallForPrice();

  $(document).on('change', 'input[name="products_category"], input[name="filter_by_manufacturer"]', function(){
    if ($(this).val() == 1) {
        $("#all_products_on").attr("checked", false);
        $("#all_products_off").attr("checked", true);
    }

    showHideCallForPrice();
  });

  $(document).on('change', 'input[name="all_products"]', function(){
    if ($(this).val() == 1) {
        $("#products_category_on, #filter_by_manufacturer_on").attr("checked", false);
        $("#products_category_off, #filter_by_manufacturer_off").attr("checked", true);
    }
    showHideCallForPrice();
  });

  $('input[name=price_value]').keyup(function(){
    showHideCallForPrice();
  });

  $('input[name=quantity_value]').keyup(function(){
    showHideCallForPrice();
  });

  $(document).on('click change', 'input[name=selection_type_price]', function(){
    $('.price .label_selection_type').removeClass('active');
    $(this).prev().addClass('active');
  });

  $(document).on('change', 'input[name=selection_type_quantity]', function(){
    $('.quantity .label_selection_type').removeClass('active');
    $(this).prev().addClass('active');
  });



  var customer_group_filter_switch = $('input[name=filter_by_customer_group]:checked').val();

  if (customer_group_filter_switch == 1) {
      $(".customer_group_list").show();
  } else {
      $(".customer_group_list").hide();
  }

  setTimeout(function() {
      if ($(".filter-by-customer-group-switch .switch-input").hasClass("-checked")) {
          $(".product-page .customer_group_list").show();
      } else {
          $(".product-page .customer_group_list").hide();
      }
  }, 2000);


  $(document).on("change", ".filter-by-customer-group-switch .switch-input", function() {
    if ($(this).hasClass("-checked")) {
        $(".product-page .customer_group_list").show();
    } else {
        $(".product-page .customer_group_list").hide();
    }
  });

  $(document).on("change", "input[name=filter_by_customer_group]", function() {
      if ($(this).val() == 1) {
          $(".customer_group_list").show();
      } else {
          $(".customer_group_list").hide();
      }
  });

  $(document).on("click", "#save_callforprice_item_settings", function(e) {
    e.preventDefault();
    var product_callforprice_container = "#product-callforprice";
    var input_values = {};
    
    $(product_callforprice_container + " input[type='text']").each(function() {
        input_values[$(this).attr("name")] = $(this).val();
    });
    
    $(product_callforprice_container + " textarea").each(function() {
      if ($(this).hasClass("rte") && tinymce.editors.length > 0) {
          input_values[$(this).attr("name")] = tinymce.get($(this).attr("id")).getContent();
      } else {
          input_values[$(this).attr("name")] = $(this).val();
      }
    });

    $(product_callforprice_container + " input[type='checkbox']").each(function() {
        if ($(this).parent().hasClass("switch-input")) {
          if ($(this).parent().hasClass("-checked")) {
            input_values[$(this).attr("name")] = 1;
          } else {
            input_values[$(this).attr("name")] = 0;
          }
        } else {
          if ($(this).is(":checked")) {
            input_values[$(this).attr("name")] = $(this).val();
          }
        }
    });
    
    var request_url = $(product_callforprice_container).data("admin-url") + '/index.php?rand=' + new Date().getTime();
    var id_product = $(product_callforprice_container).data("product-id");
    var token = $(product_callforprice_container).data("token");
    
    $.ajax({
      type: "POST",
      url: request_url,
      dataType: 'json',
      data: {
        ajax: true,
        controller: 'AdminCallForPrice',
        fc: 'module',
        module : 'callforprice',
        action: 'saveCallforpriceSettingsForSpecificProduct',
        id_product : id_product,
        token: token,
        input_values: JSON.stringify(input_values)
      },
      success: function(data) {
        if (data['success']) {
            showSuccessMessage(data['success']);
        } else if (data['error']) {
            showErrorMessage(data['error']);
        }
      },
      error: function() {
          showErrorMessage('Callforprice Ajax request has failed.');
      }
    });
  });
  
  $(document).on("click", "#delete_callforprice_item_settings", function(e) {
      e.preventDefault();
    
      var product_callforprice_container = "#product-callforprice";
      var request_url = $(product_callforprice_container).data("admin-url") + '/index.php?rand=' + new Date().getTime();
      var id_product = $(product_callforprice_container).data("product-id");
      var token = $(product_callforprice_container).data("token");
    
      $.ajax({
          type: "POST",
          url: request_url,
          dataType: 'json',
          data: {
              ajax: true,
              controller: 'AdminCallForPrice',
              fc: 'module',
              module : 'callforprice',
              action: 'deleteCallforpriceItemSettings',
              id_product : id_product,
              token: token
          },
          success: function(data) {
              if (data['success']) {
                  showSuccessMessage(data['success']);
                  window.location.reload(false);
              } else if (data['error']) {
                  showErrorMessage(data['error']);
              }
          },
          error: function() {
              showErrorMessage('Callforprice Ajax request has failed.');
          }
      });
  });
});

function showHideCallForPrice() {
  var cat = $('input[name=products_category]:checked').val();
  var manufacturer_filter_switch = $('input[name=filter_by_manufacturer]:checked').val();
  var prod = $('input[name=all_products]:checked').val();
  var price = $('input[name=price_value]').val();
  var quantity = $('input[name=quantity_value]').val();

  if(cat == 1){
    $('.block_settings_cat').show();
  } else{
    $('.block_settings_cat').hide();
  }

  if (manufacturer_filter_switch == 1) {
      $('.manufacturer_list_callforprice').show();
  } else {
      $('.manufacturer_list_callforprice').hide();
  }

  if(prod == 1 || price !== '' || quantity !== '' || cat == 1 || manufacturer_filter_switch == 1){
    $('.block_settings_general, .block_settings_line').show();
  } else{
    $('.block_settings_general, .block_settings_line').hide();
  }
}

function showSuccessMessage(msg) {
  $.growl.notice({ title: "", message:msg});
}

function showErrorMessage(msg) {
  $.growl.error({ title: "", message:msg});
}

function showNoticeMessage(msg) {
  $.growl.notice({ title: "", message:msg});
}

$(document).on("click", ".AdminCallForPrice .nav-tabs a[href='#support']", function(e) {
    var url = $('.support_url').val();
    var win = window.open(url, '_blank');
    win.focus();
    $(".AdminCallForPrice .nav-tabs a[href=#general]").click();
});


