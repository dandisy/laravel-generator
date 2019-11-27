<!DOCTYPE html>
<html lang="en">
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Twitter -->
    <meta name="twitter:site" content="@webcore">
    <meta name="twitter:creator" content="@webcore">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Webcore">
    <meta name="twitter:description" content="Webcore platform">
    <meta name="twitter:image" content="https://dummyimage.com/600x100/f5f5f5/999999&text=Webcore">

    <!-- Facebook -->
    <meta property="og:url" content="http://localhost/webcore/public">
    <meta property="og:title" content="Webcore">
    <meta property="og:description" content="Webcore platform">

    <meta property="og:image" content="https://dummyimage.com/600x100/f5f5f5/999999&text=Webcore">
    <meta property="og:image:secure_url" content="https://dummyimage.com/600x100/f5f5f5/999999&text=Webcore">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="600">

    <!-- Meta -->
    <meta name="description" content="RWebcore platform">
    <meta name="author" content="dandisy">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('vendor/dashforge/assets/img/favicon.png') }}">

    <title>Webcore platform</title>

    <!-- vendor css -->
    <link href="{{ asset('vendor/dashforge/lib/@fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/dashforge/lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">

     <!-- include Summernote -->
     <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/summernote/summernote.css') }}">

    <!-- Date Time Picker -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/datetimepicker/css/bootstrap-datetimepicker.css') }}">

    <!-- include Fancybox -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fancybox/jquery.fancybox.min.css') }}">

    <!-- include Fileuploader -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fileuploader/jquery.fileuploader.css') }}">

    <!-- include Multi-select -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/multi-select/css/multi-select.css') }}">

    <!-- include Select2 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/select2/select2.min.css') }}">

    <!-- DashForge CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/dashforge/assets/css/dashforge.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/dashforge/assets/css/dashforge.dashboard.css') }}">    

    @yield('styles')
    @yield('style')
    @yield('css')
  </head>
  <body>
    <div class="content ht-100v pd-0">
      @yield('contents')
    </div>

    <script src="{{ asset('vendor/dashforge/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/jquery.flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/lib/jquery.flot/jquery.flot.resize.js') }}"></script>

    <!-- Fancybox -->
    <script src="{{ asset('vendor/adminlte/plugins/fancybox/jquery.fancybox.min.js') }}"></script>

    <script src="{{ asset('vendor/adminlte/plugins/moment/moment.min.js') }}"></script>

    <!-- Fileuploader -->
    <script src="{{ asset('vendor/adminlte/plugins/fileuploader/jquery.fileuploader.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('vendor/adminlte/plugins/select2/select2.min.js') }}"></script>

    <!-- Multi-select -->
    <script src="{{ asset('vendor/adminlte/plugins/multi-select/js/jquery.multi-select.js') }}"></script>

    <!-- Date Time Picker -->
    <script src="{{ asset('vendor/adminlte/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>

    <script src="{{ asset('vendor/dashforge/assets/js/dashforge.js') }}"></script>
    <script src="{{ asset('vendor/dashforge/assets/js/dashforge.sampledata.js') }}"></script>

    <!-- append theme customizer -->
    <script src="{{ asset('vendor/dashforge/lib/js-cookie/js.cookie.js') }}"></script>
    <!-- {{--<script src="{{ asset('vendor/dashforge/assets/js/dashforge.settings.js') }}"></script>--}} -->

    <script>
        $(document).ready(function() {
            // start summernote
            var snfmContext;

            var fileManager = function(context) {
                snfmContext = context;

                var ui = $.summernote.ui;

                // create button
                var button = ui.button({
                    contents: '<i class="fa fa-photo"/>',
                    tooltip: 'File Manager',
                    click: function() {
                        $('.sn-filemanager').trigger('click');
                    }
                });

                return button.render();
            }

            // $('.rte').summernote({
            //     height: 250,
            //     minHeight: 100,
            //     maxHeight: 300,
            //     toolbar: [
            //         ['style', ['bold', 'italic', 'underline', 'clear']],
            //         ['fontsize', ['fontsize']],
            //         ['color', ['color']],
            //         ['para', ['ul', 'ol', 'paragraph']],
            //         ['table', ['table']],
            //         ['insert', ['link', 'hr']],
            //         ['image', ['fm']],
            //         ['video', ['video']],
            //         ['misc', ['fullscreen', 'codeview']]
            //     ],
            //     buttons: {
            //         fm: fileManager
            //     }
            // });

            $('.sn-filemanager').fancybox({
                type : 'iframe',
                afterClose: function() {
                    var snfmImage = $('#snfmImage-thumb').find('img').attr('src');
                    snfmContext.invoke('editor.insertImage', snfmImage, snfmImage.substr(snfmImage.lastIndexOf('/') + 1));
                }
            });
            // end summernote

            $('#dataTableBuilder').wrap('<div class="table-responsive col-md-12"></div>');

            $('.filemanager').fancybox({
                type : 'iframe'
            });

            $(".select2").select2();

            $(".multi-select").multiSelect({
                selectableHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Search...'>",
                selectionHeader: "<input type='text' class='search-input form-control' autocomplete='off' placeholder='Search...'>",
                afterInit: function(ms){
                    var that = this,
                        $selectableSearch = that.$selectableUl.prev(),
                        $selectionSearch = that.$selectionUl.prev(),
                        selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e){
                        if (e.which === 40){
                            that.$selectableUl.focus();
                            return false;
                        }
                    });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function(e){
                        if (e.which == 40){
                            that.$selectionUl.focus();
                            return false;
                        }
                    });
                },
                afterSelect: function(){
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function(){
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });

            // $(".date").datepicker({
            //     format:	'yyyy-mm-dd'
            // });

            $(".date").datetimepicker({
                format:	'YYYY-MM-DD'
            });

            $(".datetime").datetimepicker({
                // format:	'YYYY-MM-DDTHH:mm:ss.XZ'
                format:	'YYYY-MM-DD HH:mm'
            });

            $(".time").datetimepicker({
                format:	'HH:mm:ss'
            });

            // $(".currency").inputmask({ alias : "currency", prefix: "", digits: 0 });

            $('#filer_input').fileuploader({
                enableApi: true,
                maxSize: 10,
                extensions: ["jpg", "png", "jpeg"],
                captions: {
                    feedback: 'Upload foto',
                    button: '+ Foto Album'
                },
                showThumbs: true,
                addMore: true,
                allowDuplicates: false,
                onRemove: function (data, el) {
                    albumDeleted.push(data.data.album);
                }
            });

            $(document).on('click', '.file-item .fa-trash', function() {
                $(this).parents('.file-item').remove();
                $('#album-thumb').append('<input type="hidden" name="deleteFiles[]" value="' + $(this).data('identity') + '" />');
            });

            $(document).on('change', 'input[name="title"]', function() {
                $('input[name="slug"]').val(stringToSlug($(this).val()));
            });

            $('.album-manager').on('click', 'button', function(e) {
                e.preventDefault();

                $('#album-thumb').append('' +
                '<div class="file-item">' +
                '<div class="col-md-3 col-sm-3 col-xs-3"><img src="http://img.youtube.com/vi/' + $('#album').val() + '/mqdefault.jpg" style="width:100%"></div>' +
                '<div class="col-md-8" col-sm-8 col-xs-8" style="overflow-x:auto">' + $('#album').val() + '</div>' +
                '<div class="col-md-1" col-sm-1 col-xs-1"><span class="fa fa-trash" style="cursor:pointer;color:red"></span></div>' +
                '<div class="clearfix"></div>' +
                '<input type="hidden" name="files[]" value="' + $('#album').val() + '" />' +
                '</div>');

                $('#album').val('');
            });

            var stringToSlug = function (str) {
                str = str.replace(/^\s+|\s+$/g, ''); // trim
                str = str.toLowerCase();

                // remove accents, swap ñ for n, etc
                var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
                var to   = "aaaaeeeeiiiioooouuuunc------";

                for(var i=0, l=from.length ; i<l ; i++) {
                    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
                }

                str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                    .replace(/\s+/g, '-') // collapse whitespace and replace by -
                    .replace(/-+/g, '-'); // collapse dashes

                return str;
            }
        });

        // filemanager auto run when close fancybox, after select file and then insert image thumbnail
        var OnMessage = function(data){
            if(data.appendId == 'album') {
                $('#' + data.appendId + '-thumb').append('' +
                '<div class="file-item">' +
                '<div class="col-md-3 col-sm-3 col-xs-3"><img src="' + data.thumb + '" style="width:100%"></div>' +
                '<div class="col-md-8" col-sm-8 col-xs-8" style="overflow-x:auto">' + data.thumb + '</div>' +
                '<div class="col-md-1" col-sm-1 col-xs-1"><span class="fa fa-trash" style="cursor:pointer;color:red"></span></div>' +
                '<div class="clearfix"></div>' +
                '<input type="hidden" name="files[]" value="' + data.thumb + '" />' +
                '</div>');
            } else {
                $('#' + data.appendId + '-thumb').html('<img src="' + data.thumb + '" style="width:100%">');
            }
            $('input[name="' + data.appendId + '"]').val(data.thumb);
            $.fancybox.close();
        };

        $('#myModalPermissions').on('show.bs.modal', function (e) {
            var content = '';

            $.ajax({
                type: 'get',
                url: '{{ url("api/permissions") }}'
            }).done(function (res) {
                $.each(res.data, function (index, value) {
                    content += '<div class="checkbox col-sm-6"><label><input type="checkbox" name="permission" value="' + value.id + '">' + ' ' + value.display_name + '</label></div>';
                });

                $('#permission-container').html(content);
            });
        });

        $('#myModalRole').on('show.bs.modal', function (e) {
            var content = '';

            $.ajax({
                type: 'get',
                url: '{{ url("api/roles") }}'
            }).done(function (res) {
                $.each(res.data, function (index, value) {
                    content += '<div class="checkbox col-sm-6"><label><input type="radio" name="role" value="' + value.id + '">' + ' ' + value.display_name + '</label></div>';
                });

                $('#role-container').html(content);
            });
        });
    </script>

    @yield('scripts')
    @yield('script')
    @yield('js')
  </body>
</html>
