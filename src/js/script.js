function def_panel_delete(obj, e, def_panel_id, type) {
    e.preventDefault();
    var title = $(obj).closest('.card').find('.panel-title').text();
    var group_id = $('select[name="group"]').val();
    Swal.fire({
        title: 'Warning!',
        html: 'Are you sure you want to permanently delete the panel:' + title,
        icon: 'warning',
        confirmButtonText: 'YES',
        cancelButtonText: 'NO',
        showCancelButton: true,
        showConfirmButton: true,
        allowOutsideClick: false,
        timerProgressBar: false
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "POST",
                url: "store_ajax_panel.php",
                data: {
                    'ajax_action': 'delete_panel',
                    'def_panel_id': def_panel_id,
                    'type': type,
                    'group_id': group_id,
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        $(obj).closest('.card').remove();
                        Swal.fire({
                            title: 'Delete!',
                            html: response.message,
                            icon: 'success',
                            showCancelButton: false,
                            timer: 5000,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timerProgressBar: false
                        });

                    } else {
                        Swal.fire({
                            title: 'Error!',
                            html: response.message,
                            icon: 'warning',
                            showCancelButton: false,
                            timer: 5000,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timerProgressBar: false
                        });
                    }

                }
            });
        } else if (result.isDenied) {
            console.log('deny');
        }
    })

};
//$(function() {
    var advert = '';
    var deleteKey = '';

    var voteData = [];
    // var url = window.location.origin;
    $.getJSON(url + '/votedata.json?version=' + new Date().getTime()).done(
        function(json) {
            voteData = json;
        }
    );

    function setAdvertValue() {
        advert = $('#advert').summernote('code');
        if (advert == '' || advert == '<p><br></p>') {
            advert = defaultAdvert;
        }
    }

    // $(".tbl").DataTable({
    //     "responsive": true ,
    //     "autoWidth": false ,
    // });

    $(document).ready(function() {
        $('select[name="group"]').on('change', function() {
            // Save selected dashboard in storage
            localStorage.setItem('selected_dashboard', $(this).val());
            load_group_wise_panel($(this).val());
        })
        var selectedDashboard = localStorage.getItem('selected_dashboard');
        if (selectedDashboard !== null)
            $('select[name="group"]').val(selectedDashboard);
        load_group_wise_panel(selectedDashboard !== null?
                              selectedDashboard: 0);

        function load_group_wise_panel(group_id = 0) {
            $('body').find('#panel_section').html('');
            $('body').find('#default_panel').html('');
            $.ajax({
                type: "POST",
                url: "store_ajax_panel.php",
                data: {
                    'ajax_action': 'dashboard_panel',
                    'group_id': group_id,
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        if (response.default_panel_html != '')
                            $('body').find('#default_panel').html(response.default_panel_html);
                        if (response.panel_html != '')
                            $('body').find('#panel_section').html(response.panel_html);
                        var elements = $('div.panelinfo');
                        $.each(elements, function(i, ele) {
                            $(ele).mouseenter(function() {
                                //$(this).dimBackground();
                            })
                            .mouseleave(function() {
                                //$(this).undim();        // Note: We could also use `$.undim();`
                            });
                        });
                        var successElements = $('div.panelsuccess');
                        $.each(successElements, function(i, ele) {
                            $(ele).mouseenter(function() {
                               // $(this).dimBackground();
                            })
                            .mouseleave(function() {
                               // $(this).undim();        // Note: We could also use `$.undim();`
                            });
                        });
                        panel_reload_stream(response);
                        if (group_id != 0)
                            Swal.fire({
                                title: 'Searching For Posts',
                                html: 'Please Wait',
                                timer: 5000,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                timerProgressBar: true
                            });
                    }
                }
            });
        }


        jQuery(document).on('click', '.refresh', function() {
            event.preventDefault();
            $(this).find('i').removeClass('fa-solid');
            $(this).find('i').removeClass('fa-arrows-rotate');
            $(this).find('i').addClass('fas');
            $(this).find('i').addClass('fa-sync');
            $(this).find('i').addClass('fa-spin');
            var obj = $(this);
            var panel = $(this).closest('.input-group-append').find('.cp').val();
            var type = $(this).closest('.input-group-append').find('.cp-type').val();
            var group_id = $('body').find('select[name="group"]').val();
            $.ajax({
                type: "POST",
                url: "store_ajax_panel.php",
                data: {
                    'ajax_action': 'reload_panel',
                    'group_id': group_id,
                    'type': type,
                    'panel': panel,
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 'success') {
                        $('#' + response.panel).find('.social-stream').html('');
                        panel_reload_stream(response, false, true);
                        obj.find('i').addClass('fa-solid');
                        obj.find('i').addClass('fa-arrows-rotate');
                        obj.find('i').removeClass('fas');
                        obj.find('i').removeClass('fa-sync');
                        obj.find('i').removeClass('fa-spin');
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            html: response.message,
                            icon: 'warning',
                            showCancelButton: false,
                            timer: 5000,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timerProgressBar: false
                        });
                    }

                }
            });
        });

        jQuery(document).on('click', '.delete-group', e => {
            e.preventDefault();
            var group_id = $('body').find('select[name="group"]').val();
            var title = $('select[name="group"] option:selected').text();
            Swal.fire({
                title: 'Warning!',
                html: 'Are you sure you want to permanently delete the group:' + title,
                icon: 'warning',
                confirmButtonText: 'YES',
                cancelButtonText: 'NO',
                showCancelButton: true,
                showConfirmButton: true,
                allowOutsideClick: false,
                timerProgressBar: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "store_ajax_panel.php",
                        data: {
                            'ajax_action': 'delete_group',
                            'group_id': group_id
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.status == 'success') {
                                $('select[name="group"] option[value="' + group_id + '"]').remove();
                                load_group_wise_panel($('body').find('select[name="group"]').val());

                            } else if (response.status == 'default_group') {
                                Swal.fire({
                                    title: 'Sorry!',
                                    html: response.message,
                                    icon: 'warning',
                                    showCancelButton: false,
                                    timer: 5000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timerProgressBar: false
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    html: response.message,
                                    icon: 'warning',
                                    showCancelButton: false,
                                    timer: 5000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timerProgressBar: false
                                });
                            }

                        }
                    });
                }
            })
        });
        jQuery(document).on('click', '#btn_preview', e => {
            e.preventDefault();
            var inputError = false;
            var stype = $("select[name='stype[]']")
                .map(function() {
                    if (!$(this).val()) {
                        Swal.fire({
                            title: 'Warning!',
                            html: 'Please select type!',
                            icon: 'warning',
                            showCancelButton: false,
                            timer: 5000,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timerProgressBar: false
                        });
                        $(this).focus();
                        inputError = true;
                        return true;
                    }
                    return $(this).val();
                })
                .get();
            if (inputError)
                return true;
            var networks = $("select[name='network[]']")
                .map(function(index, el) {
                    if (!$(this).val() && stype[index] !== 'csv' && 
                        stype[index] !== 'rss' &&
                        stype[index] !== 'bookmark' &&
                        stype[index] !== 'email') {
                        Swal.fire({
                            title: 'Warning!',
                            html: 'Please select network!',
                            icon: 'warning',
                            showCancelButton: false,
                            timer: 5000,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timerProgressBar: false
                        });
                        $(this).focus();
                        inputError = true;
                        return true;
                    }
                    return $(this).val();
                })
                .get();
            if (inputError)
                return true;
            var keywords = $("input[name='keyword[]']")
                .map(function(index, el) {
                    if (!$(this).val() && stype[index] !== 'csv' && 
                        stype[index] !== 'rss' &&
                        stype[index] !== 'bookmark' &&
                        stype[index] !== 'email') {
                        Swal.fire({
                            title: 'Warning!',
                            html: 'Please enter keyword!',
                            icon: 'warning',
                            showCancelButton: false,
                            timer: 5000,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timerProgressBar: false
                        });
                        $(this).focus();
                        inputError = true;
                        return true;
                    }
                    return $(this).val();
                })
                .get();
            if (inputError)
                return true;
            var rssUrls = $("input[name='rss_urls[]']")
                .map(function(index, el) {
                    if (!$(this).val() && (stype[index] === 'rss' || stype[index] === 'bookmark' || stype[index] === 'email')) {
                        Swal.fire({
                            title: 'Warning!',
                            html: stype[index] === 'rss'? 'Please enter valid rss feed!': (stype[index] === 'bookmark'? 'Please import bookmarks first!': 'Please import emails first!'),
                            icon: 'warning',
                            showCancelButton: false,
                            timer: 5000,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            timerProgressBar: false
                        });
                        $(this).focus();
                        inputError = true;
                        return true;
                    }
                    return $(this).val();
                })
                .get();
            if (inputError)
                return true;
            var full_text_feed = $("input[name='full_text_feed[]']")
                .map(function() {

                    if ($(this).is(':checked')) {
                        return 'YES';
                    }
                    return 'NO';
                })
                .get();
            var schedule = $("input[name='schedule[]']")
                .map(function() {

                    if ($(this).is(':checked')) {
                        return 1;
                    }
                    return 0;
                })
                .get();

            var group_id = $('select[name="group"]').val();
            var panel_action = $('#panel-action').val();
            var panel_title = $('#panel-name-title').val();
            if (!panel_action) {
                Swal.fire({
                    title: 'Warning!',
                    html: 'Please select an option first and enter panel name!',
                    icon: 'warning',
                    showCancelButton: false,
                    timer: 5000,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: false
                });
                return true;
            }

            // Create form data to send in ajax
            var formData = new FormData();
            $('[name="csv_file[]"]').map(function () {
                formData.append('csv_file[]', this.files[0]);
            });
            formData.append('title', panel_title);
            networks.map(value => formData.append('networks[]', value));
            keywords.map(value => formData.append('keywords[]', value));
            rssUrls.map(value => formData.append('rss_urls[]', value));
            stype.map(value => formData.append('stype[]', value));
            full_text_feed.map(value => formData.append('full_text_feed', value));
            schedule.map(value => formData.append('schedule[]', value));

            clearForm();
            self = e.target;
            /**
             *  Group store
             */
            if (panel_action == 'group_panel') {
                formData.set('ajax_action', 'store_group_with_panel');
                $.ajax({
                    type: "POST",
                    enctype: 'multipart/form-data',
                    url: "store_ajax_panel.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    // data: {
                    //     'ajax_action': 'store_group_with_panel',
                    //     'title': panel_title,
                    //     'networks': networks,
                    //     'keywords': keywords,
                    //     'stype': stype,
                    //     'full_text_feed': full_text_feed,
                    //     'schedule': schedule
                    // },
                    // dataType: "json",
                    beforeSend: function () {
                        $(self).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                    },
                    success: function(response) {
                        $(self).html('Go!')
                        response = JSON.parse(response);
                        if (response.status == 'success') {
                            if (response.displayMessage === true) {
                                Swal.fire({
                                    title: 'Warning!',
                                    html: response.message,
                                    icon: 'warning',
                                    showCancelButton: false,
                                    timer: 3000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timerProgressBar: true
                                }).then(() => {
                                    load_group_wise_panel(response.group_id);
                                    $('body').find('select[name="group"]').append($('<option>', { value: response.group_id, text: response.title }));
                                    $('body').find('select[name="group"]').val(response.group_id);
                                    localStorage.setItem('selected_dashboard', response.group_id);
                                });
                                return;
                            }
                            load_group_wise_panel(response.group_id);
                            // if (response.reloadFeed !== true) {
                            //     return true;
                            // }
                            $('body').find('select[name="group"]').append($('<option>', { value: response.group_id, text: response.title }));
                            $('body').find('select[name="group"]').val(response.group_id);
                            localStorage.setItem('selected_dashboard', response.group_id);
                        } else if (response.status == 'LIMIT_WARNING') {
                            Swal.fire({
                                title: 'Sorry!',
                                html: response.message,
                                icon: 'warning',
                                showCancelButton: false,
                                timer: 5000,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                timerProgressBar: false
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                html: response.message,
                                icon: 'error',
                                showCancelButton: false,
                                timer: 5000,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                timerProgressBar: false
                            });
                        }

                    }
                });

            }
            if (!panel_title) {
                clearForm();
                Swal.fire({
                    title: 'Enter Panel Name',
                    html: "Please enter Panel Name",
                    icon: 'warning',
                    showCancelButton: false,
                    timer: 3000,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timerProgressBar: false
                });
                return true;
            }
            var panels = $('body').find('.def-title');
            var panel_id = '';

            panels.each(function() {
                if (panel_title.trim().toUpperCase() == $(this).text().trim().toUpperCase()) {
                    panel_id = $(this).data('id');
                    return true;
                }
            });
            clearForm();
            if (panel_action == 'current_panel') {
                clearForm();
                if (panel_id != 0) {
                    $.ajax({
                        type: "POST",
                        url: "store_ajax_panel.php",
                        data: {
                            'ajax_action': 'store_default',
                            'title': panel_title,
                            'networks': networks,
                            'keywords': keywords,
                            'panel_id': panel_id,
                            'group_id': group_id,
                            'stype': stype,
                            'full_text_feed': full_text_feed,
                            'schedule': schedule
                        },
                        dataType: "json",
                        beforeSend: function () {
                            $(self).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                        },
                        success: function(response) {
                            $(self).html('Go!');
                            if (response.status == 'success') {
                                $('#' + response.panel).find('.social-stream').html('');
                                panel_reload_stream(response, true);
                                Swal.fire({
                                    title: 'Searching For Posts',
                                    html: 'Please Wait',
                                    timer: 5000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timerProgressBar: true
                                });
                            } else if (response.status == 'LIMIT_WARNING') {
                                Swal.fire({
                                    title: 'Sorry!',
                                    html: response.message,
                                    icon: 'warning',
                                    showCancelButton: false,
                                    timer: 5000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timerProgressBar: false
                                });
                            }
                        }
                    });
                } else {
                    formData.set('ajax_action', 'check_panel_store');
                    formData.set('group_id', group_id);
                    $.ajax({
                        type: "POST",
                        url: "store_ajax_panel.php",
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        // data: {
                        //     'ajax_action': 'check_panel_store',
                        //     'title': panel_title,
                        //     'networks': networks,
                        //     'keywords': keywords,
                        //     'group_id': group_id,
                        //     'stype': stype,
                        //     'full_text_feed': full_text_feed,
                        //     'schedule': schedule
                        // },
                        // dataType: "json",
                        beforeSend: function () {
                            console.log(self);
                            $(self).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                        },
                        success: function(response) {
                            $(self).html('Go!');
                            response = JSON.parse(response);
                            if (response.status == 'success') {
                                if (response.displayMessage) {
                                    Swal.fire({
                                        title: 'Warning',
                                        html: response.message,
                                        icon: 'warning',
                                        showCancelButton: false,
                                        timer: 3000,
                                        showConfirmButton: false,
                                        allowOutsideClick: false,
                                        timerProgressBar: true
                                    });
                                }
                                console.log($('#' + response.panel));
                                console.log($('#' + response.panel).find('.social-stream'));
                                $('#' + response.panel).find('.social-stream').html('');
                                panel_reload_stream(response, true);
                                // if (response.reloadFeed !== true)
                                //     return;
                                Swal.fire({
                                    title: 'Searching For Posts',
                                    html: 'Please Wait',
                                    timer: 5000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timerProgressBar: true
                                });
                            } else if (response.status == 'LIMIT_WARNING') {
                                Swal.fire({
                                    title: 'Sorry!',
                                    html: response.message,
                                    icon: 'warning',
                                    showCancelButton: false,
                                    timer: 5000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timerProgressBar: false
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    html: response.message,
                                    icon: 'warning',
                                    showCancelButton: false,
                                    timer: 5000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timerProgressBar: false
                                });
                            }
                        }
                    });

                }

            } else if (panel_action == 'new_panel') {
                clearForm();
                if (panel_id != '') {
                    Swal.fire({
                        title: 'Panel Exist',
                        html: response.message,
                        icon: 'warning',
                        showCancelButton: false,
                        timer: 5000,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        timerProgressBar: false
                    });
                    return true;
                }
                formData.set('ajax_action', 'store_panel');
                formData.set('group_id', group_id);
                $.ajax({
                    type: "POST",
                    url: "store_ajax_panel.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    // data: {
                    //     'ajax_action': 'store_panel',
                    //     'title': panel_title,
                    //     'networks': networks,
                    //     'keywords': keywords,
                    //     'stype': stype,
                    //     'group_id': group_id,
                    //     'full_text_feed': full_text_feed,
                    //     'schedule': schedule
                    // },
                    // dataType: "json",
                    beforeSend: function () {
                        $(self).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                    },
                    success: function(response) {
                        $(self).html('Go!');
                        response = JSON.parse(response);
                        if (response.status == 'success') {
                            if (response.displayMessage) {
                                Swal.fire({
                                    title: 'Warning',
                                    html: response.message,
                                    icon: 'warning',
                                    showCancelButton: false,
                                    timer: 3000,
                                    showConfirmButton: false,
                                    allowOutsideClick: false,
                                    timerProgressBar: true
                                });
                            }
                            if (response.html != '') {
                                $('#panel_section').prepend(response.html)
                            }
                            panel_reload_stream(response);
                            // if (response.reloadFeed !== true)
                            //     return;
                            Swal.fire({
                                title: 'Searching For Posts',
                                html: 'Please Wait',
                                timer: 5000,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                timerProgressBar: true
                            });
                        } else if (response.status == 'LIMIT_WARNING') {
                            Swal.fire({
                                title: 'Sorry!',
                                html: response.message,
                                icon: 'warning',
                                showCancelButton: false,
                                timer: 5000,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                timerProgressBar: false
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                html: response.message,
                                icon: 'warning',
                                showCancelButton: false,
                                timer: 5000,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                timerProgressBar: false
                            });
                        }

                    }
                });

            }
        });

        // jQuery(document).on('change', '#posts', e => {
        //     e.preventDefault();
        //     var rssfeed = socialStream.o.feeds.rss.id;
        //     var color = socialStream.o.style.colour;
        //     var limit = $('#posts').val();

        //     previewSocialStream(rssfeed, color, limit, voteData);
        // });

        $("#banner").on("summernote.change", function(e) {
            var html = $("#banner").summernote('code');
            $('#stream_banner').html(html);
        });

        jQuery(document).on('change', '#style', e => {
            e.preventDefault();
            var rssfeed = socialStream.o.feeds.rss.id;
            var limit = $('#posts').val();
            var color = $('#style').val();
            previewSocialStream(rssfeed, color, limit, voteData);
        });

        // jQuery(document).on('change', '.stype', e => {
        //     e.preventDefault();
        //     var rssfeed = socialStream.o.feeds.rss.id;
        //     var limit = $('#posts').val();
        //     var color = socialStream.o.style.colour;
        //     previewSocialStream(rssfeed, color, limit, voteData);
        // });

        jQuery(document).on('click', '#adv_preview', e => {
            e.preventDefault();
            var rssfeed = socialStream.o.feeds.rss.id;
            var limit = $('#posts').val();
            var color = socialStream.o.style.colour;
            previewSocialStream(rssfeed, color, limit, voteData);
        });
        jQuery(document).on('click', '.panel-option', function() {
            event.preventDefault();
            $('#panel-action').val($(this).data('action'));
            $(this).closest('.input-group').find('.dropdown-toggle').text($(this).text());
        });

    });

    function reset_panel_attachment(items) {
        $.each(items, function() {
            if ($(this)[0].panel_id != 'social_default_stream') {
                var panelBox = $('#' + $(this)[0].panel_id).closest('.card').clone().wrap('<div>').parent().html();
                $('#' + $(this)[0].panel_id).closest('.card').remove();
                $('body').find('#panel_section').prepend(panelBox);
            }
            return true;
        });
    }

    function panel_reload_stream(response, showInStart = false, reload = false) {
        if (response.def_items)
            attach_item_to_default_steam(response.def_items, reload);
        if (response.def_ad_items)
            attach_item_to_default_steam(response.def_ad_items, reload);
        if (response.dynamic_items)
            attach_item_to_default_steam(response.dynamic_items, reload);
        if (response.def_search_items) {
            if (showInStart) {
                showInStart = false;
                reset_panel_attachment(response.def_search_items);
            }
            attach_item_to_steam(response.def_search_items, reload);
        }
        // if (response.items) {
        //     if (showInStart) {
        //         showInStart = false;
        //         reset_panel_attachment(response.def_search_items);
        //     }
        //     attach_item_to_steam(response.items, reload);
        // }
        if (response.feeds) {
            attach_item_to_steam2(response.feeds, reload);
        }
        if (response.def_ad_kw) {
            if (showInStart) {
                showInStart = false;
                reset_panel_attachment(response.def_search_items);
            }
            attach_item_to_steam(response.def_ad_kw, reload);
        }
        if (response.dynamic_search_items) {
            if (showInStart) {
                showInStart = false;
                reset_panel_attachment(response.def_search_items);
            }
            attach_item_to_steam(response.dynamic_search_items, reload);
        }

    }

    function attach_item_to_steam(items, reload_item = false) {
        $.each(items, function() {
            if ($(this)[0].network_type == null) {
                return true;
            }
            if ($(this)[0].network_type != '' && $(this)[0].network_type != 'Leads') {
                var streamrss = 'https://suite.social/search/search-result.php?q=' + $(this)[0].keywork + '&site=' + $(this)[0].network_type.replace(' ', '+') + '&rss';
            } else if ($(this)[0].keywork != '') {
                var streamrss = 'https://suite.social/search/search-result.php?q="I+want+a+' + $(this)[0].keywork + '"+OR+"I+need+a+' + $(this)[0].keywork + '"+OR+"I+am+looking+for+a+' + $(this)[0].keywork + '"+OR+"I+am+seeking+a+' + $(this)[0].keywork + '"+OR+"recommend+a+' + $(this)[0].keywork + '"&site=leads&rss';
            }
            if ($(this)[0].is_full_text_feed == 1) {
                previewfullTextStream($(this)[0].html, $(this)[0].panel_id);
            } else
                previewSocialStream(streamrss, $(this)[0].color, $(this)[0].per_page_limit, $(this)[0].page_type, $(this)[0].keywork, $(this)[0].network_type, $(this)[0].panel_id, [], 0, reload_item);
        })
    }

    function attach_item_to_steam2(items, reload_item = false) {
        $.each(items, function() {
            // if ($(this)[0].network_type == null) {
            //     return true;
            // }
            // if ($(this)[0].network_type != '' && $(this)[0].network_type != 'Leads') {
            //     var streamrss = 'https://suite.social/search/search-result.php?q=' + $(this)[0].keywork + '&site=' + $(this)[0].network_type.replace(' ', '+') + '&rss';
            // } else if ($(this)[0].keywork != '') {
            //     var streamrss = 'https://suite.social/search/search-result.php?q="I+want+a+' + $(this)[0].keywork + '"+OR+"I+need+a+' + $(this)[0].keywork + '"+OR+"I+am+looking+for+a+' + $(this)[0].keywork + '"+OR+"I+am+seeking+a+' + $(this)[0].keywork + '"+OR+"recommend+a+' + $(this)[0].keywork + '"&site=leads&rss';
            // }
            var streamrss = $(this)[0].url;
            if ($(this)[0].is_full_text_feed == 1) {
                previewfullTextStream($(this)[0].html, $(this)[0].panel_id);
            } else
                previewSocialStream(streamrss, $(this)[0].color, /*$(this)[0].per_page_limit*/ Infinity, $(this)[0].page_type, $(this)[0].keywork, $(this)[0].network_type, $(this)[0].panel_id, [], 0, reload_item, $(this)[0].isNewsReader);
        })
    }

    function attach_item_to_default_steam(items, reload_item = false) {
        $.each(items, function(panel_id, el) {
            default_stream_load(el.stream, panel_id, el.out, el.twitterId, el.color, el.limit, 0, reload_item);
        })
    }

    function clearForm() {
        var form = $('body').find('#search_form');
        form.trigger("reset");
        form.find(".item").not(':first').remove();
        var action = $('body').find('#panel-action');
        var actionBox = action.closest('.input-group');
        actionBox.find('.dropdown-toggle').text('Select');
        action.val('');
    }
    // Full Text Feed
    function previewfullTextStream(html, panel_id) {

        var panel_box = $$('#' + panel_id);
        panel_box.find('.wall').prepend('<div class="social-stream">' + html + '</div>');

    }

    function previewSocialStream(rssfeed, color = 'dark', limit = 10, stype = '', keywork, network_type, panel = 'social_default_stream', voteData = [], key = 0, reload_item = true, isNewsReader) {
        var panel_box = $$('#' + panel);
        var id = '#' + panel+" .wall";
        $(id).empty();
        setAdvertValue();
        advert = processAdvert(advert);
        var return_html = '';
        if (reload_item) {
            return_html = cache_get(network_type, keywork, stype, limit);
        }

        var out = 'intro,thumb,title,text,user,share,edit';
        if (isNewsReader) {
            var out = 'intro,thumb,title,text,user,share';
        }
        if (stype == 'voting') {
            out = 'intro,thumb,title,text,user,vote';
        }
        panel_box.find('.wall').prepend('<div class="social-stream"></div>');
        if (return_html != '') {
            panel_box.find('.social-stream').first().html(return_html);
            return true;
        }
        socialStream = panel_box.find('.social-stream').first().dcSocialStream({
            feeds: {
                rss: {
                    id: rssfeed,
                    out: out,
                    url: 'src/rss.php'
                }
            },
            rotate: {
                delay: 0
            },
            style: {
                layout: 'modern',
                colour: color
            },
            twitterId: 'suite.social',
            control: false,
            order: 'random',
            filter: false,
            wall: true,
            center: true,
            cache: true,
            max: 'days',
            days: 365,
            limit: limit,
            advert: advert,
            skipAdvert: false,
            voteData: voteData,
            streamKey: key,
            iconPath: 'src/img/',
            imagePath: 'src/img/',
            debug: false,
        });
        //Store Cache
        var page = panel_box.find('.social-stream').first().html();
        var checkPage = panel_box.find('.social-stream').first().find('.stream');
        if (checkPage.find('li').length > 0) {
            cache_store(network_type, keywork, stype, limit, page);
        } else {
            console.log(page);
        }

    }

    function default_stream_load(stream, panel_id, out, color = 'dark', twitterId, limit = 0, reload_item = false) {
        var voteData = window.voteData;
        var panel_box = $$('#' + panel_id);
        setAdvertValue();
        advert = processAdvert(window.defaultAdvert);
        panel_box.find('.wall').prepend('<div class="social-stream"></div>');
        socialStream = panel_box.find('.social-stream').first().dcSocialStream({
            feeds: {
                rss: {
                    id: stream,
                    out: out,
                    url: 'src/rss.php'
                }
            },
            rotate: {
                delay: 0
            },
            style: {
                layout: 'modern',
                colour: 'dark',
            },
            twitterId: twitterId,
            control: false,
            order: 'random',
            filter: true,
            wall: true,
            center: true,
            cache: true,
            max: 'days',
            days: 365,
            limit: limit,
            advert: advert,
            skipAdvert: false,
            voteData: voteData,
            streamKey: [],
            iconPath: 'src/img/',
            imagePath: 'src/img/',
            debug: false,
        });
    }
    // let v = $(this);
    // console.log(v);
    // var scriptSource = (function() {
    //     var scripts = $('script[src]');
    //     return scripts[scripts.length - 1].src
    // }());
    //
    // var params = parseQueryString(scriptSource.split('?')[1]);
    // console.log(params);
    // function parseQueryString(queryString) {
    //     var params = {};
    //     if (queryString) {
    //         var keyValues = queryString.split('&');
    //         for (var i=0; i < keyValues.length; i++) {
    //             var pair = keyValues[i].split('=');
    //             params[pair[0]] = pair[1];
    //         }
    //     }
    //     return params;
    // }

    $(function() {
        let data = '';
        let voiceType = 'UK English Female';
        if (page == 'index') {
            $.getJSON('https://jsonip.com/?callback=?', function(data) {
                ipaddress = data.ip;
                // console.log(makeKeyFromIPAndID(ipaddress, 0));
                getDataFromJson();
            });
        } else if (page == 'widget') {
            getDataFromJson();
        }
    });

    function getDataFromJson() {
        $.getJSON(
            url +
            '/db/data.json?version=' +
            new Date().getTime()
        ).done(function(json) {
            data = json[ipaddress];
            // console.log(data);
            if (page == 'index' && data == null) {
                data = [];
            } else if (
                page == 'widget' &&
                (data == null || data[projectId] == null)
            ) {
                location.href = 'home';
            }

            if (page == 'index') {
                $('.cName').text('Your Name');
                $('.img').attr('src', 'src/img/default.jpg');
                voiceType = 'UK English Female';
            } else if (page == 'widget') {
                $('#projectName').text(data[projectId].project['pname']);
                $('.cName').text(data[projectId].project['name']);
                $('.img').attr('src', data[projectId].project['url']);
                voiceType = data[projectId].project['voice'];
                $('#banner').html(data[projectId].project['banner']);
            }
            fillVoice();
            start();
            pTbl();
        });
    }

    function cache_get(networks, keywords, stype, limit) {
        var html_respone = '';
        $.ajax({
            type: "POST",
            url: "cache.php",
            data: {
                'ajax_action': 'cache_get',
                'network_type': networks,
                'keywork': keywords,
                'page_type': stype,
                'per_page_limit': limit,
            },
            dataType: "json",
            success: function(response) {
                if (response.status == 'success') {
                    html_respone = response.html;
                } else if (response.status == 'NOT_FOUND') {
                    html_respone = response.html;
                }

            }
        });
        return html_respone;
    }

    function cache_store(networks, keywords, stype, limit, page) {
        $.ajax({
            type: "POST",
            url: "cache.php",
            data: {
                'ajax_action': 'cache_store',
                'page': page,
                'network_type': networks,
                'keywork': keywords,
                'page_type': stype,
                'per_page_limit': limit,
            },
            dataType: "json",
            success: function(response) {
                console.log(response);

            }
        });

    }
    // let voicelist = responsiveVoice.getVoices();

    function fillVoice() {
        try {
            $.each(voicelist, function(key, value) {
                $('#voice').append(
                    $('<option></option>')
                    .attr('value', value.name)
                    .text(value.name)
                );
            });
            $("#voice>option[value='" + voiceType + "']").prop(
                'selected',
                true
            );
            $('#voice').select2();
        } catch (err) {}
    }

    // var saveData = (function () {
    //     var a = document.createElement("a");
    //     document.body.appendChild(a);
    //     a.style = "display: none";
    //     return function (data , fileName) {
    //         var json = JSON.stringify(data) ,
    //             blob = new Blob([json] , {type: "octet/stream"}) ,
    //             url = window.URL.createObjectURL(blob);
    //         a.href = url;
    //         a.download = fileName;
    //         a.click();
    //         window.URL.revokeObjectURL(url);
    //     };
    // }());
    // let fileName = "data.json";

    $('#form').submit(function(e) {
        e.preventDefault();
        $('#msend').trigger('click');
        return false;
    });

    $('.status').html('last seen today at ' + getTime());

    let receivedMsg = '';
    let originalMsg = '';
    let tick =
        "<svg style='position: absolute;transition: .5s ease-in-out;' xmlns='http://www.w3.org/2000/svg' width='16'height='15' id='msg-dblcheck-ack' x='2063' y='2076'><path d='M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.88a.32.32 0 0 1-.484.032l-.358-.325a.32.32 0 0 0-.484.032l-.378.48a.418.418 0 0 0 .036.54l1.32 1.267a.32.32 0 0 0 .484-.034l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.88a.32.32 0 0 1-.484.032L1.892 7.77a.366.366 0 0 0-.516.005l-.423.433a.364.364 0 0 0 .006.514l3.255 3.185a.32.32 0 0 0 .484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z' fill='#4fc3f7'/></svg>";

    $('#msend').click(function() {
        // console.log(data);
        let scroll = $('.conversation-container').scrollTop() + 1550;

        let msg = $('#val').val().trim();
        let res =
            "<div class='message sent'>" +
            msg +
            "<span class='metadata'> <span class='time'>" +
            getTime() +
            "</span><span class='tick'>" +
            tick +
            '</span></span></div>';

        msg == '' ?
            $('#val').focus() :
            ($('#ap').append(res),
                $('#form')[0].reset(),
                setTimeout(function() {
                    $('.status').html('online ');
                }, 900),
                setTimeout(function() {
                    $('.status').html('typing... ');
                }, 1000),
                (receivedMsg = msg.toUpperCase().trim()),
                (originalMsg = msg.trim()),
                $('.conversation-container').scrollTop(scroll),
                send());
    });

    // saveData(response, fileName);
    function findQue(que) {
        return que.toUpperCase() === receivedMsg;
    }

    let support = false;

    function send() {
        let scroll = $('.conversation-container').scrollTop() + 1550;
        let resMsg = '';
        let speakMsg = '';
        let flag = false;
        if (receivedMsg.substring(0, 6) == 'SEARCH') {
            speakMsg = 'This are the top results.';
            resMsg =
                "<b align='center'>This are the top results </b><nav class='back'  onclick='history.back()'>&larr;</nav><nav class='forword' onclick='history.forward()'>&rarr;</nav><iframe style = 'z-index:1;overflow-x:scroll; overflow-y:scroll;' scrolling='yes' height='300px' width='100%' src='https://www.bing.com/search?q=" +
                receivedMsg.slice(7) +
                "'></iframe>";
            flag = true;
        } else if (receivedMsg === 'QUESTIONS' || receivedMsg === 'QUESTION') {
            sendQues(1000);
            return;
            flag = true;
        } else if (
            receivedMsg === 'DEPARTMENTS' ||
            receivedMsg === 'DEPARTMENT'
        ) {
            showDepartment();
            return;
        } else if (receivedMsg === 'KEYWORDS' || receivedMsg === 'KEYWORD') {
            showKeywords();
            return;
        } else if (receivedMsg === 'SUPPORT' || receivedMsg === 'SUPORRTS') {
            speakMsg = 'Okay, You require support, Please enter your message';
            resMsg = 'Please enter your Message.';
            support = true;
            flag = true;
        } else if (support) {
            support = false;
            showDepartment(false, true);
            return;
        } else {
            if (flag === false) {
                for (let res in data[projectId]['keywords']) {
                    if (
                        typeof data[projectId]['keywords'][res][0].find(
                            findQue
                        ) === 'string'
                    ) {
                        if (
                            data[projectId].keywords[res][1].indexOf('RSS:') ==
                            -1
                        ) {
                            speakMsg = resMsg =
                                data[projectId].keywords[res][1];
                        } else {
                            resMsg =
                                "<iframe style = 'z-index:1;overflow-x:scroll; overflow-y:scroll;' scrolling='yes' height='300px' width='100%' src='rss/index.html?rss=" +
                                data[projectId].keywords[res][1].replace(
                                    'RSS:',
                                    ''
                                ) +
                                "'></iframe>";
                        }
                        flag = true;
                        break;
                    }
                }
            }
            if (flag === false) {
                for (let res in data[projectId]['question']) {
                    if (
                        typeof data[projectId]['question'][res].find(
                            findQue
                        ) === 'string'
                    ) {
                        if (
                            data[projectId].question[res][1].indexOf('RSS:') ==
                            -1
                        ) {
                            speakMsg = resMsg =
                                data[projectId].question[res][1];
                        } else {
                            resMsg =
                                "<iframe style = 'z-index:1;overflow-x:scroll; overflow-y:scroll;' scrolling='yes' height='300px' width='100%' src='rss/index.html?rss=" +
                                data[projectId].question[res][1].replace(
                                    'RSS:',
                                    ''
                                ) +
                                "'></iframe>";
                        }
                        flag = true;
                        break;
                    }
                }
            }
            if (flag === false) {
                for (let res in data[projectId]['department']) {
                    if (
                        typeof data[projectId]['department'][res].find(
                            findQue
                        ) === 'string'
                    ) {
                        speakMsg = 'Contact to the department.';
                        resMsg =
                            "Contact Department Through : <a href='https://api.whatsapp.com/send?phone=" +
                            data[projectId]['department'][res][1] +
                            "' target='_blank'>" +
                            data[projectId]['department'][res][0] +
                            ' Department</a>';
                        flag = true;
                        break;
                    }
                }
            }
        }
        if (flag === false) {
            speakMsg =
                "Sorry, I didn't understand. For list of Keywords type keywords or if you have another question then type questions or Contact for support.";
            resMsg =
                "Sorry, I didn't understand, please enter proper spelling. For list of supported Keywords type <b>KEYWORDS</b> or if you have a question then type <b>QUESTIONS</b>";
            showDepartment(false);
        }

        let res =
            "<div class='message received'>" +
            resMsg +
            "<span class='metadata'> <span class='time'>" +
            getTime() +
            '</span></span></div>';
        setTimeout(function() {
            $('#ap').append(res);
            $('.status').html('online');
            $('.conversation-container').scrollTop(scroll);
        }, 1100);
        speak(strip_html_tags(speakMsg));
    }

    function strip_html_tags(str) {
        if (str === null || str === '') return false;
        else str = str.toString();
        return str.replace(/<[^>]*>/g, '');
    }

    function start() {
        $('.message').remove(); //Remove all previous messages
        let scroll = $('.conversation-container').scrollTop() + 1550;
        let resMsg = 'Hello, hope your good. I can provide any assistance.';
        let res =
            "<div class='message received'>" +
            resMsg +
            "<span class='metadata'> <span class='time'>" +
            getTime() +
            '</span></span></div>';

        $('#ap').append(res);
        $('.status').html('online');

        resMsg =
            'Choose from the menu or type your question to get started. <br><hr>';
        resMsg +=
            '<a href="javascript:void(0)" class="que-link start-link" data-que="question">Questions</a>';
        resMsg +=
            '<a href="javascript:void(0)" class="que-link start-link" data-que="keywords">Keywords</a>';
        resMsg +=
            '<a href="javascript:void(0)" class="que-link start-link" data-que="support">Support</a>';
        res =
            "<div class='message received'>" +
            resMsg +
            "<span class='metadata'> <span class='time'>" +
            getTime() +
            '</span></span></div>';
        setTimeout(function() {
            $('#ap').append(res);
            $('.status').html('online');
            $('.conversation-container').scrollTop(scroll);

            $('.start-link').on('click', function() {
                let que = $(this).data('que');
                if (que === 'question') {
                    sendQues();
                    return;
                } else if (que === 'keywords') {
                    showKeywords();
                    return;
                } else {
                    speakMsg =
                        'Okay, You require support, Please enter your message';
                    resMsg = 'Please enter your Message.';
                    support = true;
                }
                res =
                    "<div class='message received'>" +
                    resMsg +
                    "<span class='metadata'> <span class='time'>" +
                    getTime() +
                    '</span></span></div>';
                setTimeout(function() {
                    $('#ap').append(res);
                    $('.status').html('online');
                    $('.conversation-container').scrollTop(scroll);
                }, 1100);
                speak(strip_html_tags(speakMsg));
            });
        }, 1100);
        let speakMsg =
            'Hello, hope your good. I can provide any assistance. Choose from the menu or type your question to get started.';
        speak(strip_html_tags(speakMsg));
        //Vladimir - set chat box page and chat button URL
        $('#inputChatBoxPage').val(
            url +
            '/widget.php?key=' +
            makeKeyFromIPAndID(ipaddress, projectId)
        );
        $('#inputChatBoxIframe').val(
            '<iframe src="' +
            url +
            '/widget.php?key=' +
            makeKeyFromIPAndID(ipaddress, projectId) +
            '" height="600" width="100%" align="center" overflow-y="hidden"></iframe>'
        );
        $('#inputChatButtonJS').val(
            '<script src="' +
            url +
            '/button/' +
            makeKeyFromIPAndID(ipaddress, projectId) +
            '.js"></script>'
        );
    }

    function showKeywords(flag = true, key = null, page = 0) {
        let scroll = $('.conversation-container').scrollTop() + 1550;
        let resMsg = '';
        if (flag) {
            let speakMsg = 'Following are all supported keywords';
            resMsg =
                'Following are all supported keywords  <br><hr><span data-key="' +
                Math.floor(Math.random() * 100 + 1) +
                '">';
            speak(strip_html_tags(speakMsg));
        }
        let len = data[projectId].keywords.length;
        let lastKey = len > page + 10 ? page + 10 : len;
        for (let key = page; key < lastKey; key++) {
            resMsg +=
                '<a href="javascript:void(0)" data-key="' +
                key +
                '" class="key-link">' +
                data[projectId].keywords[key][0][0] +
                (key !== lastKey - 1 ? ', ' : '');
        }
        if (len > page + 10) {
            resMsg +=
                '<a href="javascript:void(0)" data-key="' +
                Math.floor(Math.random() * 100 + 1) +
                '" data-page="' +
                (page + 10) +
                '" class="more-link">...MORE</a></span>';
        }

        setTimeout(
            function() {
                if (flag) {
                    let res =
                        "<div class='message received'>" +
                        resMsg +
                        "<span class='metadata'> <span class='time'>" +
                        getTime() +
                        '</span></span></div>';
                    $('#ap').append(res);
                } else {
                    $('span[data-key="' + key + '"]').append(', ' + resMsg);
                }
                $('.status').html('online');
                $('.conversation-container').scrollTop(scroll);

                $('.key-link').on('click', function(e) {
                    if (e.handled !== true) {
                        let key = $(this).data('key');
                        let res =
                            "<div class='message sent'>" +
                            data[projectId].keywords[key][0][0] +
                            "<span class='metadata'> <span class='time'>" +
                            getTime() +
                            "</span><span class='tick'>" +
                            tick +
                            '</span></span></div>';
                        $('#ap').append(res);
                        $('.conversation-container').scrollTop(scroll);
                        receivedMsg = data[projectId].keywords[key][0][0]
                            .toUpperCase()
                            .trim();
                        send();
                        e.handled = true;
                    }
                    return false;
                });

                $('.more-link').on('click', function() {
                    key = $(this).parent('span').data('key');
                    page = $(this).data('page');
                    showKeywords(false, key, page);
                    $(this).remove();
                });
            },
            flag ? 1100 : 0
        );
    }

    function sendQues() {
        let scroll = $('.conversation-container').scrollTop() + 1550;

        let msg = 'Select Question to get started. <br><hr>';

        for (let que in data[projectId].question) {
            msg +=
                '<a href="javascript:void(0)" class="que-link ques" data-que="' +
                que +
                '">' +
                data[projectId].question[que][0] +
                '</a>';
        }

        let res =
            "<div class='message received'>" +
            msg +
            "<span class='metadata'> <span class='time'>" +
            getTime() +
            '</span></span></div>';
        setTimeout(function() {
            $('#ap').append(res);
            $('.status').html('online');
            $('.conversation-container').scrollTop(scroll);

            $('.ques').on('click', function() {
                let key = $(this).data('que');
                let res =
                    "<div class='message sent'>" +
                    data[projectId].question[key][0] +
                    "<span class='metadata'> <span class='time'>" +
                    getTime() +
                    "</span><span class='tick'>" +
                    tick +
                    '</span></span></div>';
                $('#ap').append(res);
                $('.conversation-container').scrollTop(scroll);
                if (data[projectId].question[key][1].indexOf('RSS:') == -1) {
                    res =
                        "<div class='message received'>" +
                        data[projectId].question[key][1] +
                        "<span class='metadata'> <span class='time'>" +
                        getTime() +
                        '</span></span></div>';
                    speak(strip_html_tags(data[projectId].question[key][1]));
                } else {
                    res =
                        "<div class='message received'><iframe style = 'z-index:1;overflow-x:scroll; overflow-y:scroll;' scrolling='yes' height='300px' width='100%' src='rss/index.html?rss=" +
                        data[projectId].question[key][1].replace('RSS:', '') +
                        "'></iframe><span class='metadata'> <span class='time'>" +
                        getTime() +
                        '</span></span></div>';
                }
                setTimeout(function() {
                    $('#ap').append(res);
                    $('.status').html('online');
                    $('.conversation-container').scrollTop(scroll);
                }, 1100);
            });
        }, 1100);
        msg = 'Select Question to get started';
        speak(msg);
    }

    function showDepartment(flag = true, department = false) {
        let scroll = $('.conversation-container').scrollTop() + 1550;
        let delay = flag == true || department == true ? 1100 : 2000;
        let msg;
        if (department) msg = 'Select Department Link :  <br><hr>';
        else msg = 'Contact Department through Link :  <br><hr>';

        for (let que in data[projectId].department) {
            if (data[projectId]['department'][que][0] === 'WhatsApp') {
                msg +=
                    '<a href="https://api.whatsapp.com/send?phone=' +
                    data[projectId]['department'][que][2] +
                    (department === true ?
                        '&text=' + encodeURI(originalMsg) :
                        '') +
                    '" target="_blank" class="que-link"><i class="fab fa-whatsapp"></i> ' +
                    data[projectId].department[que][1] +
                    ' department </a>';
            } else if (data[projectId]['department'][que][0] === 'Messenger') {
                msg +=
                    '<a href="https://m.me/' +
                    data[projectId]['department'][que][2] +
                    '" target="_blank" class="que-link"><i class="fab fa-facebook-messenger"></i> ' +
                    data[projectId].department[que][1] +
                    ' department </a>';
            } else if (data[projectId]['department'][que][0] === 'Skype') {
                msg +=
                    '<a href="skype:' +
                    data[projectId]['department'][que][2] +
                    '?chat" target="_blank" class="que-link"><i class="fab fa-skype"></i> ' +
                    data[projectId].department[que][1] +
                    ' department </a>';
            } else if (data[projectId]['department'][que][0] === 'Telegram') {
                msg +=
                    '<a href="https://t.me/' +
                    data[projectId]['department'][que][2] +
                    '" target="_blank" class="que-link"><i class="fab fa-telegram"></i> ' +
                    data[projectId].department[que][1] +
                    ' department </a>';
            } else if (data[projectId]['department'][que][0] === 'Email') {
                msg +=
                    '<a href="mailto:' +
                    data[projectId]['department'][que][2] +
                    '?subject=Support' +
                    (department === true ?
                        '&body=' + encodeURI(originalMsg) :
                        '') +
                    '" target="_blank" class="que-link"><i class="far fa-envelope"></i> ' +
                    data[projectId].department[que][1] +
                    ' department </a>';
            } else if (data[projectId]['department'][que][0] === 'Phone') {
                msg +=
                    '<a href="tel:' +
                    data[projectId]['department'][que][2] +
                    '" target="_blank" class="que-link"><i class="fas fa-phone"></i> ' +
                    data[projectId].department[que][1] +
                    ' department </a>';
            }
        }

        let res =
            "<div class='message received'>" +
            msg +
            "<span class='metadata'> <span class='time'>" +
            getTime() +
            '</span></span></div>';
        setTimeout(function() {
            $('#ap').append(res);
            $('.status').html('online');
            $('.conversation-container').scrollTop(scroll);
        }, delay);
        if (flag) speak('Following are our the department.');
        if (department) speak('okay, please click a department link');
    }

    let profile = [];

    $('#visitWidgetButton').on('click', function() {
        window.open($('#inputChatBoxPage').val(), '_blank');
    });

    $('#save').on('click', async function() {

        let dName = $('.dName');
        let dNo = $('.dNo');
        let type = $('.type');

        let que = $('.que');
        let Qans = $('.Qans');

        let keyword = $('.keyword');
        let Kans = $('.Kans');

        let pName = $('.pName').val();
        let industry = $('.industry').val();
        let banner = $("#banner").summernote('code');
        let advert = $('#advert').summernote('isEmpty') ? '' : $('#advert').summernote('code');
        let pDesc = $('.desc').val();
        let stype = $('.stype').val();

        let editId = $('#editId').val();
        let dataLen = data.length;

        if (
            typeof editId !== 'undefined' &&
            editId != '' &&
            (await conf()) === false
        ) {
            return false;
        }

        if (typeof editId !== 'undefined' && editId != '') {
            profile['name'] == '' || typeof profile['name'] === 'undefined' ?
                (profile['name'] = $('#cName').val()) :
                '';
            data[editId]['department'] = [];
            data[editId]['question'] = [];
            data[editId]['keywords'] = [];
            dataLen = editId;
            $('#editId').val('');
            $('#state').text('NEW');
        }

        var networks = $("select[name='network[]']")
            .map(function() {
                return $(this).val();
            })
            .get();
        var rsskeywords = $("input[name='keyword[]']")
            .map(function() {
                return $(this).val();
            })
            .get();

        var len = networks.length;
        var generatedRSS = [];
        for (var i in networks) {
            if (rsskeywords[i] != '' && networks[i] != 'Leads') {
                generatedRSS.push('https://suite.social/search/search-result.php?q=' + rsskeywords[i] + '&site=' + networks[i].replace(' ', '+') + '&rss');
            } else if (rsskeywords[i] != '') {
                generatedRSS.push(
                    'https://suite.social/search/search-result.php?q="I+want+a+' +
                    rsskeywords[i] +
                    '"+OR+"I+need+a+' +
                    rsskeywords[i] +
                    '"+OR+"I+am+looking+for+a+' +
                    rsskeywords[i] +
                    '"+OR+"I+am+seeking+a+' +
                    rsskeywords[i] +
                    '"+OR+"recommend+a+' +
                    rsskeywords[i] +
                    '"&site=leads&rss'
                );
            }
        }
        var streamrss = generatedRSS.join(',');

        //projects
        let projects = {
            name: typeof profile['name'] != 'undefined' ? profile['name'] : '',
            url: typeof profile['url'] != 'undefined' ? profile['url'] : '',
            voice: voiceType,
            pname: pName,
            description: pDesc,
            industry: industry,
            banner: banner,
            posts: $('#posts').val(),
            style: $('#style').val(),
            advert: advert ? advert : '',
            stype: stype,
            rssfeed: streamrss,
            key: makeKeyFromIPAndID(ipaddress, dataLen)
        };
        //Department
        let department = [];
        let dLen = dName.length;
        dName.each(function(a, ele) {
            if (
                $(ele).val() != '' &&
                $(dNo[a]).val() != '' &&
                $(type[a]).val() != ''
            ) {
                department.push([
                    $(type[a]).val(),
                    $(ele).val(),
                    $(dNo[a]).val()
                ]);
                // data.department.push([$(type[a]).val(),$(ele).val(),$(dNo[a]).val()]);
            }
            if (dLen === a + 1) {
                $(ele).val('');
                $(dNo[a]).val('');
            } else {
                $(ele).closest('.item').remove();
            }
        });
        //Questions
        let question = [];
        let qLen = que.length;
        que.each(function(a, ele) {
            if ($(ele).val() != '' && $(Qans[a]).val() != '') {
                question.push([$(ele).val(), $(Qans[a]).val()]);
                // data.question.push([$(ele).val(),$(Qans[a]).val()]);
            }
            if (qLen === a + 1) {
                $(ele).val('');
                $(Qans[a]).val('');
            } else {
                $(ele).closest('.item').remove();
            }
        });

        //Keywords
        let keywords = [];
        let wLen = keyword.length;
        keyword.each(function(a, ele) {
            if ($(ele).val() != '' && $(Kans[a]).val() != '') {
                keywords.push([
                    $(ele).val().toUpperCase().split(','),
                    $(Kans[a]).val()
                ]);
                // data.keywords.push([$(ele).val().toUpperCase().split(",") ,$(Kans[a]).val()]);
                $(ele).val('');
                $(Kans[a]).val('');
            }
            if (wLen === a + 1) {
                $(ele).val('');
                $(Kans[a]).val('');
            } else {
                $(ele).closest('.item').remove();
            }
        });
        data[dataLen] = { department: department };
        data[dataLen]['keywords'] = keywords;
        data[dataLen]['question'] = question;
        data[dataLen]['project'] = projects;
        if (data[dataLen]['project']['name'] == '')
            data[dataLen]['project']['name'] = 'Your Name';
        if (data[dataLen]['project']['url'] == '')
            data[dataLen]['project']['url'] = 'src/img/default.jpg';
        // console.log(data[dataLen]);
        $.ajax({
            url: 'save.php',
            method: 'post',
            data: {
                type: 'details',
                ipaddress: ipaddress,
                len: dataLen,
                key: makeKeyFromIPAndID(ipaddress, dataLen),
                data: data[dataLen]
            },
            success: function(res) {
                if (res == true && editId == '') {
                    Swal.fire(
                        'Created!',
                        'Your project has been Created.',
                        'success'
                    );
                }
            },
            error: function(e) {
                console.log(e);
            }
        });
        $('.pName').val('');
        $('#cName').val('');
        $('.desc').val('');
        $('#banner').summernote('code', ''); //banner
        $('#advert').summernote('code', ''); //advert
        $('.cName').text('Your Name');
        $('.img').attr('src', 'src/img/default.jpg');
        voiceType = 'UK English Female';
        fillVoice();
        await pTbl();
        $('.card-footer').show();
    });

    async function conf() {
        let v = await Swal.fire({
            title: 'Are you sure?',
            text: 'You want to Update Details!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Edit it!'
        }).then(result => {
            if (result.value) {
                // Swal.fire(
                //     'Updated!',
                //     'Your file has been Updated.',
                //     'success'
                // )
            }
            return result;
        });
        return v.isConfirmed;
    }

    //Delete Swal Alert
    async function deleteconf() {
        let v = await Swal.fire({
            title: 'Are you sure?',
            text: 'You want to Remove Project!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
        }).then(result => {
            if (result.value) {}
            return result;
        });
        return v.isConfirmed;
    }

    $('#submitProfile').on('click', async function() {
        let cName = $('#cName').val();
        let search = $('#profile').val();
        let type = $('#type').val();

        if (cName == '' || search == '') {
            alert('Please fill the Details');
            return false;
        }

        if (type == 'twitter') {
            $.ajax({
                url: 'twitter.php',
                type: 'POST',
                data: {
                    username: search
                },
                success: function(res) {
                    if (res !== 'Not Found') {
                        $('.img').attr('src', res);
                        saveProfile(cName, res);
                    } else {
                        let msg = confirm(
                            'Username not found \nWant to Continue???'
                        );
                        if (msg) {
                            saveProfile(cName, 'src/img/default.jpg');
                        }
                    }
                }
            });
        } else if (type == 'insta') {
            await $.instagramFeed({
                username: search,
                callback: function(data) {
                    $('.img').attr('src', data.profile_pic_url_hd);
                    saveProfile(cName, data.profile_pic_url_hd);
                },
                on_error: function() {
                    let msg = confirm(
                        'Username not found \nWant to Continue???'
                    );
                    if (msg) {
                        saveProfile(cName, 'src/img/default.jpg');
                    }
                }
            });
        } else if (search != '') {
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                data: {
                    action: type,
                    username: search
                },
                success: function(data) {
                    if (data !== 'Username not found') {
                        $('.img').attr('src', data);
                        saveProfile(cName, data);
                    } else {
                        let msg = confirm(
                            'Username not found \nWant to Continue???'
                        );
                        if (msg) {
                            saveProfile(cName, 'src/img/default.jpg');
                        }
                    }
                }
            });
        }
    });

    function saveProfile(cName, profileUrl) {
        // $.ajax({
        //     url: 'save.php' ,
        //     method: 'post' ,
        //     data: {
        //         type: 'profile' ,
        //         cName: cName ,
        //         profile: profile
        //     } ,
        //     success: function (res) {
        //         if (res == true) {
        //             alert("Details are saved Successfully");
        //         }
        //     } ,
        //     error: function (e) {
        //         console.log(e);
        //     }
        // });
        profile['name'] = cName;
        profile['url'] = profileUrl;
        $('#cName').val('');
        $('#profile').val('');
        $('.cName').text(profile['name']);
        $('.img').attr('src', profile['url']);
    }

    $('#voice').on('change', function() {
        let value = $(this).val();
        voiceType = value;
    });

    $('#delete').on('click', function() {
        profile['name'] = 'Your Name';
        profile['url'] = 'src/img/default.jpg';
        $('.cName').text(profile['name']);
        $('.img').attr('src', profile['url']);
    });
    //
    // $("#updateDepartment").on('click' , function () {
    //     let updatedDName = $("#updatedDName").val();
    //     let updatedDno = $("#updatedDno").val();
    //     let updateId = $("#updateId").val();
    //
    //     if (updatedDName == '' || updatedDno == '') {
    //         alert("Please Fill the proper details.");
    //         return false;
    //     }
    //
    //     data.department[updateId][0] = [updatedDName];
    //     data.department[updateId][1] = [updatedDno];
    //     $.ajax({
    //         url: 'save.php' ,
    //         method: 'post' ,
    //         data: {
    //             type: 'updateDepartment' ,
    //             updateId: updateId ,
    //             updatedDName: updatedDName ,
    //             updatedDno: updatedDno
    //         } ,
    //         success: function (res) {
    //             if (res == true) {
    //                 alert("Details are saved Successfully");
    //             }
    //         } ,
    //         error: function (e) {
    //             console.log(e);
    //         }
    //     });
    //     // console.log(data);
    //     // saveData(data , fileName);
    //     // notify();
    //     $("#departmentModal").modal('toggle');
    //     dTbl();
    // });
    //
    // $("#updateQuestion").on('click' , function () {
    //     let updatedQue = $("#updatedQue").val();
    //     let updatedQans = $("#updatedQans").val();
    //     let updateId = $("#updateId").val();
    //
    //     if (updatedQue == '' || updatedQans == '') {
    //         alert("Please Fill the proper details.");
    //         return false;
    //     }
    //
    //     data.question[updateId][0] = [updatedQue];
    //     data.question[updateId][1] = [updatedQans];
    //     $.ajax({
    //         url: 'save.php' ,
    //         method: 'post' ,
    //         data: {
    //             type: 'updateQuestion' ,
    //             updateId: updateId ,
    //             updatedQue: updatedQue ,
    //             updatedQans: updatedQans
    //         } ,
    //         success: function (res) {
    //             if (res == true) {
    //                 alert("Details are saved Successfully");
    //             }
    //         } ,
    //         error: function (e) {
    //             console.log(e);
    //         }
    //     });
    //     // console.log(data);
    //     // saveData(data , fileName);
    //     // notify();
    //     $("#questionModal").modal('toggle');
    //     queTbl();
    // });
    //
    // $("#updateKeyword").on('click' , function () {
    //     let updatedKeyword = $("#updatedKeyword").val().toUpperCase().split(",");
    //     let updatedKans = $("#updatedKans").val();
    //     let updateId = $("#updateId").val();
    //
    //     if (updatedKeyword == '' || updatedKans == '') {
    //         alert("Please Fill the proper details.");
    //         return false;
    //     }
    //
    //     data.keywords[updateId][0] = updatedKeyword;
    //     data.keywords[updateId][1] = [updatedKans];
    //     $.ajax({
    //         url: 'save.php' ,
    //         method: 'post' ,
    //         data: {
    //             type: 'updateKeywords' ,
    //             updateId: updateId ,
    //             updatedKeyword: updatedKeyword ,
    //             updatedKans: updatedKans
    //         } ,
    //         success: function (res) {
    //             if (res == true) {
    //                 alert("Details are saved Successfully");
    //             }
    //         } ,
    //         error: function (e) {
    //             console.log(e);
    //         }
    //     });
    //     // console.log(data);
    //     // saveData(data , fileName);
    //     // notify();
    //     $("#keywordModal").modal('toggle');
    //     keywordTbl();
    // });
    //

    async function pTbl() {
        // console.log(data);
        let html = '';
        $('#example1').DataTable().destroy();
        for (let key in data) {
            let industry = $(
                ".industry>option[value='" +
                data[key].project['industry'] +
                "']"
            ).text();
            html +=
                '<tr>' +
                '<td><img class="img-fluid" src="https://suite.social/images/wall/devices2.png" alt="Category"></td>' +
                '<td>' +
                data[key].project.pname +
                '</td>' +
                '<td>' +
                data[key].project.stype +
                '</td>' +
                '<td>' +
                data[key].project.description +
                '</td>' +
                //'<td>30 Days</td>' +
                '<td>' +
                industry +
                '</td>' +
                '<td>' +
                /*'<div class="btn-group">' +
                '<button type="button" class="btn btn-success btn-sm">Embed</button>' +
                '<button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">' +
                '    <span class="sr-only">Toggle Dropdown</span>' +
                '       <div class="dropdown-menu" role="menu" style="">' +
                '           <a class="dropdown-item" href="#">Chat Box</a>' +
                '           <div class="dropdown-divider"></div>' +
                '           <a class="dropdown-item" href="#">Chat Button</a>' +
                '       </div>' +
                '</button> ' +
                '</div> ' +*/
                '<button type="button" class="btn btn-primary btn-sm view" data-id="' +
                key +
                '" id="goToProject" data-location="' + url +
                '/widget.php?key=' + data[key].project.key + '"><i class="fas fa-link"></i> View</button> ' +
                '<button type="button" class="btn btn-info btn-sm edit" data-id="' +
                key +
                '"><i class="fas fa-pencil-alt"></i> Edit</button> ' +
                '<button type="button" class="btn btn-danger btn-sm delete" data-id="' +
                key +
                '" data-streamkey="' + data[key].project.key + '"><i class="fas fa-trash"></i> Delete</button>';

            if (data[key].project.stype == 'voting') {
                html +=
                    '&nbsp;<button type="button" data-votekey="' + data[key].project.key + '" class="btn btn-success btn-sm voteadmin" data-toggle="modal" data-target="#votes" ><i class="fas fa-vote-yea"></i> Votes</button> ';
            }

            html += '</td></tr>';
        }
        $('#pTbl').html(html);

        $('#example1').DataTable({
            responsive: true,
            autoWidth: false
        });

        // $('#example1').DataTable({
        //     "processing": true ,
        //     "serverSide": true ,
        //     "order": [] ,
        //     "ajax": {
        //         url: "fetch.php" ,
        //         type: "POST" ,
        //     }
        // });
        // setTimeout(function () {
        //     $(".select2").select2();
        // } , 2000);
    }

    // $(document).on('click', '.indus', function(){
    //     var id = $(this).data("id");
    //     let val = $(".industry>option").map(function() { return $(this).val(); });
    //     let text = $(".industry>option").map(function() { return $(this).text(); });
    //     // console.log(select);
    //     // let select = '<select class="select2"><option>Option</option><option>Option</option><option>Option</option></select>';
    //
    //     var $select = $("<select class='select2 newIndus'></select>");
    //     $.each(text, function(k,v){
    //         var $option = $("<option></option>", {
    //             "text": v,
    //             "value": val[k]
    //         });
    //         $select.append($option);
    //     });
    //
    //     $(this).html($select);
    //     $(".select2").select2();
    // });

    // $('#example1 tbody').on('click', 'tr .view', function () {
    //     let id = $(this).data('id');
    //     dTbl(id);
    //     queTbl(id);
    //     keywordTbl(id);
    // });


    $('#example1 tbody').on('click', 'tr .view', function(e) {
        e.preventDefault();
        let streamUrl = $(this).data('location');
        window.open(streamUrl, '_blank');
    });

    $('#example1 tbody').on('click', 'tr .voteadmin', function(e) {
        $("#example2").DataTable().clear().draw();
        let voteKey = $(this).data('votekey');
        deleteKey = voteKey;
        let contestData = [];

        if (voteData[voteKey]) {
            $.each(voteData[voteKey], function(index, value) {
                if (parseInt(value.count) > 0) {
                    const postUrl = index;
                    let postDomain = (new URL(postUrl)).hostname.replace('www.', '');
                    postDomain = postDomain.substring(0, postDomain.lastIndexOf("."));
                    postDomain = postDomain.substring(0, postDomain.lastIndexOf("."));
                    contestData.push([parseInt(value.count), value.title, postDomain, index])
                }
            });
            $('#example2').dataTable().fnAddData(contestData);
        }

    });

    $(document).on('click', '#deleteVotes', async function() {
        var votingKey = deleteKey;

        $.ajax({
            url: 'vote.php',
            method: 'post',
            data: {
                action: 'deleteVotes',
                voteKey: votingKey
            },
            success: function(res) {
                var res = jQuery.parseJSON(res);
                if (res.status == 'success') {

                    Swal.fire(
                        'Done!',
                        'Votes Delete.',
                        'success'
                    );

                    $.getJSON(url + '/votedata.json?version=' + new Date().getTime()).done(
                        function(json) {
                            voteData = json;
                        }
                    );
                    $("#example2").DataTable().clear().draw();
                }
            },
            error: function(e) {
                console.log(e);
            }
        });
    });


    $(document).on('click', '.delete', async function() {
        var id = $(this).data('id');
        var voteKey = $(this).data('streamkey');

        if (await deleteconf()) {
            data = $.grep(data, function(v, k) {
                return k != id;
            });
            $.ajax({
                url: 'delete.php',
                method: 'POST',
                data: {
                    id: id,
                    ipaddress: ipaddress,
                    voteKey: voteKey,
                    key: makeKeyFromIPAndID(ipaddress, id)
                },
                success: function(data) {
                    $('#example1').DataTable().destroy();
                    pTbl();
                    $('.card-footer').hide();
                }
            });
        }
    });

    // $(document).on('change' , '.indus' , function () {
    //     var id = $(this).data("id");
    //     $(".save[data-id='" + id + "']").removeClass('d-none');
    //     // alert($(this).val())
    // });
    // $(document).on('DOMSubtreeModified' , '.update' , function () {
    //     var id = $(this).data("id");
    //     $(".save[data-id='" + id + "']").removeClass('d-none');
    // });
    // $(document).on('click' , '.save' , function () {
    //
    //     let id = $(this).data("id");
    //     let column_0 = $("div[data-id=" + id + "][data-column=0]").text();
    //     let column_1 = $(".indus").val();
    //     let column_2 = $("div[data-id=" + id + "][data-column=2]").text();
    //
    //     $.ajax({
    //         url: "update.php" ,
    //         method: "POST" ,
    //         data: {id: id , project_name: column_0 , industry: column_1 , desc: column_2} ,
    //         success: function (data) {
    //             $('#example1').DataTable().destroy();
    //             pTbl();
    //         }
    //     });
    //
    //     $(".save[data-id='" + id + "']").addClass('d-none');
    // });

    var dUpdatebind = false;

    function dTbl(id) {
        // let html = '';
        //
        // for (let key in data[id].department) {
        //     html += '<tr>' +
        //         '<td><img src="src/img/default.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle"></td>' +
        //         '<td>' + data[id].department[key][1] + '</td>' +
        //         '<td>' + data[id].department[key][0] + '</td>' +
        //         '<td>' + data[id].department[key][2] + '</td>' +
        //         // '<td><button type="button" class="btn btn-success edit-department" data-id="' + key + '" data-toggle="modal" data-target="#departmentModal"><span class="glyphicon glyphicon-pencil"></span></button>&nbsp;<button type="button" class="btn btn-danger delete-department" data-id="' + key + '"><span class="glyphicon glyphicon-remove"></span></td>' +
        //         '</tr>';
        // }
        //
        // $("#dTbl tbody").html(html);

        $('#dTbl').DataTable().destroy();
        $('#dTbl').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: 'fetch.php',
                method: 'POST',
                data: {
                    type: 'department',
                    id: id,
                    ipaddress: ipaddress
                }
            }
        });
        if (!dUpdatebind) {
            $(document).on('blur', '.dUpdate', function() {
                var did = $(this).data('id');
                var column = $(this).data('column');
                let value = $(this).text();
                $.ajax({
                    url: 'update.php',
                    method: 'POST',
                    data: {
                        id: id,
                        ipaddress: ipaddress,
                        did: did,
                        type: 'department',
                        column: column,
                        value: value
                    },
                    success: function() {
                        $('#dTbl').DataTable().destroy();
                        dTbl(id);
                        showInlineUpdate();
                    }
                });
            });
            dUpdatebind = true;
        }
    }

    var qUpdatebind = false;

    function queTbl(id) {
        $('#qTbl').DataTable().destroy();
        $('#qTbl').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: 'fetch.php',
                method: 'POST',
                data: {
                    type: 'question',
                    id: id,
                    ipaddress: ipaddress
                }
            }
        });
        if (!qUpdatebind) {
            $(document).on('blur', '.qUpdate', function() {
                // console.log('qupdated');
                var did = $(this).data('id');

                var column = $(this).data('column');
                let value = $(this).text();
                $.ajax({
                    url: 'update.php',
                    method: 'POST',
                    data: {
                        id: id,
                        ipaddress: ipaddress,
                        did: did,
                        type: 'question',
                        column: column,
                        value: value
                    },
                    success: function() {
                        $('#qTbl').DataTable().destroy();
                        queTbl(id);
                        showInlineUpdate();
                    }
                });
            });
            qUpdatebind = true;
        }
    }

    var kUpdatebind = false;

    function keywordTbl(id) {
        $('#kTbl').DataTable().destroy();
        $('#kTbl').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: 'fetch.php',
                method: 'POST',
                data: {
                    type: 'keywords',
                    id: id,
                    ipaddress: ipaddress
                }
            }
        });
        if (!kUpdatebind) {
            $(document).on('blur', '.kUpdate', function() {
                var did = $(this).data('id');
                var column = $(this).data('column');
                let value = $(this).text();
                if (column == 0) {
                    value = value.split(',');
                }
                $.ajax({
                    url: 'update.php',
                    method: 'POST',
                    data: {
                        id: id,
                        ipaddress: ipaddress,
                        did: did,
                        type: 'keywords',
                        column: column,
                        value: value
                    },
                    success: function() {
                        $('#dTbl').DataTable().destroy();
                        keywordTbl(id);
                        showInlineUpdate();
                    }
                });
            });
            kUpdatebind = true;
        }
    }

    $('#example1 tbody').on('click', 'tr .edit', function() {
        let id = $(this).data('id');
        projectId = id;
        start();

        let queContainer = $('#question');
        let keyContainer = $('#keywords');
        let dContainer = $('#department');
        profile['url'] = data[id].project.url;
        $('#editId').val(id);

        $('#state').text(data[id].project.pname);

        $('html,body').animate({ scrollTop: 0 }, 'slow');
        $('#collapse1').addClass('in show');

        $(".industry>option[value='" + data[id].project.industry + "']").prop(
            'selected',
            true
        );
        $('.pName').val(data[id].project.pname);
        $('.desc').val(data[id].project.description);

        $("#voice>option[value='" + data[id].project.voice + "']").prop(
            'selected',
            true
        );

        $('#cName').val(data[id].project.name);
        $('.cName').text(data[id].project.name); //preview - Name
        $('#previewName').text(data[id].project.name);
        $('#previewImg').attr('src', data[id].project.url);
        $('.img').attr('src', data[id].project.url); //prview -  profile
        $('#banner').summernote('code', data[id].project.banner); //banner
        $('#advert').summernote('code', data[id].project.advert); //advert
        $('#stream_banner').html(data[id].project.banner);
        $('.stype').val(data[id].project.stype);

        $('.select2').select2();
        $('#posts').attr('src', data[id].project.posts);
        $('#style').attr('src', data[id].project.style);

        // console.log(data);
        // new_field_group.find('span.select2').remove();
        for (const i in data[id].question) {
            let clone = queContainer
                .children()
                .filter('.item:first-child')
                .clone();
            clone
                .find('select>option[value="' + data[id].question[i][0] + '"]')
                .prop('selected', true);
            clone.find('span.select2').remove();
            clone.find('select.select2').select2();
            clone.find('input').val(data[id].question[i][1]);
            queContainer.append(clone);
        }
        queContainer.children().filter('.item:first-child').remove();
        let key = data[id].project.key;

        previewSocialStream(
            data[id].project.rssfeed,
            data[id].project.style,
            data[id].project.posts,
            voteData,
            key
        );

        $('.card-footer').show();
        // for (const i in data[id].keywords) {
        //     let clone = keyContainer.children().filter('.item:first-child').clone();
        //     clone.find('input').val(data[id].keywords[i][0]);
        //     clone.find('textarea').val(data[id].keywords[i][1]);
        //     keyContainer.append(clone);
        // }
        // keyContainer.children().filter('.item:first-child').remove();

        // for (const i in data[id].department) {
        //     let clone = dContainer.children().filter('.item:first-child').clone();
        //     clone.find('input.dName').val(data[id].department[i][1]);
        //     clone.find('input.dNo').val(data[id].department[i][2]);
        //     clone.find('select>option[value="' + data[id].department[i][0] + '"]').prop("selected", true);
        //     dContainer.append(clone);
        // }
        // dContainer.children().filter('.item:first-child').remove();

        $('.editor').on('click', function() {
            item = $(this).closest('.item');
            let data = $(item).data('item');
            if (data == 'que') {
                val = item.find('.Qans').val();
                if (val != '' && val.indexOf('RSS:') == -1)
                    $('#summernote').summernote('code', val);
                else $('#summernote').summernote('code');
            } else if (data == 'keyword') {
                val = item.find('.Kans').val();
                if (val != '' && val.indexOf('RSS:') == -1)
                    $('#summernote').summernote('code', val);
                else $('#summernote').summernote('code');
            }
        });

        $('.rss').on('click', function() {
            item = $(this).closest('.item');
            let data = $(item).data('item');
            if (data == 'que') {
                val = item.find('.Qans').val();
                if (val.indexOf('RSS:') === 0)
                    $('#rssUrl').val(val.replace('RSS:', ''));
                else $('#rssUrl').val('');
            } else if (data == 'keyword') {
                val = item.find('.Kans').val();
                if (val.indexOf('RSS:') === 0)
                    $('#rssUrl').val(val.replace('RSS:', ''));
                else $('#rssUrl').val('');
            }
        });
    });

    function showInlineUpdate() {
        $.getJSON(
            'https://suite.social/chat/data.json?version=' +
            new Date().getTime()
        ).done(function(json) {
            data = json[ipaddress];
            id = projectId;

            let queContainer = $('#question');
            let keyContainer = $('#keywords');
            let dContainer = $('#department');

            for (const i in data[id].question) {
                var index = Number(i) + 1;
                var field = queContainer.find(
                    'div.item:nth-child(' + index + ')'
                );
                field.find('input').val(data[id].question[i][0]);
                field.find('textarea').val(data[id].question[i][1]);
            }

            for (const i in data[id].keywords) {
                var index = Number(i) + 1;
                var field = keyContainer.find(
                    'div.item:nth-child(' + index + ')'
                );
                field.find('input').val(data[id].keywords[i][0]);
                field.find('textarea').val(data[id].keywords[i][1]);
            }

            for (const i in data[id].department) {
                var index = Number(i) + 1;
                var field = dContainer.find(
                    'div.item:nth-child(' + index + ')'
                );
                field.find('input.dName').val(data[id].department[i][1]);
                field.find('input.dNo').val(data[id].department[i][2]);
                field
                    .find(
                        'select>option[value="' +
                        data[id].department[i][0] +
                        '"]'
                    )
                    .prop('selected', true);
            }
        });
    }

    function getTime() {
        let dt = new Date();
        let hr = dt.getHours();
        let min = dt.getMinutes();
        10 > hr ? (hr = '0' + hr) : hr;
        10 > min ? (min = '0' + min) : min;
        12 > hr ?
            (time = hr + ':' + min + ' am') :
            (time = hr - 12 + ':' + min + ' pm');

        return time;
    }

    function speak(msg) {
        // responsiveVoice.speak(msg , voiceType);
    }

    let item;
    $(document).on('click', '.add-more', function(e) {
        e.preventDefault();
        var container = $(this).closest('.field');
        if (window.add_more_limit == container.find('.item').length && window.add_more_limit_active) {
            Swal.fire({
                title: 'Sorry!',
                html: "Sorry limit reached.",
                icon: 'warning',
                showCancelButton: false,
                timer: 3000,
                showConfirmButton: false,
                allowOutsideClick: false,
                timerProgressBar: false
            });
            return true;
        }
        new_field_group = container
            .children()
            .filter('.item:first-child')
            .clone();
        new_field_group.find('span.select2').remove();
        new_field_group.find('select.select2').val(container.children().filter('.item:first-child').find('select.select2').val());
        new_field_group.find('select.select2').select2();
        new_field_group.find('input').each(function() {
            $(this).val('');
        });
        new_field_group.find('textarea').each(function() {
            $(this).val('');
        });
        var scheduleInput = new_field_group.find('.schedule-input');
        var fieldNum = $('#question .item').length + 1;
        scheduleInput.find('input').attr('id', 'schedule'+fieldNum);
        scheduleInput.find('label').attr('for', 'schedule'+fieldNum);
        container.append(new_field_group);

        $(".your_input").on('input', function(key) {
            var value = $(this).val();
            $(this).val(value.replace(/ /g, '-'));
        })

        $('.editor').on('click', function() {
            item = $(this).closest('.item');
            let data = $(item).data('item');
            if (data == 'que') {
                val = item.find('.Qans').val();
                if (val != '' && val.indexOf('RSS:') == -1)
                    $('#summernote').summernote('code', val);
                else $('#summernote').summernote('code');
            } else if (data == 'keyword') {
                val = item.find('.Kans').val();
                if (val != '' && val.indexOf('RSS:') == -1)
                    $('#summernote').summernote('code', val);
                else $('#summernote').summernote('code');
            }
        });

        $('.rss').on('click', function() {
            item = $(this).closest('.item');
            let data = $(item).data('item');
            if (data == 'que') {
                val = item.find('.Qans').val();
                if (val.indexOf('RSS:') === 0)
                    $('#rssUrl').val(val.replace('RSS:', ''));
                else $('#rssUrl').val('');
            } else if (data == 'keyword') {
                val = item.find('.Kans').val();
                if (val.indexOf('RSS:') === 0)
                    $('#rssUrl').val(val.replace('RSS:', ''));
                else $('#rssUrl').val('');
            }
        });
    });
    $(document).on('click', '.remove', function(e) {
        e.preventDefault();
        var container = $(this).closest('.field');
        if (container.find('.item').length == 1)
            return false;
        $(this).closest('.item').remove();
    });

    $('.editor').on('click', function() {
        item = $(this).closest('.item');
        let data = $(item).data('item');
        let val;
        if (data == 'que') {
            val = item.find('.Qans').val();
            if (val != '' && val.indexOf('RSS:') == -1)
                $('#summernote').summernote('code', val);
            else $('#summernote').summernote('code');
        } else if (data == 'keyword') {
            val = item.find('.Kans').val();
            if (val != '' && val.indexOf('RSS:') == -1)
                $('#summernote').summernote('code', val);
            else $('#summernote').summernote('code');
        }
    });

    $('#saveEditor').on('click', function() {
        let val = $('.note-editable').html();
        let data = $(item).data('item');
        if (data == 'que') {
            $(item).find('.Qans').val(val);
        } else if (data == 'keyword') {
            $(item).find('.Kans').val(val);
        }
        $('#summernote').summernote('destroy');
    });

    $('.rss').on('click', function() {
        item = $(this).closest('.item');
        let data = $(item).data('item');
        if (data == 'que') {
            val = item.find('.Qans').val();
            if (val.indexOf('RSS:') === 0)
                $('#rssUrl').val(val.replace('RSS:', ''));
            else $('#rssUrl').val('');
        } else if (data == 'keyword') {
            val = item.find('.Kans').val();
            if (val.indexOf('RSS:') === 0)
                $('#rssUrl').val(val.replace('RSS:', ''));
            else $('#rssUrl').val('');
        }
    });

    $('#saveRss').on('click', function() {
        let val = $('#rssUrl').val();
        let data = $(item).data('item');
        if (data == 'que') {
            $(item)
                .find('.Qans')
                .val('RSS:' + val);
        } else if (data == 'keyword') {
            $(item)
                .find('.Kans')
                .val('RSS:' + val);
        }
        $('#rssUrl').val('');
    });
//});

function searchPanel() {
    var input, filter, cards, cardContainer, title, i, box;
    input = document.getElementById("search-panel");
    filter = input.value.toUpperCase();
    cardContainer = document.getElementById("panel_section");
    cards = cardContainer.getElementsByClassName("panel-title");
    for (i = 0; i < cards.length; i++) {
        title = cards[i];
        if (title.innerText.toUpperCase().indexOf(filter) > -1) {
            box = cards[i].closest('.card');
            box.style.display = "";
        } else {
            box = cards[i].closest('.card');
            box.style.display = "none";
        }
    }
}

function quicksearch(obj, eve, panelId) {
    eve.preventDefault();
    var cantainer = $('#' + panelId);
    var titleBox = cantainer.find('.section-title');
    var filter = $(obj).val().toUpperCase();
    $.each(titleBox, function() {
        var titleText = $(this).text().toUpperCase();
        if (titleText.indexOf(filter) > -1) {
            $(this).closest('.dcsns-li').show('slow');
            $(this).closest('.dcsns-li').css('top', 'auto');
            $(this).closest('.dcsns-li').css('position', '');


        } else {
            $(this).closest('.dcsns-li').hide('slow');
        }
    })
}

function changeSType(event) {
    var fieldGroup = $(event.target).closest('.item');
    switch (event.target.value) {
        case 'rss':
            fieldGroup.find('.stype-group:not(.rss-management)').hide();
            fieldGroup.find('.schedule-input').show();
            fieldGroup.find('.rss-management').show();
            fieldGroup.find('.rss-management .hide-on-bookmark').show();
            fieldGroup.find('.rss-management .select2').show();
            fieldGroup.find('.rss-management .hide-on-rss').hide();
            fieldGroup.find('.rss-management label .label-text').text("What RSS?");
            $('.label-panel-name').text(3);
            break;
        case 'bookmark':
            fieldGroup.find('.stype-group:not(.bookmark-management)').hide();
            fieldGroup.find('.schedule-input').show();
            fieldGroup.find('.rss-management').show();
            fieldGroup.find('.rss-management .hide-on-bookmark').hide();
            fieldGroup.find('.rss-management .select2').hide();
            fieldGroup.find('.rss-management .show-on-bookmark').show();
            fieldGroup.find('.rss-management label .label-text').text("What bookmark feed?");
            $('.label-panel-name').text(3);
            break;
        case 'email':
            fieldGroup.find('.stype-group:not(.email-management)').hide();
            fieldGroup.find('.schedule-input').show();
            fieldGroup.find('.rss-management').show();
            fieldGroup.find('.rss-management .hide-on-email').hide();
            fieldGroup.find('.rss-management .select2').hide();
            fieldGroup.find('.rss-management .show-on-email').show();
            fieldGroup.find('.rss-management label .label-text').text("What email feed?");
            $('.label-panel-name').text(3);
            break;
        case 'csv':
            fieldGroup.find('.stype-group:not(.csv-management)').hide();
            fieldGroup.find('.schedule-input').hide();
            fieldGroup.find('.csv-management').show();
            $('.label-panel-name').text(3);
            break;
        default:
            fieldGroup.find('.stype-group:not(.social-management)').hide();
            fieldGroup.find('.schedule-input').show();
            fieldGroup.find('.social-management').show();
            $('.label-panel-name').text(4);
            break;
    }
}

/*================ Imort Image Url ===================*/
function popupImages(url, searchImageBtn)
{
    var w = 1024;
    var h = 768;
    var title = 'Images';
    var left = (screen.width / 2) - (w / 2);
    var top = (screen.height / 2) - (h / 2);
    var popupWindow = window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    addMessageListener(searchImageBtn);
}

function addMessageListener(searchImageBtn) {
    window.addEventListener('message', (event) => importImageUrl(event, searchImageBtn));
}
  
function importImageUrl(event, searchImageBtn) {
    if (event.data.imageUrl) {
        let imageUrl = event.data.imageUrl;
        const parentForm = searchImageBtn.closest('form');
        const textBox = parentForm.querySelector('[name="image_url"]');
        textBox.value = imageUrl;
    }
}

/*====================== Add New Post ====================*/
$(document).ready(function () {
    // Add panel id to add post form
    $('#modal-add').on('shown.bs.modal', function (e) {
        let btn = e.relatedTarget;
        let panelId = btn.dataset.panelId;
        $('#modal-add #addPostForm input[name="panel_id"]').val(panelId);
    });
})

function savePost(self)
{
    $.ajax({
        url: "post.php?action=create",
        type: "POST",
        data: $('#addPostForm').serialize(),
        beforeSend: function (xhr) {
            $(self).html('<span class="spinner-border spinner-border-sm d-inline-block"></span> Saving...');
        },
        success: function (res) {
            $(self).html('Save Post');
            res = JSON.parse(res);
            if (res.status) {
            $('#addPostForm')[0].reset();
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Post created successfully!',
                timer: 3000,
                timerProgressBar: true,
            });
            let newPost = res.data;
            let newItem = $('<li class="dcsns-li dcsns-rss dcsns-feed-0">' +
                                '<div class="inner">' +
                                    '<span class="thumb-5 section-thumb">' +
                                        '<a data-image_count="5" href="'+newPost.link+'">' +
                                            '<img src="'+newPost.image_url+'" alt="" style="opacity: 1; display: inline;">' +
                                        '</a>' +
                                    '</span>' +
                                    '<span class="title-5 section-title">' +
                                        '<a href="'+newPost.link+'">'+newPost.title+'</a>' +
                                    '</span>' +
                                    '<span class="text-5 section-text">'+newPost.description+'</span>' +
                                    '<span class="user-5 section-user"></span>' +
                                    '<span class="share-5 section-share">' +
                                        '<div class="d-flex justify-content-center align-items-center mb-4">' +
                                            '<a href="https://www.facebook.com/sharer.php?u=https%3A%2F%2Fwww.amazon.co.uk%2FBest-Sellers-Sales-Marketing%2Fzgbs%2Fbooks%2F268314&amp;t=Amazon.co.uk%20Best%20Sellers%3A%20The%20most%20popular%20items%20in%20Sales%20..." class="share-facebook" target="_blank"><i class="fab fa-facebook-f"></i></a>' +
                                            '<a href="https://www.linkedin.com/shareArticle?mini=true&amp;url=https%3A%2F%2Fwww.amazon.co.uk%2FBest-Sellers-Sales-Marketing%2Fzgbs%2Fbooks%2F268314&amp;title=Amazon.co.uk%20Best%20Sellers%3A%20The%20most%20popular%20items%20in%20Sales%20..." class="share-linkedin" target="_blank"><i class="fab fa-linkedin-in"></i></a>' +
                                            '<a href="https://pinterest.com/pin/create/button/?url=https%3A%2F%2Fwww.amazon.co.uk%2FBest-Sellers-Sales-Marketing%2Fzgbs%2Fbooks%2F268314&amp;media=https%3A%2F%2Fwww.amazon.co.uk%2FBest-Sellers-Sales-Marketing%2Fzgbs%2Fbooks%2F268314&amp;description=Amazon.co.uk%20Best%20Sellers%3A%20The%20most%20popular%20items%20in%20Sales%20..." class="share-pinterest" target="_blank"><i class="fab fa-pinterest"></i></a>' +
                                            '<a href="https://reddit.com/submit?url=https%3A%2F%2Fwww.amazon.co.uk%2FBest-Sellers-Sales-Marketing%2Fzgbs%2Fbooks%2F268314&amp;title=Amazon.co.uk%20Best%20Sellers%3A%20The%20most%20popular%20items%20in%20Sales%20..." class="share-reddit" target="_blank"><i class="fab fa-reddit"></i></a>' +
                                            '<a href="https://web.skype.com/share?url=https%3A%2F%2Fwww.amazon.co.uk%2FBest-Sellers-Sales-Marketing%2Fzgbs%2Fbooks%2F268314&amp;text=https%3A%2F%2Fwww.amazon.co.uk%2FBest-Sellers-Sales-Marketing%2Fzgbs%2Fbooks%2F268314" class="share-skype" target="_blank"><i class="fab fa-skype"></i></a>' +
                                            '<a href="https://t.me/share/url?url=https%3A%2F%2Fwww.amazon.co.uk%2FBest-Sellers-Sales-Marketing%2Fzgbs%2Fbooks%2F268314&amp;text=Amazon.co.uk%20Best%20Sellers%3A%20The%20most%20popular%20items%20in%20Sales%20..." class="share-telegram" target="_blank"><i class="fab fa-telegram"></i></a>' +
                                            '<a href="https://www.tumblr.com/widgets/share/tool?canonicalUrl=https%3A%2F%2Fwww.amazon.co.uk%2FBest-Sellers-Sales-Marketing%2Fzgbs%2Fbooks%2F268314&amp;title=Amazon.co.uk%20Best%20Sellers%3A%20The%20most%20popular%20items%20in%20Sales%20...&amp;caption=https%3A%2F%2Fwww.amazon.co.uk%2FBest-Sellers-Sales-Marketing%2Fzgbs%2Fbooks%2F268314" class="share-tumblr" target="_blank"><i class="fab fa-tumblr"></i></a>' +
                                            '<a href="https://twitter.com/share?url=https%3A%2F%2Fwww.amazon.co.uk%2FBest-Sellers-Sales-Marketing%2Fzgbs%2Fbooks%2F268314&amp;text=Amazon.co.uk%20Best%20Sellers%3A%20The%20most%20popular%20items%20in%20Sales%20...&amp;via=suite.social" class="share-twitter" target="_blank"><i class="fab fa-twitter"></i></a>' +
                                            '<a href="https://api.whatsapp.com/send?text=https%3A%2F%2Fwww.amazon.co.uk%2FBest-Sellers-Sales-Marketing%2Fzgbs%2Fbooks%2F268314" class="share-whatsapp" target="_blank"><i class="fab fa-whatsapp"></i></a>' +
                                            '<a href="https://mail.google.com/mail/?view=cm&amp;to={email_address}&amp;su=Amazon.co.uk%20Best%20Sellers%3A%20The%20most%20popular%20items%20in%20Sales%20...&amp;body=https%3A%2F%2Fwww.amazon.co.uk%2FBest-Sellers-Sales-Marketing%2Fzgbs%2Fbooks%2F268314" class="share-google" target="_blank"><i class="fab fa-google"></i></a>' +
                                        '</div>' +
                                        '<div class="">' +
                                            '<button style="background: #343a40;" class="editPost btn btn-sm btn-secondary mb-3 d-block w-100">' +
                                                '<i class="fa-solid fa-plus"></i> EDIT POST' +
                                            '</button>' +
                                            '<div class="d-flex justify-content-center">' +
                                                (newPost.hasNotes? '<button class="btn p-0 text-light mx-1"><i class="fa-solid fa-file-lines"></i></button>': '') + 
                                                '<button class="btn p-0 text-light mx-1" onclick="deletePost(this, \''+newPost.title+'\')">' +
                                                    '<i class="fa-solid fa-trash"></i>' +
                                                '</button>' +
                                            '</div>' +
                                        '</div>' +
                                    '</span>' +
                                    '<span class="clear"></span>' +
                                '</div>' +
                                '<a href=""><span class="socicon socicon-rss"></span></a>' +
                            '</li>');
            let stream = $('[data-panel="'+newPost.panel_id+'"] .stream');
            stream.prepend(newItem).isotope('prepended', newItem);
            $('li.dcsns-rss .section-thumb img',stream).css('opacity',0).show().fadeTo(800,1);
                        $('img',stream).on('load', function(){ stream.isotope('layout'); });
            } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: res.message,
                timer: 3000,
                timerProgressBar: true,
            })
            }
        },
        error: function (err) {
            //alert('error='+JSON.stringify(err));
        },
    });
}

/*====================== Edit Post ====================*/
$(document).ready(function () {
    // Load post data in form fields
    $('#modal-edit').on('show.bs.modal', function (e) {
        let btn = e.relatedTarget;
        let panel = btn.closest('.row[data-panel]');
        if (!panel) return;
        let panelId = panel.dataset.panel;
        let data = $(btn).closest('li');
    
        data.addClass('editing');

        $('#modal-edit #editPostForm input[name="panel_id"]').val(panelId);
        $('#modal-edit #editPostForm input[name="current_title"]').val(data.find('.section-title a').text());
	    $('#modal-edit input[name="post_id"]').val(data.find('.section-title a').attr('data-id'));
        $('#modal-edit input[name="image_url"]').val(data.find('.section-thumb img').attr('src'));
        $('#modal-edit input[name="title"]').val(data.find('.section-title a').text());
        $('#modal-edit input[name="pubDate"]').val(data.find('.section-title a').attr('data-date'));
        $('#modal-edit textarea[name="description"]').val(data.find('.section-text').text());
        $('#modal-edit input[name="link"]').val(data.find('.section-title a').attr('href'));
        $('#modal-edit textarea[name="notes"]').val(data.find('.post-notes').text());
    });
});

function updatePost(self) {
    const postEl = document.querySelector('li.editing');
    $.ajax({
      url: "post.php?action=update",
      type: "POST",
      data: $('#editPostForm').serialize(),
      beforeSend: function (xhr) {
            $(self).html('<span class="spinner-border spinner-border-sm d-inline-block"></span> Saving...');
        },
      success: function (res) {
        $(self).html('Save Changes');
        res = JSON.parse(res);
        if (!res.status) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: res.message? res.message: 'Post could not be updated',
            timer: 3000,
            timerProgressBar: true,
          });
          return;
        }
  
        // Update post view
        let updatedPost = res.data;
        const img = postEl.querySelector('.section-thumb img');
        const link = postEl.querySelector('.section-thumb a');
        if (img) img.src = updatedPost.image_url;
        if (link) link.href = updatedPost.link;
        postEl.querySelector('.section-title a').textContent = updatedPost.title;
        postEl.querySelector('.section-text').textContent = updatedPost.description;
        postEl.querySelector('.section-title a').href = updatedPost.link;
        postEl.querySelector('.post-notes').textContent = updatedPost.notes;
  
        const fileIcon = postEl.querySelector('.notes-icon');
        if (updatedPost.hasNotes && !fileIcon) {
          let el = postEl.querySelector('.post-tools');
          el.innerHTML = '<button class="btn p-0 text-light mx-1 notes-icon" data-target="#modal-edit" data-toggle="modal"><i class="fa-solid fa-file-lines"></i></button>' + el.innerHTML;
        } else if(!updatedPost.hasNotes && fileIcon) {
          fileIcon.remove();
        }
  
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: 'Post updated successfully!',
          timer: 3000,
          timerProgressBar: true,
        });
      }
    })
  }
  
  // Remove editing class on modal close
  $('#modal-edit').on('hidden.bs.modal', function () {
    document.querySelector('li.editing').classList.remove('editing');
  });

/*====================== Delete Post ====================*/
function deletePost(self, title) {
    let panel = self.closest('.row[data-panel]');
    if (!panel) return;
    let panelId = panel.dataset.panel;
    Swal.fire({
      title: 'Are you sure?',
      text: "Do you really want to delete this?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'No',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'post.php?action=delete',
          type: 'POST',
          data: {panel_id: panelId, title: title},
          success: function (res) {
            res = JSON.parse(res);
            if (res.status) {
              const item = self.closest('li.dcsns-li');
              $(self.closest('.stream')).isotope('remove', item).isotope('layout');
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Post could not be deleted!',
                timer: 3000,
                timerProgressBar: true,
              })
            }
          }
        })  
      }
    })
  }

/*====================== Edit Panel Keywords ========================*/
const loadedKeywords = {};
const keywordFields = document.querySelector('#keywordFields');
$(document).ready(function () {
    // Display panel keywords in modal
    $('#modal-panel_settings').on('shown.bs.modal', function (e) {
        let btn = e.relatedTarget;
        let panelTitle = btn.closest('.card').querySelector('.card-title').textContent;
        $('#modal-panel_settings .modal-title').text(panelTitle);

        let panelId = btn.dataset.panelId;
        loadPanelKeywords(panelId, function (keywords) {
            displayPanelKeywords(keywords);
            $('#modal-panel_settings .loader').css('transform', 'translateY(-100%)');
        });
        $('#modal-panel_settings .loader').css('transform', 'translateY(0)');
    });
    $('#modal-panel_settings').on('hidden.bs.modal', function (e) {
        $('#modal-panel_settings .network-cards').html('');
    });
});
function addKeywordField(self) {
  self.classList.add('d-none');
  self.parentNode.querySelector('.rm-field').classList.remove('d-none');
  let html = '<div class="input-group mb-2">' + 
              '<input type="text" class="form-control new-field" name="panel_keywords[]">' +
              '<button type="button" class="add-field btn btn-success btn-sm btn-flat" onclick="addKeywordField(this)"><i class="fa fa-plus"></i></button>' +
              '<button type="button" class="rm-field btn btn-danger btn-sm btn-flat d-none" onclick="rmKeywordField(this)"><i class="fa fa-minus"></i></button>' +
              '</div>';
  $(self.closest('.keyword-fields')).append(html);
}

function rmKeywordField(self) {
  self.parentNode.remove();
}

function saveKeywords(self) {
  $.ajax({
    url: 'store_ajax_panel.php',
    type: 'POST',
    data: $(self.closest('form')).serialize(),
    beforeSend: function () {
      $(self).html('<span class="spinner-border spinner-border-sm d-inline-block"></span> Saving...')
    },
    success: function (res) {
      res = JSON.parse(res);
      $(self).html('Submit');
      if (res.status) {
        displayPanelKeywords(res.data);
        $('#' + res.panel).find('.social-stream').html('');
        panel_reload_stream(res, true);
        Swal.fire({
            title: 'Searching For Posts',
            html: 'Please Wait',
            timer: 5000,
            showConfirmButton: false,
            allowOutsideClick: false,
            timerProgressBar: true
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: res.message? res.message: 'Could not save keywords!',
          timer: 3000,
          timerProgressBar: true,
        });
      }
    }
  })
}

function loadPanelKeywords(panelId, callback) {
  $.ajax({
    url: 'store_ajax_panel.php',
    type: 'POST',
    data: {
        ajax_action: 'get_panel_keywords',
        panel_id: panelId
    },
    success: function (res) {
      res = JSON.parse(res);
      if (res.status) {
        callback(res.data);
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: res.message? res.message: 'Could not Load Keywords!',
          timer: 3000,
          timerProgressBar: true,
        });
      }
    }
  })
}

function deletePanelKeyword(self) {
  let id = self.dataset.keywordId;
  $.ajax({
    url: 'store_ajax_panel.php',
    type: 'POST',
    data: {
        id: id,
        ajax_action: 'delete_panel_keyword',
    },
    beforeSend: function () {
      $(self).html('<span class="spinner-border spinner-border-sm d-inline-block"></span>')
    },
    success: function (res) {
      res = JSON.parse(res);
      if (res.status) {
        $(self.parentNode).fadeOut();
        setTimeout(() => {
          self.parentNode.remove();
        }, 3000);
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: res.message? res.message: 'Could not delete keyword!',
          timer: 3000,
          timerProgressBar: true,
        });
      }
    }
  })
}

function displayPanelKeywords(keywords) {
    if (keywords.length === 0) return;

    $('#modal-panel_settings .network-cards').html('');
    if (!loadedKeywords[keywords[0].panel_id]) {
        loadedKeywords[keywords[0].panel_id] = {};
    }
    // Sort keywords by network
    keywords.map(keyword => {
        if (!loadedKeywords[keywords[0].panel_id][keyword['network_type']])
            loadedKeywords[keywords[0].panel_id][keyword['network_type']] = [];
        let keywordExists = false;
        loadedKeywords[keywords[0].panel_id][keyword['network_type']].map(k => {
            if (k.keywork.toLowerCase() === keyword.keywork.toLowerCase())
                keywordExists = true;
        })
        if (!keywordExists)
            loadedKeywords[keywords[0].panel_id][keyword['network_type']].push(keyword);
    });

    let sortedKeywords = loadedKeywords[keywords[0].panel_id];
    
    for (const network in sortedKeywords) {
        if (!sortedKeywords.hasOwnProperty(network))
            continue;
        if (!sortedKeywords[network].is_scheduled === 1)
            continue;

        let panelId = sortedKeywords[network][0].panel_id;
        let pageType = sortedKeywords[network][0].page_type;
        let perPage = sortedKeywords[network][0].per_page_limit;
        let isFullTextFeed = sortedKeywords[network][0].is_full_text_feed;
        let html = '<div class="card card-primary">' +
                        '<div class="card-header">' +
                            '<h3 class="card-title">'+network+'</h3>' +
                        '</div>' +
                        '<form>' +
                            '<div class="card-body">' +
                            '<input type="hidden" name="ajax_action" value="save_panel_keywords">' +
                            '<input type="hidden" name="group_id" value="'+$('[name="group"]').val()+'">' +
                            '<input type="hidden" name="panel_id" value="'+panelId+'">' +
                            '<input type="hidden" name="network_type" value="'+network+'">' +
                            '<input type="hidden" name="page_type" value="'+pageType+'">' +
                            '<input type="hidden" name="per_page_limit" value="'+perPage+'">' +
                            '<input type="hidden" name="is_full_text_feed" value="'+isFullTextFeed+'">' +
                                '<label>Delete or add keywords</label>' +
                                '<div class="form-group keyword-fields">';
        sortedKeywords[network].forEach(({id, keywork}) => {
            html += '<div class="input-group mb-2">' + 
                        '<input type="text" class="form-control" name="panel_keywords[]" value="'+keywork+'">' +
                        '<button type="button" class="add-field btn btn-success btn-sm btn-flat d-none" onclick="addKeywordField(this)"><i class="fa fa-plus"></i></button>' +
                        '<button type="button" class="rm-field btn btn-danger btn-sm btn-flat" onclick="deletePanelKeyword(this)" data-keyword-id="'+id+'"><i class="fa fa-minus"></i></button>' +
                    '</div>';
        });
    
        html += '<div class="input-group mb-2">' + 
                    '<input type="text" class="form-control new-field" name="panel_keywords[]">' +
                    '<button type="button" class="add-field btn btn-success btn-sm btn-flat" onclick="addKeywordField(this)"><i class="fa fa-plus"></i></button>' +
                    '<button type="button" class="rm-field btn btn-danger btn-sm btn-flat d-none" onclick="rmKeywordField(this)"><i class="fa fa-minus"></i></button>' +
                '</div>';
    
        html +=     '</div>' +
                    '<div class="mt-4">' +
                        '<button type="button" class="btn btn-primary" onclick="saveKeywords(this)">Submit</button>' +
                    '</div>' +
                    '<br>' +
                    '<small>Press <span class="fa fa-plus"></span> to add another form field :)</small>' +
                '</div>' +
                '</form>' +
                '</div>';
        $('#modal-panel_settings .network-cards').append(html);
    }
}

/*====================== Edit Panel Rss Feed ========================*/
const rssFields = document.querySelector('#rssFields');
$(document).ready(function () {
    // Display panel Rss urls in modal
    $('#modal-panel_settings').on('shown.bs.modal', function (e) {
        let btn = e.relatedTarget;
        let panelId = btn.dataset.panelId;
        $('#modal-panel_settings #importRssForm [name="panel_id"]').val(panelId);
        loadRssUrls(panelId);
    });
    $('#modal-panel_settings').on('hidden.bs.modal', function (e) {
        $(rssFields).html('');
    });
});

function addRssField(self) {
  self.classList.add('d-none');
  self.parentNode.querySelector('.rm-field').classList.remove('d-none');
  let html = '<div class="input-group mb-2">' + 
              '<input type="text" class="form-control new-field" name="urls[]">' +
              '<button type="button" class="add-field btn btn-success btn-sm btn-flat" onclick="addRssField(this)"><i class="fa fa-plus"></i></button>' +
              '<button type="button" class="rm-field btn btn-danger btn-sm btn-flat d-none" onclick="rmRssField(this)"><i class="fa fa-minus"></i></button>' +
              '<button type="button" class="btn btn-default btn-sm btn-flat d-none"><i class="fa fa-clock"></i></button>' +
              '</div>';
  $(rssFields).append(html);
}

function rmRssField(self) {
  self.parentNode.remove();
}

function scheduleRss(self) {
  $.ajax({
    url: 'store_ajax_panel.php',
    type: 'POST',
    data: $('#importRssForm').serialize(),
    beforeSend: function () {
      $(self).html('<span class="spinner-border spinner-border-sm d-inline-block"></span> Saving...')
    },
    success: function (res) {
      res = JSON.parse(res);
      $(self).html('Submit');
      if (res.status) {
        displayRssUrls(res.data);
        $('#' + res.panel).find('.social-stream').html('');
        panel_reload_stream(res, true);
        Swal.fire({
            title: 'Searching For Posts',
            html: 'Please Wait',
            timer: 5000,
            showConfirmButton: false,
            allowOutsideClick: false,
            timerProgressBar: true
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: res.message? res.message: 'Could not schedule Rss!',
          timer: 3000,
          timerProgressBar: true,
        });
      }
    }
  })
}

function loadRssUrls(panelId) {
  $.ajax({
    url: 'store_ajax_panel.php',
    type: 'POST',
    data: {
        ajax_action: 'get_rss_urls',
        panel_id: panelId
    },
    success: function (res) {
      res = JSON.parse(res);
      if (res.status) {
        displayRssUrls(res.data);
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: res.message? res.message: 'Could not Load Rss Urls!',
          timer: 3000,
          timerProgressBar: true,
        });
      }
    }
  })
}

function deleteRssUrl(self) {
  let id = self.dataset.id;

  $.ajax({
    url: 'store_ajax_panel.php',
    type: 'POST',
    data: {
        id: id,
        ajax_action: 'delete_rss_url',
    },
    beforeSend: function () {
      $(self).html('<span class="spinner-border spinner-border-sm d-inline-block"></span>')
    },
    success: function (res) {
      res = JSON.parse(res);
      if (res.status) {
        $(self.parentNode).fadeOut();
        setTimeout(() => {
          self.parentNode.remove();
        }, 3000);
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: res.message? res.message: 'Could not delete rss url!',
          timer: 3000,
          timerProgressBar: true,
        });
      }
    }
  })
}

function displayRssUrls(urls) {
  let html = '';
  urls.forEach((url) => {
    html += '<div class="input-group mb-2">' + 
              '<input type="text" class="form-control" name="urls[]" value="'+url.url+'">' +
              '<button type="button" class="add-field btn btn-success btn-sm btn-flat d-none" onclick="addRssField(this)"><i class="fa fa-plus"></i></button>' +
              '<button type="button" class="rm-field btn btn-danger btn-sm btn-flat" onclick="deleteRssUrl(this)" data-id="'+url.id+'"><i class="fa fa-minus"></i></button>' +
            '</div>';
  });

  html += '<div class="input-group mb-2">' + 
              '<input type="text" class="form-control new-field" name="urls[]">' +
              '<button type="button" class="add-field btn btn-success btn-sm btn-flat" onclick="addRssField(this)"><i class="fa fa-plus"></i></button>' +
              '<button type="button" class="rm-field btn btn-danger btn-sm btn-flat d-none" onclick="rmRssField(this)"><i class="fa fa-minus"></i></button>' +
          '</div>';

  $(rssFields).html(html);
}

/*====================== Select Default Feed ========================*/
function selectDefaultFeed(self) {
    const urlField = $(self.parentNode).find('[name="rss_urls[]"]');
    if ($(self).val()) {
        urlField.val($(self).val());
    } else {
        urlField.val('');
    }
}

/*====================== Import Bookmarks ========================*/
function openBookmarksImportWindow(url, self)
{
    var w = 1024;
    var h = 768;
    var title = 'Import Bookmarks';
    var left = (screen.width / 2) - (w / 2);
    var top = (screen.height / 2) - (h / 2);
    var popupWindow = window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    addMessageListenerToImportBookmarkBtn(self);
}

function addMessageListenerToImportBookmarkBtn(btn) {
    window.addEventListener('message', (event) => importBookmarksFeed(event, btn));
}
  
function importBookmarksFeed(event, btn) {
    if (event.data.url) {
        let feedUrl = event.data.url;
        // feedUrl = feedUrl.replace('./', '');
        // feedUrl = baseUrl + '/bookmark/'+ feedUrl;
        // feedUrl = feedUrl.replace('//', '/');
        const fromGroup = btn.closest('.form-group');
        const textBox = fromGroup.querySelector('[name="rss_urls[]"]');
        textBox.value = feedUrl;
    }
}

/*====================== Import Emails ========================*/
function openEmailsImportWindow(url, self)
{
    var w = 1024;
    var h = 768;
    var title = 'Import Emails';
    var left = (screen.width / 2) - (w / 2);
    var top = (screen.height / 2) - (h / 2);
    var popupWindow = window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    addMessageListenerToImportEmailBtn(self);
}

function addMessageListenerToImportEmailBtn(btn) {
    window.addEventListener('message', (event) => importEmailsFeed(event, btn));
}
  
function importEmailsFeed(event, btn) {
    if (event.data.url) {
        let feedUrl = event.data.url;
        const fromGroup = btn.closest('.form-group');
        const textBox = fromGroup.querySelector('[name="rss_urls[]"]');
        textBox.value = feedUrl;
    }
}

function showTrash(id) {
    $('#trash_data').DataTable().destroy();
      $('#trash_data').DataTable({
             "processing": true ,
             "pageLength": 5,
             "ajax": 'trash.php?action=trash_list&panel_id='+id,
             "columnDefs": [{
                "targets": 0, "data": "", "render": function (data, type, row, meta) {
                    return '' +
                            '<span>Panel: '+row.panel_name+'<br>'+row.title+'</span>';
    
                }
            },
            {
                "targets": 1, "data": "", "render": function (data, type, row, meta) {
                    let date = '<span>'+row.date+'</span>';
                    return date;

                }
            },
                {
                    "targets": 2, "data": "", "render": function (data, type, row, meta) {
                        let title = `'${row.title}'`
                        let buttons = '<button type="button" class="btn btn-sm btn-danger" onclick="hardDelete(' + title + ', ' + row.panel_id + ')"><i class="fa-solid fa-trash-can"></i></button>'+
						'<button type="button" class="btn btn-sm btn-success" onclick="reloadTrash(' + title + ', ' + row.panel_id + ')"><i class="fa-solid fa-recycle"></i></button>';
                        
                        return buttons;
    
                    }
                }
            ]
    
         });
     
    $('#modal-trash').modal('show');
}

function reloadTrash(title, id){
    $.ajax({
        type: 'post',
        url: 'trash.php',
        data: {title: title, panel_id: id, action: "trash_reload"},
        success: function (response) {
            Swal.fire({
                title: 'Done!',
                text: 'Post Restrored Successfully.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            })
            $('#trash_data').DataTable().ajax.reload();
            let id = "#feed_panel_"+response.trim()+" .refresh";
            
            $(id).trigger('click');

        }

    });
}

function hardDelete(title, id){
    $.ajax({
        type: 'post',
        url: 'trash.php',
        data: {title: title, panel_id: id, action: "trash_remove"},
        success: function (response) {
            Swal.fire({
                title: 'Done!',
                text: 'Post Deleted Successfully.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            })
            $('#trash_data').DataTable().ajax.reload();
        }

    });
}

function showSettingsDialog(id)
{
    $("#moderation_panel_id").val(id)
    $.ajax({
        type: 'post',
        url: 'trash.php',
        data: {panel_id: id, action: "get_rss_link"},
        success: function (response) {
            response = JSON.parse(response)
            if(response.rss_feed_url){
                $("#settings_rss_link").val(response.rss_feed_url)
            }else{
                $("#settings_rss_link").val('')
            }

            if(response.published_rss_feed){
                $("#InputPublishingRSS").val(response.published_rss_feed)
            }else{
                $("#InputPublishingRSS").val('')
            }

            if(response.moderation_page_url){
                $("#InputModerationPage").val(response.moderation_page_url)
                document.getElementById("enable-moderation-page").disabled = true;
            }else{
                $("#InputModerationPage").val('')
                document.getElementById("disable-moderation-page").disabled = false;
                document.getElementById("enable-moderation-page").disabled = false;
            }

            if(response.publish_page_url){
                $("#InputPublishingPage").val(response.publish_page_url)
            }else{
                $("#InputPublishingPage").val('')
            }

            if(response.is_page_disbaled === 'yes'){
                document.getElementById("disable-moderation-page").disabled = true;
                document.getElementById("enable-moderation-page").disabled = false;
            }
        
            $('#modal-settings').modal('show');
        }

    });
    $('#modal-settings').modal('show');
}

function copyText() {
    /* Get the text field */
    var copyText = document.getElementById("settings_rss_link");
    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    /* Copy the text inside the text field */
    document.execCommand("copy");

    $('#myTooltip').attr('title', "Copied: " + copyText.value);
}

function updateText() {
    $('#myTooltip').attr('title', "Copy to clipboard");
}

function enableModerationPage()
{
    let panel_id = $("#moderation_panel_id").val();
    $.ajax({
        type: 'post',
        url: 'moderation.php',
        data: {panel_id: panel_id, action: "enable_moderation_page"},
        success: function (response) {
            response = JSON.parse(response)
            if(response.type === 'success')
            {
             Swal.fire({
                 title: 'Done!',
                 text: response.message,
                 icon: 'success',
                 timer: 1500,
                 showConfirmButton: false
             })
            }else{
             Swal.fire({
                 title: 'Error!',
                 text: response.message,
                 icon: 'error',
                 timer: 1500,
                 showConfirmButton: false
             })
            }

            if(response.published_rss_feed){
                $("#InputPublishingRSS").val(response.published_rss_feed)
            }

            if(response.moderation_page_url){
                $("#InputModerationPage").val(response.moderation_page_url)
                document.getElementById("enable-moderation-page").disabled = true;
            }

            if(response.publish_page_url){
                $("#InputPublishingPage").val(response.publish_page_url)
            }

            document.getElementById("disable-moderation-page").disabled = false;

            if(response.is_page_disbaled === 'yes'){
                document.getElementById("disable-moderation-page").disabled = true;
                ument.getElementById("enable-moderation-page").disabled = false;
            }
        
        }

    });
}

function disbaleModerationPage()
{
    let panel_id = $("#moderation_panel_id").val();
    $.ajax({
        type: 'post',
        url: 'moderation.php',
        data: {panel_id: panel_id, action: "disable_moderation_page"},
        success: function (response) {
            response = JSON.parse(response)
           if(response.type === 'success')
           {
            Swal.fire({
                title: 'Done!',
                text: response.message,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            })
            document.getElementById("disable-moderation-page").disabled = true;
            document.getElementById("enable-moderation-page").disabled = false;
           }else{
            Swal.fire({
                title: 'Error!',
                text: response.message,
                icon: 'error',
                timer: 1500,
                showConfirmButton: false
            })
           }
        }

    });
}

function deleteModerationPage()
{
    let panel_id = $("#moderation_panel_id").val();
    $.ajax({
        type: 'post',
        url: 'moderation.php',
        data: {panel_id: panel_id, action: "delete_moderation_page"},
        success: function (response) {
            response = JSON.parse(response)
            if(response.type === 'success')
            {
             Swal.fire({
                 title: 'Done!',
                 text: response.message,
                 icon: 'success',
                 timer: 1500,
                 showConfirmButton: false
             })
            }else{
             Swal.fire({
                 title: 'Error!',
                 text: response.message,
                 icon: 'error',
                 timer: 1500,
                 showConfirmButton: false
             })
            }
            $("#InputModerationPage").val('');
            $("#InputPublishingPage").val('');
            $("#InputPublishingRSS").val('');
            document.getElementById("enable-moderation-page").disabled = false;
            document.getElementById("disable-moderation-page").disabled = false;
        }

    });
}

function visitURL(type)
{
    let url = '';
    if(type === "moderation"){
        url = $("#InputModerationPage").val();
    }else{
        url = $("#InputPublishingPage").val();
    }
    
    if(url){
        window.open(url, '_blank');
    }
}


function copyPublishedText() {
    /* Get the text field */
    var copyText = document.getElementById("InputPublishingRSS");
    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    /* Copy the text inside the text field */
    document.execCommand("copy");

    $('#myTooltip1').attr('title', "Copied: " + copyText.value);
}

function updatePublishedText() {
    $('#myTooltip1').attr('title', "Copy to clipboard");
}

function openStatusModal(self, status, title)
{
    let panel = self.closest('.row[data-panel]');
    if (!panel) return;

    let panelId = panel.dataset.panel;
    let label = status;
    if(status === 'null'){
        label = "Pending"
    }
    let checkbox_id = label+"_checkbox";
    
    let statuses = ["Approved", "Closed", "Completed", "On Hold", "Open", "Pending", "Published", "Replied"]
    for (let i = 0; i < statuses.length; i++) {
        if(statuses[i] !== label){
           document.getElementById(statuses[i]+"_checkbox").checked = false;
            document.getElementById(statuses[i]+"_list").classList.remove('done');
        }else{
            document.getElementById(checkbox_id).checked = true;
            document.getElementById(label+"_list").classList.add('done');
        }
    }
    $("#status_panel_id").val(panelId);
    $("#status_panel_title").val(title);
    $("#status_current_value").val(label);

    $('#modal-status').modal('show');
}

function changeStatusValue(self)
{
    let label = self.value;
    let status = ["Approved", "Closed", "Completed", "On Hold", "Open", "Pending", "Published", "Replied"]
    for (let i = 0; i < status.length; i++) {
        if(status[i] !== label){
           document.getElementById(status[i]+"_checkbox").checked = false;
            document.getElementById(status[i]+"_list").classList.remove('done');
        }
    }
    $("#status_current_value").val(label);
}

function savePostStatus()
{
   let panel_id = $("#status_panel_id").val();
   let title = $("#status_panel_title").val();
   let status =  $("#status_current_value").val();
  
   if(document.getElementById(status+"_checkbox").checked === false){
    Swal.fire({
        title: 'Error!',
        text: "Please select status and then proceed further.",
        icon: 'error',
        timer: 1500,
        showConfirmButton: false
    })
    return;
   }

   $.ajax({
    type: 'post',
    url: 'moderation.php',
    data: {panel_id: panel_id, title: title, status: status, action: "update_post_status"},
    success: function (response) {
        response = JSON.parse(response)
        Swal.fire({
            title: 'Done!',
            text: "Status Updated Successfully",
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        })

        let id = "#feed_panel_"+response.panel_id.trim()+" .refresh";
        $(id).trigger('click');

        $('#modal-status').modal('hide');

        $("#status_panel_id").val('');
        $("#status_panel_title").val('');
        $("#status_current_value").val('');
    
        document.getElementById(response.status+"_checkbox").checked = false;
        document.getElementById(response.status+"_list").classList.remove('done');
        
    }
});

}