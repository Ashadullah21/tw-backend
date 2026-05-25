<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\StreamedResponse;

trait CanExportCsv
{
    /**
     * Generate a memory-efficient streamed CSV download response.
     *
     * @param string $filename
     * @param array $headers
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $query
     * @param callable $rowCallback
     * @return StreamedResponse
     */
    protected function streamCsvExport(string $filename, array $headers, $query, callable $rowCallback): StreamedResponse
    {
        return new StreamedResponse(function () use ($headers, $query, $rowCallback) {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM to ensure proper character encoding (Excel support)
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write column headers
            fputcsv($handle, $headers);
            
            // Chunk query to avoid loading too many records into memory
            $query->chunk(200, function ($rows) use ($handle, $rowCallback) {
                foreach ($rows as $row) {
                    fputcsv($handle, $rowCallback($row));
                }
            });
            
            fclose($handle);
        }, 200, [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
    }
}
