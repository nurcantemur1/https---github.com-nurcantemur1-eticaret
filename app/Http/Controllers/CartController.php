<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContentFormRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{
    public function index()
    {
        $cartItem = session('cart', []);

        $totalPrice = 0;
        foreach ($cartItem as $cart) {
            $totalPrice += $cart['price'] * $cart['qty'];
        }
        return view('frontend.pages.cart', compact('cartItem', 'totalPrice'));
    }

    public function remove(Request $request)
    {
        $productId = $request->productId;
        $cartItem = session('cart', []);

        if (array_key_exists($productId, $cartItem)) {
            unset($cartItem[$productId]);
        }

        session(['cart' => $cartItem]);

        return back()->withSuccess('Başarıyla Sepetten Kaldırıldı!');
    }

    public function add(Request $request)
    {

        $productId = $request->productId;
      //  return $productId;
        $quantity = $request->quantity ?? 1;
        $size = $request->size;

        $product = Product::find($request->productId);
        if (!$product) {
            return back()->withError('Ürün Bulunamadı!');
        }

        $cartItem = session('cart', []);

        if (array_key_exists($productId, $cartItem)) {
            $cartItem[$productId]['qty'] += $quantity;
        } else {
            $cartItem[$productId] = [
                'image' => $product->image,
                'name' => $product->productName,
                'price' => $product->price,
                'qty' => $quantity,
                'size' => $size,
            ];
        }


        session(['cart' => $cartItem]);

       return back()->withSuccess('Ürün Sepete Eklendi!');
    }
}
