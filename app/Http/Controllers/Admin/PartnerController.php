<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PartnerStoreRequest;
use App\Http\Requests\Admin\PartnerUpdateRequest;
use App\Models\Partner;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of partners.
     */
    public function index(Request $request)
    {
        $partners = Partner::ordered()
            ->when($request->filled('search'), function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(15);

        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new partner.
     */
    public function create()
    {
        $nextOrder = Partner::max('order') + 1;
        return view('admin.partners.create', compact('nextOrder'));
    }

    /**
     * Store a newly created partner in storage.
     */
    public function store(PartnerStoreRequest $request)
    {
        $data = $request->validated();
        $data['image'] = $this->handleImageUpload($request->file('image'), 'partners');

        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner created successfully!');
    }

    /**
     * Display the specified partner.
     */
    public function show(Partner $partner)
    {
        return view('admin.partners.show', compact('partner'));
    }

    /**
     * Show the form for editing the specified partner.
     */
    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    /**
     * Update the specified partner in storage.
     */
    public function update(PartnerUpdateRequest $request, Partner $partner)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($partner->image) {
                Storage::disk('public')->delete('partners/' . $partner->image);
            }
            $data['image'] = $this->handleImageUpload($request->file('image'), 'partners');
        }

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully!');
    }

    /**
     * Remove the specified partner from storage.
     */
    public function destroy(Partner $partner)
    {
        // Delete image
        if ($partner->image) {
            Storage::disk('public')->delete('partners/' . $partner->image);
        }

        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner moved to Recycle Bin.');
    }

    /**
     * Handle single image upload for partners.
     */
    private function handleImageUpload($image, $directory = 'partners')
    {
        if (! $image) {
            return null;
        }

        $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs($directory, $filename, 'public');

        return $path;
    }
}

