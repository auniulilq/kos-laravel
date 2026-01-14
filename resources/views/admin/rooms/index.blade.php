@extends('layouts.app')

@section('title', 'Kelola Kamar')

@section('content')
<div style="max-width: 1240px; margin: 0 auto; padding: 25px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    {{-- Breadcrumb --}}
    @include('components.breadcrumb', ['breadcrumbs' => [
        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['title' => 'Kamar', 'url' => route('admin.rooms.index')]
    ]])
    {{-- Header Dashboard --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 35px;">
        <div>
            <h1 style="font-size: 30px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -0.5px;">Kelola Kamar</h1>
            <p style="color: #64748b; margin: 8px 0 0 0; font-size: 15px;">Manajemen unit, status hunian, dan harga kamar secara real-time.</p>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('admin.categories.index') }}" style="background: white; color: #334155; text-decoration: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 14px; border: 1px solid #e2e8f0; transition: 0.3s; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
                📁 Kategori
            </a>
            <a href="{{ route('admin.rooms.create') }}" style="background: #3b82f6; color: white; text-decoration: none; padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 14px; transition: 0.3s; box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.2); display: flex; align-items: center; gap: 8px;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                <span>+</span> Tambah Kamar
            </a>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 35px;">
        <div style="background: white; padding: 24px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="color: #94a3b8; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Total Unit</div>
            <div style="font-size: 32px; font-weight: 800; color: #1e293b;">{{ $stats['total'] }} <span style="font-size: 14px; color: #94a3b8; font-weight: 500;">Kamar</span></div>
        </div>
        <div style="background: white; padding: 24px; border-radius: 20px; border-left: 6px solid #10b981; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="color: #10b981; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Kosong</div>
            <div style="font-size: 32px; font-weight: 800; color: #1e293b;">{{ $stats['empty'] }}</div>
        </div>
        <div style="background: white; padding: 24px; border-radius: 20px; border-left: 6px solid #3b82f6; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="color: #3b82f6; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Terisi</div>
            <div style="font-size: 32px; font-weight: 800; color: #1e293b;">{{ $stats['occupied'] }}</div>
        </div>
        <div style="background: white; padding: 24px; border-radius: 20px; border-left: 6px solid #f59e0b; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="color: #f59e0b; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Perbaikan</div>
            <div style="font-size: 32px; font-weight: 800; color: #1e293b;">{{ $stats['maintenance'] }}</div>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div style="background: white; padding: 20px; border-radius: 20px; border: 1px solid #f1f5f9; margin-bottom: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
        <form method="GET" action="{{ route('admin.rooms.index') }}" style="display:flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px; position: relative;">
                <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8;">🔍</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nomor kamar atau penyewa..." 
                    style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; outline: none; transition: 0.3s; font-size: 14px;"
                    onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';">
            </div>

            <select name="status" style="padding: 12px 15px; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; color: #475569; min-width: 180px; font-weight: 600; outline: none;">
                <option value="">Semua Status</option>
                @foreach(['empty' => 'Kosong', 'occupied' => 'Terisi', 'maintenance' => 'Perbaikan'] as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <button type="submit" style="background: #1e293b; color: white; border: none; padding: 12px 30px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s;" onmouseover="this.style.background='#000'" onmouseout="this.style.background='#1e293b'">
                Terapkan
            </button>
            <a href="{{ route('admin.rooms.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 700; padding: 0 10px;">Reset</a>
        </form>
    </div>

    {{-- Table Card --}}
    <div style="background: white; border-radius: 24px; border: 1px solid #f1f5f9; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead style="background: #fafafa; border-bottom: 1px solid #f1f5f9;">
                    <tr>
                        <th style="padding: 20px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Kamar & Kategori</th>
                        <th style="padding: 20px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Info Detail</th>
                        <th style="padding: 20px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Harga</th>
                        <th style="padding: 20px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Status</th>
                        <th style="padding: 20px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Penyewa</th>
                        <th style="padding: 20px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px; color: #334155;">
                    @forelse($rooms as $room)
                    <tr style="border-bottom: 1px solid #f8fafc; transition: 0.2s;" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 20px;">
                            <div style="font-weight: 800; color: #1e293b; font-size: 16px;">{{ $room->room_number }}</div>
                            <div style="font-size: 12px; color: #3b82f6; font-weight: 600; margin-top: 4px;">{{ $room->category->name ?? 'Default' }}</div>
                        </td>
                        <td style="padding: 20px;">
                            <div style="font-size: 13px; color: #475569;">📏 {{ $room->area }}</div>
                            <div style="font-size: 13px; color: #475569; margin-top: 4px;">⚡ {{ $room->electricity }} Watt</div>
                        </td>
                        <td style="padding: 20px; font-weight: 700; color: #1e293b;">{{ $room->formatted_price }}</td>
                        <td style="padding: 20px;">
                            @if($room->status === 'empty')
                                <span style="background: #ecfdf5; color: #065f46; padding: 6px 14px; border-radius: 10px; font-size: 11px; font-weight: 800; border: 1px solid #d1fae5;">KOSONG</span>
                            @elseif($room->status === 'occupied')
                                <span style="background: #eff6ff; color: #1e40af; padding: 6px 14px; border-radius: 10px; font-size: 11px; font-weight: 800; border: 1px solid #dbeafe;">TERISI</span>
                            @else
                                <span style="background: #fffbeb; color: #92400e; padding: 6px 14px; border-radius: 10px; font-size: 11px; font-weight: 800; border: 1px solid #fef3c7;">PERBAIKAN</span>
                            @endif
                        </td>
                        <td style="padding: 20px;">
                            <div style="font-weight: 600; color: #475569;">{{ $room->user->name ?? '-' }}</div>
                            <div style="font-size: 12px; color: #94a3b8;">Max: {{ $room->capacity }} Orang</div>
                        </td>
                        <td style="padding: 20px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.rooms.show', $room) }}" style="padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; color: #1e293b; text-decoration: none; font-weight: 700; font-size: 12px; transition: 0.2s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.color='#3b82f6'">Detail</a>
                                <a href="{{ route('admin.rooms.edit', $room) }}" style="padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; color: #0891b2; text-decoration: none; font-weight: 700; font-size: 12px; transition: 0.2s;" onmouseover="this.style.borderColor='#0891b2'; this.style.background='#f0f9ff'">Edit</a>
                                <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Hapus kamar ini?')" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding: 8px 14px; background: white; border: 1px solid #fee2e2; border-radius: 10px; color: #ef4444; font-weight: 700; font-size: 12px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#fef2f2'">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 80px 20px; text-align: center; color: #94a3b8;">
                            <div style="font-size: 48px; margin-bottom: 15px;">🏨</div>
                            <div style="font-weight: 800; color: #1e293b; font-size: 18px;">Belum Ada Data Kamar</div>
                            <div style="font-size: 14px; margin-top: 5px;">Mulai dengan menambahkan unit kamar pertama Anda.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div style="margin-top: 30px; display: flex; justify-content: center;">
        {{ $rooms->links() }}
    </div>
</div>
@endsection