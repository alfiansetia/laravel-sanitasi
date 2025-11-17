 <div class="modal fade text-left" id="modal_form" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
                                     <label for="nama" class="required">Nama IPLT</label>
                                     <input type="text" id="nama" class="form-control" name="nama"
                                         placeholder="Nama IPLT" required>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="kecamatan_id" class="required">Kecamatan</label>
                                     <select id="kecamatan_id" name="kecamatan_id" class="choices form-select">
                                         <option value="">--Select Kecamatan--</option>
                                         @foreach ($kecamatans as $item)
                                             <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="kelurahan_id" class="required">Kelurahan/Desa</label>
                                     <select id="kelurahan_id" name="kelurahan_id" class="choices form-select">
                                         <option value="">Select Kelurahan</option>
                                     </select>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="latitude" class="required">Titik Koordinat</label>
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
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="tahun_konstruksi" class="required">Tahun Konstruksi</label>
                                     <input type="text" id="tahun_konstruksi" class="form-control"
                                         name="tahun_konstruksi" placeholder="Tahun Konstruksi" required>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="terpasang">Kapasitas Terpasang</label>
                                     <input type="text" id="terpasang" class="form-control mask_angka"
                                         name="terpasang" placeholder="Kapasitas Terpasang">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="terpakai">Kapasitas Terpakai</label>
                                     <input type="text" id="terpakai" class="form-control mask_angka"
                                         name="terpakai" placeholder="Kapasitas Terpakai">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="tidak_terpakai">Kapasitas Tidak Terpakai</label>
                                     <input type="text" id="tidak_terpakai" class="form-control mask_angka"
                                         name="tidak_terpakai" placeholder="Kapasitas Tidak Terpakai">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="truk">Truk Tinja (Unit)</label>
                                     <input type="text" id="truk" class="form-control mask_angka"
                                         name="truk" placeholder="Truk Tinja (Unit)">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="kapasitas_truk">Kapasitas Truk (M3)</label>
                                     <input type="text" id="kapasitas_truk" class="form-control mask_angka"
                                         name="kapasitas_truk" placeholder="Kapasitas Truk (M3)">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="kondisi_truk" class="required">Kondisi Truk</label>
                                     <select id="kondisi_truk" name="kondisi_truk" class="choices form-select"
                                         required>
                                         <option value="">Select Kondisi Truk</option>
                                         @foreach (config('enums.opsi_baik') as $item)
                                             <option value="{{ $item }}">{{ $item }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="rit">Jumlah Ritasi (Rit/Hari)</label>
                                     <input type="text" id="rit" class="form-control mask_angka"
                                         name="rit" placeholder="Jumlah Ritasi (Rit/Hari)">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="pemanfaat_kk">Jumlah Pemanfaat KK</label>
                                     <input type="text" id="pemanfaat_kk" class="form-control mask_angka"
                                         name="pemanfaat_kk" placeholder="Jumlah Pemanfaat KK">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="pemanfaat_jiwa">Jumlah Pemanfaat Jiwa</label>
                                     <input type="text" id="pemanfaat_jiwa" class="form-control mask_angka"
                                         name="pemanfaat_jiwa" placeholder="Jumlah Pemanfaat Jiwa">
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
