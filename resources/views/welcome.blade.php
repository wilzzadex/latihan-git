<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
        <!-- Styles -->
        
    </head>
    <body>
        <div class="container mt-5">
            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#exampleModal">
                Tambah Data
            </button>
            <table id="dataTable" class="table table-bordered">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    {{-- <th scope="col">Username</th> --}}
                    <th scope="col">Email</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                 
                </tbody>
              </table>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <form action="#" id="addData">
                      {{ csrf_field() }}
                      <div class="form-group">
                          <label for="">Nama</label>
                          <input type="text" class="form-control" id="name" name="name">
                      </div>
                      <div class="form-group">
                          <label for="">Email</label>
                          <input type="text" class="form-control" id="email" name="email">
                      </div>
                      <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
                
            </div>
          </div>
        </div>

          <div class="modal fade" id="EditModal">
	
          </div>

        <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.70/jquery.blockUI.js"></script>
        <script src="{{ asset('assets/sweetalert2/sweetalert2.all.min.js') }}"></script>
        <script>
            $(document).ready( function () {
                $('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('table.user') }}",
                    columns: [
                        {data: 'DT_RowIndex'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'action', name: 'action',orderable:false},
                        // {data: 'action', name: 'action'}
            ]
                });


                // CREATE
                $('#addData').on('submit',function(e){
                    e.preventDefault();
                    var form = $('#addData')

                    // Menghapus Validasi Error
                    form.find('.invalid-feedback').remove();
                    form.find('.form-group').removeClass('is-invalid');
                        // alert('tes');
                    $.ajax({
                        type : 'POST',
                        url : '{{route("user.store")}}',
                        data : $('#addData').serialize(),
                        // beforeSend:function(request){
                        //     $('#exampleModal').block({ css: { 
                        //         border: 'none', 
                        //         padding: '15px', 
                        //         backgroundColor: '#000', 
                        //         '-webkit-border-radius': '10px', 
                        //         '-moz-border-radius': '10px', 
                        //         opacity: .5, 
                        //         color: '#fff' 
                        //     } }); 
                        // },
                        success:function (response){
                            $('#addData').trigger('reset');
                            $('#exampleModal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();

                            swal({
                                type : 'success',
                                title : 'Success!',
                                text : 'Data has been saved!'
                            });
                        },
                        error:function(xhr){
                            var res = xhr.responseJSON;
                            // console.log(res)
                                if ($.isEmptyObject(res) == false) {
                                    $.each(res.errors, function (key, value) {
                                        $('#' + key)
 
                                            .addClass('is-invalid')
                                            .closest('.form-group')
                                            .append(' <div class="invalid-feedback">'+value+'</div>');
                                    });
                                }
                                // $('#exampleModal').unblock() 
                        }
                    })
                })

                // MODAL EDIT SHOW
				$("#dataTable").on("click",".edit-button",function(){
					var id = $(this).attr("id");
                    // console.log(m)
					$.ajax({
						url: "user/"+id+"/edit", 
						type: "GET",
						data : {id: id,},
						success: function (ajaxData){
							$("#EditModal").html(ajaxData);
							$("#EditModal").modal('show');
						},
						error: function(err)
						{
							console.log(err);
						}
					});
				});
                // END MODAL EDIT SHOW

                 // CREATE
                 $("#EditModal").on("click","#editBtn",function(e){
                    e.preventDefault(); 
                    var form = $('#editData')
                    var id = $('#editBtn').attr('data-id')
                    // console.log(id)
                    // Menghapus Validasi Error
                    form.find('.invalid-feedback').remove();
                    form.find('.form-group').removeClass('is-invalid');
                        // alert('tes');
                    $.ajax({
                        type : 'PUT',
                        url : 'user/'+ id,
                        data : $('#editData').serialize(),
                        success:function (response){
                            $('#editData').trigger('reset');
                            $('#EditModal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();

                            swal({
                                type : 'success',
                                title : 'Success!',
                                text : 'Data has been updated!'
                            });
                        },
                        error:function(xhr){
                            var res = xhr.responseJSON;
                            // console.log(res)
                                if ($.isEmptyObject(res) == false) {
                                    $.each(res.errors, function (key, value) {
                                        $('#e_' + key)
 
                                            .addClass('is-invalid')
                                            .closest('.form-group')
                                            .append(' <div class="invalid-feedback">'+value+'</div>');
                                    });
                                }
                                // $('#exampleModal').unblock() 
                        }
                    })
                })
                // END UPDATE

                // delete
                $('body').on('click', '.delete-button', function (event) {
                    event.preventDefault();
                    var id = $(this).attr('id')
                    var name = $(this).attr('title')
                    var csrf_token = $('meta[name="csrf-token"]').attr('content');
                    // console.log(id)
                    swal({
                        title: 'Are you sure want to delete '+name+' ?',
                        text: 'You won\'t be able to revert this!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: 'user/'+id,
                                type: "POST",
                                data: {
                                    '_method': 'DELETE',
                                    '_token': csrf_token,
                                },
                                success: function (response) {
                                    $('#dataTable').DataTable().ajax.reload();
                                    swal({
                                        type: 'success',
                                        title: 'Success!',
                                        text: 'Data has been deleted!'
                                    });
                                },
                                error: function (xhr) {
                                    swal({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong!'
                                    });
                                }
                            });
                        }
                    });

                })
			});
		</script>
    </body>
</html>
