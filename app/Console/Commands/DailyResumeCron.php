<?php

namespace App\Console\Commands;

use App\Mail\DailyResumeMail;
use App\Models\OrderLine;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DailyResumeCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily-resume:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $yesterday = Carbon::yesterday()->toDateString();
        $dishCounts = OrderLine::select('dish_id')
            ->whereDate('created_at', $yesterday)
            ->selectRaw('COUNT(*) as count')
            ->groupBy('dish_id')
            ->with('dish')
            ->get();

        $optionCounts = OrderLine::select('option_id')
            ->whereNotNull('option_id')
            ->whereDate('created_at', $yesterday)
            ->whereHas('option', function ($query) {
                $query->whereNotNull('price');
            })
            ->selectRaw('COUNT(*) as count')
            ->groupBy('option_id')
            ->with('option')
            ->get();

        $pdf = new Dompdf();
        $html = view('pdf.daily-resume', compact('dishCounts', 'optionCounts'));
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        Mail::to(env('MAIL_ADMIN_ADDRESS'))->send(new DailyResumeMail($pdf->output()));
    }
}
