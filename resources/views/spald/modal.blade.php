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
                                     <label for="nama">Nama Instalasi</label>
                                     <input type="text" id="nama" class="form-control" name="nama"
                                         placeholder="Nama Instalasi" required>
                                 </div>
                             </div>
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
                                     <label for="kelurahan_id">Desa/Kelurahan</label>
                                     <select id="kelurahan_id" name="kelurahan_id" class="choices form-select">
                                         <option value="">--Select Kelurahan--</option>
                                     </select>
                                 </div>
                             </div>
                             <div class="col-12">
                                 <div class="form-group">
                                     <label for="alamat">Alamat</label>
                                     <textarea class="form-control" name="alamat" id="alamat" required></textarea>
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
                                     <label for="skala">Skala Pelayanan</label>
                                     <select class="form-select" id="skala" name="skala" required>
                                         <option value="">--Select Skala Pelayanan--</option>
                                         @foreach (config('enums.skala_pelayanan') as $item)
                                             <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                         @endforeach
                                     </select>
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
                                     <label for="sumber">Sumber Dana</label>
                                     <select class="form-select" id="sumber" name="sumber" required>
                                         <option value="">--Select Sumber Dana--</option>
                                         @foreach (config('enums.sumber_dana') as $item)
                                             <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="status_keberfungsian">Status Keberfungsian</label>
                                     <select class="form-select" id="status_keberfungsian" name="status_keberfungsian"
                                         required>
                                         <option value="">--Select Status Keberfungsian--</option>
                                         @foreach (config('enums.opsi_befungsi') as $item)
                                             <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="kondisi">Keterangan Kondisi</label>
                                     <select class="form-select" id="kondisi" name="kondisi" required>
                                         <option value="">--Select Keterangan Kondisi--</option>
                                         @foreach (config('enums.opsi_baik') as $item)
                                             <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="status_lahan">Status Lahan</label>
                                     <select class="form-select" id="status_lahan" name="status_lahan" required>
                                         <option value="">--Select Status Lahan--</option>
                                         @foreach (config('enums.status_lahan') as $item)
                                             <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="kapasitas">Kapasitas Desain (m3/hari)</label>
                                     <input type="text" id="kapasitas" class="form-control mask_decimal"
                                         name="kapasitas" placeholder="Kapasitas Desain">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="jenis">Jenis Pengelolaan</label>
                                     <select class="form-select" id="jenis" name="jenis" required>
                                         <option value="">--Select Jenis Pengelolaan--</option>
                                         @foreach (config('enums.jenis_pengelolaan') as $item)
                                             <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="teknologi">Opsi Teknologi</label>
                                     <select class="form-select" id="teknologi" name="teknologi" required>
                                         <option value="">--Select Opsi Teknologi--</option>
                                         @foreach (config('enums.opsi_teknologi') as $item)
                                             <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="pemanfaat_jiwa">Jumlah Pemanfaat Jiwa</label>
                                     <input type="text" id="pemanfaat_jiwa" class="form-control mask_angka"
                                         name="pemanfaat_jiwa" placeholder="Jumlah Pemanfaat Jiwa">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="rumah_terlayani">Jumlah Rumah Terlayani</label>
                                     <input type="text" id="rumah_terlayani" class="form-control mask_angka"
                                         name="rumah_terlayani" placeholder="Jumlah Rumah Terlayani">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="unit_tangki">Jumlah Unit Tangki Septik</label>
                                     <input type="text" id="unit_tangki" class="form-control mask_angka"
                                         name="unit_tangki" placeholder="Jumlah Unit Tangki Septik">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="unit_bilik">Jumlah Unit Bilik</label>
                                     <input type="text" id="unit_bilik" class="form-control mask_angka"
                                         name="unit_bilik" placeholder="Jumlah Unit Bilik">
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="status_penyedotan">Penyedotan Lumpur Tinja</label>
                                     <select class="form-select" id="status_penyedotan" name="status_penyedotan"
                                         required>
                                         <option value="">--Select Penyedotan Lumpur Tinja--</option>
                                         @foreach (config('enums.opsi_ada') as $item)
                                             <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                             </div>
                             <div class="col-md-6">
                                 <div class="form-group">
                                     <label for="tanggal_update">Tanggal Update</label>
                                     <input type="text" id="tanggal_update" class="form-control"
                                         name="tanggal_update" placeholder="Tanggal Update" required>
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
