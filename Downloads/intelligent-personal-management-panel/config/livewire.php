<?php

return [
    'class_namespace' => 'App\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => 'layouts.app',
    'lazy_placeholder' => null,
    'temporary_file_upload' => [
        'disk' => null,
        'rules' => null,
        'directory' => 'livewire-tmp',
        'max_upload_time' => 12,
    ],
    'render_on_skip' => false,
    'legacy_morphmap' => false,
    'morphmap' => null,
];
