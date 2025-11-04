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
                                     <label for="tahun">Tahun</label>
                                     <input type="text" id="tahun" class="form-control" name="tahun"
                                         placeholder="Tahun" required>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="nama">Nama Kegiatan</label>
                                     <textarea class="form-control" name="nama" id="nama" placeholder="Nama Kegiatan" required></textarea>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="lokasi">Lokasi</label>
                                     <textarea class="form-control" name="lokasi" id="lokasi" placeholder="Lokasi" required></textarea>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="pagu">Pagu Anggaran</label>
                                     <input type="text" id="pagu" class="form-control mask_angka" name="pagu"
                                         placeholder="Pagu Anggaran" value="0" required>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="jumlah">Jumlah Anggaran</label>
                                     <input type="text" id="jumlah" class="form-control mask_angka" name="jumlah"
                                         placeholder="Jumlah Anggaran" value="0" required>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="sumber">Sumber Dana</label>
                                     <select class="form-select" id="sumber" name="sumber" required>
                                         <option value="">Pilih Sumber Dana</option>
                                         <option value="DAK">DAK</option>
                                         <option value="DAU">DAU</option>
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="lat">Latitude</label>
                                     <input type="text" id="lat" class="form-control" name="lat"
                                         placeholder="Latitude">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="long">Longitude</label>
                                     <input type="text" id="long" class="form-control" name="long"
                                         placeholder="Longitude">
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


 <div class="modal fade text-left" id="modal_import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel18"
     aria-hidden="true">
     <form action="{{ route('api.spaldts.import') }}" id="form_import" method="POST">
         <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h4 class="modal-title">Import Data</h4>
                     <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                         <i data-feather="x"></i>
                     </button>
                 </div>
                 <div class="modal-body">
                     <div class="form-body">
                         <div class="row">
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="import_file">Pilih File</label>
                                     <input type="file" id="import_file" class="form-control" name="file"
                                         placeholder="Pilih File" accept=".xlsx,.xls,.csv" required>
                                 </div>
                             </div>

                         </div>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                         <i class="fas fa-times me-1"></i>Close
                     </button>
                     <a href="{{ asset('master/master_spaldts.xlsx') }}" class="btn btn-info" target="_blank">
                         <i class="fas fa-download me-1" title="Download Sample"></i>Download Sample
                     </a>
                     <button type="submit" class="btn btn-primary ms-1">
                         <i class="fas fa-save me-1"></i>Save
                     </button>
                 </div>
             </div>
         </div>
     </form>
 </div>
