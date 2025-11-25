<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StorePurchaseOrderRequest;
use App\Models\Order\PurchaseOrder;
use App\Models\Order\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;

// Para el PDF
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Inventory\Provider;
use App\Models\User;

class PurchaseOrderController extends Controller
{
    /**
     * Crear orden de compra
     */
    public function store(StorePurchaseOrderRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {

                // Calcular subtotal
                $subtotal = collect($request->items)->sum(function ($item) {
                    return $item['quantity'] * $item['price'];
                });

                $vat = $subtotal * 0.16; // IVA
                $total = $subtotal + $vat;

                // Crear la orden
                $order = PurchaseOrder::create([
                    'provider_id' => $request->provider_id,
                    'area_requester' => $request->area_requester,
                    'delivery_date' => $request->delivery_date,
                    'subtotal' => $subtotal,
                    'vat' => $vat,
                    'total' => $total,
                    'user_id' => auth()->id(),
                ]);

                // Crear los items
                foreach ($request->items as $item) {
                    PurchaseOrderItem::create([
                        'purchase_order_id' => $order->id,
                        'inventory_material_id' => $item['inventory_material_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'total' => $item['price'] * $item['quantity'],
                    ]);
                }

                return response()->json([
                    'message' => 'Orden de compra creada correctamente',
                    'order' => $order->load('items.material.unit'),
                ], 201);
            });
        } catch (\Exception $e) {
            \Log::error('Error creating purchase order: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear la orden de compra.'], 500);
        }
    }

    /**
     * 📄 GENERAR PDF DE LA ORDEN DE COMPRA
     */
    public function pdf($id)
{
    $order = PurchaseOrder::with([
        'items.material.unit',
        'provider'
    ])->findOrFail($id);

    $provider = $order->provider;
    $buyer = User::find($order->user_id);

    $data = [
        'order' => $order,
        'provider' => $provider,
        'buyer' => $buyer,
    ];

$pdf = Pdf::loadView('pdf.purchase_orders', $data)
        ->setPaper('letter');

    // Para descargar el PDF
    return $pdf->download("orden_compra_{$order->id}.pdf");
    
    // Si prefieres abrir en el navegador:
    // return $pdf->stream("orden_compra_{$order->id}.pdf");
}
}