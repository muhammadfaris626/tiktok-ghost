<?php

namespace App\Jobs;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class ProcessMatemorphJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;
    protected $file, $title, $count;
    public function __construct($file, $title, $count)
    {
        $this->file = $file;
        $this->title = $title;
        $this->count = $count;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // File input
        $inputFile = $this->file;

        // Ambil ukuran file asli (dalam MB)
        $fileSizeMB = Storage::disk('public')->size($inputFile) / (1024 * 1024);

        // Ambil durasi video dengan ffprobe
        $ffprobe = \FFMpeg\FFProbe::create();
        $duration = $ffprobe->format(Storage::disk('public')->path($inputFile))->get('duration');

        // Looping 5 kali untuk membuat versi berbeda
        for ($i = 1; $i <= $this->count; $i++) {
            // Tentukan nama file output
            $outputFile = "converted/converted_" . str_replace(' ', '_', strtolower($this->title)) . "_" . $i . ".mp4";


            // Hitung bitrate otomatis dengan variasi
            $maxSizeMB = $fileSizeMB + rand(2, 5); // Ukuran random (2MB - 5MB lebih besar dari asli)
            $targetBitrate = ($maxSizeMB * 1024 * 1024 * 8) / $duration;
            $finalBitrate = max($targetBitrate, rand(250, 800)); // Pastikan bitrate tidak terlalu rendah

            // Variasi parameter tambahan agar tidak ada video yang sama
            $additionalParams = [
                ['-crf', '22', '-preset', 'fast', '-tune', 'animation', '-profile:v', 'main', '-level', '4.0'],
                ['-crf', '25', '-preset', 'medium', '-tune', 'film', '-profile:v', 'high', '-level', '4.1'],
                ['-crf', '28', '-preset', 'slow', '-tune', 'grain', '-profile:v', 'baseline', '-level', '3.1'],
                ['-crf', '30', '-preset', 'ultrafast', '-tune', 'zerolatency', '-profile:v', 'high', '-level', '4.2'],
                ['-crf', '23', '-preset', 'veryslow', '-tune', 'psnr', '-profile:v', 'main', '-level', '5.1'],
            ];

            // Variasi efek video & audio
            $videoFilters = [
                '-vf', 'hflip,eq=contrast=1.1:brightness=0.03', // Flip horizontal & kontras naik
                '-vf', 'vflip,eq=contrast=0.9:brightness=-0.02', // Flip vertikal & kontras turun
                '-vf', 'scale=-2:720', // Resize ke 720p dengan aspect ratio tetap
                '-vf', 'scale=-2:480, hue=h=10', // Resize ke 480p & ubah hue
                '-vf', 'scale=-2:1080, unsharp=5:5:0.8:5:5:0.8', // Resize ke 1080p & tambah sharpening
            ];

            $audioFilters = [
                '-af', 'atempo=1.02, volume=1.05', // Percepat audio sedikit & tambah volume
                '-af', 'atempo=0.98, volume=0.95', // Perlambat audio sedikit & kurangi volume
                '-af', 'atempo=1.05, bass=g=3', // Percepat audio sedikit & tambah bass
                '-af', 'atempo=0.97, treble=g=5', // Perlambat audio & tambah treble
                '-af', 'atempo=1.01, volume=1.02, dynaudnorm=f=150', // Normalisasi audio
            ];

            // Variasi frame rate dan keyframe interval
            $frameRates = ['24', '25', '29.97', '30', '50'];
            $keyFrames = ['25', '30', '35', '40', '50'];

            // Eksekusi konversi dengan parameter berbeda-beda
            \ProtoneMedia\LaravelFFMpeg\Support\FFMpeg::fromDisk('public')
                ->open($inputFile)
                ->export()
                ->toDisk('public')
                ->inFormat((new \FFMpeg\Format\Video\X264)
                    ->setKiloBitrate($finalBitrate / 1000)
                    ->setAudioKiloBitrate(48)
                    ->setAdditionalParameters(array_merge(
                        $additionalParams[$i - 1], // Pilih preset sesuai iterasi
                        [
                            '-metadata', "title=Generated Video {$i}",
                            '-metadata', "author=Edited by AI {$i}",
                            '-metadata', "comment=This is version {$i} of the video",
                            '-metadata', "copyright=2025 Custom Edit",
                            '-map_metadata', '-1', // Hapus metadata asli
                            '-g', $keyFrames[$i - 1], // Variasi keyframe
                            '-r', $frameRates[$i - 1], // Variasi frame rate
                            '-pix_fmt', 'yuv420p', // Ganti format pixel agar encoding berubah
                        ]
                    ))
                )
                // ->addFilter($videoFilters[$i - 1]) // Pilih filter video
                // ->addFilter($audioFilters[$i - 1]) // Pilih filter audio
                ->save($outputFile);
        }
    }
}
