@extends('layouts.main')

@section('title')
    Data Siswa
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/button-action.css') }}">
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.css') }}">
@endsection

@section('content')
    <div class="pcoded-main-container">
        <div class="pcoded-content pt-1 mt-1">
            @include('components.breadcrumbs')
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header custom-header">
                            <div class="custom-left">
                                <div class="custom-btn-tambah-wrap">
                                    <a class="btn btn-primary custom-btn-tambah text-white" data-toggle="modal"
                                        data-target=".bd-example-modal-lg">
                                        <span data-container="body" data-toggle="tooltip" data-placement="top"
                                            title="Tambah Data Siswa"><i class="fa fa-plus-circle mr-1"></i>Tambah</span>
                                    </a>
                                </div>
                                {{-- <form action="{{ route('master.member.import') }}" method="POST"
                                    enctype="multipart/form-data" class="custom-form-import">
                                    @csrf
                                    <input type="file" name="file" class="custom-input-file" accept=".xlsx" required>
                                    <button type="submit" class="btn btn-success custom-btn-import">
                                        <i class="fa fa-file-import"></i> Import
                                    </button>
                                </form> --}}
                            </div>
                            <div class="custom-right">
                                <div class="custom-limit-page">
                                    <select name="limitPage" id="limitPage" class="form-control">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                    </select>
                                </div>
                                <div class="custom-search">
                                    <input id="tb-search" class="tb-search form-control" type="search" name="search"
                                        placeholder="Cari Data" aria-label="search">
                                </div>
                            </div>
                        </div>
                        <div class="content">
                            <x-adminlte-alerts />
                            <div class="card-body p-0">
                                <div class="table-responsive table-scroll-wrapper">
                                    <table class="table table-striped table-bordered m-0">
                                        <thead>
                                            <tr class="tb-head">
                                                <th class="text-center text-wrap align-top">No</th>
                                                <th class="text-wrap align-top">Nama</th>
                                                <th class="text-wrap align-top">NIK</th>
                                                <th class="text-wrap align-top">TTL</th>
                                                <th class="text-wrap align-top">Domisili</th>
                                                <th class="text-wrap align-top">Jenis Kelamin</th>
                                                <th class="text-wrap align-top">Agama</th>
                                                <th class="text-wrap align-top">No. HP / Email</th>
                                                <th class="text-wrap align-top">Tahun Masuk</th>
                                                <th class="text-center text-wrap align-top">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listData">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3">
                                    <div class="text-center text-md-start mb-2 mb-md-0">
                                        <div class="pagination">
                                            <div>Menampilkan <span id="countPage">0</span> dari <span
                                                    id="totalPage">0</span> data</div>
                                        </div>
                                    </div>
                                    <nav class="text-center text-md-end">
                                        <ul class="pagination justify-content-center justify-content-md-end"
                                            id="pagination-js">
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-form" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" style="max-width: 1200px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title h4" id="myLargeModalLabel">Tambah Data Siswa</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body table-border-style">
                                    <div class="table-responsive">
                                        <form action="{{ route('master.member.store') }}" method="post" class="">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5 class="mb-3">Data Identitas Pribadi</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Nama lengkap<span
                                                                style="color: red">*</span></label>
                                                        <input type="text" name="nama_member"
                                                            placeholder="Masukkan nama lengkap Siswa" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">NIK (Nomor Induk Kependudukan)<span
                                                                style="color: red">*</span></label>
                                                        <input type="text" name="nik" maxlength="16"
                                                            placeholder="16 digit" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Tempat Lahir<span
                                                                style="color: red">*</span></label>
                                                        <input type="text" name="tempat_lahir" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Tanggal Lahir<span
                                                                style="color: red">*</span></label>
                                                        <input type="date" name="tanggal_lahir" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Jenis kelamin<span
                                                                style="color: red">*</span></label>
                                                        <select name="jenis_kelamin" class="form-control" required>
                                                            <option value="">~Pilih~</option>
                                                            <option value="Laki-laki">Laki-laki</option>
                                                            <option value="Perempuan">Perempuan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Agama<span
                                                                style="color: red">*</span></label>
                                                        <select name="agama" class="form-control" required>
                                                            <option value="">~Pilih~</option>
                                                            <option value="Islam">Islam</option>
                                                            <option value="Kristen">Kristen</option>
                                                            <option value="Katolik">Katolik</option>
                                                            <option value="Hindu">Hindu</option>
                                                            <option value="Buddha">Buddha</option>
                                                            <option value="Konghucu">Konghucu</option>
                                                            <option value="Lainnya">Lainnya</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Status pernikahan<span
                                                                style="color: red">*</span></label>
                                                        <select name="status_pernikahan" class="form-control" required>
                                                            <option value="">~Pilih~</option>
                                                            <option value="Belum Menikah">Belum Menikah</option>
                                                            <option value="Menikah">Menikah</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Tahun Masuk LPK<span
                                                                style="color: red">*</span></label>
                                                        <input type="number" name="tahun_ajaran" min="1900" max="2100"
                                                            placeholder="Contoh: 2026" class="form-control" required>
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <h5 class="mb-3">Data Kontak & Alamat</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">No. HP<span
                                                                style="color: red">*</span></label>
                                                        <input type="number" name="no_hp"
                                                            placeholder="Contoh : 08xxxxxxxxxx" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Email<span
                                                                style="color: red">*</span></label>
                                                        <input type="email" name="email"
                                                            placeholder="Contoh : siswa@email.com" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Provinsi<span
                                                                style="color: red">*</span></label>
                                                        <select id="province_code" name="province_code"
                                                            class="form-control province_code" required></select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Kabupaten/Kota<span
                                                                style="color: red">*</span></label>
                                                        <select id="city_code" name="city_code"
                                                            class="form-control city_code" disabled required></select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Kecamatan<span
                                                                style="color: red">*</span></label>
                                                        <select id="district_code" name="district_code"
                                                            class="form-control district_code" disabled required></select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Desa/Kelurahan<span
                                                                style="color: red">*</span></label>
                                                        <select id="village_code" name="village_code"
                                                            class="form-control village_code" disabled required></select>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Alamat<span
                                                                style="color: red">*</span></label>
                                                        <textarea name="alamat_domisili" rows="3" class="form-control"
                                                            required></textarea>
                                                    </div>
                                                </div>

                                                <div class="col-12 mt-2">
                                                    <h5 class="mb-3">Data Pendidikan</h5>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Pendidikan terakhir<span
                                                                style="color: red">*</span></label>
                                                        <select name="pendidikan_terakhir" class="form-control" required>
                                                            <option value="">~Pilih~</option>
                                                            <option value="SD">SD</option>
                                                            <option value="SMP">SMP</option>
                                                            <option value="SMA/SMK">SMA/SMK</option>
                                                            <option value="D3">D3</option>
                                                            <option value="S1">S1</option>
                                                            <option value="S2">S2</option>
                                                            <option value="S3">S3</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Jurusan<span
                                                                style="color: red">*</span></label>
                                                        <input type="text" name="jurusan" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Nama sekolah / universitas<span
                                                                style="color: red">*</span></label>
                                                        <input type="text" name="nama_sekolah" class="form-control"
                                                            required>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label">Tahun lulus<span
                                                                style="color: red">*</span></label>
                                                        <input type="number" name="tahun_lulus" min="1900" max="2100"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-dot-circle-o"></i> Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal form edit -->
    @foreach ($member as $mbr)
        <div class="modal fade edit-member-modal" id="editMemberModal{{ $mbr->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editMemberModalLabel{{ $mbr->id }}" aria-hidden="true"
            data-province="{{ $mbr->province_code }}" data-city="{{ $mbr->city_code }}"
            data-district="{{ $mbr->district_code }}" data-village="{{ $mbr->village_code }}">
            <div class="modal-dialog modal-xl" style="max-width: 1200px;" role="document">
                <div class="modal-content">
                    <form action="{{ route('master.member.update', $mbr->id) }}" method="post">
                        @csrf
                        @method('put')
                        <div class="modal-header">
                            <h6 class="modal-title h4" id="editMemberModalLabel{{ $mbr->id }}">Edit Data Siswa</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">Data Identitas Pribadi</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Nama lengkap<span
                                                style="color: red">*</span></label>
                                        <input type="text" name="nama_member" class="form-control" required
                                            value="{{ $mbr->nama_member }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">NIK (Nomor Induk Kependudukan)<span
                                                style="color: red">*</span></label>
                                        <input type="text" name="nik" maxlength="16" class="form-control" required
                                            value="{{ $mbr->nik }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Tempat Lahir<span
                                                style="color: red">*</span></label>
                                        <input type="text" name="tempat_lahir" class="form-control" required
                                            value="{{ $mbr->tempat_lahir }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Tanggal Lahir<span
                                                style="color: red">*</span></label>
                                        <input type="date" name="tanggal_lahir" class="form-control" required
                                            value="{{ $mbr->tanggal_lahir }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Jenis kelamin<span
                                                style="color: red">*</span></label>
                                        <select name="jenis_kelamin" class="form-control" required>
                                            <option value="">~Pilih~</option>
                                            <option value="Laki-laki" {{ $mbr->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ $mbr->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Agama<span
                                                style="color: red">*</span></label>
                                        <select name="agama" class="form-control" required>
                                            <option value="">~Pilih~</option>
                                            <option value="Islam" {{ $mbr->agama == 'Islam' ? 'selected' : '' }}>Islam</option>
                                            <option value="Kristen" {{ $mbr->agama == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                            <option value="Katolik" {{ $mbr->agama == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                            <option value="Hindu" {{ $mbr->agama == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                            <option value="Buddha" {{ $mbr->agama == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                            <option value="Konghucu" {{ $mbr->agama == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                            <option value="Lainnya" {{ $mbr->agama == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Status pernikahan<span
                                                style="color: red">*</span></label>
                                        <select name="status_pernikahan" class="form-control" required>
                                            <option value="">~Pilih~</option>
                                            <option value="Belum Menikah" {{ $mbr->status_pernikahan == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                            <option value="Menikah" {{ $mbr->status_pernikahan == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Tahun Masuk LPK<span
                                                style="color: red">*</span></label>
                                        <input type="number" name="tahun_ajaran" min="1900" max="2100"
                                            placeholder="Contoh: 2026" class="form-control" required
                                            value="{{ $mbr->tahun_ajaran }}">
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <h5 class="mb-3">Data Kontak & Alamat</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">No. HP<span
                                                style="color: red">*</span></label>
                                        <input type="number" name="no_hp" class="form-control" required
                                            value="{{ $mbr->no_hp }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Email<span
                                                style="color: red">*</span></label>
                                        <input type="email" name="email" class="form-control" required
                                            value="{{ $mbr->email }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Provinsi<span
                                                style="color: red">*</span></label>
                                        <select id="province_code_{{ $mbr->id }}" name="province_code"
                                            class="form-control province_code" required></select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Kabupaten/Kota<span
                                                style="color: red">*</span></label>
                                        <select id="city_code_{{ $mbr->id }}" name="city_code"
                                            class="form-control city_code" disabled required></select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Kecamatan<span
                                                style="color: red">*</span></label>
                                        <select id="district_code_{{ $mbr->id }}" name="district_code"
                                            class="form-control district_code" disabled required></select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Desa/Kelurahan<span
                                                style="color: red">*</span></label>
                                        <select id="village_code_{{ $mbr->id }}" name="village_code"
                                            class="form-control village_code" disabled required></select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Alamat<span
                                                style="color: red">*</span></label>
                                        <textarea name="alamat_domisili" rows="3" class="form-control" required>{{ $mbr->alamat_domisili ?? $mbr->alamat }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <h5 class="mb-3">Data Pendidikan</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Pendidikan terakhir<span
                                                style="color: red">*</span></label>
                                        <select name="pendidikan_terakhir" class="form-control" required>
                                            <option value="">~Pilih~</option>
                                            <option value="SD" {{ $mbr->pendidikan_terakhir == 'SD' ? 'selected' : '' }}>SD</option>
                                            <option value="SMP" {{ $mbr->pendidikan_terakhir == 'SMP' ? 'selected' : '' }}>SMP</option>
                                            <option value="SMA/SMK" {{ $mbr->pendidikan_terakhir == 'SMA/SMK' ? 'selected' : '' }}>SMA/SMK</option>
                                            <option value="D3" {{ $mbr->pendidikan_terakhir == 'D3' ? 'selected' : '' }}>D3</option>
                                            <option value="S1" {{ $mbr->pendidikan_terakhir == 'S1' ? 'selected' : '' }}>S1</option>
                                            <option value="S2" {{ $mbr->pendidikan_terakhir == 'S2' ? 'selected' : '' }}>S2</option>
                                            <option value="S3" {{ $mbr->pendidikan_terakhir == 'S3' ? 'selected' : '' }}>S3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Jurusan<span
                                                style="color: red">*</span></label>
                                        <input type="text" name="jurusan" class="form-control" required value="{{ $mbr->jurusan }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Nama sekolah / universitas<span
                                                style="color: red">*</span></label>
                                        <input type="text" name="nama_sekolah" class="form-control" required value="{{ $mbr->nama_sekolah }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Tahun lulus<span
                                                style="color: red">*</span></label>
                                        <input type="number" name="tahun_lulus" min="1900" max="2100"
                                            class="form-control" required value="{{ $mbr->tahun_lulus }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="detailMemberModal" tabindex="-1" role="dialog" aria-labelledby="detailMemberModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" style="max-width: 1000px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title h4" id="detailMemberModalLabel">Detail Biodata Siswa</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" id="detailMemberContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('asset_js')
    <script src="{{ asset('js/pagination.js') }}"></script>
@endsection

@section('js')
    <script>
        let title = 'Data Member';
        let defaultLimitPage = 10;
        let currentPage = 1;
        let totalPage = 1;
        let defaultAscending = 0;
        let defaultSearch = '';
        let customFilter = {};

        async function getListData(limit = 10, page = 1, ascending = 0, search = '', customFilter = {}) {
            $('#listData').html(loadingData());

            let filterParams = {};

            let getDataRest = await renderAPI(
                'GET',
                '{{ route('master.getmember') }}', {
                    page: page,
                    limit: limit,
                    ascending: ascending,
                    search: search,
                    ...filterParams
                }
            ).then(function(response) {
                return response;
            }).catch(function(error) {
                let resp = error.response;
                return resp;
            });

            if (getDataRest && getDataRest.status == 200 && Array.isArray(getDataRest.data.data)) {
                let handleDataArray = await Promise.all(
                    getDataRest.data.data.map(async item => await handleData(item))
                );
                await setListData(handleDataArray, getDataRest.data.pagination);
            } else {
                errorMessage = getDataRest?.data?.message;
                let errorRow = `
                            <tr class="text-dark">
                                <th class="text-center" colspan="${$('.tb-head th').length}"> ${errorMessage} </th>
                            </tr>`;
                $('#listData').html(errorRow);
                $('#countPage').text("0 - 0");
                $('#totalPage').text("0");
            }
        }

        async function handleData(data) {
            let detail_button = `
                <button type="button" class="btn btn-sm btn-light border border-2 detail-data px-2"
                    data-toggle="modal" data-target="#detailMemberModal" data-id='${data.id}'>
                    <i class="fa fa-eye text-info" data-container="body" data-toggle="tooltip" data-placement="top"
                        title="Detail Data Siswa: ${data.nama}"></i>
                </button>`;

            let edit_button = `
                <button type="button" class="btn btn-sm btn-light border border-2 edit-data px-2"
                    data-toggle="modal" data-target="#editMemberModal${data.id}" data-id='${data.id}'>
                    <i class="fa fa-edit text-warning" data-container="body" data-toggle="tooltip" data-placement="top"
                        title="Edit Data Siswa: ${data.nama}"></i>
                </button>`;

            let delete_button = `
                <button type="button" class="btn btn-sm btn-light border border-2 hapus-data px-2"
                    data-container="body" data-toggle="tooltip" data-placement="top"
                    title="Hapus Data Siswa: ${data.nama}"
                    data-id='${data.id}'
                    data-name='${data.nama}'>
                    <i class="fa fa-trash text-danger"></i>
                </button>`;

            return {
                id: data?.id ?? '-',
                nama: data?.nama ?? '-',
                nik: data?.nik ?? '-',
                tempat_lahir: data?.tempat_lahir ?? '-',
                tanggal_lahir: data?.tanggal_lahir ?? '-',
                kabupaten_provinsi: data?.kabupaten_provinsi ?? '-',
                kecamatan_desa: data?.kecamatan_desa ?? '-',
                jenis_kelamin: data?.jenis_kelamin ?? '-',
                agama: data?.agama ?? '-',
                no_hp: data?.no_hp ?? '-',
                email: data?.email ?? '-',
                tahun_masuk_lpk: data?.tahun_masuk_lpk ?? '-',
                detail_button,
                edit_button,
                delete_button,
            };
        }

        async function setListData(dataList, pagination) {
            totalPage = pagination.total_pages;
            currentPage = pagination.current_page;
            let display_from = ((defaultLimitPage * (currentPage - 1)) + 1);
            let display_to = Math.min(display_from + dataList.length - 1, pagination.total);

            let getDataTable = '';
            let classCol = 'align-center text-dark text-wrap';
            dataList.forEach((element, index) => {
                const kabProvRaw = `${element.kabupaten_provinsi ?? '-'}`;
                const kabProvSplitIndex = kabProvRaw.lastIndexOf(',');
                const kabupatenText = kabProvSplitIndex > -1 ? kabProvRaw.slice(0, kabProvSplitIndex).trim() : kabProvRaw.trim();
                const provinsiText = kabProvSplitIndex > -1 ? kabProvRaw.slice(kabProvSplitIndex + 1).trim() : '';
                const kabupatenHtml = kabupatenText ? `<strong>${kabupatenText}</strong>` : '';
                const provinsiHtml = provinsiText ? `<strong>${provinsiText}</strong>` : '';
                const barisAtas = provinsiHtml ? `${kabupatenHtml}, ${provinsiHtml}` : kabupatenHtml;
                const wilayahHtml = `<div>${barisAtas || '-'}</div><div class="text-muted" style="font-size: 12px; line-height: 1.2;">${element.kecamatan_desa ?? '-'}</div>`;
                const ttlTempat = `${element.tempat_lahir ?? '-'}`;
                const ttlTanggal = `${element.tanggal_lahir ?? '-'}`;
                const ttlHtml = `<div>${ttlTempat}${ttlTempat !== '-' ? ',' : ''}</div><div class="text-muted" style="font-size: 12px; line-height: 1.2;">${ttlTanggal}</div>`;
                const noHpHtml = `<div>${element.no_hp ?? '-'}</div><div class="text-muted" style="font-size: 12px; line-height: 1.2;">${element.email ?? '-'}</div>`;
                getDataTable += `
                    <tr class="text-dark">
                        <td class="${classCol} text-center">${display_from + index}.</td>
                        <td class="${classCol}">${element.nama}</td>
                        <td class="${classCol}">${element.nik}</td>
                        <td class="${classCol}">${ttlHtml}</td>
                        <td class="${classCol}">${wilayahHtml}</td>
                        <td class="${classCol}">${element.jenis_kelamin}</td>
                        <td class="${classCol}">${element.agama}</td>
                        <td class="${classCol}">${noHpHtml}</td>
                        <td class="${classCol}">${element.tahun_masuk_lpk}</td>
                        <td class="${classCol}">
                            <div class="d-flex justify-content-center w-100">
                                <div class="hovering p-1">
                                    ${element.detail_button}
                                </div>
                                <div class="hovering p-1">
                                    ${element.edit_button}
                                </div>
                                <div class="hovering p-1">
                                    ${element.delete_button}
                                </div>
                            </div>
                        </td>
                    </tr>`;
            });

            $('#listData').html(getDataTable);
            $('#totalPage').text(pagination.total);
            $('#countPage').text(`${display_from} - ${display_to}`);
            $('[data-toggle="tooltip"]').tooltip();
            renderPagination();
        }

        async function deleteData() {
            $(document).on("click", ".hapus-data", async function() {
                isActionForm = "destroy";
                let id = $(this).attr("data-id");
                let name = $(this).attr("data-name");

                swal({
                    title: `Hapus Member ${name}`,
                    text: "Apakah anda yakin?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Tidak, Batal!",
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true,
                    confirmButtonClass: "btn btn-danger",
                    cancelButtonClass: "btn btn-secondary",
                }).then(async (result) => {
                    let postDataRest = await renderAPI(
                        'DELETE',
                        `member/delete/${id}`
                    ).then(function(response) {
                        return response;
                    }).catch(function(error) {
                        let resp = error.response;
                        return resp;
                    });

                    if (postDataRest.status == 200) {
                        setTimeout(function() {
                            getListData(defaultLimitPage, currentPage, defaultAscending,
                                defaultSearch, customFilter);
                        }, 500);
                        notificationAlert('success', 'Pemberitahuan', postDataRest.data
                            .message);
                    }
                }).catch(swal.noop);
            })
        }

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        async function detailData() {
            $(document).on("click", ".detail-data", async function() {
                const id = $(this).attr("data-id");
                $('#detailMemberContent').html('<div class="p-3">Memuat...</div>');

                let getDataRest = await renderAPI(
                    'GET',
                    `member/detail/${id}`, {}
                ).then(function(response) {
                    return response;
                }).catch(function(error) {
                    return error.response;
                });

                if (!getDataRest || getDataRest.status !== 200) {
                    $('#detailMemberContent').html('<div class="p-3 text-danger">Gagal memuat data.</div>');
                    return;
                }

                const d = getDataRest.data?.data ?? {};
                const kabProv = escapeHtml(d.kabupaten_provinsi ?? '-');
                const splitIndex = kabProv.lastIndexOf(',');
                const kab = splitIndex > -1 ? kabProv.slice(0, splitIndex).trim() : kabProv.trim();
                const prov = splitIndex > -1 ? kabProv.slice(splitIndex + 1).trim() : '';
                const domisiliAtas = prov ? `<strong>${escapeHtml(kab)}</strong>, <strong>${escapeHtml(prov)}</strong>` : `<strong>${escapeHtml(kab)}</strong>`;
                const domisiliBawah = escapeHtml(d.kecamatan_desa ?? '-');

                const ttlTempat = escapeHtml(d.tempat_lahir ?? '-');
                const ttlTanggal = escapeHtml(d.tanggal_lahir ?? '-');
                const ttlHtml = `${ttlTempat}${ttlTempat !== '-' ? ', ' : ''}${ttlTanggal}`;

                const html = `
                    <div class="card mb-3">
                        <div class="card-header"><strong>Data Identitas Pribadi</strong></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted" style="font-size: 12px;">Nama</div>
                                    <div>${escapeHtml(d.nama ?? '-')}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted" style="font-size: 12px;">NIK</div>
                                    <div>${escapeHtml(d.nik ?? '-')}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted" style="font-size: 12px;">Tempat, Tanggal Lahir</div>
                                    <div>${ttlHtml}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted" style="font-size: 12px;">Tahun Masuk LPK</div>
                                    <div>${escapeHtml(d.tahun_masuk_lpk ?? '-')}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted" style="font-size: 12px;">Jenis Kelamin</div>
                                    <div>${escapeHtml(d.jenis_kelamin ?? '-')}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted" style="font-size: 12px;">Agama</div>
                                    <div>${escapeHtml(d.agama ?? '-')}</div>
                                </div>
                                <div class="col-md-6 mb-0">
                                    <div class="text-muted" style="font-size: 12px;">Status Pernikahan</div>
                                    <div>${escapeHtml(d.status_pernikahan ?? '-')}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header"><strong>Data Kontak & Alamat</strong></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted" style="font-size: 12px;">No. HP</div>
                                    <div>${escapeHtml(d.no_hp ?? '-')}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted" style="font-size: 12px;">Email</div>
                                    <div>${escapeHtml(d.email ?? '-')}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted" style="font-size: 12px;">Domisili</div>
                                    <div>${domisiliAtas}</div>
                                    <div class="text-muted" style="font-size: 12px; line-height: 1.2;">${domisiliBawah}</div>
                                </div>
                                <div class="col-md-6 mb-0">
                                    <div class="text-muted" style="font-size: 12px;">Alamat</div>
                                    <div>${escapeHtml(d.alamat_domisili ?? '-')}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-0">
                        <div class="card-header"><strong>Data Pendidikan</strong></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted" style="font-size: 12px;">Pendidikan Terakhir</div>
                                    <div>${escapeHtml(d.pendidikan_terakhir ?? '-')}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted" style="font-size: 12px;">Jurusan</div>
                                    <div>${escapeHtml(d.jurusan ?? '-')}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted" style="font-size: 12px;">Nama Sekolah/Universitas</div>
                                    <div>${escapeHtml(d.nama_sekolah ?? '-')}</div>
                                </div>
                                <div class="col-md-6 mb-0">
                                    <div class="text-muted" style="font-size: 12px;">Tahun Lulus</div>
                                    <div>${escapeHtml(d.tahun_lulus ?? '-')}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $('#detailMemberContent').html(html);
            });
        }

        async function initPageLoad() {
            await getListData(defaultLimitPage, currentPage, defaultAscending, defaultSearch, customFilter);
            await searchList();
            await deleteData();
            await detailData();
            await initIndonesiaChain();
        }

        async function fetchJSON(url) {
            try {
                const res = await renderAPI('GET', url, {});
                return res?.data ?? [];
            } catch (e) {
                return [];
            }
        }

        async function initIndonesiaChain() {
            async function initIndonesiaChainForModal($modal, selected = {}) {
                if ($modal.data('indonesiaInitRunning')) return;
                $modal.data('indonesiaInitRunning', true);

                console.debug('[member] initIndonesiaChainForModal', $modal.attr('id'), selected);

                const $prov = $modal.find('.province_code').first();
                const $city = $modal.find('.city_code').first();
                const $dist = $modal.find('.district_code').first();
                const $vill = $modal.find('.village_code').first();

                if (!$prov.length || !$city.length || !$dist.length || !$vill.length) {
                    $modal.data('indonesiaInitRunning', false);
                    return;
                }

                const normalizeCode = (value) => {
                    if (value === undefined || value === null) return null;
                    const str = String(value).trim();
                    return str === '' ? null : str;
                };

                if (!$prov.hasClass('select2-hidden-accessible')) {
                    $prov.select2({
                        dropdownParent: $modal,
                        placeholder: '~Pilih Provinsi~',
                        allowClear: true,
                        width: '100%',
                    });
                }
                if (!$city.hasClass('select2-hidden-accessible')) {
                    $city.select2({
                        dropdownParent: $modal,
                        placeholder: '~Pilih Kabupaten/Kota~',
                        allowClear: true,
                        width: '100%',
                    });
                }
                if (!$dist.hasClass('select2-hidden-accessible')) {
                    $dist.select2({
                        dropdownParent: $modal,
                        placeholder: '~Pilih Kecamatan~',
                        allowClear: true,
                        width: '100%',
                    });
                }
                if (!$vill.hasClass('select2-hidden-accessible')) {
                    $vill.select2({
                        dropdownParent: $modal,
                        placeholder: '~Pilih Desa/Kelurahan~',
                        allowClear: true,
                        width: '100%',
                    });
                }

                $prov.prop('disabled', true).empty().append('<option value="">Memuat...</option>').val(null).trigger('change');

                const resetDependent = () => {
                    $city.prop('disabled', true).empty().append('<option value=""></option>').val(null).trigger('change');
                    $dist.prop('disabled', true).empty().append('<option value=""></option>').val(null).trigger('change');
                    $vill.prop('disabled', true).empty().append('<option value=""></option>').val(null).trigger('change');
                };

                const setCities = async (provinceCode, citySelected = null) => {
                    $city.empty().append('<option value=""></option>').val(null).trigger('change');
                    $dist.empty().append('<option value=""></option>').val(null).trigger('change');
                    $vill.empty().append('<option value=""></option>').val(null).trigger('change');

                    $city.prop('disabled', !provinceCode).trigger('change');
                    $dist.prop('disabled', true).trigger('change');
                    $vill.prop('disabled', true).trigger('change');

                    if (!provinceCode) return;
                    const cities = await fetchJSON(`{{ url('/admin/indonesia/regencies') }}/${provinceCode}`);
                    cities.forEach(c => $city.append(`<option value="${c.id}">${c.name}</option>`));
                    if (citySelected) {
                        $city.val(citySelected).trigger('change');
                    }
                };

                const setDistricts = async (cityCode, districtSelected = null) => {
                    $dist.empty().append('<option value=""></option>').val(null).trigger('change');
                    $vill.empty().append('<option value=""></option>').val(null).trigger('change');

                    $dist.prop('disabled', !cityCode).trigger('change');
                    $vill.prop('disabled', true).trigger('change');

                    if (!cityCode) return;
                    const districts = await fetchJSON(`{{ url('/admin/indonesia/districts') }}/${cityCode}`);
                    districts.forEach(d => $dist.append(`<option value="${d.id}">${d.name}</option>`));
                    if (districtSelected) {
                        $dist.val(districtSelected).trigger('change');
                    }
                };

                const setVillages = async (districtCode, villageSelected = null) => {
                    $vill.empty().append('<option value=""></option>').val(null).trigger('change');
                    $vill.prop('disabled', !districtCode).trigger('change');
                    if (!districtCode) return;
                    const villages = await fetchJSON(`{{ url('/admin/indonesia/villages') }}/${districtCode}`);
                    villages.forEach(v => $vill.append(`<option value="${v.id}">${v.name}</option>`));
                    if (villageSelected) {
                        $vill.val(villageSelected).trigger('change');
                    }
                };

                resetDependent();

                const provinces = await fetchJSON('{{ url('/admin/indonesia/provinces') }}');
                $prov.empty().append('<option value=""></option>');
                provinces.forEach(p => $prov.append(`<option value="${p.id}">${p.name}</option>`));
                $prov.prop('disabled', false).trigger('change');

                const provinceSelected = normalizeCode(selected.province_code);
                const citySelected = normalizeCode(selected.city_code);
                const districtSelected = normalizeCode(selected.district_code);
                const villageSelected = normalizeCode(selected.village_code);

                let isInitializing = true;

                $prov.off('change.indonesia').on('change.indonesia', async function() {
                    if (isInitializing) return;
                    const code = $(this).val();
                    await setCities(code, null);
                });
                $city.off('change.indonesia').on('change.indonesia', async function() {
                    if (isInitializing) return;
                    const code = $(this).val();
                    await setDistricts(code, null);
                });
                $dist.off('change.indonesia').on('change.indonesia', async function() {
                    if (isInitializing) return;
                    const code = $(this).val();
                    await setVillages(code, null);
                });

                $prov.val(provinceSelected).trigger('change');
                if (provinceSelected) {
                    await setCities(provinceSelected, citySelected);
                }
                if (citySelected) {
                    await setDistricts(citySelected, districtSelected);
                }
                if (districtSelected) {
                    await setVillages(districtSelected, villageSelected);
                }

                isInitializing = false;
                $modal.data('indonesiaInitRunning', false);
            }

            const getSelectedFromModal = ($m) => ({
                province_code: $m.data('province') || null,
                city_code: $m.data('city') || null,
                district_code: $m.data('district') || null,
                village_code: $m.data('village') || null,
            });

            $(document)
                .off('shown.bs.modal.indonesiaAdd', '#modal-form')
                .on('shown.bs.modal.indonesiaAdd', '#modal-form', async function() {
                    await initIndonesiaChainForModal($(this));
                });

            $(document)
                .off('shown.bs.modal.indonesiaEdit', '.edit-member-modal')
                .on('shown.bs.modal.indonesiaEdit', '.edit-member-modal', async function() {
                    const $m = $(this);
                    await initIndonesiaChainForModal($m, getSelectedFromModal($m));
                });

            $(document)
                .off('click.indonesiaEditInit', '.edit-data')
                .on('click.indonesiaEditInit', '.edit-data', async function() {
                    const target = $(this).attr('data-target');
                    if (!target) return;
                    const $m = $(target);
                    if (!$m.length) return;
                    await initIndonesiaChainForModal($m, getSelectedFromModal($m));
                });
        }
    </script>
@endsection
