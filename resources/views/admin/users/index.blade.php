@extends('layouts.app')

@section('title', 'Kelola Penyewa')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 25px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    {{-- Breadcrumb --}}
    @include('components.breadcrumb', ['breadcrumbs' => [
        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['title' => 'Penyewa', 'url' => route('admin.users.index')]
    ]])
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -1px;">Daftar Penyewa</h1>
            <p style="color: #64748b; margin-top: 5px; font-size: 15px;">Manajemen data pengguna dan monitoring status penyewaan unit.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" style="text-decoration: none; background: #1e293b; color: white; padding: 14px 28px; border-radius: 14px; font-weight: 700; font-size: 14px; transition: 0.3s; box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.2); display: flex; align-items: center; gap: 8px;" onmouseover="this.style.background='#000'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#1e293b'; this.style.transform='translateY(0)'">
            <span style="font-size: 18px;">+</span> Tambah Penyewa Baru
        </a>
    </div>

    {{-- Filter & Search Card --}}
    <div style="background: white; padding: 20px; border-radius: 20px; border: 1px solid #f1f5f9; margin-bottom: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 300px; position: relative;">
                <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8;">🔍</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, email, atau nomor telepon..." 
                    style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #f8fafc; outline: none; transition: 0.2s; font-size: 14px;"
                    onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';">
            </div>

            <select name="has_room" style="padding: 12px 15px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #f8fafc; color: #475569; font-weight: 600; cursor: pointer; min-width: 200px; font-size: 14px; outline: none;">
                <option value="">Semua Status Kamar</option>
                <option value="yes" {{ request('has_room') === 'yes' ? 'selected' : '' }}>✓ Sudah Ada Kamar</option>
                <option value="no" {{ request('has_room') === 'no' ? 'selected' : '' }}>○ Belum Ada Kamar</option>
            </select>

            <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 12px 30px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.2s; font-size: 14px;" onmouseover="this.style.background='#2563eb'">
                Terapkan Filter
            </button>
            
            <a href="{{ route('admin.users.index') }}" style="text-decoration: none; color: #94a3b8; font-size: 13px; font-weight: 700; padding: 0 5px; transition: 0.2s;" onmouseover="this.style.color='#ef4444'">Reset</a>
        </form>
    </div>

    {{-- Table Card --}}
    <div style="background: white; border-radius: 24px; border: 1px solid #f1f5f9; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.04);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: #fafafa; border-bottom: 1px solid #f1f5f9;">
                <tr>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">Penyewa</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">Kontak</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">Penempatan</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px;">Status</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 0.5px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="font-size: 14px; color: #334155;">
                @forelse($users as $user)
                <tr style="border-bottom: 1px solid #f8fafc; transition: 0.2s;" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 15px 25px;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 42px; height: 42px; background: #eff6ff; color: #3b82f6; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px; border: 1px solid #dbeafe;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 700; color: #1e293b; font-size: 15px;">{{ $user->name }}</div>
                                <div style="font-size: 11px; color: #94a3b8; margin-top: 2px; font-weight: 600;">ID: USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 15px 25px;">
                        <div style="color: #475569; font-weight: 600; font-size: 13px;">{{ $user->email }}</div>
                        <div style="font-size: 12px; color: #94a3b8; margin-top: 2px;">{{ $user->phone }}</div>
                    </td>
                    <td style="padding: 15px 25px;">
                        @if($user->room)
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="background: #3b82f6; color: white; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 11px;">
                                    Unit {{ $user->room->room_number }}
                                </span>
                            </div>
                        @else
                            <span style="color: #cbd5e1; font-size: 12px; font-weight: 500; font-style: italic;">Belum dialokasi</span>
                        @endif
                    </td>
                    <td style="padding: 15px 25px;">
                        @if($user->room)
                            <span style="display: inline-flex; align-items: center; gap: 5px; background: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">
                                <span style="width: 6px; height: 6px; background: #10b981; border-radius: 50%;"></span> AKTIF
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; gap: 5px; background: #f1f5f9; color: #64748b; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800;">
                                <span style="width: 6px; height: 6px; background: #94a3b8; border-radius: 50%;"></span> NON-AKTIF
                            </span>
                        @endif
                    </td>
                    <td style="padding: 20px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.users.show', $user) }}" style="padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; color: #1e293b; text-decoration: none; font-weight: 700; font-size: 12px; transition: 0.2s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.color='#3b82f6'">Detail</a>
                                <a href="{{ route('admin.users.edit', $user) }}" style="padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; color: #0891b2; text-decoration: none; font-weight: 700; font-size: 12px; transition: 0.2s;" onmouseover="this.style.borderColor='#0891b2'; this.style.background='#f0f9ff'">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus pengguna ini?')" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding: 8px 14px; background: white; border: 1px solid #fee2e2; border-radius: 10px; color: #ef4444; font-weight: 700; font-size: 12px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#fef2f2'">Hapus</button>
                                </form>
                            </div>
                        </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 100px 20px; text-align: center;">
                        <div style="font-size: 50px; margin-bottom: 15px;">👥</div>
                        <div style="font-weight: 800; font-size: 18px; color: #1e293b;">Belum Ada Penyewa</div>
                        <p style="color: #94a3b8; font-size: 14px; margin-top: 5px;">Data penyewa yang Anda tambahkan akan muncul di sini.</p>
                        <a href="{{ route('admin.users.create') }}" style="display: inline-block; margin-top: 20px; color: #3b82f6; font-weight: 700; text-decoration: none; font-size: 14px;">+ Tambah Penyewa Pertama</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="margin-top: 30px; display: flex; justify-content: center;">
        {{ $users->links() }}
    </div>
</div>
@endsection