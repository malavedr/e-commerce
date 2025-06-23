@component('mail::message')
# ¡Gracias por tu compra, {{ $order->buyer->name }}!

Tu orden #{{ $order->id }} ha sido creada exitosamente.

## 🛒 Detalle de productos:

@component('mail::table')
| Producto            | Cant. | P. Unit. | Subtotal     |
|---------------------|----------|------------------|--------------|
@foreach ($order->items as $item)
| {{ $item->product->name }} | {{ $item->quantity }} | ${{ $item->unit_price }} | ${{ $item->total_price }} |
@endforeach
@endcomponent

**Total:** ${{ $order->total }}

Gracias por confiar en nosotros.  
El equipo de {{ config('app.name') }}
@endcomponent