<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Restaurant;

class OrderController extends Controller
{
    // Customer: place order from cart
    public function store(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id'    => 'required|exists:restaurants,id',
            'delivery_address' => 'required|string|max:255',
            'phone'            => 'required|regex:/^[0-9]{10,11}$/',
            'notes'            => 'nullable|string|max:500',
            'payment_method'   => 'required|in:cash,momo,bank_transfer',
            'items'            => 'required|array|min:1',
            'items.*.id'       => 'required|exists:menu_items,id',
            'items.*.qty'      => 'required|integer|min:1',
        ], [
            'delivery_address.required' => 'Địa chỉ giao hàng không được để trống.',
            'phone.required'            => 'Số điện thoại không được để trống.',
            'phone.regex'               => 'Số điện thoại không hợp lệ.',
            'items.required'            => 'Giỏ hàng trống.',
            'payment_method.required'   => 'Vui lòng chọn phương thức thanh toán.',
        ]);

        $restaurant = Restaurant::findOrFail($validated['restaurant_id']);
        $subtotal   = 0;
        $orderItems = [];

        foreach ($validated['items'] as $item) {
            $menuItem = MenuItem::findOrFail($item['id']);
            if ($menuItem->restaurant_id !== $restaurant->id) {
                return back()->withErrors(['items' => 'Có món ăn không thuộc nhà hàng này.']);
            }
            $price    = $menuItem->effective_price;
            $sub      = $price * $item['qty'];
            $subtotal += $sub;
            $orderItems[] = [
                'menu_item_id' => $menuItem->id,
                'item_name'    => $menuItem->name,
                'item_price'   => $price,
                'quantity'     => $item['qty'],
                'subtotal'     => $sub,
            ];
        }

        if ($subtotal < $restaurant->min_order) {
            return back()->withErrors(['items' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($restaurant->min_order) . 'đ.']);
        }

        $total = $subtotal + $restaurant->delivery_fee;

        $order = Order::create([
            'user_id'          => Auth::id(),
            'restaurant_id'    => $restaurant->id,
            'order_number'     => Order::generateOrderNumber(),
            'status'           => 'pending',
            'subtotal'         => $subtotal,
            'delivery_fee'     => $restaurant->delivery_fee,
            'total'            => $total,
            'delivery_address' => $validated['delivery_address'],
            'phone'            => $validated['phone'],
            'notes'            => $validated['notes'] ?? null,
            'payment_method'   => $validated['payment_method'],
            'payment_status'   => 'pending',
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        return redirect()->route('orders.show', $order)->with('success', 'Đặt hàng thành công! Mã đơn: ' . $order->order_number);
    }

    // Customer: my orders list
    public function index()
    {
        $orders = Auth::user()->orders()
            ->with(['restaurant', 'items'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    // Customer: order detail
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $order->load(['restaurant', 'items.menuItem', 'review']);
        return view('orders.show', compact('order'));
    }

    // Customer: cancel order
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) abort(403);
        if (!in_array($order->status, ['pending'])) {
            return back()->withErrors(['status' => 'Chỉ có thể hủy đơn hàng đang chờ xác nhận.']);
        }

        $order->update(['status' => 'cancelled']);
        return back()->with('success', 'Đã hủy đơn hàng!');
    }

    // Admin: list all orders
    public function adminIndex(Request $request)
    {
        $this->authorize('admin-action');

        $query = Order::with(['user', 'restaurant']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($search = $request->get('search')) {
            $query->where('order_number', 'like', "%{$search}%");
        }

        $orders = $query->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    // Admin: update order status
    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('admin-action');

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,delivering,delivered,cancelled',
        ]);

        $order->update($validated);
        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng!');
    }
}
