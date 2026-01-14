@extends('layouts.app')

@section('title', 'Kelola Kategori')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 20px; font-family: 'Inter', sans-serif;">
    {{-- Breadcrumb --}}
    @include('components.breadcrumb', ['breadcrumbs' => [
        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['title' => 'Kategori Kamar', 'url' => route('admin.categories.index')]
    ]])
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin: 0;">Kategori Kamar</h1>
            <p style="color: #64748b; margin-top: 5px;">Klasifikasikan unit kamar berdasarkan tipe dan level layanan.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" style="text-decoration: none; background: #1e293b; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 14px; transition: 0.2s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);" onmouseover="this.style.background='#000'">
            + Tambah Kategori
        </a>
    </div>

    {{-- Table Card --}}
    <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <tr>
                    <th style="padding: 18px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800;">Nama Kategori</th>
                    <th style="padding: 18px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800;">Slug (URL)</th>
                    <th style="padding: 18px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800;">Total Unit</th>
                    <th style="padding: 18px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="font-size: 14px; color: #334155;">
                @forelse($categories as $category)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: 0.2s;" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 20px 25px;">
                        <div style="font-weight: 700; color: #1e293b; font-size: 15px;">{{ $category->name }}</div>
                    </td>
                    <td style="padding: 20px 25px;">
                        <code style="background: #f1f5f9; padding: 4px 8px; border-radius: 6px; color: #475569;">{{ $category->slug }}</code>
                    </td>
                    <td style="padding: 20px 25px;">
                        <span style="background: #eff6ff; color: #3b82f6; padding: 6px 12px; border-radius: 8px; font-weight: 800; font-size: 12px;">
                            {{ $category->rooms_count ?? 0 }} Unit
                        </span>
                    </td>
                    <td style="padding: 20px 25px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.categories.edit', $category) }}" style="padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; color: #0891b2; text-decoration: none; font-weight: 700; font-size: 12px; transition: 0.2s;" onmouseover="this.style.borderColor='#0891b2'; this.style.background='#f0f9ff'">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding: 8px 14px; background: white; border: 1px solid #fee2e2; border-radius: 10px; color: #ef4444; font-weight: 700; font-size: 12px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#fef2f2'">Hapus</button>
                                </form>
                            </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 60px 20px; text-align: center;">
                        <div style="font-size: 40px; margin-bottom: 10px;">📂</div>
                        <div style="font-weight: 700; font-size: 16px; color: #1e293b;">Kategori Belum Tersedia</div>
                        <p style="color: #94a3b8; font-size: 14px;">Tambahkan kategori untuk mulai mengelompokkan kamar.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $categories->links() }}
    </div>
</div>
@endsection