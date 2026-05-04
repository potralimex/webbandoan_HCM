<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class OrdersExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    protected ?string $fromDate;
    protected ?string $toDate;

    public function __construct(?string $fromDate = null, ?string $toDate = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate   = $toDate;
    }

    public function query()
    {
        $query = Order::with(['user', 'restaurant'])
            ->orderByDesc('created_at');

        if ($this->fromDate) {
            $query->whereDate('created_at', '>=', $this->fromDate);
        }
        if ($this->toDate) {
            $query->whereDate('created_at', '<=', $this->toDate);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Mã đơn hàng',
            'Tên khách hàng',
            'Email',
            'Nhà hàng',
            'Tổng tiền (đ)',
            'Phí giao hàng (đ)',
            'Thanh toán',
            'Trạng thái',
            'Ngày tạo',
        ];
    }

    public function map($order): array
    {
        $statusMap = [
            'pending'    => 'Chờ xác nhận',
            'confirmed'  => 'Đã xác nhận',
            'preparing'  => 'Đang chuẩn bị',
            'delivering' => 'Đang giao',
            'delivered'  => 'Đã giao',
            'cancelled'  => 'Đã hủy',
        ];

        $paymentMap = [
            'cod'           => 'Tiền mặt (COD)',
            'bank_transfer' => 'Chuyển khoản',
            'momo'          => 'MoMo',
            'zalopay'       => 'ZaloPay',
            'cash'          => 'Tiền mặt',
            'card'          => 'Thẻ',
        ];

        return [
            $order->id,
            $order->order_number,
            $order->user?->name ?? 'N/A',
            $order->user?->email ?? 'N/A',
            $order->restaurant?->name ?? 'N/A',
            number_format($order->total, 0, ',', '.'),
            number_format($order->delivery_fee, 0, ',', '.'),
            $paymentMap[$order->payment_method] ?? $order->payment_method,
            $statusMap[$order->status] ?? $order->status,
            $order->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row: bold, background màu cam ResDeli, chữ trắng
            1 => [
                'font' => [
                    'bold'  => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size'  => 11,
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFF6B35'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['argb' => 'FFDDDDDD'],
                    ],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Danh sách đơn hàng';
    }
}
