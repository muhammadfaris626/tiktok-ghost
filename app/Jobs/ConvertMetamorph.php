<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertMetamorph implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $inputFile = $this->file; // Sesuaikan path
        $outputFile = "media/small_steve.mp4";

        dump("Input File Path : " . $inputFile);


        // \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk('public')
        //     ->open($inputFile)
        //     ->export()
        //     ->toDisk('public')
        //     ->inFormat((new \FFMpeg\Format\Video\X264)
        //         ->setKiloBitrate(500) // Atur bitrate ke 500kbps
        //         ->setAudioKiloBitrate(128) // Bitrate audio ke 128kbps
        //         ->setAdditionalParameters(['-crf', '28', '-preset', 'slow']) // CRF lebih tinggi = lebih kecil ukuran file
        //     )
        //     ->resize(640, 480)
        //     ->save($outputFile);
    }
}
