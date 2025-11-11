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
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="nama">Nama TPA</label>
                                     <input type="text" id="nama" class="form-control" name="nama"
                                         placeholder="Nama TPA" maxlength="200" required>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="kecamatan_id">Lokasi (Kecamatan)</label>
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
                                     <label for="kelurahan_id">Lokasi (Desa)</label>
                                     <select id="kelurahan_id" name="kelurahan_id" class="choices form-select">
                                         <option value="">--Select Kelurahan--</option>
                                     </select>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="latitude">Titik Koordinat</label>
                                     <div class="input-group">
                                         <input type="text" id="latitude" class="form-control" name="latitude"
                                             placeholder="Latitude" required>
                                         <input type="text" id="longitude" class="form-control" name="longitude"
                                             placeholder="Longitude" required>
                                         <button type="button" class="btn btn-secondary">
                                             <i class="fas fa-map-marked-alt me-2"></i>
                                         </button>
                                     </div>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="sumber">Sumber Anggaran</label>
                                     <select class="form-select" id="sumber" name="sumber">
                                         <option value="">Select Sumber Anggaran</option>
                                         @foreach (config('enums.sumber_dana') as $item)
                                             <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="rencana">Rencana Umur Beroperasi (Tahun)</label>
                                     <input type="text" id="rencana" class="form-control mask_angka" name="rencana"
                                         placeholder="Rencana Umur Beroperasi" required>
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
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="kecamatan_terlayani">Kecamatan Terlayani</label>
                                     <select id="kecamatan_terlayani" name="kecamatan_terlayani[]"
                                         class="choices form-select" multiple>
                                         <option value="">--Select Kecamatan--</option>
                                         @foreach ($kecamatans as $item)
                                             <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="luas_sarana">Luas Sarana</label>
                                     <input type="text" id="luas_sarana" class="form-control mask_decimal"
                                         name="luas_sarana" placeholder="Luas Sarana">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="luas_sel">Luas Sel</label>
                                     <input type="text" id="luas_sel" class="form-control mask_decimal"
                                         name="luas_sel" placeholder="Luas Sel">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="pengelola">Jenis Pengelola (Dinas/UPT)</label>
                                     <select id="pengelola" name="pengelola" class="choices form-select">
                                         <option value="">Select Pengelola</option>
                                         @foreach (config('enums.pengelola') as $item)
                                             <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="kondisi">Kondisi TPA</label>
                                     <select id="kondisi" name="kondisi" class="choices form-select">
                                         <option value="">Select Kondisi</option>
                                         @foreach (config('enums.opsi_baik') as $item)
                                             <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-12">
                                 <div class="form-group">
                                     <label for="pengelola_desc">Deskripsi Pengelola</label>
                                     <input type="text" id="pengelola_desc" class="form-control"
                                         name="pengelola_desc" placeholder="Deskripsi Pengelola" maxlength="200">
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
