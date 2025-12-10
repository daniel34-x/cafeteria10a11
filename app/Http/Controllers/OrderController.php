<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class OrderController extends Controller
{
    public function checkout() {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu.index')->withErrors(['cart'=>'Tu carrito está vacío']);
        }
        $total = collect($cart)->sum(fn($i) => $i['quantity'] * $i['unit_price']);
        return view('checkout.index', compact('cart','total'));
    }

    public function place(Request $request) {
        $request->validate(['payment_method'=>'required|in:card,cash']);
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu.index')->withErrors(['cart'=>'Tu carrito está vacío']);
        }
        $total = collect($cart)->sum(fn($i) => $i['quantity'] * $i['unit_price']);

        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total' => $total,
            'turn_number' => 'A'.random_int(10,99), // simple
        ]);

        foreach ($cart as $c) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $c['product_id'],
                'quantity' => $c['quantity'],
                'unit_price' => $c['unit_price'],
                'subtotal' => $c['quantity'] * $c['unit_price'],
            ]);
        }

        session()->forget('cart');

        // Si es online, redirigir al pago; si es efectivo, marcar para caja
        return redirect()->route('ticket.show', $order)->with('success', 'Pedido generado');
    }
      use AuthorizesRequests;


    public function ticket(Order $order) {
        $this->authorize('view', $order); // opcional: Policy
        $order->load(['items.product','user','payment']);
        return view('ticket.show', compact('order'));
    }

}
