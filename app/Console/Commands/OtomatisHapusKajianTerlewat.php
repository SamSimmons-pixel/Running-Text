<?php

namespace App\Console\Commands;

use App\Models\running_text_data;
use Illuminate\Console\Command;

class OtomatisHapusKajianTerlewat extends Command
{
    protected $signature = 'kajian:hapus-terlewat';
    protected $description = 'Hapus kajian data yang sudah terlewat 2 hari';

    public function handle()
    {
        $potongoff = now()->subDays(1);

        $terhapus = running_text_data::where('Tanggal', '<', $potongoff)->delete();

        $this->info("Data kajian terhapus: {$terhapus}");
    }
}
