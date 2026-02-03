<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase configuration
    |--------------------------------------------------------------------------
    |
    | We verify Firebase Authentication ID tokens on the API.
    | The Admin SDK credentials are the same service-account JSON used for
    | Firestore.
    |
    */

    'project_id' => env('FIREBASE_PROJECT_ID', env('FIRESTORE_PROJECT_ID')),

    // Absolute path inside the container.
    'credentials' => env('GOOGLE_APPLICATION_CREDENTIALS'),
];
