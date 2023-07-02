@extends('master')

@section('title')
    Input Request
@endsection

@section('container')


{{-- <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet"> --}}

{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/> --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" media="all" rel="stylesheet" type="text/css"/>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/js/fileinput.js" type="text/javascript"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.4.7/themes/fa/theme.js" type="text/javascript"></script> --}}
<link href="{{ asset('dist/assets/extensions/fileinput/css/fileinput.min.css') }}" media="all" rel="stylesheet" type="text/css"/>
<script src="{{ asset('dist/assets/extensions/fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ asset('dist/assets/extensions/fileinput/themes/fa4/theme.min.js') }}"></script>
<script src="{{ asset('dist/assets/js/fikasa_fileinput.js') }}"></script>
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

                    <a class="btn btn-success" href="javascript:void(0)" id="createNewProduct"> Create New Struktur</a>
                    <table class="table table-bordered data-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Struktur</th>
                                <th>Name</th>
                                <th>Email</th>
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

    <div class="modal fade" id="ajaxModel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modelHeading"></h4>
                    </div>
                    <div class="modal-body">
                        <form id="productForm" name="productForm" class="form-horizontal">
                        <input type="hidden" name="id" id="id">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Struktur</label>
                                <div class="col-sm-12">
                                    <?php   use \App\Http\Controllers\StrukturController; ?>
                                    <select name='struktur' class="form-control">
                                        @foreach (StrukturController::getAllStruktur() as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Title" value="" maxlength="50" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="name" name="email" placeholder="Enter Title" value="" maxlength="50" required="">
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
            $(document).ready(function () {
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });

              var table = $('.data-table').DataTable({
                  processing: true,
                  serverSide: true,
                  ajax: "{{ route('struktur.index') }}",
                  columns: [
                      {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                      {data: 'struktur', name: 'struktur'},
                      {data: 'name', name: 'name'},
                      {data: 'email', name: 'email'},
                      {data: 'action', name: 'action', orderable: false, searchable: false},
                  ]
              });

              $('#createNewProduct').click(function () {
                  $('#saveBtn').val("create-product");
                  $('#product_id').val('');
                  $('#productForm').trigger("reset");
                  $('#modelHeading').html("Create New Struktur");
                  $('#ajaxModel').modal('show');
              });

              $('body').on('click', '.editProduct', function () {
                  var product_id = $(this).data('id');
                  $.get("{{ route('struktur.index') }}" +'/' + product_id +'/edit', function (data) {
                      $('#modelHeading').html("Edit Struktur");
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
                      url: "{{ route('struktur.store') }}",
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
                      url: "{{ route('struktur.store') }}"+'/'+product_id,
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
