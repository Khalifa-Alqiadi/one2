<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\Image\Image;

class FileController extends Controller
{

    public function view($path = "")
    {
        if ($path == "" || Str::contains($path, ['..', './', '\\'])) {
            return redirect()->route("frontendRoute", ["part1" => Helper::currentLanguage()->code]);
        }

        $disk = config('filesystems.default');
        if (!Storage::disk($disk)->exists($path)) {
            return redirect()->route("frontendRoute", ["part1" => Helper::currentLanguage()->code]);
        }

        $mime = Storage::disk($disk)->mimeType($path);
        $content = Storage::disk($disk)->get($path);

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $isImage = Str::startsWith($mime, 'image/');
        $width = \request()->query('w');
        $height = \request()->query('h');
        $fit = \request()->query('r');

        if ($isImage && ($width || $height)) {
            try {
                if (@class_exists("Imagick")) {
                    if ($width == "") {
                        $width = $height;
                    }
                    if ($height == "") {
                        $height = $width;
                    }
                    $resizedPath = 'cache/'.$width.'x'.$height.'/'.$path;
                    if (!Storage::disk($disk)->exists($resizedPath)) {
                        // Get full temp file path
                        $tempInputPath = Storage::disk($disk)->path($path);
                        $tempOutputPath = Storage::disk($disk)->path($resizedPath);

                        // Ensure directory exists
                        $resizedDir = dirname($tempOutputPath);
                        if (!file_exists($resizedDir)) {
                            mkdir($resizedDir, 0755, true);
                        }
                        // Resize using Spatie
                        if ($fit == "fit") {
                            Image::load($tempInputPath)->fit(Fit::Crop, $width, $height)->save($tempOutputPath);
                        } else {
                            Image::load($tempInputPath)->width($width)->height($height)->save($tempOutputPath);
                        }
                    }
                    $content = Storage::disk($disk)->get($resizedPath);
                }
            } catch (\Exception $e) {

            }
        }

        // custom render for SVG
        if ($extension === 'svg') {
            $stream = Storage::disk($disk)->readStream($path);
            return response()->stream(function () use ($stream) {
                fpassthru($stream);
                fclose($stream);
            }, 200, [
                'Content-Type' => 'image/svg+xml',
                'Content-Disposition' => 'inline; filename="'.basename($path).'"',
            ]);
        }

        return Response::make($content, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="'.basename($path).'"'
        ]);
    }


    public function download($path = "")
    {
        if ($path == "" || Str::contains($path, ['..', './', '\\'])) {
            return redirect()->route("frontendRoute", ["part1" => Helper::currentLanguage()->code]);
        }

        $disk = config('filesystems.default');
        if (!Storage::disk($disk)->exists($path)) {
            return redirect()->route("frontendRoute", ["part1" => Helper::currentLanguage()->code]);
        }

        return Storage::disk($disk)->download($path);
    }
}
