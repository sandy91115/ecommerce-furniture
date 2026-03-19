<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use App\Mail\QuotationSubmitted; // Optional

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
        $product->load('images');
        return view('quotation-form', compact('product'));
    }

    public function storeQuotation(Request $request, Product $product)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'message' => 'required|string',
        ]);

        Quotation::create([
            'product_id' => $product->id,
            'customer_name' => $request->customer_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
        ]);

        // Optional: Mail::to(config('mail.from.address'))->send(new QuotationSubmitted($quotation));

        return redirect()->back()->with('success', 'Quotation request submitted successfully! We will contact you soon.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
