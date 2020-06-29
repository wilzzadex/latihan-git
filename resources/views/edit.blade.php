<div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{ 'user.update',$user->id }}" id="editData">
            {{ csrf_field() }}
            @method('PUT')
            <div class="form-group">
                
                <label for="">Nama</label>
                <input type="text" class="form-control" value="{{ $user->name }}" id="e_name" name="name">
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input type="text" class="form-control" id="e_email" value="{{ $user->email }}" name="email">
            </div>
            <button type="button" data-id="{{ $user->id }}" id="editBtn" class="btn btn-primary">Save changes</button>
          </form>
      </div>
      
  </div>
</div>