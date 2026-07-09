<?php
return [
    // Thứ tự ưu tiên khi render <picture>
    'formats' => ['webp', 'jpg'], // thêm 'avif' nếu server hỗ trợ

    // Chất lượng
    'quality' => [
        'jpg'  => 100,
        'webp' => 100,
        // 'avif' => 50,
    ],
];
