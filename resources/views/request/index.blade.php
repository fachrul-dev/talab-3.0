@extends('master')

@section('title')
    Input Request
@endsection

@section('container')






    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Request</h3>
                </div>
            </div>
        </div>

        <!-- Basic Tables start -->
        <section class="section">
            <div class="card">
                <div class="card-header">
                    List Request
                </div>
                <div class="card-body">

                    <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Request</a>
                    <table class="table table-bordered data-table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Created</th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>User Input</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <!-- Tambahkan script berikut -->


                </div>
            </div>

        </section>
        <!-- Basic Tables end -->
    </div>

    <div class="modal" id="statusRequest" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div> --}}
                <div class="modal-body" style=background-color:#eee;>
                    <div class="container">
                        <div class="row">
                            <div class=col-xs-7>
                                <h2>Status Request</h2>

                                <span><b>STATUS:</b> <span class='tempat_last_status'>Waiting for Supervisor Confirmation</span></span>

                                <div class="full-size">
                                    <div class="bulet tempat_bulet_status" style="">

                                    </div>

                                    <div class='tempat_content_status'>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ajaxModel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading"></h4>
                </div>
                <div class="modal-body">
                    <form id="productForm" name="productForm" class="form-horizontal">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="files">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-12">
                                <select name='type' class="form-control">
                                    <option value="fte">FTE</option>
                                    <option value="fte_director">FTE Director</option>
                                    <option value="nonfte">Non FTE</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter Title" value="" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Requirements</label>
                            <div class="col-sm-12">
                                <textarea rows="8" id="requirements" name="requirements" required="" placeholder="Enter Requirements" class="form-control"></textarea>
                            </div>
                        </div>

                        {!! csrf_field() !!}
                        <div class="form-group">
                            <div class="file-loading">
                                <input id="file-1" type="file" name="Files[]" multiple class="file" data-overwrite-initial="false" data-min-file-count="2">
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).on('click', '#createNewProduct, .editProduct', function () {
            var id = $(this).data('id');
            fileinput_fikasa($("[name='Files[]']"), $("[name='files']"), id, 'Files', 'RequestData')
        })
    </script>


    <script type="text/javascript">


        $(document).ready(function () {


            $(document).on('click', '.StatusRequest', function(){
                $("#statusRequest").modal('show');
                var request_id = $(this).attr('data-id');

                $.ajax({
                    url:"{{url('request/loadviewstatusrequest/')}}/"+request_id,
                    type:'get',
                    dataType:'json',
                    success:function(data){
                        $(".tempat_bulet_status").html(data.Bullet);
                        $(".tempat_content_status").html(data.Content);
                        $(".tempat_last_status").html(data.LastStatus);
                    }
                })
            })
            var loading = '<i class="fa loading fa-circle-o-notch fa-spin"></i>';

            $(document).on('click', '.SendEmailRequest', function(){
                var html_awal = $(this).html();
                var thisclass = $(this);
                $(this).attr('disabled');
                $(this).html(loading);
                var request_id = $(this).attr('data-id');

                $.ajax({
                    url:"{{url('request/sendemailrequest/')}}/"+request_id,
                    type:'get',
                    dataType:'json',
                    success:function(data){
                        var alert_content = data.failed?data.failed:data.success;
                        alert(alert_content);
                        table.draw()
                    }
                }).always(function(){
                    thisclass.removeAttr('disabled');
                    thisclass.html(html_awal);
                })
            })
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('request.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'type', name: 'type'},
                    {data: 'title', name: 'title'},
                    //   {data: 'requirements', name: 'requirements'},
                    {data: 'User', name: 'User.name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('#createNewProduct').click(function () {
                $('#saveBtn').val("create-product");
                $('#product_id').val('');
                $('#productForm').trigger("reset");
                $("#productForm input[name='id']").val(0);
                $('#modelHeading').html("Create New Request");
                $('#ajaxModel').modal('show');
            });

            $('body').on('click', '.editProduct', function () {
                var product_id = $(this).data('id');
                $.get("{{ route('request.index') }}" +'/' + product_id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Request");
                    $('#saveBtn').val("edit-user");
                    $('#ajaxModel').modal('show');

                    $.each(data, function(key, row){
                        var div_form = $("#ajaxModel [name='"+key+"']");
                        if(div_form.length > 0){
                            div_form.val(row);
                        }
                    })
                })
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Sending..');

                $.ajax({
                    data: $('#productForm').serialize(),
                    url: "{{ route('request.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {

                        $('#productForm').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();

                    },
                    error: function (data) {
                        console.log('Error:', data);

                    }
                })
                    .always(function(){
                        $('#saveBtn').html('Save Changes');
                    })
            });

            $('body').on('click', '.deleteProduct', function () {

                var product_id = $(this).data("id");
                confirm("Are You sure want to delete !");

                $.ajax({
                    type: "DELETE",
                    url: "{{ route('request.store') }}"+'/'+product_id,
                    success: function (data) {
                        table.draw();
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });
        });
    </script>
@endsection
