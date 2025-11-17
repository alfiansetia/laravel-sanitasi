 <div class="modal fade text-left" id="modal_form" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
         <div class="modal-content">
             <form action="" id="form">
                 <div class="modal-header">
                     <h4 class="modal-title" id="modal_title">Add</h4>
                     <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                         <i data-feather="x"></i>
                     </button>
                 </div>
                 <div class="modal-body">
                     <div class="form-body">
                         <div class="row">
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="kecamatan_id">Nama Kecamatan</label>
                                     <select id="kecamatan_id" name="kecamatan_id" class="choices form-select">
                                     </select>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="kode">Kode kelurahan</label>
                                     <input type="text" id="kode" class="form-control" name="kode"
                                         placeholder="Kode kelurahan" maxlength="100" required>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="nama">Nama kelurahan</label>
                                     <input type="text" id="nama" class="form-control" name="nama"
                                         placeholder="Nama kelurahan" maxlength="100" required>
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
             </form>
         </div>
     </div>
 </div>


 @include('components.modal_import')
