@extends('master')

@section('title')
    Input Request
@endsection

@section('container')
    <script src="{{ asset('dist/assets/js/tableToExcel.js') }}"></script>
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Report Request</h3>
                </div>
            </div>
        </div>

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    Report Request
                </div>
                <div class="card-body">
                    <button class="btn_export_excel  btn btn-primary btn-flat">Export Excel</button>
                    <br><br>
                    <table  data-b-a-s='thin' border='1' class="table table-bordered table-striped table-report-request">
                        <thead>
                        <tr>
                            <th data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>Tanggal</th>
                            <th data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>Nama User</th>
                            <th data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>Email User</th>
                            <th data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>Type</th>
                            <th data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>Title</th>
                            <th data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>Nama Direksi</th>
                            <th data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>Email Direksi</th>
                            <th data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data_report as $row)
                            <tr>
                                <td data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>{{date('d/m/Y', strtotime($row->created_at))}}</td>
                                <td data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>{{$row->User->name}}</td>
                                <td data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>{{$row->User->email}}</td>
                                <td data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>{{$row->type}}</td>
                                <td data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>{{$row->title}}</td>
                                @if($row->getLastStatus())
                                    <td data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>{{$row->getLastStatus()->getStrukturLabel()}}</td>
                                    <td data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>{{$row->getLastStatus()->getAllEmailConcatStruktur()}}</td>
                                    <td data-a-wrap='true'  data-a-v='middle' data-fill-color='ffff00' data-f-bold='true'>{{$row->getLastStatus()->status}}</td>
                                @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>


                </div>
            </div>

        </section>
        <!-- Basic Tables end -->
    </div>

    <script>
        $('.btn_export_excel').on('click',function(){
            exportTableToExcel($(this));
        });

        function exportTableToExcel(button, filename = ''){
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            //var tableSelect = document.getElementById(tableID);
            //var tableSelect = $('.table-responsive table')[0];
            var tableSelect = $(".table-report-request")[0];
            var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
            tableHTML = tableHTML.replace(/#/g,' ')
            // Specify file name
            filename = filename?filename+'.xls':'table-report-request.xls';

            // Create download link element
            downloadLink = document.createElement("a");

            document.body.appendChild(downloadLink);

            if(navigator.msSaveOrOpenBlob){
                var blob = new Blob(['ufeff', tableHTML], {
                    type: dataType
                });
                navigator.msSaveOrOpenBlob( blob, filename);
            }else{
                // Create a link to the file
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

                // Setting the file name
                downloadLink.download = filename;

                //triggering the function
                downloadLink.click();
            }
        }
    </script>
@endsection
