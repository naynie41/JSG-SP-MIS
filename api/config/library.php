<?php

declare(strict_types=1);

/**
 * The public resource library (PRD §7.14).
 *
 * Everything the editorial side of this feature needs to change without a code
 * change lives here. The categories in particular are a fixed vocabulary rather
 * than a database table: a lookup table would buy a CRUD screen, an empty-category
 * state and a rule for deleting a category that still has resources, none of which
 * anyone asked for.
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Categories
    |--------------------------------------------------------------------------
    | key => label. The key is stored on the row; the label is what the public
    | page and the admin filter show. Adding one needs a deploy, which is the
    | accepted trade (decision 2026-09-20).
    |
    | Removing one is the case to be careful with: existing rows keep the old key
    | and would render with no label, so retire a category by leaving it here and
    | archiving its items rather than by deleting the line.
    */
    'categories' => [
        'policy' => 'Policies',
        'guideline' => 'Guidelines',
        'tool' => 'Tools',
        'report' => 'Reports',
        'template' => 'Templates',
    ],

    /*
    |--------------------------------------------------------------------------
    | Uploads
    |--------------------------------------------------------------------------
    | `max_file_kb` matches the dev nginx client_max_body_size (25M) so a file that
    | uploads locally also uploads in production. Raising it means raising nginx in
    | BOTH docker/nginx/default.conf and NGINX_CLIENT_MAX_BODY_SIZE, or the request
    | is rejected by the proxy before PHP ever sees it.
    */
    'max_file_kb' => (int) env('LIBRARY_MAX_FILE_KB', 25 * 1024),
    'max_thumbnail_kb' => (int) env('LIBRARY_MAX_THUMBNAIL_KB', 2 * 1024),

    /*
    | Extension allowlist, checked ALONGSIDE the MIME type rather than instead of
    | it. A browser-supplied MIME can be spoofed and an extension can mislead; the
    | pair is checked so both have to agree.
    |
    | Note what is absent: html, htm, svg and xml. Those render as documents, and
    | the download endpoint is unauthenticated — serving one from the state's own
    | origin is a stored-XSS primitive, even with nosniff and an attachment
    | disposition. Documents only.
    */
    'allowed_extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'csv', 'txt'],

    'allowed_mimes' => [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'text/csv',
        'text/plain',
    ],

    'allowed_thumbnail_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
    'allowed_thumbnail_mimes' => ['image/jpeg', 'image/png', 'image/webp'],

    /*
    |--------------------------------------------------------------------------
    | Storage
    |--------------------------------------------------------------------------
    | The PRIVATE disk, always. storage/app/public is not a persisted volume in
    | production (docker-compose.prod.yml mounts only storage/app/private), so a
    | file written to the public disk disappears on the next redeploy while its
    | database row survives — a broken download with no error anywhere.
    |
    | Files are therefore streamed by LibraryDownloadController, which is also what
    | makes withdrawal real: archiving an item stops the download immediately,
    | whereas a public-disk URL stays fetchable by anyone who noted it down.
    */
    'disk' => 'local',
    'file_directory' => 'library/files',
    'thumbnail_directory' => 'library/thumbnails',

];
