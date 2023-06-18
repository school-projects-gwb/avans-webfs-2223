<?php

namespace App\Console\Commands;

use App\Mail\DailyResumeMail;
use App\Models\OrderLine;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

        Mail::to(env('MAIL_ADMIN_ADDRESS'))->send(new DailyResumeMail($this->buildCsv($dishCounts, $optionCounts)));
    }

    private function buildCsv($dishCounts, $optionCounts)
    {
        $tempFilePath = storage_path('app/counts.xlsx');
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setTitle('Daily Resume');

        $worksheet->setCellValue('A1', 'Product');
        $worksheet->setCellValue('B1', 'Hoeveelheid');
        $worksheet->setCellValue('C1', 'Prijs');

        $row = 2;
        $totalPrice = 0;

        foreach ($dishCounts as $dishCount) {
            $price = $dishCount->dish->price * $dishCount->count;
            $worksheet->setCellValue('A' . $row, $dishCount->dish->name);
            $worksheet->setCellValue('B' . $row, $dishCount->count);
            $worksheet->setCellValue('C' . $row, $dishCount->dish->price * $dishCount->count);
            $row++;
            $totalPrice += $price;
        }

        foreach ($optionCounts as $optionCount) {
            $price = $optionCount->option->price * $optionCount->count;
            $worksheet->setCellValue('A' . $row, $optionCount->option->name);
            $worksheet->setCellValue('B' . $row, $optionCount->count);
            $worksheet->setCellValue('C' . $row, $optionCount->option->price * $optionCount->count);
            $row++;
            $totalPrice += $price;
        }

        $worksheet->setCellValue('A' . $row += 1, "Totale Omzet: €" . $totalPrice . ",-");
        $worksheet->setCellValue('A' . $row += 1, "Omzet overzicht van: " . Carbon::yesterday()->format('d-m-Y'));

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        return $tempFilePath;
    }

}
