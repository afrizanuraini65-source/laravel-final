<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class ExcelService
{
    /**
     * Generate an Excel (.xlsx) file binary stream and return as download response.
     * Gracefully falls back to UTF-8 BOM CSV if ZipArchive is unavailable in the environment.
     */
    public static function downloadXlsx(string $filename, array $headers, array $rows): StreamedResponse
    {
        if (! class_exists('\ZipArchive')) {
            $csvFilename = preg_replace('/\.xlsx$/i', '.csv', $filename);

            return response()->streamDownload(function () use ($headers, $rows) {
                echo "\xEF\xBB\xBF";
                $out = fopen('php://output', 'w');
                fputcsv($out, $headers);
                foreach ($rows as $row) {
                    fputcsv($out, (array) $row);
                }
                fclose($out);
            }, $csvFilename, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="'.$csvFilename.'"',
                'Cache-Control' => 'max-age=0',
            ]);
        }

        try {
            $content = self::buildXlsxContent($headers, $rows);

            return response()->streamDownload(function () use ($content) {
                echo $content;
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
                'Cache-Control' => 'max-age=0',
            ]);
        } catch (\Throwable $e) {
            $csvFilename = preg_replace('/\.xlsx$/i', '.csv', $filename);

            return response()->streamDownload(function () use ($headers, $rows) {
                echo "\xEF\xBB\xBF";
                $out = fopen('php://output', 'w');
                fputcsv($out, $headers);
                foreach ($rows as $row) {
                    fputcsv($out, (array) $row);
                }
                fclose($out);
            }, $csvFilename, [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="'.$csvFilename.'"',
                'Cache-Control' => 'max-age=0',
            ]);
        }
    }

    /**
     * Build standard OpenXML (.xlsx) package binary string.
     */
    public static function buildXlsxContent(array $headers, array $rows): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new ZipArchive;
        $zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // 1. [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'.
            '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'.
            '<Default Extension="xml" ContentType="application/xml"/>'.
            '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'.
            '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'.
            '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'.
            '</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // 2. _rels/.rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'.
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'.
            '</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // 3. xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'.
            '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'.
            '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'.
            '</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // 4. xl/workbook.xml
        $wb = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'.
            '<sheets>'.
            '<sheet name="Data" sheetId="1" r:id="rId1"/>'.
            '</sheets>'.
            '</workbook>';
        $zip->addFromString('xl/workbook.xml', $wb);

        // 5. xl/styles.xml
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'.
            '<fonts count="2">'.
            '<font><sz val="11"/><name val="Calibri"/></font>'.
            '<font><b/><sz val="11"/><name val="Calibri"/><color rgb="FFFFFFFF"/></font>'.
            '</fonts>'.
            '<fills count="3">'.
            '<fill><patternFill patternType="none"/></fill>'.
            '<fill><patternFill patternType="gray125"/></fill>'.
            '<fill><patternFill patternType="solid"><fgColor rgb="FF064E3B"/></patternFill></fill>'.
            '</fills>'.
            '<borders count="2">'.
            '<border><left/><right/><top/><bottom/></border>'.
            '<border><left style="thin"/><right style="thin"/><top style="thin"/><bottom style="thin"/></border>'.
            '</borders>'.
            '<cellXfs count="3">'.
            '<xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>'.
            '<xf numFmtId="0" fontId="1" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1"/>'.
            '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyBorder="1"/>'.
            '</cellXfs>'.
            '</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // 6. xl/worksheets/sheet1.xml
        $sheetData = '<sheetData>';
        $rowIndex = 1;

        // Header Row
        $sheetData .= '<row r="'.$rowIndex.'">';
        $colIndex = 1;
        foreach ($headers as $h) {
            $colLetter = self::columnIndexToLetter($colIndex);
            $safeVal = htmlspecialchars((string) $h, ENT_XML1, 'UTF-8');
            $sheetData .= '<c r="'.$colLetter.$rowIndex.'" t="inlineStr" s="1"><is><t>'.$safeVal.'</t></is></c>';
            $colIndex++;
        }
        $sheetData .= '</row>';
        $rowIndex++;

        // Data Rows
        foreach ($rows as $row) {
            $sheetData .= '<row r="'.$rowIndex.'">';
            $colIndex = 1;
            foreach ($row as $val) {
                $colLetter = self::columnIndexToLetter($colIndex);
                $safeVal = htmlspecialchars((string) ($val ?? ''), ENT_XML1, 'UTF-8');
                $sheetData .= '<c r="'.$colLetter.$rowIndex.'" t="inlineStr" s="2"><is><t>'.$safeVal.'</t></is></c>';
                $colIndex++;
            }
            $sheetData .= '</row>';
            $rowIndex++;
        }
        $sheetData .= '</sheetData>';

        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'.
            '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'.
            $sheetData.
            '</worksheet>';
        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);

        $zip->close();
        $binary = file_get_contents($tempFile);
        @unlink($tempFile);

        return $binary;
    }

    /**
     * Read an uploaded XLSX file and return extracted rows as array of associative arrays.
     */
    public static function readXlsx(string $filePath): array
    {
        $zip = new ZipArchive;
        if ($zip->open($filePath) !== true) {
            throw new \Exception('File Excel (.xlsx) tidak dapat dibuka atau korup.');
        }

        // Shared strings
        $sharedStrings = [];
        $sharedXmlContent = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXmlContent) {
            $xml = simplexml_load_string($sharedXmlContent);
            if ($xml && isset($xml->si)) {
                foreach ($xml->si as $si) {
                    $sharedStrings[] = (string) ($si->t ?? ($si->r ? $si->r->t : ''));
                }
            }
        }

        // Sheet 1
        $sheetXmlContent = $zip->getFromName('xl/worksheets/sheet1.xml');
        if (! $sheetXmlContent) {
            $zip->close();
            throw new \Exception('Worksheet sheet1.xml tidak ditemukan dalam file Excel.');
        }

        $sheetXml = simplexml_load_string($sheetXmlContent);
        $zip->close();

        $rows = [];
        if (! $sheetXml || ! isset($sheetXml->sheetData->row)) {
            return $rows;
        }

        $headers = [];
        foreach ($sheetXml->sheetData->row as $r) {
            $rowNum = (int) $r['r'];
            $rowData = [];

            foreach ($r->c as $c) {
                $cellRef = (string) $c['r'];
                preg_match('/([A-Z]+)(\d+)/', $cellRef, $matches);
                $colLetter = $matches[1] ?? 'A';
                $colIdx = self::letterToColumnIndex($colLetter);

                $cellType = (string) $c['t'];
                $val = '';

                if ($cellType === 's') { // shared string index
                    $idx = (int) $c->v;
                    $val = $sharedStrings[$idx] ?? '';
                } elseif ($cellType === 'inlineStr') {
                    $val = (string) ($c->is->t ?? '');
                } else {
                    $val = (string) ($c->v ?? '');
                }

                $rowData[$colIdx] = trim($val);
            }

            if ($rowNum === 1) {
                // Header row
                foreach ($rowData as $idx => $hName) {
                    $headers[$idx] = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $hName));
                }
            } else {
                if (! empty(array_filter($rowData))) {
                    $namedRow = [];
                    foreach ($headers as $colIdx => $hKey) {
                        $namedRow[$hKey] = $rowData[$colIdx] ?? '';
                    }
                    $namedRow['_row_number'] = $rowNum;
                    $rows[] = $namedRow;
                }
            }
        }

        return $rows;
    }

    /**
     * Convert 1-based column index to letter (1 => A, 27 => AA).
     */
    public static function columnIndexToLetter(int $col): string
    {
        $letter = '';
        while ($col > 0) {
            $mod = ($col - 1) % 26;
            $letter = chr(65 + $mod).$letter;
            $col = (int) (($col - $mod) / 26);
        }

        return $letter ?: 'A';
    }

    /**
     * Convert column letter to 1-based index (A => 1, AA => 27).
     */
    public static function letterToColumnIndex(string $letter): int
    {
        $col = 0;
        $len = strlen($letter);
        for ($i = 0; $i < $len; $i++) {
            $col = $col * 26 + (ord(strtoupper($letter[$i])) - 64);
        }

        return $col;
    }
}
