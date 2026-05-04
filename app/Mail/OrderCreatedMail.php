<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        // Eager load tất cả quan hệ cần thiết cho email
        $this->order = $order->loadMissing(['user', 'restaurant', 'items']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[ResDeli] Đơn hàng mới #' . $this->order->order_number
                   . ' từ ' . ($this->order->user?->name ?? 'Khách hàng'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
