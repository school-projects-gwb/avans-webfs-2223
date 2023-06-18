<?php

namespace App\Console\Commands;

use App\Http\Controllers\SalesController;
use App\Http\Requests\SalesGetRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DailyResumeCron extends Command
{
    protected $signature = 'daily-resume:cron';

    protected $description = 'Command description';

    public function handle()
    {
        $yesterday = Carbon::today();

        $startDate = $yesterday->copy()->startOfDay();
        $endDate = $yesterday->copy()->endOfDay();

        $request = new SalesGetRequest();
        $request->merge([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $salesData = SalesController::getData($request);
        $this->buildExcel($salesData, $yesterday);
    }

    private function buildExcel($salesData, $date)
    {
        $tempFilePath = storage_path('app/sales_exports' . $date . '_sales.xlsx');
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setTitle('Daily Resume');

        $worksheet->setCellValue('A1', 'Product');
        $worksheet->setCellValue('B1', 'Hoeveelheid');
        $worksheet->setCellValue('C1', 'Prijs in euro\'s');

        $row = 2;

        foreach ($salesData['orderLines'] as $orderLine) {
            $worksheet->setCellValue('A' . $row, $orderLine['dish_name']);
            $worksheet->setCellValue('B' . $row, $orderLine['amount']);
            $worksheet->setCellValue('C' . $row, round($orderLine['combined_price'], 2));
            $row++;
        }

        $worksheet->setCellValue('A' . $row += 1, "Totale Omzet: €" . $salesData['total_gross'] . ",-");
        $worksheet->setCellValue('A' . $row += 1, "BTW: €" . $salesData['total_vat'] . ",-");
        $worksheet->setCellValue('A' . $row += 1, "Excl. BTW: €" . $salesData['total_net'] . ",-");
        $worksheet->setCellValue('A' . $row += 1, "Omzet overzicht van: " . Carbon::yesterday()->format('d-m-Y'));

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFilePath);

        return $tempFilePath;
    }
}
