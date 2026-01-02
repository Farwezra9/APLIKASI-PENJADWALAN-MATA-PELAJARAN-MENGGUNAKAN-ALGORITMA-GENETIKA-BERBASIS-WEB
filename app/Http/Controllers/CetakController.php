<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Jam;
use App\Models\Murid;
use App\Models\Mengajar;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Http\Request;

class CetakController extends Controller
{
    public function guruexcel(Request $request)
    {
        $dataSemua = Guru::all();
    
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'NAMA');
        $sheet->setCellValue('D1', 'PANGKAT');
        $sheet->setCellValue('E1', 'EMAIL');
        $sheet->setCellValue('F1', 'ALAMAT');
        $sheet->setCellValue('G1', 'NO TELP');
    
        $no = 1;
        $x = 2; // Start with the header row
    
        foreach ($dataSemua as $value) {
            $sheet->setCellValue('A' . $x, $no++);
            $sheet->setCellValueExplicit('B' . $x, $value->nip, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C' . $x, $value->nama);
            $sheet->setCellValue('D' . $x, $value->pangkat);
            $sheet->setCellValue('E' . $x, $value->email);
            $sheet->setCellValue('F' . $x, $value->alamat);
            $sheet->setCellValue('G' . $x, $value->no_telp);
            $x++;
        }
    
        // Set different widths for specific columns
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(30); 
        $sheet->getColumnDimension('E')->setWidth(25); 
        $sheet->getColumnDimension('F')->setWidth(40);
        $sheet->getColumnDimension('G')->setWidth(25);
    
        $contentStyle = [
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ];
    
        // Apply alignment settings to the header row only
        $sheet->getStyle('A1:G1')->applyFromArray($contentStyle);
    
        // Apply alignment settings to the data rows
        $sheet->getStyle('A2:B' . ($x - 1))->applyFromArray($contentStyle);
    
        $borderStyle = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]];
        $sheet->getStyle('A1:G' . ($x - 1))->applyFromArray($borderStyle);
    
        $filename = 'DATA GURU SMKN 2 KUNINGAN.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function muridexcel(Request $request)
{
    $dataSemua = Murid::with('kelas')->get();

    $spreadsheet = new Spreadsheet;
    $sheet = $spreadsheet->getActiveSheet();

    // Header
    $sheet->setCellValue('A1', 'NO');
    $sheet->setCellValue('B1', 'NISN');
    $sheet->setCellValue('C1', 'NAMA');
    $sheet->setCellValue('D1', 'JENIS KELAMIN'); 
    $sheet->setCellValue('E1', 'KELAS');
    $sheet->setCellValue('F1', 'EMAIL');
    $sheet->setCellValue('G1', 'ALAMAT');
    $sheet->setCellValue('H1', 'NO TELP');

    $no = 1;
    $x = 2;

    foreach ($dataSemua as $value) {
        $sheet->setCellValue('A' . $x, $no++);
        $sheet->setCellValueExplicit('B' . $x, $value->nisn, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('C' . $x, $value->nama);
        $sheet->setCellValue('D' . $x, $value->jk); // ← Isi JK
        $sheet->setCellValue('E' . $x, $value->kelas->nama_kelas);
        $sheet->setCellValue('F' . $x, $value->email);
        $sheet->setCellValue('G' . $x, $value->alamat);
        $sheet->setCellValue('H' . $x, $value->notelp);
        $x++;
    }

    // Atur lebar kolom
    $sheet->getColumnDimension('A')->setWidth(10);
    $sheet->getColumnDimension('B')->setWidth(25);
    $sheet->getColumnDimension('C')->setWidth(30);
    $sheet->getColumnDimension('D')->setWidth(10);  // JK
    $sheet->getColumnDimension('E')->setWidth(15); 
    $sheet->getColumnDimension('F')->setWidth(25); 
    $sheet->getColumnDimension('G')->setWidth(40);
    $sheet->getColumnDimension('H')->setWidth(20);

    $contentStyle = [
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    ];

    // Header style
    $sheet->getStyle('A1:H1')->applyFromArray($contentStyle);

    // Center NISN dan No
    $sheet->getStyle('A2:B' . ($x - 1))->applyFromArray($contentStyle);

    // Border untuk semua data
    $sheet->getStyle('A1:H' . ($x - 1))->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
    ]);

    // Output
    $filename = 'DATA MURID SMKN 2 KUNINGAN.xlsx';
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
}

    public function mengajarexcel(Request $request)
    {
        $mengajar = Mengajar::with(['pelajaran', 'guru'])->get();
        $groupedMengajar = $mengajar->groupBy('guru.nama');
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'GURU');
        $sheet->setCellValue('D1', 'MATA PELAJARAN');
    
        $allClasses = [];
        foreach ($mengajar as $item) {
            $kelas = json_decode($item->kelas, true);
            foreach ($kelas as $k) {
                $allClasses[] = $k['kelas'];
            }
        }
    
        $allClasses = array_unique($allClasses);
        sort($allClasses);
    
        $colIndex = 'E';
        foreach ($allClasses as $kelas) {
            $sheet->setCellValue($colIndex . '1', $kelas);
            $sheet->getStyle($colIndex . '1')->getAlignment()->setTextRotation(90);
            $colIndex++;
        }
    
        $sheet->setCellValue($colIndex . '1', 'TOTAL SKS');
    
        $no = 1;
        $row = 2;
        $colors = ['FFFFFF', 'D3D3D3']; // Putih dan Abu-abu Muda
        $colorIndex = 0;
    
        foreach ($groupedMengajar as $guruNama => $mengajarGroup) {
            $totalSks = 0;
            foreach ($mengajarGroup as $item) {
                $kelas = json_decode($item->kelas, true);
                $totalSks += $item->sks * count($kelas);
            }
    
            $subjectCount = $mengajarGroup->count();
            $startRow = $row;
    
            if ($subjectCount > 1) {
                $sheet->mergeCells("A{$row}:A" . ($row + $subjectCount - 1));
                $sheet->mergeCells("B{$row}:B" . ($row + $subjectCount - 1));
                $sheet->mergeCells("C{$row}:C" . ($row + $subjectCount - 1));
                $sheet->mergeCells("{$colIndex}{$row}:{$colIndex}" . ($row + $subjectCount - 1));
            }
    
            $nip = $mengajarGroup->first()->guru->nip;
            $sheet->setCellValue("A{$row}", $no++);
            $sheet->setCellValueExplicit("B{$row}", $nip, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue("C{$row}", $guruNama);
    
            foreach ($mengajarGroup as $subject) {
                $pelajaranNama = $subject->pelajaran->nama_pel;
                $sheet->setCellValue("D{$row}", $pelajaranNama);
    
                $kelasArray = json_decode($subject->kelas, true);
                foreach ($kelasArray as $kelas) {
                    $kelasCol = array_search($kelas['kelas'], $allClasses);
                    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($kelasCol + 5);
                    $sheet->setCellValue($columnLetter . $row, $subject->sks);
                }
    
                $color = $colors[$colorIndex % 2];
                $sheet->getStyle("A{$row}:{$colIndex}{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => $color]
                    ]
                ]);
    
                $row++;
            }
    
            $sheet->setCellValue("{$colIndex}" . $startRow, $totalSks);
            $colorIndex++;
        }
    
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(40);
    
        foreach ($allClasses as $index => $kelas) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index + 5);
            $sheet->getColumnDimension($columnLetter)->setWidth(5);
        }
    
        $sheet->getStyle('A1:' . $colIndex . '1')->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
    
        $borderStyle = ['borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]];
        $sheet->getStyle('A1:' . $colIndex . ($row - 1))->applyFromArray($borderStyle);
        $sheet->getStyle('A1:' . $colIndex . '1')->applyFromArray($borderStyle);
        $sheet->getStyle("{$colIndex}2:{$colIndex}" . ($row - 1))->applyFromArray($borderStyle);
        $sheet->getStyle("{$colIndex}1")->applyFromArray($borderStyle);
    
        $filename = 'DATA_TUGAS_GURU_MENGAJAR_SEMESTER' . $mengajar->first()->semester . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
    
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    
    


    
    public function jadwalguru(Request $request)
{
    $guru = Auth::user(); // Sesuaikan dengan model atau struktur autentikasi yang Anda gunakan
    $dataJam = Jam::whereNotNull('jeda')
        ->where('jeda', '!=', 'Jumat')
        ->where('jeda', '!=', 'Terakhir')
        ->get();

    // Gabungkan jam dengan jeda yang sama
    $jedaIstirahat = [];
    foreach ($dataJam as $jam) {
        if ($jam->jeda == 'Istirahat') {
            if (!isset($jedaIstirahat[$jam->jeda])) {
                $jedaIstirahat[$jam->jeda] = $jam->range_jam;
            } else {
                $jedaIstirahat[$jam->jeda] .= ' & ' . $jam->range_jam;
            }
        } else {
            $jedaIstirahat[$jam->jeda] = $jam->range_jam;
        }
    }

    // Mendapatkan jadwal hanya untuk guru yang login
    $dataSemua = Jadwal::where('guru', $guru->nama)->orderByDesc('hari')->get();
    $pdf = new \FPDF('l', 'mm', 'A4');
    $pdf->SetFont('Arial', '', 12);

    // Add the page first before adding content
    $pdf->AddPage();

    // Add header section
    $firstJadwal = $dataSemua->first();
    $pdf->SetFont('Times', 'B', 14);
    $pdf->SetFont('Times', '', 18);

    $pdf->SetFillColor(200, 200, 200);
    $pdf->Cell(280, 9, 'JADWAL MATA PELAJARAN SMKN 2 KUNINGAN', 0, 1, 'C');
    $pdf->Cell(280, 9, 'SEMESTER ' . $firstJadwal->semester, 0, 1, 'C');
    $pdf->Cell(280, 9, 'TAHUN AJARAN ' . $firstJadwal->tahun_akademik, 0, 1, 'C');
    $pdf->SetFont('Times', 'I', 12);
    $pdf->cell(280, 9, 'No. 77 Jalan. Cigugur Sukamulya 45552 Cigugur Jawa Barat', 0, 1, 'C');

    // Gambar garis horizontal
    $pdf->SetDrawColor(100, 100, 100);
    $pdf->SetLineWidth(1);
    $pdf->Line(40, 36, 350 - 100, 36);
    $pdf->SetLineWidth(0);
    $pdf->Line(40, 37, 350 - 100, 37);
    $pdf->Ln(5);

    // Cetak jeda dan jam istirahat
    $pdf->SetFont('Times', 'B', 12); // Mengatur Times New Roman ukuran 12 tanpa italic
    $jamText = ''; 
    foreach ($jedaIstirahat as $jeda => $jam) {
        $jamText .= $jeda . ' : ' . $jam . ', ';
    }
    $jamText = rtrim($jamText, ', ');
    $pdf->Cell(40); // Indentasi
    $pdf->Cell(10, 8, $jamText, 0, 1);

    // HEADER TABEL
    $pdf->SetFont('Times', 'B', 12);
    $pdf->SetFillColor(29, 139, 139); // Header warna hijau
    $pdf->SetTextColor(255, 255, 255); 
    $pdf->Cell(40);
    $pdf->Cell(10, 8, 'No', 1, 0, 'C', true);
    $pdf->Cell(30, 8, 'HARI', 1, 0, 'C', true);
    $pdf->Cell(30, 8, 'JAM', 1, 0, 'C', true);
    $pdf->Cell(70, 8, 'MATA PELAJARAN', 1, 0, 'C', true);
    $pdf->Cell(20, 8, 'SKS', 1, 0, 'C', true);
    $pdf->Cell(30, 8, 'KELAS', 1, 0, 'C', true);
    $pdf->Ln(); 

    // DATA TABEL
    $pdf->SetFont('Times', '', 12);
    $pdf->SetTextColor(0, 0, 0);
    foreach ($dataSemua as $index => $jadwal) {
        $pdf->cell(40);

        // Kondisi silang-silang warna abu-abu
        if ($index % 2 == 0) {
            $pdf->SetFillColor(220, 220, 220); // Warna abu-abu
            $fill = true;
        } else {
            $fill = false;
        }

        $pdf->Cell(10, 8, $index + 1, 1, 0, 'C', $fill);
        $pdf->Cell(30, 8, $jadwal->hari, 1, 0, 'C', $fill);
        $pdf->Cell(30, 8, $jadwal->jam, 1, 0, 'C', $fill);
        $pdf->Cell(70, 8, $jadwal->mata_pelajaran, 1, 0, 'J', $fill);
        $pdf->Cell(20, 8, $jadwal->sks, 1, 0, 'C', $fill);
        $pdf->Cell(30, 8, $jadwal->kelas, 1, 0, 'C', $fill);
        $pdf->Ln();
    }

    // Output the PDF
    $pdf->Output('D', 'Jadwal Mata Pelajaran SMKN 2 Kuningan.pdf');
}

    public function jadwalmurid(Request $request)
{
    $userId = Auth::id();
    $user = User::with('murid')->find($userId);
    $murid = $user->murid;
    $idKelas = $murid->id_kelas;
    $dataJam = Jam::whereNotNull('jeda')
        ->where('jeda', '!=', 'Jumat')
        ->where('jeda', '!=', 'Terakhir')
        ->get();

    // Gabungkan jam dengan jeda yang sama
    $jedaIstirahat = [];
    foreach ($dataJam as $jam) {
        if ($jam->jeda == 'Istirahat') {
            if (!isset($jedaIstirahat[$jam->jeda])) {
                $jedaIstirahat[$jam->jeda] = $jam->range_jam;
            } else {
                $jedaIstirahat[$jam->jeda] .= ' & ' . $jam->range_jam;
            }
        } else {
            $jedaIstirahat[$jam->jeda] = $jam->range_jam;
        }
    }

    // Get class data
    $dataKelas = Kelas::find($idKelas);

    // Perform a join between 'jadwal' and 'kelas' tables
    $dataSemua = Jadwal::join('kelas', 'jadwal.kelas', '=', 'kelas.nama_kelas')
        ->where('kelas.id', $idKelas)
        ->orderBy('jadwal.hari', 'desc')
        ->orderBy('jadwal.jam', 'asc')
        ->get(['jadwal.*']);

    $pdf = new \FPDF('L', 'mm', 'A4'); // Orientasi Landscape
    $pdf->SetFont('Arial', '', 12);

    // Tambahkan halaman
    $pdf->AddPage();

    // Bagian header
    $firstJadwal = $dataSemua->first();
    $pdf->SetFont('Times', 'B', 14);
    $pdf->SetFont('Times', '', 18);

    $pdf->SetFillColor(200, 200, 200);
    $pdf->Cell(280, 9, 'JADWAL MATA PELAJARAN SMKN 2 KUNINGAN', 0, 1, 'C');
    $pdf->Cell(280, 9, 'SEMESTER ' . $firstJadwal->semester, 0, 1, 'C');
    $pdf->Cell(280, 9, 'TAHUN AJARAN ' . $firstJadwal->tahun_akademik, 0, 1, 'C');
    $pdf->SetFont('Times', 'I', 12);
    $pdf->cell(280, 9, 'No. 77 Jalan. Cigugur Sukamulya 45552 Cigugur Jawa Barat', 0, 1, 'C');

    // Gambar garis horizontal
    $pdf->SetDrawColor(100, 100, 100);
    $pdf->SetLineWidth(1);
    $pdf->Line(40, 36, 350 - 100, 36);
    $pdf->SetLineWidth(0);
    $pdf->Line(40, 37, 350 - 100, 37);
    $pdf->Ln(5);

    // Cetak jeda dan jam istirahat
    $pdf->SetFont('Times', 'B', 12); // Mengatur Times New Roman ukuran 12 tanpa italic
    $jamText = ''; 
    foreach ($jedaIstirahat as $jeda => $jam) {
        $jamText .= $jeda . ' : ' . $jam . ', ';
    }

    // Menghapus koma dan spasi terakhir
    $jamText = rtrim($jamText, ', ');

    $pdf->Cell(30); // Indentasi
    $pdf->Cell(10, 6, $jamText, 0, 1);

    // Header Tabel
    $pdf->SetFont('Times', 'B', 12);
    $pdf->SetFillColor(29, 139, 139); // Header warna hijau
    $pdf->SetTextColor(255, 255, 255); 
    $pdf->Cell(30); // Indentasi
    $pdf->Cell(8, 6, 'No', 1, 0, 'C', true);  
    $pdf->Cell(15, 6, 'HARI', 1, 0, 'C', true); 
    $pdf->Cell(30, 6, 'JAM', 1, 0, 'C', true); 
    $pdf->Cell(70, 6, 'MATA PELAJARAN', 1, 0, 'C', true); 
    $pdf->Cell(60, 6, 'GURU', 1, 0, 'C', true); 
    $pdf->Cell(10, 6, 'SKS', 1, 0, 'C', true); 
    $pdf->Cell(20, 6, 'KELAS', 1, 0, 'C', true); 
    $pdf->Ln(); 

    // Konten Tabel
    $pdf->SetFont('Times', '', 12);
    $pdf->SetTextColor(0, 0, 0);
    foreach ($dataSemua as $index => $jadwal) {
        $pdf->Cell(30); // Indentasi
        if ($index % 2 == 0) {
            $pdf->SetFillColor(220, 220, 220); // Warna abu-abu
            $fill = true;
        } else {
            $fill = false;
        }
        $pdf->Cell(8, 6, $index + 1, 1, 0, 'C', $fill); 
        $pdf->Cell(15, 6, $jadwal->hari, 1, 0, 'C', $fill); 
        $pdf->Cell(30, 6, $jadwal->jam, 1, 0, 'C', $fill); 
        $pdf->Cell(70, 6, $jadwal->mata_pelajaran, 1, 0, 'L', $fill); 
        $pdf->Cell(60, 6, $jadwal->guru, 1, 0, 'L', $fill); 
        $pdf->Cell(10, 6, $jadwal->sks, 1, 0, 'C', $fill); 
        $pdf->Cell(20, 6, $jadwal->kelas, 1, 0, 'C', $fill); 
        $pdf->Ln();
    }

    // Output PDF
    $pdf->Output('D', 'Jadwal Mata Pelajaran SMKN 2 Kuningan.pdf');
}



    public function jadwalexcel(Request $request)
    {
        // Mengambil semua data jadwal dan mengurutkan berdasarkan kelas, hari, dan jam
        $dataSemua = Jadwal::join('kelas', 'jadwal.kelas', '=', 'kelas.nama_kelas') // Join berdasarkan nama kelas
            ->join('jurusan', 'kelas.id_jurusan', '=', 'jurusan.id') // Join berdasarkan id_jurusan
            ->orderBy('jurusan.nama_jurusan', 'asc') // Urutkan berdasarkan jurusan
            ->orderBy('kelas.nama_kelas', 'asc')    // Urutkan berdasarkan kelas
            ->orderBy('hari', 'desc')               // Urutkan berdasarkan hari
            ->orderBy('jam', 'asc')                 // Urutkan berdasarkan jam
            ->select('jadwal.*', 'jurusan.nama_jurusan', 'kelas.nama_kelas')
            ->get();
            
        $spreadsheet = new Spreadsheet();
        
        // Sheet pertama: Jadwal Semua Kelas
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Semua Kelas');
    
        // Mengisi header tabel tanpa judul tambahan
        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'HARI');
        $sheet->setCellValue('C1', 'JAM');
        $sheet->setCellValue('D1', 'GURU');
        $sheet->setCellValue('E1', 'SEMESTER');
        $sheet->setCellValue('F1', 'MATA PELAJARAN');
        $sheet->setCellValue('G1', 'KELAS');
        $sheet->setCellValue('H1', 'SKS');
        $sheet->setCellValue('I1', 'TAHUN AKADEMIK');
        
        // Mengisi data tabel
        $no = 1;
        $x = 2; // Baris dimulai dari 2 karena header berada di baris 1
        foreach ($dataSemua as $value) {
            $sheet->setCellValue('A' . $x, $no++);
            $sheet->setCellValue('B' . $x, $value->hari);
            $sheet->setCellValue('C' . $x, $value->jam);
            $sheet->setCellValue('D' . $x, $value->guru);
            $sheet->setCellValue('E' . $x, $value->semester);
            $sheet->setCellValue('F' . $x, $value->mata_pelajaran);
            $sheet->setCellValue('G' . $x, $value->kelas);
            $sheet->setCellValue('H' . $x, $value->sks);
            $sheet->setCellValue('I' . $x, $value->tahun_akademik);
            $x++;
        }
    
        // Mengatur gaya dan lebar kolom pada sheet pertama
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(5);
        $sheet->getColumnDimension('I')->setWidth(15);
    
        // Mengatur header style
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'name' => 'Times New Roman'],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4285F4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);
    
        // Sheet kedua: Per Kelas dan Jurusan (urut ascending berdasarkan kelas dan jurusan)
        $groupedData = $dataSemua->groupBy(['nama_jurusan', 'kelas']); // Group by jurusan dan kelas

        foreach ($groupedData as $jurusan => $kelasData) {
            foreach ($kelasData as $kelas => $jadwalData) {
                $sheet = $spreadsheet->createSheet();
                $sheet->setTitle($kelas);
        
                // Title formatting similar to your PDF format
                $firstJadwal = $jadwalData->first();
        
                // Judul utama
                $sheet->mergeCells('A1:G1');
                $sheet->setCellValue('A1', 'JADWAL MATA PELAJARAN SMKN 2 KUNINGAN');
                $sheet->getStyle('A1')->getFont()->setName('Times New Roman')->setSize(14)->setBold(true);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
                // Semester
                $sheet->mergeCells('A2:G2');
                $sheet->setCellValue('A2', 'SEMESTER ' . $firstJadwal->semester);
                $sheet->getStyle('A2')->getFont()->setName('Times New Roman')->setSize(12)->setBold(true);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
                // Tahun Ajaran
                $sheet->mergeCells('A3:G3');
                $sheet->setCellValue('A3', 'TAHUN AJARAN ' . $firstJadwal->tahun_akademik);
                $sheet->getStyle('A3')->getFont()->setName('Times New Roman')->setSize(12)->setBold(true);
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
                // Alamat sekolah
                $sheet->mergeCells('A4:G4');
                $sheet->setCellValue('A4', 'No. 77 Jalan. Cigugur Sukamulya 45552 Cigugur Jawa Barat');
                $sheet->getStyle('A4')->getFont()->setName('Times New Roman')->setSize(10)->setItalic(true);
                $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
                // Garis bawah
                $sheet->getStyle('A5:G5')->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                ]);
                $sheet->mergeCells('A5:G5'); // Empty row for line effect
        
                // Mengisi header tabel
                $sheet->setCellValue('A6', 'NO');
                $sheet->setCellValue('B6', 'HARI');
                $sheet->setCellValue('C6', 'JAM');
                $sheet->setCellValue('D6', 'MATA PELAJARAN');
                $sheet->setCellValue('E6', 'SKS');
                $sheet->setCellValue('F6', 'KELAS');
                $sheet->setCellValue('G6', 'GURU');
        
                // Mengatur lebar kolom pada sheet kedua
                $sheet->getColumnDimension('A')->setWidth(8);
                $sheet->getColumnDimension('B')->setWidth(12);
                $sheet->getColumnDimension('C')->setWidth(12);
                $sheet->getColumnDimension('D')->setWidth(40);
                $sheet->getColumnDimension('E')->setWidth(10);
                $sheet->getColumnDimension('F')->setWidth(12);
                $sheet->getColumnDimension('G')->setWidth(30);
        
                // Mengatur gaya header dengan warna biru muda
                $headerStyle = [
                    'font' => ['bold' => true],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                    'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => '4285F4'] // Warna biru muda
                    ],
                ];
                $sheet->getStyle('A6:G6')->applyFromArray($headerStyle);
        
                // Mengisi data jadwal
                $no = 1;
                $x = 7;
                foreach ($jadwalData as $value) {
                    $sheet->setCellValue('A' . $x, $no++);
                    $sheet->setCellValue('B' . $x, $value->hari);
                    $sheet->setCellValue('C' . $x, $value->jam);
                    $sheet->setCellValue('D' . $x, $value->mata_pelajaran);
                    $sheet->setCellValue('E' . $x, $value->sks);
                    $sheet->setCellValue('F' . $x, $value->kelas);
                    $sheet->setCellValue('G' . $x, $value->guru);
        
                    // Memberi warna silang-silang
                    if ($no % 2 == 0) {
                        $sheet->getStyle('A' . $x . ':G' . $x)->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFDDDDDD']
                            ]
                        ]);
                    } else {
                        $sheet->getStyle('A' . $x . ':G' . $x)->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFFFFFFF']
                            ]
                        ]);
                    }
                    $x++;
                }
        
                // Mengatur alignment untuk kolom tertentu
                $centeredColumns = ['A', 'B', 'C', 'E', 'F'];
                foreach ($centeredColumns as $column) {
                    $sheet->getStyle($column . '6:' . $column . ($x - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
        
                // Mengatur border dan style konten
                $borderStyle = ['borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]];
                $sheet->getStyle('A6:G' . ($x - 1))->applyFromArray($borderStyle);
            }
        }
        

    
        // Simpan dan ekspor file Excel
        $filename = 'JADWAL_MATA_PELAJARAN_SMKN2_KUNINGAN_' . $dataSemua->first()->tahun_akademik . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
    
}
