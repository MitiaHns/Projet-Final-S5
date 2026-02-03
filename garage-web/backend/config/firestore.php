<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firestore configuration
    |--------------------------------------------------------------------------
    |
    | This project uses Firestore (Google Cloud) as the main database for the
    | mobile/client domain (users, cars, repairs, payments, notifications...).
    |
    | Provide a service account JSON file and mount it in Docker.
    |
    */

    'project_id' => env('FIRESTORE_PROJECT_ID'),

    // Absolute path inside the container, e.g. /var/www/html/storage/app/firebase/service-account.json
    'credentials' => env('GOOGLE_APPLICATION_CREDENTIALS'),
];
