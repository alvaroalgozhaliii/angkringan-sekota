<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class UploadGambar
{
    public const EKSTENSI = ['jpg', 'jpeg', 'png', 'webp'];

    public const UKURAN_MAKSIMAL = 2048;

    public static function aturan(string $field = 'gambar'): array
    {
        return [$field => 'required|image|mimes:jpg,jpeg,png,webp|max:'.self::UKURAN_MAKSIMAL];
    }

    public static function simpan(UploadedFile $file, string $folder): string
    {
        $ekstensi = strtolower($file->getClientOriginalExtension());
        if (! in_array($ekstensi, self::EKSTENSI, true)) {
            $ekstensi = $file->guessExtension() ?: 'jpg';
        }
        if (! in_array($ekstensi, self::EKSTENSI, true)) {
            $ekstensi = 'jpg';
        }
        if ($file->getSize() > self::UKURAN_MAKSIMAL * 1024) {
            throw new \InvalidArgumentException('Ukuran gambar maksimal 2 MB.');
        }

        $nama = Str::random(40).'.'.$ekstensi;

        Storage::disk('public')->putFileAs($folder, $file, $nama, 'public');

        return $folder.'/'.$nama;
    }

    public static function hapus(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http') && ! str_starts_with($path, 'images/')) {
            Storage::disk('public')->delete($path);
        }
    }
}
