<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Convert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iso2mkv:convert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $filesInSourceDir = (Storage::allFIles("arm"));
        $toBeConvertedIsosPaths = Arr::where($filesInSourceDir, function ($value, $key) {
            return str_ends_with($value, ".iso");
        });
        foreach ($toBeConvertedIsosPaths as $isoPath) {
            $tmpSourceFilename = "tmpSource" . Str::uuid()->toString();
            $tmpSourceFilePath = "tmp/" . $tmpSourceFilename;
            Storage::move($isoPath, 'tmp/' . $tmpSourceFilePath);
            $absoluteSourcePathIso = Storage::path($tmpSourceFilePath);
            $folderName = "mkvs/" . explode("/", $isoPath)[1];
            Storage::makeDirectory($folderName);
            $absPathForTargetDir = Storage::path($folderName);
            dd(exec("makemkvcon mkv iso:" . $absoluteSourcePathIso . " all ".$absPathForTargetDir));

        }
        return 0;
    }
}
