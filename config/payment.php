<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Thông tin tài khoản ngân hàng nhận thanh toán VietQR
    |--------------------------------------------------------------------------
    | Danh sách bank_id: https://api.vietqr.io/v2/banks
    | Ví dụ: MB, VCB, TCB, ACB, VPB, BIDV, VTB, TPB, MSB...
    */

    'bank_id'        => env('PAYMENT_BANK_ID', 'MB'),
    'bank_name'      => env('PAYMENT_BANK_NAME', 'MB Bank'),
    'account_number' => env('PAYMENT_ACCOUNT_NUMBER', '0123456789'),
    'account_name'   => env('PAYMENT_ACCOUNT_NAME', 'RESDELI'),

    // MoMo
    'momo_phone' => env('PAYMENT_MOMO_PHONE', '0123456789'),
    'momo_name'  => env('PAYMENT_MOMO_NAME', 'RESDELI'),

    // ZaloPay
    'zalopay_phone' => env('PAYMENT_ZALOPAY_PHONE', '0123456789'),
    'zalopay_name'  => env('PAYMENT_ZALOPAY_NAME', 'RESDELI'),
];
