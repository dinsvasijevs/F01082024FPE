<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Storage;


class FileUploadController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        // Get the uploaded file
        $file = $request->file('document');

        // Create an S3 client instance
        $s3 = new S3Client([
            'key' => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION'),
        ]);

        // Upload the file to S3
        $result = $s3->putObject([
            'Bucket' => env('AWS_BUCKET'),
            'Key' => $file->getClientOriginalName(),
            'Body' => fopen($file, 'r'),
        ]);

        // Store the uploaded file in the database (optional)
        // ...

        return response()->json(['message' => 'File uploaded successfully!']);
    }
}
