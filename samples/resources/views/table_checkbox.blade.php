@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/dxDataGrid/css/dx.common.css') }}" />
    <link rel="dx-theme" data-theme="generic.light" href="{{ asset('vendor/dxDataGrid/css/dx.light.css') }}" />
    <style>
        .dx-datagrid .dx-data-row > td.bullet {
            padding-top: 0;
            padding-bottom: 0;
        }
        .dx-datagrid-content .dx-datagrid-table .dx-row .dx-command-edit {
            width: auto;
            min-width: 140px;
        }
    </style>
@endsection

<div class="dx-viewport">
    <div class="demo-container">
        <div id="gridContainer"></div>
    </div>
</div>

@section('scripts')
    <script src="{{ asset('vendor/dxDataGrid/js/cldr.min.js') }}"></script>
    <script src="{{ asset('vendor/dxDataGrid/js/cldr/event.min.js') }}"></script>
    <script src="{{ asset('vendor/dxDataGrid/js/cldr/supplemental.min.js') }}"></script>
    <script src="{{ asset('vendor/dxDataGrid/js/cldr/unresolved.min.js') }}"></script>	
    <script src="{{ asset('vendor/dxDataGrid/js/globalize.min.js') }}"></script>
    <script src="{{ asset('vendor/dxDataGrid/js/globalize/message.min.js') }}"></script>
    <script src="{{ asset('vendor/dxDataGrid/js/globalize/number.min.js') }}"></script>
    <script src="{{ asset('vendor/dxDataGrid/js/globalize/date.min.js') }}"></script>
    <script src="{{ asset('vendor/dxDataGrid/js/globalize/currency.min.js') }}"></script>
    <script src="{{ asset('vendor/dxDataGrid/js/dx.web.js') }}"></script>
    <script>
        var doDeselection;
        function logEvent(eventName) {
            var logList = $("#events ul"),
                newItem = $("<li>", { text: eventName });

            logList.prepend(newItem);
        }

        $("#gridContainer").dxDataGrid({
            dataSource: @json($products),
            columnAutoWidth: true,
            allowColumnResizing: true,
            columnResizingMode: 'widget', // or 'nextColumn'
            rowAlternationEnabled: true,
            hoverStateEnabled: true, 
            // showBorders: true,
            grouping: {
                autoExpandAll: false,
                contextMenuEnabled: true
            },
            groupPanel: {
                visible: true
            },       
            searchPanel: {
                visible: true
            },   
            filterRow: {
                visible: true
            },
            headerFilter: {
                visible: true
            },
            columnChooser: {
                enabled: true,
                mode: "dragAndDrop" // or "select"
            },
            columnFixing: {
                enabled: true
            },
            // {{--@role('superadministrator')--}}
            selection: {
                mode: "multiple",
                // allowSelectAll : false,
                selectAllMode: 'page',
                showCheckBoxesMode : "always"
            },
            // {{--@endrole--}}
            // height: 420,            
            paging: {
                pageSize: 10
            },
            pager: {
                showPageSizeSelector: true,
                allowedPageSizes: [10, 50, 100],
                showInfo: true
            },
            keyExpr: "id",
            columns: [
                // "id", 
                // {
                //     dataField: "Image",                    
                //     cellTemplate: function (cellElement, cellInfo) {
                //         cellElement.html('<img width="70" src="'+cellInfo.data.image+'">');
                //     }
                // },
                //"category_id",
                {
                    dataField: 'category_name',
                    caption: 'Category',
                    // customizeText: function(data) {
                    //     return data.value.category_name;
                    // }
                    // calculateCellValue: function(data) {
                    //     return this.defaultCalculateCellValue(data).category.name;
                    // }
                },
                "sku", //"sku_id",
                // {
                //     dataField: 'category',
                //     // customizeText: function(data) {
                //     //     return data.value.category_name;
                //     // }
                //     calculateCellValue: function(data) {
                //         return this.defaultCalculateCellValue(data).category_name;
                //     }
                // },
                // {
                //     dataField: "category.category_name",
                //     caption: 'category',
                //     calculateDisplayValue: "category.category_name"
                // },
                // "title", //"name",
                {
                    dataField: "title",
                    caption: 'Name'
                },
                "wilayah_id",
                "wilayah",
                "type",
                "simbol",
                "berat_kemasan",
                "jumlah_kemasan",
                "satuan",
                "satuan_simbol",
                {
                    dataField: "min_qty",
                    caption: 'Minimal quantity'
                },
                "grade",
                {
                    dataField: "normalprice",
                    caption: 'Normal price'
                },
                {
                    dataField: "unitprice",
                    caption: 'Unit price'
                },
                "unitprice_ts",
                "index_ts_profit",
                "index_ts",
                "index_cust",
                "potongan_harga_item",
                // {
                //     dataField: 'harga',
                //     caption: 'Unit price',
                //     // customizeText: function(data) {
                //     //     return data.value.category_name;
                //     // }
                //     calculateCellValue: function(data) {
                //         // console.log(this.defaultCalculateCellValue(data));
                //         if(this.defaultCalculateCellValue(data)) {
                //             return this.defaultCalculateCellValue(data).unitprice;
                //         }
                //     }
                // },
                // {
                //     dataField: "checked",
                //     sortOrder: "asc"
                // }
                // {{--@role('superadministrator|administrator|maker')--}}
                // {
                //     width: 100,
                //     alignment: 'center',
                //     cellTemplate: function (cellElement, cellInfo) {
                //         $('<a/>').addClass('dx-link')
                //             .text('Edit')
                //             .on('dxclick', function () {
                //                 window.location = '{{url("products")}}/'+cellInfo.data.id+'/edit'
                //             })
                //             .appendTo(cellElement);
                //         // $('<a/>').attr('data-key', cellInfo.data.id).dxButton({
                //         //     text: "Edit",
                //         //     onClick: function (e) {
                //         //         window.location = 'http://localhost/mb-dash/public/users/'+e.element.attr('data-key')+'/edit'
                //         //     }
                //         // }).appendTo(cellElement);
                //     }
                // },
                // {{--@endrole--}}
                // {{--@role('superadministrator|checker')--}}
                // {
                //     width: 100,
                //     alignment: 'center',
                //     cellTemplate: function (cellElement, cellInfo) {
                //         $('<a/>').addClass('dx-link text-success')
                //             .text('Approve')
                //             .on('dxclick', function () {
                //                 window.location = '{{url("produtcs")}}/'+cellInfo.data.id+'/approve'
                //             })
                //             .appendTo(cellElement);
                //         // $('<a/>').attr('data-key', cellInfo.data.id).dxButton({
                //         //     text: "Edit",
                //         //     onClick: function (e) {
                //         //         window.location = 'http://localhost/mb-dash/public/users/'+e.element.attr('data-key')+'/edit'
                //         //     }
                //         // }).appendTo(cellElement);
                //     }
                // },
                // {
                //     width: 100,
                //     alignment: 'center',
                //     cellTemplate: function (cellElement, cellInfo) {
                //         $('<a/>').addClass('dx-link text-danger')
                //             .text('Reject')
                //             .on('dxclick', function () {
                //                 window.location = '{{url("products")}}/'+cellInfo.data.id+'/reject'
                //             })
                //             .appendTo(cellElement);
                //         // $('<a/>').attr('data-key', cellInfo.data.id).dxButton({
                //         //     text: "Edit",
                //         //     onClick: function (e) {
                //         //         window.location = 'http://localhost/mb-dash/public/users/'+e.element.attr('data-key')+'/edit'
                //         //     }
                //         // }).appendTo(cellElement);
                //     }
                // },
                // {{--@endrole--}}
            ],
            // editing: {
            //     mode: "row",
            //     // allowUpdating: true,
            //     allowDeleting: function(e) {
            //         return false;
            //         // {{--@role('superadministrator|administrator|maker')
            //         //     if(e.row.data.checked_at) {
            //         //         return true;
            //         //     }
                        
            //         //     return false;
            //         // @endrole
            //         // @role(['checker'])
            //         //     return false;
            //         // @endrole--}}
            //     },
            //     // allowAdding: true
            // },            
            // editing: {
            //     mode: "form",
            //     allowUpdating: true
            // },
            // editing: {
            //     mode: "popup",
            //     allowUpdating: true,
            //     popup: {
            //         title: "Employee Info",
            //         showTitle: true,
            //         width: 700,
            //         height: 345,
            //         position: {
            //             my: "top",
            //             at: "top",
            //             of: window
            //         }
            //     }
            // },
            onCellPrepared: function (e) {
                // {{--if (e.rowType === "data" && e.column.command === "edit") {
                //     if(!e.row.data.checked_at) {
                //         @role('administrator|maker')
                //         e.cellElement.prepend('&nbsp;');
                //         $('<a/>').addClass('dx-link text-info')
                //                 .text('Checking...')
                //                 .on('dxclick', function () {
                //                     window.location = '{{url("products")}}/'+e.row.data.id
                //                 })
                //                 .prependTo(e.cellElement);
                //         // $('<a>')
                //         //     .text("Test")
                //         //     .attr({ onclick: "alert('" + e.row.data.firstName + "');", "class": "dx-link" })
                //         //     .appendTo(e.cellElement);
                //         @endrole
                //         @role('superadministrator|checker')
                //         e.cellElement.prepend('&nbsp;');
                //         $('<a/>').addClass('dx-link text-danger')
                //             .text('Reject')
                //             .on('dxclick', function () {
                //                 window.location = '{{url("products")}}/'+e.row.data.id+'/reject'
                //             })
                //             .prependTo(e.cellElement);
                //         e.cellElement.prepend('&nbsp;');
                //         $('<a/>').addClass('dx-link text-success')
                //             .text('Approve')
                //             .on('dxclick', function () {
                //                 window.location = '{{url("products")}}/'+e.row.data.id+'/approve'
                //             })
                //             .prependTo(e.cellElement);
                //         e.cellElement.prepend('&nbsp;');
                //         $('<a/>').addClass('dx-link text-info')
                //             .text('View')
                //             .on('dxclick', function () {
                //                 window.location = '{{url("products")}}/'+e.row.data.id
                //             })
                //             .prependTo(e.cellElement);
                //         @endrole
                //     } else {
                //         @role('superadministrator|administrator|maker')
                //         e.cellElement.prepend('&nbsp;');
                //         $('<a/>').addClass('dx-link')
                //             .text('Edit')
                //             .on('dxclick', function () {
                //                 window.location = '{{url("products")}}/'+e.row.data.id+'/edit'
                //             })
                //             .prependTo(e.cellElement);
                //         @endrole
                //         @role('checker')
                //         e.cellElement.prepend('&nbsp;');
                //         $('<a/>').addClass('dx-link')
                //             .text('View')
                //             .on('dxclick', function () {
                //                 window.location = '{{url("products")}}/'+e.row.data.id
                //             })
                //             .prependTo(e.cellElement);
                //         @endrole
                //     }
                // }
                // @role('superadministrator|checker')
                //     if (e.rowType === "data" & e.column.command === 'select' && e.data.checked != null) {
                //         e.cellElement.find('.dx-select-checkbox').dxCheckBox("instance").option("disabled", true);
                //         e.cellElement.off();
                //     }
                // @endrole--}}
            },
            onContentReady: function(e){
                e.component.selectRows(@json($salesOrderDetails), true)
                // {{--var selectAll = e.element.find(".dx-header-row").find(".dx-select-checkbox");
                // selectAll.dxCheckBox("instance").option("onValueChanged", null);
                // selectAll.click(function(x) {     
                //     x.preventDefault();
                //     x.stopPropagation();
                    
                //     if (doDeselection)    {
                //         e.component.deselectAll();
                //         doDeselection = false;
                //     }
                //     else {
                //         var filtered = @json($products).filter(function(s) {
                //             return s.checked == null;
                //         });
                //         var id = [];
                //         $.each (filtered, function (i, item){
                //             id.push(item.id);
                //         })
                                                
                //         e.component.selectRows(id);
                //         doDeselection = true;
                //     }
                // });--}}
                // {{--var checkBox = $('.dx-header-row .dx-checkbox').first();
                // checkBox.off('dxclick.MyNamespace');
                // checkBox.on('dxclick.MyNamespace', function(x) {
                //     // alert(checkBox.dxCheckBox('instance').option('value'));
                //     x.preventDefault();
                //     x.stopPropagation();
                    
                //     if (doDeselection)    {
                //         e.component.deselectAll();
                //         doDeselection = false;
                //     }
                //     else {
                //         var filtered = @json($products).filter(function(s) {
                //             return s.checked == null;
                //         });
                //         var id = [];
                //         $.each (filtered, function (i, item){
                //             id.push(item.id);
                //         })
                                                
                //         e.component.selectRows(id);
                //         doDeselection = true;
                //     }
                // });
                // var cek = @json($products).filter(function(s) {
                //     return s.checked == null;
                // });
                // if(cek.length != 0){
                //     $('.btn-select').show();
                // }--}}
            },
            onSelectionChanged: function(e) {
                // $('.btn-select').toggle();
                var selectedRows = e.selectedRowKeys;
                $('.idVal').val(selectedRows);
                console.log(selectedRows);
                
            },
            // onRowClick: function(e) {
            //     {{--@role('superadministrator|administrator|maker'])
            //     if(e.data.checked_at) {
            //         window.location = '{{ url()->current() }}/'+e.data.id+'/edit'
            //     }
            //     @endrole--}}
            // },
            // onEditingStart: function(e) {
            //     logEvent("EditingStart");
            // },
            // onInitNewRow: function(e) {
            //     logEvent("InitNewRow");
            // },
            // onRowInserting: function(e) {
            //     logEvent("RowInserting");
            // },
            // onRowUpdated: function(e) {
            //     logEvent("RowUpdated");
            // },
            // onRowRemoved: function(e) {
            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });
            //     $.ajax({
            //         url: '{{ url()->current() }}/'+JSON.stringify(e.data.id),
            //         type: 'delete',
            //         method: 'POST'
            //     }).success(function(res) {
            //         location = '{{ url()->current() }}';
            //         // console.log(res);
            //     }).error(function(res) {
            //         location = '{{ url()->current() }}';
            //         // console.log(res);
            //     });
                
            //     logEvent("RowRemoved");
            // },
        });
        
        // function OnClick() {
        //     var arrId = $("#gridContainer").dxDataGrid("instance").getSelectedRowKeys();
        //     console.log('id', arrId);
        // }
    </script>
@endsection