@extends('layouts.main') <!-- Sesuaikan dengan nama layout utamamu, misal: layouts.app atau layouts.main -->

@section('content')
<div class="py-12" style="padding: 3rem 1rem;">
    <div style="max-width: 56rem; margin: 0 auto;">
        
        <!-- Header Halaman -->
        <div style="background-color: #d1fae5; border-left: 4px solid #16a34a; padding: 1rem; border-radius: 0 0.5rem 0.5rem 0; margin-bottom: 1.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
            <h2 style="font-weight: 600; font-size: 1.25rem; color: #14532d; margin: 0;">
                Detail Informasi Buku
            </h2>
        </div>

        <!-- Card Konten Utama -->
        <div style="background-color: rgba(209, 250, 229, 0.7); box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); border-radius: 0.5rem; border: 1px solid #86efac; padding: 2rem;">
            
            @if($buku)
                {{-- Tampilan jika data buku DITEMUKAN --}}
                <div style="border-bottom: 1px solid #86efac; padding-bottom: 1rem; margin-bottom: 1.5rem;">
                    <span style="display: inline-block; padding: 0.25rem 0.75rem; font-size: 0.75rem; font-weight: 600; border-radius: 9999px; background-color: #bbf7d0; color: #166534; margin-bottom: 0.5rem;">
                        Kategori: {{ $buku['kategori'] ?? '-' }}
                    </span>
                    <h3 style="font-size: 1.5rem; font-weight: bold; color: #14532d; margin: 0;">
                        {{ $buku['judul'] ?? '-' }}
                    </h3>
                </div>

                <!-- Informasi Detail Buku -->
                <div style="background-color: #ffffff; border-radius: 0.5rem; border: 1px solid #e5e7eb; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                        <div>
                            <span style="display: block; font-size: 0.75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase;">ID Buku</span>
                            <span style="font-size: 1rem; font-weight: 500; color: #1f2937;">#{{ $buku['id'] ?? '-' }}</span>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase;">Penulis</span>
                            <span style="font-size: 1rem; font-weight: 500; color: #1f2937;">{{ $buku['penulis'] ?? '-' }}</span>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase;">Tahun Terbit</span>
                            <span style="font-size: 1rem; font-weight: 500; color: #1f2937;">{{ $buku['tahun_terbit'] ?? '-' }}</span>
                        </div>
                    </div>

                    @if(!empty($buku['deskripsi']))
                        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f3f4f6;">
                            <span style="display: block; font-size: 0.75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; margin-bottom: 0.25rem;">Deskripsi</span>
                            <p style="font-size: 0.875rem; color: #374151; line-height: 1.5; margin: 0;">{{ $buku['deskripsi'] }}</p>
                        </div>
                    @endif
                </div>

                <!-- Tombol Aksi & Kembali -->
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <a href="{{ route('buku.index') }}" style="display: inline-flex; align-items: center; font-size: 0.875rem; font-weight: 500; color: #15803d; background-color: #ffffff; border: 1px solid #86efac; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                        &larr; Kembali ke Daftar Buku
                    </a>

                    @role('siswa')
                    <button onclick="alert('Pengajuan peminjaman buku berhasil dikirim ke petugas!')" style="background-color: #16a34a; color: #ffffff; padding: 0.5rem 1.25rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 500; border: none; cursor: pointer; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                        Pinjam Buku Ini
                    </button>
                    @endrole
                </div>

            @else
                {{-- Tampilan jika data buku TIDAK DITEMUKAN --}}
                <div style="text-align: center; padding: 2rem 0;">
                    <h2 style="font-size: 1.25rem; font-weight: bold; color: #dc2626; margin-bottom: 0.5rem;">Buku Tidak Ditemukan</h2>
                    <p style="color: #64748b; margin-bottom: 1.5rem;">Maaf, data buku yang kamu cari tidak ada atau kode ID salah.</p>
                    <div>
                        <a href="{{ route('buku.index') }}" style="display: inline-flex; align-items: center; font-size: 0.875rem; font-weight: 500; color: #15803d; background-color: #ffffff; border: 1px solid #86efac; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none;">
                            &larr; Kembali ke Daftar Buku
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection