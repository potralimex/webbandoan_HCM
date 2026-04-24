public function dashboardApi(\Illuminate\Http\Request $request)
{
    $query = \App\Models\Order::with(['user','restaurant']);

    // ===== FILTER =====
    if ($request->from && $request->to) {
        $query->whereBetween('created_at', [$request->from, $request->to]);
    }

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->restaurant) {
        $query->whereHas('restaurant', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->restaurant . '%');
        });
    }

    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('order_number', 'like', '%' . $request->search . '%')
              ->orWhereHas('user', function ($u) use ($request) {
                  $u->where('name', 'like', '%' . $request->search . '%');
              });
        });
    }

    // ===== SORT =====
    $sort = $request->sort ?? 'created_at';
    $direction = $request->direction ?? 'desc';
    $query->orderBy($sort, $direction);

    // ===== PAGINATION =====
    $perPage = $request->per_page ?? 10;
    $orders = $query->paginate($perPage);

    // ===== FORMAT DATA =====
    $data = $orders->getCollection()->map(function ($o) {
        return [
            'order_number' => $o->order_number,
            'customer_name' => optional($o->user)->name ?? 'Khách',
            'restaurant_name' => optional($o->restaurant)->name ?? '',
            'status' => $o->status,
            'status_badge' => $o->status_badge,
            'total' => $o->total,
            'created_at' => $o->created_at->format('Y-m-d'),
        ];
    });

    // ===== STATS =====
    $statsQuery = clone $query;

    return response()->json([
        'stats' => [
            'total_orders' => $statsQuery->count(),
            'total_revenue' => $statsQuery->sum('total'),
            'pending' => (clone $query)->where('status','pending')->count(),
            'delivered' => (clone $query)->where('status','delivered')->count(),
        ],

        'orders' => $data,

        'pagination' => [
            'current_page' => $orders->currentPage(),
            'last_page' => $orders->lastPage(),
            'per_page' => $orders->perPage(),
        ]
    ]);
}