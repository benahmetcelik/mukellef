<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Payment Try Status
    |--------------------------------------------------------------------------
    |
    | Başarısız olan ödemeleri doğrudan silmek için true olarak ayarlanmalıdır
    |
    */
    'delete_failed_payments' => env('DELETE_FAILED_PAYMENTS', false),

    /*
    |--------------------------------------------------------------------------
    | System Mode (default/custom)
    |--------------------------------------------------------------------------
    |
    | Bu alana default girilirse dökümanda belirtilen şekilde çalışır
    | Eğer custom değeri girilirse sahip olmasını gerektiğini düşündüğüm
    | minimum gereksinimlere sahip olur ve endpointler değişecektir.
    |
    */
    'system_mode' => env('SYSTEM_MODE', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Subscribe After Direct Pay
    |--------------------------------------------------------------------------
    |
    | Bu alan true olarak ayarlandığında abonelikten hemen sonra ödemeyi alacaktır
    |
    */
    'subscribe_after_direct_pay' => env('SUBSCRIBE_AFTER_DIRECT_PAY', false),

    /*
    |--------------------------------------------------------------------------
    | Payment Status
    |--------------------------------------------------------------------------
    |
    | Bu alan true girildiğinde yapılan ödemeler varsayılan olarak başarılı sayılır
    |
    */
    'payment_status' => env('PAYMENT_STATUS', false),

];
