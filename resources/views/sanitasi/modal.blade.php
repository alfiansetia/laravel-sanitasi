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
                                     <label for="tahun">Tahun</label>
                                     <input type="text" id="tahun" class="form-control" name="tahun"
                                         placeholder="Tahun" required>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="nama">Nama Kegiatan</label>
                                     <textarea class="form-control" name="nama" id="nama" placeholder="Nama Kegiatan" maxlength="200" required></textarea>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="lokasi">Lokasi</label>
                                     <textarea class="form-control" name="lokasi" id="lokasi" placeholder="Lokasi" maxlength="200" required></textarea>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="pagu">Pagu Anggaran</label>
                                     <input type="text" id="pagu" class="form-control mask_angka" name="pagu"
                                         placeholder="Pagu Anggaran">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="jumlah">Jumlah Anggaran</label>
                                     <input type="text" id="jumlah" class="form-control mask_angka" name="jumlah"
                                         placeholder="Jumlah Anggaran">
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="sumber">Sumber Dana</label>
                                     <select class="form-select" id="sumber" name="sumber" required>
                                         <option value="">--Select Sumber Dana--</option>
                                         @foreach (config('enums.sumber_dana') as $item)
                                             <option value="{{ $item }}">{{ $item }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-12">
                                 <div class="form-group">
                                     <label for="lat">Titik Koordinat</label>
                                     <div class="input-group">
                                         <input type="text" id="latitude" class="form-control" name="latitude"
                                             placeholder="Latitude" required>
                                         <input type="text" id="longitude" class="form-control" name="longitude"
                                             placeholder="Longitude" required>
                                         <button type="button" id="btn_map" class="btn btn-secondary">
                                             <i class="fas fa-map-marked-alt me-2"></i>
                                         </button>
                                     </div>
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

 @include('components.modal_map')
