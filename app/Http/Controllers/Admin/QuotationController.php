<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Product;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $quotations = Quotation::with('product')->latest()->paginate(10);
        return view('admin.quotations.index', compact('quotations'));
    }

    public function showForm(Product $product)
    {
        if ($product->product_type !== 'quotation') {
            abort(404);
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

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'desired_price' => 'nullable|numeric|min:0',
            'message' => 'nullable|string|max:1000',
        ]);

        $quotation = Quotation::create([
            'product_id' => $product->id,
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'desired_price' => $request->desired_price,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quotation request submitted successfully! We will contact you soon.',
            'quotation' => $quotation,
        ]);

        // Optional: Mail::to(config('mail.from.address'))->send(new QuotationSubmitted($quotation));


    }

    /**
     * Display the specified resource.
     */
    public function show(Quotation $quotation)
    {
        $quotation->load('product.images');

        return view('admin.quotations.show', compact('quotation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quotation $quotation)
    {
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
        $quotation->delete();

        return redirect()
            ->route('admin.quotations.index')
            ->with('success', 'Quotation moved to Recycle Bin successfully.');
    }
}
