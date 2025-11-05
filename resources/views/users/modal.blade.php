 <div class="modal fade text-left" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
     aria-hidden="true">
     <form action="" id="form">
         <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h4 class="modal-title" id="modal_title">Tambah</h4>
                     <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                         <i data-feather="x"></i>
                     </button>
                 </div>
                 <div class="modal-body">
                     <div class="form-body">
                         <div class="row">
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="name">Name</label>
                                     <input type="text" id="name" class="form-control" name="name"
                                         placeholder="Name" required>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="email">Email</label>
                                     <input type="email" id="email" class="form-control" name="email"
                                         placeholder="Email" required>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="password">Password</label>
                                     <input type="text" id="password" class="form-control" name="password"
                                         placeholder="Password" required>
                                     <p>
                                         <small class="text-muted" id="password_helper">
                                             Kosongkan jika tidak ingin mengubah.
                                         </small>
                                     </p>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="role">Role</label>
                                     <select class="form-select" id="role" name="role" required>
                                         <option value="">Select Role</option>
                                         @foreach ($roles as $item)
                                             <option value="{{ $item }}">{{ $item }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                         <i class="fas fa-times me-1"></i>Close
                     </button>
                     <button type="submit" class="btn btn-primary ms-1">
                         <i class="fas fa-save me-1"></i>Save
                     </button>
                 </div>
             </div>
         </div>
     </form>
 </div>
