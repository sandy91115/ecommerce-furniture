<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderStoreRequest;
use App\Http\Requests\Admin\OrderUpdateRequest;
use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
<<<<<<< HEAD
        $this->authorize('orders.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $orders = $this->orderService->all();
        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
<<<<<<< HEAD
        $this->authorize('orders.update');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $products = \App\Models\Product::where('status', 'active')->get();
        return view('admin.orders.create', compact('products'));
    }

    public function store(OrderStoreRequest $request)
    {
<<<<<<< HEAD
        $this->authorize('orders.update');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $data = $request->validated();
        $this->orderService->create($data);

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
<<<<<<< HEAD
        $this->authorize('orders.show');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $order->load(['user', 'vendor', 'orderItems.product.images']);

        return view('admin.orders.show', [
            'order' => $order,
            'snapshotItems' => collect($order->items),
        ]);
    }

    public function edit(Order $order)
    {
<<<<<<< HEAD
        $this->authorize('orders.update');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $order->load(['user', 'vendor', 'orderItems.product.images']);

        $editableItems = collect($order->items);

        if ($editableItems->isEmpty() && $order->orderItems->isNotEmpty()) {
            $editableItems = $order->orderItems->map(function ($item) {
                return [
                    'name' => $item->product_name,
                    'sku' => $item->product_sku,
                    'quantity' => $item->quantity,
                    'price' => (float) $item->price,
                    'image' => $item->product?->images->first()?->path,
                    'attributes' => $item->variation_data ?? [],
                ];
            });
        }

        return view('admin.orders.edit', [
            'order' => $order,
            'editableItems' => $editableItems,
        ]);
    }

    public function update(OrderUpdateRequest $request, Order $order)
    {
<<<<<<< HEAD
        $this->authorize('orders.update');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $data = $request->validated();
        $this->orderService->update($order->id, $data);

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
<<<<<<< HEAD
        $this->authorize('orders.delete');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $this->orderService->delete($order->id);
        return redirect()->route('admin.orders.index')->with('success', 'Order moved to Recycle Bin successfully.');
    }

    public function pending()
    {
<<<<<<< HEAD
        $this->authorize('orders.view');

=======
>>>>>>> a4263c56a3ac3187932f99434605d5942427c646
        $orders = $this->orderService->byStatus('pending');
        return view('admin.orders.pending', compact('orders'));
    }
}
