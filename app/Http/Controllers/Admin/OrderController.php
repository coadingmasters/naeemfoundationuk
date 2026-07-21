<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public const STATUSES = ['pending', 'processing', 'completed', 'cancelled'];

    public function index()
    {
        $orders = Order::latest()->paginate(15);

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'revenue' => (float) Order::whereIn('status', ['pending', 'processing', 'completed'])->sum('subtotal'),
        ];

        return view('admin.orders.index', [
            'orders' => $orders,
            'stats' => $stats,
            'statuses' => self::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(self::STATUSES)],
        ]);

        $order->update(['status' => $data['status']]);

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'orders-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Reference', 'Name', 'Email', 'Phone', 'Address', 'Items', 'Total', 'Status', 'Placed']);

            Order::latest()->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $o) {
                    $items = collect($o->items ?? [])
                        ->map(fn ($i) => ($i['qty'] ?? 1).'x '.($i['name'] ?? ''))
                        ->implode('; ');

                    fputcsv($out, [
                        $o->reference,
                        $o->name,
                        $o->email,
                        $o->phone,
                        $o->address,
                        $items,
                        $o->subtotal,
                        $o->status,
                        $o->created_at?->format('Y-m-d H:i'),
                    ]);
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
