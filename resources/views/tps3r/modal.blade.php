 <div class="modal fade text-left" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
     aria-hidden="true">
     <form action="" id="form">
         <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h4 class="modal-title" id="modal_title">Add</h4>
                     <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                         <i data-feather="x"></i>
                     </button>
                 </div>
                 <div class="modal-body">
                     <div class="form-body">
                         <div class="row">
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="kecamatan_id">Kecamatan</label>
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
                                     <label for="kelurahan_id">Kelurahan/Desa</label>
                                     <select id="kelurahan_id" name="kelurahan_id" class="choices form-select">
                                         <option value="">--Select Kelurahan--</option>
                                     </select>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="luas">Luas</label>
                                     <div class="input-group">
                                         <input type="text" id="luas" class="form-control mask_angka"
                                             name="luas" placeholder="Luas" required>
                                         <span class="btn btn-secondary">
                                             M2
                                         </span>
                                     </div>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="tahun_konstruksi">Tahun Konstruksi</label>
                                     <input type="text" id="tahun_konstruksi" class="form-control"
                                         name="tahun_konstruksi" placeholder="Tahun Konstruksi" required>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="tahun_beroperasi">Tahun Beroperasi</label>
                                     <input type="text" id="tahun_beroperasi" class="form-control"
                                         name="tahun_beroperasi" placeholder="Tahun Beroperasi" required>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="jumlah_timbunan">Jumlah Timbunan Sampah (Ton/Hari)</label>
                                     <input type="text" id="jumlah_timbunan" class="form-control mask_decimal"
                                         name="jumlah_timbunan"
                                         placeholder="Jumlah Timbunan Timbunan Sampah (Ton/Hari)">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="jumlah_penduduk">Jumlah Penduduk</label>
                                     <input type="text" id="jumlah_penduduk" class="form-control mask_angka"
                                         name="jumlah_penduduk" placeholder="Jumlah Penduduk">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="jumlah_kk">Jumlah KK Terlayani</label>
                                     <input type="text" id="jumlah_kk" class="form-control mask_angka"
                                         name="jumlah_kk" placeholder="Jumlah KK Terlayani">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="gerobak">Gerobak</label>
                                     <input type="text" id="gerobak" class="form-control mask_angka" name="gerobak"
                                         placeholder="Gerobak">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="motor">Motor Roda Tiga</label>
                                     <input type="text" id="motor" class="form-control mask_angka"
                                         name="motor" placeholder="Motor Roda Tiga">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="status">Keberfungsian</label>
                                     <select id="status" name="status" class="choices form-select" required>
                                         <option value="">Select Keberfungsian</option>
                                         @foreach (config('enums.opsi_befungsi') as $item)
                                             <option value="{{ $item->value }}">{{ $item->label() }}</option>
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


 <div class="modal fade text-left" id="modal_import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel18"
     aria-hidden="true">
     <form action="{{ route('api.tpas.import') }}" id="form_import" method="POST">
         <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h4 class="modal-title"><i class="fas fa-file-excel me-1"></i>Import Data</h4>
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
                     <a href="{{ asset('master/master_sanitasis.xlsx') }}" class="btn btn-info" target="_blank">
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
