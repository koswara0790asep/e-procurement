<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportProductsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $userId;

    public function __construct($filePath, $userId)
    {
        $this->filePath = $filePath;
        $this->userId = $userId;
    }

    public function handle(): void
    {
        $path = storage_path("app/{$this->filePath}");

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle, 1000, ',');

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            $data = array_combine($header, $row);

            $validator = Validator::make($data, [
                'vendor_id' => 'required|exists:vendors,id',
                'name'      => 'required|string',
                'price'     => 'required|numeric',
                'stock'     => 'required|integer',
            ]);

            if ($validator->fails()) continue;

            $vendor = Vendor::where('id', $data['vendor_id'])
                            ->where('user_id', $this->userId)
                            ->first();

            if (!$vendor) continue;

            $vendor->products()->create([
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'price'       => $data['price'],
                'stock'       => $data['stock'],
            ]);
        }

        fclose($handle);
    }
}