<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuItem;

class CartController extends Controller
{
    private function getCart(): array
    {
        return session('cart', []);
    }

    private function saveCart(array $cart): void
    {
        session(['cart' => $cart]);
    }

    public function index()
    {
        $cart = $this->getCart();
        $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'quantity'     => 'integer|min:1',
        ]);

        $item = MenuItem::findOrFail($request->menu_item_id);
        $cart = $this->getCart();
        $id   = (string) $item->id;
        $qty  = (int) $request->get('quantity', 1);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
        } else {
            $cart[$id] = [
                'id'       => $item->id,
                'name'     => $item->name,
                'price'    => $item->effective_price,
                'quantity' => $qty,
                'image'    => $item->image_url,
            ];
        }

        $this->saveCart($cart);
        $count = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng!',
            'count'   => $count,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'menu_item_id' => 'required',
            'quantity'     => 'required|integer|min:0',
        ]);

        $cart = $this->getCart();
        $id   = (string) $request->menu_item_id;

        if ($request->quantity <= 0) {
            unset($cart[$id]);
        } elseif (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
        }

        $this->saveCart($cart);
        $subtotal = isset($cart[$id]) ? $cart[$id]['price'] * $cart[$id]['quantity'] : 0;
        $total    = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        $count    = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'success'  => true,
            'subtotal' => $subtotal,
            'total'    => $total,
            'count'    => $count,
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate(['menu_item_id' => 'required']);

        $cart = $this->getCart();
        unset($cart[(string) $request->menu_item_id]);
        $this->saveCart($cart);

        $total = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
        $count = array_sum(array_column($cart, 'quantity'));

        return response()->json(['success' => true, 'total' => $total, 'count' => $count]);
    }

    public function clear()
    {
        session()->forget('cart');
        return response()->json(['success' => true, 'count' => 0]);
    }
}
