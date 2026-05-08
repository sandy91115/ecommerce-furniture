<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Quotation;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
<<<<<<< HEAD
        $this->authorize('quotations.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $quotations = Quotation::with('product')->latest()->paginate(10);
        return view('admin.quotations.index', compact('quotations'));
    }

    public function showForm(Request $request, Product $product)
    {
        if ($product->product_type !== 'quotation') {
            abort(404);
        }

        if (! $request->expectsJson() && ! $request->ajax()) {
            return redirect()->route('product-details', $product->slug);
        }

        return response()->json([
            'success' => true,
            'product' => $product,
        ]);
    }

    public function storeQuotation(Request $request, Product $product)
    {
        if ($product->product_type !== 'quotation') {
            abort(404);
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
<<<<<<< HEAD
            'city' => 'required|string|max:120',
            'country' => 'required|string|max:120',
            'pincode' => 'required|string|max:20',
=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            'desired_price' => 'nullable|numeric|min:0',
            'message' => 'nullable|string|max:1000',
        ]);

        $quotation = Quotation::create([
            'product_id' => $product->id,
<<<<<<< HEAD
            'user_id' => auth()->id(),
            'customer_name' => trim($validated['customer_name']),
            'email' => $validated['email'],
            'phone' => filled($validated['phone'] ?? null) ? $validated['phone'] : null,
            'city' => trim($validated['city']),
            'country' => trim($validated['country']),
            'pincode' => trim($validated['pincode']),
=======
            'customer_name' => trim($validated['customer_name']),
            'email' => $validated['email'],
            'phone' => filled($validated['phone'] ?? null) ? $validated['phone'] : null,
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
            'desired_price' => $validated['desired_price'] ?? null,
            'message' => trim($validated['message'] ?? ''),
            'status' => 'pending',
        ]);

        $responsePayload = [
            'success' => true,
            'message' => 'Quotation request submitted successfully! We will contact you soon.',
            'quotation' => $quotation,
        ];

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($responsePayload);
        }

        return redirect()
            ->route('product-details', $product->slug)
            ->with('success', $responsePayload['message']);
<<<<<<< HEAD
=======

        // Optional: Mail::to(config('mail.from.address'))->send(new QuotationSubmitted($quotation));
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
    }

    /**
     * Display the specified resource.
     */
    public function show(Quotation $quotation)
    {
<<<<<<< HEAD
        $this->authorize('quotations.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $quotation->load('product.images');

        return view('admin.quotations.show', compact('quotation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quotation $quotation)
    {
<<<<<<< HEAD
        $this->authorize('quotations.update');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $data = $request->validate([
            'status' => 'required|in:pending,contacted,closed',
        ]);

        $quotation->update($data);

        return redirect()
            ->route('admin.quotations.show', $quotation)
            ->with('success', 'Quotation status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quotation $quotation)
    {
<<<<<<< HEAD
        $this->authorize('quotations.delete');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $quotation->delete();

        return redirect()
            ->route('admin.quotations.index')
            ->with('success', 'Quotation moved to Recycle Bin successfully.');
    }
}
