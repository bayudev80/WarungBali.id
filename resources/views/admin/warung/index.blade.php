@extends('admin.layouts.app')

@section('title', 'Data Warung')

@section('content')

    <style>
        .warung-thumb {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        .warung-table {
            table-layout: fixed;
            width: 100%;
            font-size: 0.8rem;
        }
        .warung-table th {
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #f8f9fa;
            white-space: nowrap;
        }
        .warung-table th, .warung-table td {
            vertical-align: middle;
            padding: 0.5rem 0.4rem;
            border-color: #f1f3f5;
        }
        .warung-table td.text-truncate { max-width: 0; }
        
        /* Modern hover effect for table rows */
        .warung-table tbody tr {
            transition: background-color 0.2s ease;
        }
        .warung-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .action-buttons {
            display: flex;
            gap: 0.4rem;
            flex-wrap: nowrap;
            align-items: center;
        }
        .action-buttons .btn {
            border-radius: 6px;
            padding: 0.4rem 0.6rem;
            font-size: 0.85rem;
            transition: all 0.2s;
            border: none;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        /* Modern Card styling */
        .modern-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            overflow: hidden;
            transition: box-shadow 0.3s ease;
        }
        .modern-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        }
        .modern-card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f3f5;
            padding: 1rem 1.25rem;
        }
        
        /* Modern Search Input */
        .modern-input {
            border-radius: 10px;
            border: 1px solid #e9ecef;
            padding: 0.45rem 0.8rem;
            font-size: 0.85rem;
            background-color: #f8f9fa;
            transition: all 0.2s;
        }
        .modern-input:focus {
            background-color: #ffffff;
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.15);
        }
    </style>

<div class="content-box px-2">

    <div class="d-flex justify-content-between align-items-center mb-3 pt-1">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Data Warung</h4>
            <p class="text-muted small mb-0 mt-1">Kelola daftar warung, cabang, dan status persetujuan.</p>
        </div>
        <a href="{{ route('admin.warung.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" style="transition: 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="bi bi-plus-lg me-1"></i> Tambah Warung
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Form pencarian & filter kategori --}}
    <form action="{{ route('admin.warung.index') }}" method="GET" class="card modern-card p-2 px-3 mb-3">
        @if($kabupatenTerpilih)
            <input type="hidden" name="kabupaten" value="{{ $kabupatenTerpilih }}">
        @endif
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 translate-middle-y text-muted ms-3"></i>
                    <input type="text" name="search" value="{{ $search }}" class="form-control modern-input ps-5"
                        placeholder="Cari nama warung, alamat, atau telepon...">
                </div>
            </div>

            <div class="col-8 col-md-4">
                <select name="kategori" class="form-select modern-input text-secondary">
                    <option value="">Semua Kategori</option>
                    @foreach($kategori as $k)
                        <option value="{{ $k->id_kategori }}" @selected((string) $kategoriTerpilih === (string) $k->id_kategori)>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-4 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary text-white w-100 border-0 shadow-sm" style="border-radius: 10px; font-size: 0.85rem; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="bi bi-search me-1"></i> Cari
                </button>
                @if($search !== '' || $kategoriTerpilih)
                    <a href="{{ route('admin.warung.index') }}" class="btn btn-light text-secondary border w-100 d-flex align-items-center justify-content-center" style="border-radius: 10px; font-size: 0.85rem; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    {{-- Navbar Kabupaten --}}
    <div class="d-flex overflow-auto gap-2 mb-3 pb-2" style="white-space: nowrap;">
        <a href="{{ request()->fullUrlWithQuery(['kabupaten' => null, 'page' => 1]) }}" 
           class="btn btn-sm rounded-pill px-3 {{ !$kabupatenTerpilih ? 'btn-primary shadow-sm' : 'btn-outline-secondary bg-white text-secondary border' }}">
           Semua Kabupaten
        </a>
        @foreach($semuaKabupaten as $kab)
            <a href="{{ request()->fullUrlWithQuery(['kabupaten' => $kab->id_kabupaten, 'page' => 1]) }}" 
               class="btn btn-sm rounded-pill px-3 {{ (string)$kabupatenTerpilih === (string)$kab->id_kabupaten ? 'btn-primary shadow-sm' : 'btn-outline-secondary bg-white text-secondary border' }}">
               {{ $kab->nama_kabupaten }}
            </a>
        @endforeach
    </div>

    @if($warung->isEmpty())
        <div class="card modern-card p-5 text-center mb-4">
            <div class="d-flex flex-column align-items-center py-4">
                <i class="bi bi-shop fa-3x text-light mb-3" style="font-size: 4rem;"></i>
                @if($search !== '' || $kategoriTerpilih || $kabupatenTerpilih)
                    <h5 class="text-secondary fw-semibold">Tidak ada warung yang cocok dengan pencarian ini.</h5>
                @else
                    <h5 class="text-secondary fw-semibold">Belum ada data warung.</h5>
                    <p class="text-muted">Silakan tambahkan warung pertama Anda.</p>
                @endif
            </div>
        </div>
    @else
        <div class="card modern-card mb-4">
            <div class="table-responsive p-2">
                <table class="table table-borderless warung-table mb-0">
                    <colgroup>
                        <col style="width:40px">
                        <col style="width:55px">
                        <col>
                        <col style="width:14%">
                        <col style="width:110px">
                        <col style="width:90px">
                        <col style="width:90px">
                        <col style="width:140px">
                    </colgroup>
                    <thead>
                        <tr>
                            <th style="width:40px;" class="text-center">No</th>
                            <th style="width:55px;">Foto</th>
                            <th style="width:250px;">Nama Warung</th>
                            <th>Kategori</th>
                            <th>Telepon</th>
                            <th>Catering</th>
                            <th>Status</th>
                            <th style="width:140px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($warung as $item)
                        @php
                            $statusBadge = [
                                'pending'  => ['label' => 'Menunggu', 'class' => 'bg-warning text-dark'],
                                'approved' => ['label' => 'Disetujui', 'class' => 'bg-success'],
                                'rejected' => ['label' => 'Ditolak', 'class' => 'bg-danger'],
                            ][$item->status];
                        @endphp
                        <tr>
                            <td class="text-center text-muted fw-medium">{{ $warung->firstItem() + $loop->index }}</td>
                            <td>
                                @if($item->foto && file_exists(public_path('images/warung/'.$item->foto)))
                                    <img src="{{ asset('images/warung/'.$item->foto) }}" class="warung-thumb" alt="Foto">
                                @else
                                    <div class="warung-thumb d-flex align-items-center justify-content-center text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="text-truncate" title="{{ $item->nama_warung }}">
                                <div class="fw-bold text-dark">{{ $item->nama_warung }}</div>
                                <div class="d-flex align-items-center mt-1 gap-2">
                                    <span class="badge bg-light text-secondary border">WRG-{{ str_pad($item->id_warung, 4, '0', STR_PAD_LEFT) }}</span>
                                    @if($item->id_warung_induk)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-2">Cabang</span>
                                    @endif
                                </div>
                                @if(!$kabupatenTerpilih)
                                    <div class="text-muted small mt-1"><i class="bi bi-geo-alt-fill"></i> {{ $item->kabupaten->nama_kabupaten ?? '-' }}</div>
                                @endif
                            </td>
                            <td class="text-truncate">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                            <td class="text-nowrap">{{ $item->telepon }}</td>
                            <td class="text-center">
                                @if(!$item->is_kuliner)
                                    <span class="text-muted small">-</span>
                                @elseif($item->menerima_catering)
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Ya</span>
                                @else
                                    <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Tidak</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    @if($item->status === 'pending')
                                        <form action="{{ route('admin.warung.approve', $item->id_warung) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success text-white" title="Setujui" onclick="return confirm('Setujui warung ini?')">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.warung.reject', $item->id_warung) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-danger bg-white" title="Tolak" onclick="return confirm('Tolak warung ini?')">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('admin.warung.edit', $item->id_warung) }}" class="btn btn-warning text-white" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Dropdown untuk aksi lainnya --}}
                                    <div class="dropdown">
                                        <button class="btn btn-secondary text-white" type="button" data-bs-toggle="dropdown" title="Lainnya">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3" style="min-width:160px;font-size:0.85rem;">
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.warung.menu.index', $item->id_warung) }}">
                                                    <i class="bi bi-menu-button-wide text-info"></i> Kelola {{ $item->label_menu }}
                                                </a>
                                            </li>
                                            @if(!$item->is_cabang)
                                            <li>
                                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('admin.warung.cabang.create', $item->id_warung) }}">
                                                    <i class="bi bi-diagram-3 text-success"></i> Tambah Cabang
                                                </a>
                                            </li>
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.warung.destroy', $item->id_warung) }}" method="POST" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger" onclick="return confirm('Hapus warung ini?')">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            {{ $warung->links() }}
        </div>
    @endif

</div>

@endsection