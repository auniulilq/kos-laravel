<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOption;
use Illuminate\Http\Request;

class ServiceOptionController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceOption::query();

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('unit_name', 'like', "%$q%");
            });
        }

        $options = $query->orderBy('service_type')
            ->orderBy('name')
            ->paginate(6)
            ->appends($request->query());

        return view('admin.service-options.index', compact('options'));
    }

    public function create()
    {
        $serviceTypes = ['laundry', 'blanket', 'repair', 'other'];
        $pricingTypes = ['fixed', 'per_unit', 'quote'];
        return view('admin.service-options.create', compact('serviceTypes', 'pricingTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|in:laundry,blanket,repair,other',
            'name' => 'required|string|max:255',
            'pricing_type' => 'required|in:fixed,per_unit,quote',
            'unit_name' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'min_qty' => 'nullable|integer|min:0',
            'max_qty' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // Conditional requirements
        if ($validated['pricing_type'] !== 'quote') {
            $request->validate(['price' => 'required|numeric|min:0']);
        }
        if ($validated['pricing_type'] === 'per_unit') {
            $request->validate(['unit_name' => 'required|string|max:50']);
        }
        if (!empty($validated['min_qty']) && !empty($validated['max_qty']) && $validated['max_qty'] < $validated['min_qty']) {
            return back()->withInput()->with('error', 'Max Qty harus lebih besar dari Min Qty');
        }

        $validated['is_active'] = $request->boolean('is_active');

        ServiceOption::create($validated);

        return redirect()->route('admin.service-options.index')
            ->with('success', 'Opsi layanan berhasil ditambahkan');
    }

    public function edit(ServiceOption $service_option)
    {
        $serviceTypes = ['laundry', 'blanket', 'repair', 'other'];
        $pricingTypes = ['fixed', 'per_unit', 'quote'];
        return view('admin.service-options.edit', [
            'option' => $service_option,
            'serviceTypes' => $serviceTypes,
            'pricingTypes' => $pricingTypes,
        ]);
    }

    public function update(Request $request, ServiceOption $service_option)
    {
        $validated = $request->validate([
            'service_type' => 'required|in:laundry,blanket,repair,other',
            'name' => 'required|string|max:255',
            'pricing_type' => 'required|in:fixed,per_unit,quote',
            'unit_name' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'min_qty' => 'nullable|integer|min:0',
            'max_qty' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validated['pricing_type'] !== 'quote') {
            $request->validate(['price' => 'required|numeric|min:0']);
        }
        if ($validated['pricing_type'] === 'per_unit') {
            $request->validate(['unit_name' => 'required|string|max:50']);
        }
        if (!empty($validated['min_qty']) && !empty($validated['max_qty']) && $validated['max_qty'] < $validated['min_qty']) {
            return back()->withInput()->with('error', 'Max Qty harus lebih besar dari Min Qty');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $service_option->update($validated);

        return redirect()->route('admin.service-options.index')
            ->with('success', 'Opsi layanan berhasil diupdate');
    }

    public function destroy(ServiceOption $service_option)
    {
        $service_option->delete();
        return redirect()->route('admin.service-options.index')
            ->with('success', 'Opsi layanan berhasil dihapus');
    }
    public function show($id)
{
    $serviceOption = \App\Models\ServiceOption::findOrFail($id);
    return view('admin.service-options.show', compact('serviceOption'));
}
}