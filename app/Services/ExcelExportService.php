<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

/**
 * Excel Export Service (WEBSITE-BASED)
 * Generates formatted Excel files from data arrays
 */
class ExcelExportService
{
    /**
     * Export UTM campaign data to Excel (WEBSITE-BASED)
     */
    public function exportUTMCampaigns($campaigns, $websiteName = null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set title
        $title = 'UTM Attribution Analytics';
        if ($websiteName) {
            $title .= ' - ' . $websiteName;
        }
        $sheet->setTitle('UTM Campaigns');
        
        // Add header row with styling
        $headers = [
            'Campaign',
            'Source',
            'Medium',
            'Sessions',
            'Visitors',
            'Conversions',
            'Revenue',
            'Conversion Rate (%)',
            'Avg Revenue/Session',
            'Cost/Conversion'
        ];
        
        $sheet->fromArray($headers, null, 'A1');
        
        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);
        
        // Add data rows
        $row = 2;
        foreach ($campaigns as $campaign) {
            $sheet->setCellValue('A' . $row, $campaign['campaign']);
            $sheet->setCellValue('B' . $row, $campaign['source']);
            $sheet->setCellValue('C' . $row, $campaign['medium']);
            $sheet->setCellValue('D' . $row, $campaign['sessions']);
            $sheet->setCellValue('E' . $row, $campaign['visitors']);
            $sheet->setCellValue('F' . $row, $campaign['conversions']);
            $sheet->setCellValue('G' . $row, $campaign['revenue']);
            $sheet->setCellValue('H' . $row, $campaign['conversion_rate']);
            $sheet->setCellValue('I' . $row, $campaign['avg_revenue_per_session']);
            $sheet->setCellValue('J' . $row, $campaign['cost_per_conversion']);
            
            // Format currency columns
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('$#,##0.00');
            $sheet->getStyle('I' . $row)->getNumberFormat()->setFormatCode('$#,##0.00');
            $sheet->getStyle('J' . $row)->getNumberFormat()->setFormatCode('$#,##0.00');
            
            // Format percentage column
            $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('0.00');
            
            $row++;
        }
        
        // Add borders to all cells
        $lastRow = $row - 1;
        $sheet->getStyle('A1:J' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);
        
        // Auto-size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Add metadata
        $spreadsheet->getProperties()
            ->setCreator('Charity Platform')
            ->setTitle($title)
            ->setSubject('UTM Campaign Analytics')
            ->setDescription('Website-Based UTM campaign performance report')
            ->setKeywords('utm analytics campaigns website-based');
        
        return $spreadsheet;
    }
    
    /**
     * Export referrer data to Excel (WEBSITE-BASED)
     */
    public function exportReferrers($referrers, $websiteName = null)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set title
        $title = 'Referrer Analytics';
        if ($websiteName) {
            $title .= ' - ' . $websiteName;
        }
        $sheet->setTitle('Referrers');
        
        // Add header row with styling
        $headers = [
            'Referrer URL',
            'Domain',
            'Sessions',
            'Visitors',
            'Conversions',
            'Revenue',
            'Conversion Rate (%)',
            'Avg Revenue/Session'
        ];
        
        $sheet->fromArray($headers, null, 'A1');
        
        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '70AD47']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ];
        
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
        
        // Add data rows
        $row = 2;
        foreach ($referrers as $referrer) {
            $sheet->setCellValue('A' . $row, $referrer['referrer_url']);
            $sheet->setCellValue('B' . $row, $referrer['domain']);
            $sheet->setCellValue('C' . $row, $referrer['sessions']);
            $sheet->setCellValue('D' . $row, $referrer['visitors']);
            $sheet->setCellValue('E' . $row, $referrer['conversions']);
            $sheet->setCellValue('F' . $row, $referrer['revenue']);
            $sheet->setCellValue('G' . $row, $referrer['conversion_rate']);
            $sheet->setCellValue('H' . $row, $referrer['avg_revenue_per_session']);
            
            // Format currency columns
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('$#,##0.00');
            $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('$#,##0.00');
            
            // Format percentage column
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('0.00');
            
            $row++;
        }
        
        // Add borders to all cells
        $lastRow = $row - 1;
        $sheet->getStyle('A1:H' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);
        
        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Add metadata
        $spreadsheet->getProperties()
            ->setCreator('Charity Platform')
            ->setTitle($title)
            ->setSubject('Referrer Analytics')
            ->setDescription('Website-Based referrer performance report')
            ->setKeywords('referrer analytics traffic website-based');
        
        return $spreadsheet;
    }
    
    /**
     * Download Excel file (WEBSITE-BASED)
     */
    public function download(Spreadsheet $spreadsheet, $filename)
    {
        $writer = new Xlsx($spreadsheet);
        
        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Generate and download Excel file directly (WEBSITE-BASED)
     */
    public function generateAndDownload(Spreadsheet $spreadsheet, $filename)
    {
        $writer = new Xlsx($spreadsheet);
        
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $filename . '.xlsx"',
            'Cache-Control' => 'max-age=0',
        ];
        
        return response()->stream(
            function() use ($writer) {
                $writer->save('php://output');
            },
            200,
            $headers
        );
    }
}
