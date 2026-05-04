<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\MenuItem;
use App\Mail\OrderCreatedMail;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }
        $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        return view('checkout.index', compact('cart', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string|max:255',
            'phone'            => 'required|regex:/^[0-9]{10,11}$/',
            'payment_method'   => 'required|in:cod,bank_transfer,momo,zalopay',
            'notes'            => 'nullable|string|max:500',
        ], [
            'delivery_address.required' => 'Vui lòng nhập địa chỉ giao hàng.',
            'phone.required'            => 'Vui lòng nhập số điện thoại.',
            'phone.regex'               => 'Số điện thoại không hợp lệ.',
            'payment_method.required'   => 'Vui lòng chọn phương thức thanh toán.',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $subtotal = 0;
        $orderItems = [];

        foreach ($cart as $entry) {
            $menuItem = MenuItem::find($entry['id']);
            if (!$menuItem) continue;
            $price       = $menuItem->effective_price;
            $qty         = (int) $entry['quantity'];
            $sub         = $price * $qty;
            $subtotal   += $sub;
            $orderItems[] = [
                'menu_item_id' => $menuItem->id,
                'item_name'    => $menuItem->name,
                'item_price'   => $price,
                'quantity'     => $qty,
                'subtotal'     => $sub,
            ];
        }

        $firstItem    = MenuItem::find($cart[array_key_first($cart)]['id']);
        $restaurantId = $firstItem?->restaurant_id;

        $order = Order::create([
            'user_id'          => Auth::id(),
            'restaurant_id'    => $restaurantId,
            'order_number'     => Order::generateOrderNumber(),
            'status'           => 'pending',
            'subtotal'         => $subtotal,
            'delivery_fee'     => 0,
            'total'            => $subtotal,
            'delivery_address' => $request->delivery_address,
            'phone'            => $request->phone,
            'notes'            => $request->notes,
            'payment_method'   => $request->payment_method,
            'payment_status'   => 'pending',
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        session()->forget('cart');

        // Gửi email thông báo đến admin
        $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL'));
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new OrderCreatedMail($order));
            } catch (\Exception $e) {
                // Không để lỗi mail ảnh hưởng đến luồng đặt hàng
                \Log::error('OrderCreatedMail failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Đặt hàng thành công! Mã đơn: ' . $order->order_number);
    }
}
