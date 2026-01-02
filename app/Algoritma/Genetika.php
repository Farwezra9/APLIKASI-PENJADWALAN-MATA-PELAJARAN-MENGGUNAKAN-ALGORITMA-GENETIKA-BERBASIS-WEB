<?php
namespace App\Algoritma;
use App\Models\Mengajar;
use App\Models\Hari;
use App\Models\Jam;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\Log;
class Genetika
{    
    private $jenis_semester;
    private $populasi;
    private $crossOver;
    private $mutasi;
    
    private $mengajar = [];
    private $individu = [[]];
    private $sks = [];
    private $guru = [];
    private $mapel = [];
    private $kelas = [];
    private $jam = [];
    private $hari = [];
    private $iguru = [];
    private $ikelas = [];
    public $induk = [];
    private $output = [];
    private $kode_jumat;
    private $range_jumat = [];
    private $kode_upacara;
    private $range_upacara = [];
    private $kode_istirahat;
    private $range_istirahat = [];
    private $kode_terakhir;
    private $range_terakhir = [];
    private $range_jeda = [];

    public function __construct($jenis_semester, $populasi, $crossOver, $mutasi)
    {        
        $this->jenis_semester = $jenis_semester;
        $this->populasi       = intval($populasi);
        $this->crossOver      = $crossOver;
        $this->mutasi         = $mutasi;
        $this->kode_jumat     = 5;
        $this->kode_upacara   = 1;
        $this->kode_terakhir  = 5;
    }
    
    public function ambilDataGenerateJadwal($jenis_semester) {
        $query = mengajar::select('a.id', 'a.sks', 'a.id_guru', 'a.kelas')
    ->from('mengajar AS a')
    ->where('a.semester', $jenis_semester);

$res = $query->get()->toArray();

$data_jadwal = [];

foreach ($res as $row) {
    $kelas_json = json_decode($row['kelas'], true);
    foreach ($kelas_json as $kelas) {
        if ($kelas['kelas'] == 'XPPLG1' || $kelas['kelas'] == 'XPPLG2') {  
            $data = [
                'id' => $row['id'],
                'sks' => $row['sks'],
                'id_guru' => $row['id_guru'],
                'id_kelas' => $kelas['id_kelas'],
                'kelas' => $kelas['kelas'],
                'tingkat' => $kelas['tingkat']
            ];

            $data_jadwal[] = $data;
        }
    }
}

return $data_jadwal;

    }
    
    //ambil data
    public function AmbilData() {
        $rs_data = $this->ambilDataGenerateJadwal($this->jenis_semester);
    
        if (is_array($rs_data)) {
            $i = 0;
            foreach ($rs_data as $data) {
                $this->mengajar[$i] = intval($data['id']);
                $this->sks[$i] = intval($data['sks']);
                $this->guru[$i] = intval($data['id_guru']);
                $this->ikelas[$i] = intval($data['id_kelas']);
                $this->kelas[$i] = $data['kelas'];
                $this->tingkat[$i] = intval($data['tingkat']);
                $i++;
            }
        }

        $rs_jam = Jam::all();

        // Inisialisasi array untuk menyimpan kode_jam
        $this->jam = [];
        foreach ($rs_jam as $data) {
            // Tambahkan kondisi untuk tidak mengambil kode_jam dengan jeda "Istirahat"
            if ($data->jeda !== 'Istirahat') {
                $this->jam[] = intval($data->kode_jam);
            }
        }

        // Inisialisasi array untuk menyimpan range jeda
        $this->range_jeda = [];
        foreach ($rs_jam as $value) {
            if ($value['jeda'] !== 'Istirahat') {
                $this->range_jeda[$value['jeda']][] = $value['kode_jam'];
            }
        }

        // Ambil data hari
        $rs_hari = Hari::all();

        foreach ($rs_hari as $data) {
            $this->hari[] = intval($data->kode_hari);
        }
    }
    //end ambil data

    //inisialisasi
    public function Inisialisasi()
    {
        $jumlah_mengajar = count($this->mengajar);
        $jumlah_jam = count($this->jam);
        $jumlah_hari = count($this->hari);

        for ($i = 0; $i < $this->populasi; $i++) {
            for ($j = 0; $j < $jumlah_mengajar; $j++) {
                $sks = $this->sks[$j];
                $this->individu[$i][$j][0] = $j;
                $this->individu[$i][$j][3] = $this->kelas[$j];
                $tingkat = $this->tingkat[$j];

                // Penentuan hari secara acak
                //$this->individu[$i][$j][1] = mt_rand(0, max(0, $jumlah_jam - $sks));
                //$this->individu[$i][$j][2] = mt_rand(0, max(0, $jumlah_hari - 1));
                $this->individu[$i][$j][2] = $this->getRandomHari($this->individu[$i][$j][3], $sks, $jumlah_hari);
                $this->individu[$i][$j][1] = $this->getRandomJam($this->individu[$i][$j][2], $jumlah_jam, $sks, $tingkat);
            }
        }
    }
    //end inisialisasi

    private function getRandomHari($kelas, $sks, $jumlah_hari)
    {
        // List hari yang tersedia
        $availableHari = range(0, $jumlah_hari - 1);
        $totalSKS3 = 0;
    
        // Menghitung jumlah kelas dengan SKS 3
        foreach ($this->kelas as $key => $value) {
            if ($value === $kelas && $this->sks[$key] === 3) {
                $totalSKS3++;
            }
        }
    
        // Jika kelas memiliki 4 mata pelajaran dengan SKS 3, hindari hari Senin
        if ($totalSKS3 == 4 && $sks == 3) {
            $availableHari = array_diff($availableHari, [0]); // Hindari hari Senin (index 0)
        }
        // Pilih hari acak dari hari yang tersedia
        return $availableHari[array_rand($availableHari)];
    }

    private function getRandomJam($hari, $jumlah_jam, $sks, $tingkat)
    {
        $availableJam = range(0, $jumlah_jam - $sks);

       if ($sks == 2) {
            if ($hari == 0) {
                $availableJam = array_intersect($availableJam, [1, 3, 4, 6, 7, 9]);
            } elseif ($hari == 4 && $tingkat == 10) {
                $availableJam = array_intersect($availableJam, [1, 4]);
            } elseif ($hari == 4 && ($tingkat == 11 || $tingkat == 12)) {
                $availableJam = array_intersect($availableJam, [1, 4, 7]);
            } else {
                $availableJam = array_intersect($availableJam, [0, 2, 3, 4, 5, 6, 7, 9]);
            }
        } elseif ($sks == 3) {
            if ($hari == 0) {
                $availableJam = array_intersect($availableJam, [1, 3, 4, 5, 8]);
            } elseif ($hari == 4) {
                $availableJam = array_intersect($availableJam, [1, 3]);
            } else {
                $availableJam = array_intersect($availableJam, [0, 2, 3, 4, 5, 6, 8]);
            }
        }
    
       // Pilih jam acak dari jam yang tersedia
       return $availableJam[array_rand($availableJam)];
    }
    //cek bentrok
    private function CekFitness($indv)
    {
        $penalty = 0;
        $jumlah_mengajar = count($this->mengajar);
        $jumlah_mengajar = count($this->mengajar);
        for ($i = 0; $i < $jumlah_mengajar; $i++) {
            $sks = intval($this->sks[$i]);
            $jam_a = intval($this->individu[$indv][$i][1]);
            $hari_a = intval($this->individu[$indv][$i][2]);
            $guru_a = intval($this->guru[$i]);
            $kelas_a = intval($this->ikelas[$i]);
    
            for ($k = 0; $k < $jumlah_mengajar; $k++) {
                if ($i == $k) continue;
    
                $jam_b = intval($this->individu[$indv][$k][1]);
                $hari_b = intval($this->individu[$indv][$k][2]);
                $guru_b = intval($this->guru[$k]);
                $kelas_b = intval($this->ikelas[$k]);
    
                // Bentrok kelas dan guru
                if (($sks >= 1 && $jam_a === $jam_b && $hari_a === $hari_b && $kelas_a === $kelas_b) ||
                    ($sks >= 2 && ($jam_a + 1) === $jam_b && $hari_a === $hari_b && $kelas_a === $kelas_b) ||
                    ($sks >= 3 && ($jam_a + 2) === $jam_b && $hari_a === $hari_b && $kelas_a === $kelas_b)) {
                    $penalty += 1;
                }
                if (($sks >= 1 && $jam_a === $jam_b && $hari_a === $hari_b && $guru_a === $guru_b) ||
                    ($sks >= 2 && ($jam_a + 1) === $jam_b && $hari_a === $hari_b && $guru_a === $guru_b) ||
                    ($sks >= 3 && ($jam_a + 2) === $jam_b && $hari_a === $hari_b && $guru_a === $guru_b)) {
                    $penalty += 1;
                }
            }
        }
    
        return 1 / (1 + $penalty);
    }
    
    //end cek bentrok

    //cek fitness
    public function HitungFitness()
    {
        $fitness = array();
    
        for ($indv = 0; $indv < $this->populasi; $indv++) {
            $fitness[$indv] = $this->CekFitness($indv);
        }
    
        return $fitness;
    }
    
    
    //end cek fitness
    
    //Seleksi
    public function seleksi($fitness)
    {
        $rank = array();
        $jumlah = 0;
    
        // Evaluasi Fitness dan Penetapan Rank Untung Rank Selection
        for ($i = 0; $i < $this->populasi; $i++) {
            $rank[$i] = 1;
            for ($j = 0; $j < $this->populasi; $j++) {
                if ($fitness[$i] > $fitness[$j]) {
                    $rank[$i]++;
                }
            }
            $jumlah += $rank[$i];
        }
    
        // Perhitungan Probabilitas Seleksi
        $probabilitas = array();
        for ($i = 0; $i < $this->populasi; $i++) {
            $probabilitas[$i] = $rank[$i] / $jumlah;
        }
    
        // Menggunakan Roulette Wheel untuk memilih induk
        $individu_terpilih = array();
        for ($k = 0; $k < $this->populasi; $k++) {
            $r = mt_rand() / mt_getrandmax(); // Random number between 0 and 1
            $accumulated_probability = 0;
            for ($i = 0; $i < $this->populasi; $i++) {
                $accumulated_probability += $probabilitas[$i];
                if ($r <= $accumulated_probability) {
                    $individu_terpilih[$k] = $i;
                    break;
                }
            }
        }
    
        // Menyimpan hasil seleksi
        $this->induk = $individu_terpilih;
    } 
    //#end seleksi

   //crossover
   public function StartCrossOver()
   {
       $individu_baru = array();
       $jumlah_mengajar = count($this->mengajar);
       $populasi = count($this->individu);
       
       for ($i = 0; $i < $populasi; $i += 2) // Perulangan untuk jadwal yang terpilih
       {
           $cr = mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax();
   
           // Crossover menggunakan metode 2 titik jika nilai acak lebih kecil dari probabilitas crossover
           if ($cr < $this->crossOver) {
               $a = mt_rand(1, $jumlah_mengajar - 2);
               $b = mt_rand($a + 1, $jumlah_mengajar - 1);
               
               Log::info('Crossover antara individu = ' . $this->induk[$i] . ' X ' . $this->induk[$i + 1]);
               Log::info('Titik 1 = ' . $a . ' Titik 2 = ' . $b);
   
               // Menyalin segmen sebelum titik crossover pertama
               for ($j = 0; $j < $a; $j++) {
                   $individu_baru[$i][$j]     = $this->individu[$this->induk[$i]][$j];
                   $individu_baru[$i + 1][$j] = $this->individu[$this->induk[$i + 1]][$j];
               }
               
               // Menukar individu antara dua titik crossover
               for ($j = $a; $j < $b; $j++) {
                   $individu_baru[$i][$j]     = $this->individu[$this->induk[$i + 1]][$j];
                   $individu_baru[$i + 1][$j] = $this->individu[$this->induk[$i]][$j];
               }
               
               // Menyalin segmen setelah titik crossover kedua
               for ($j = $b; $j < $jumlah_mengajar; $j++) {
                   $individu_baru[$i][$j]     = $this->individu[$this->induk[$i]][$j];
                   $individu_baru[$i + 1][$j] = $this->individu[$this->induk[$i + 1]][$j];
               }
           } else { // Jika tidak terjadi crossover, salin individu apa adanya
               for ($j = 0; $j < $jumlah_mengajar; $j++) {
                   $individu_baru[$i][$j]     = $this->individu[$this->induk[$i]][$j];
                   $individu_baru[$i + 1][$j] = $this->individu[$this->induk[$i + 1]][$j];
               }
           }
       }
       
       // Menggantikan populasi lama dengan populasi baru
       for ($i = 0; $i < $populasi; $i++) {
           $this->individu[$i] = $individu_baru[$i];
       }
   }

    //end crossover

    // Proses mutasi
    public function Mutasi()
    {
    $fitness = array();
    $jumlah_mengajar = count($this->mengajar);
    $jumlah_jam = count($this->jam);
    $jumlah_hari = count($this->hari);

    for ($i = 0; $i < $this->populasi; $i++) {
        $r = mt_rand(0, mt_getrandmax() - 1) / mt_getrandmax();
        
        if ($r < $this->mutasi) {
            // Cari gen yang bentrok
            $bentrok = [];
            for ($j = 0; $j < $jumlah_mengajar; $j++) {
                $sks = intval($this->sks[$j]);
                $jam_a = intval($this->individu[$i][$j][1]);
                $hari_a = intval($this->individu[$i][$j][2]);
                $guru_a = intval($this->guru[$j]);
                $kelas_a = intval($this->ikelas[$j]);
                
                for ($k = 0; $k < $jumlah_mengajar; $k++) {
                    if ($j == $k) continue;
                    
                    $jam_b = intval($this->individu[$i][$k][1]);
                    $hari_b = intval($this->individu[$i][$k][2]);
                    $guru_b = intval($this->guru[$k]);
                    $kelas_b = intval($this->ikelas[$k]);
                    
                    // Bentrok kelas dan guru
                    if (($sks >= 1 && $jam_a === $jam_b && $hari_a === $hari_b && ($guru_a === $guru_b || $kelas_a === $kelas_b)) ||
                    ($sks >= 2 && ($jam_a + 1) === $jam_b && $hari_a === $hari_b && ($guru_a === $guru_b || $kelas_a === $kelas_b)) ||
                    ($sks >= 3 && ($jam_a + 2) === $jam_b && $hari_a === $hari_b && ($guru_a === $guru_b || $kelas_a === $kelas_b))) {
                        $bentrok[] = $j;
                        break;
                    }
                }
            }
            
            if (!empty($bentrok)) {
                // Pilih gen yang bentrok secara acak dan mutasi
                $krom = $bentrok[array_rand($bentrok)];
                $sks = intval($this->sks[$krom]);
                $tingkat = $this->tingkat[$krom];
                $this->individu[$i][$krom][3] = $this->kelas[$krom];
                $this->individu[$i][$krom][2] = $this->getRandomHari($this->individu[$i][$krom][3],$sks, $jumlah_hari);
                $this->individu[$i][$krom][1] = $this->getRandomJam($this->individu[$i][$krom][2], $jumlah_jam, $sks, $tingkat);
            }
        }

        $fitness[$i] = $this->CekFitness($i);
    }

    return $fitness;
    }
    //end mutasi

    //ambil jadwal
    public function GetIndividu($indv)
    {
        
        $individu_solusi = array(array());
        
        for ($j = 0; $j < count($this->mengajar); $j++)
        {
            $individu_solusi[$j][0] = intval($this->mengajar[$this->individu[$indv][$j][0]]);
            $individu_solusi[$j][1] = intval($this->jam[$this->individu[$indv][$j][1]]);
            $individu_solusi[$j][2] = intval($this->hari[$this->individu[$indv][$j][2]]);                                 
            $individu_solusi[$j][3] = $this->individu[$indv][$j][3];
        }
        
        return $individu_solusi;
    }
    //end ambil jadwal

    //ambil jadwal fitness tertinggi
    public function GetIndividuTerbaik()
    {
        $bestFitness = 0;
        $bestIndividualIndex = 0;

        for ($i = 0; $i < $this->populasi; $i++) {
            $currentFitness = $this->CekFitness($i);

            if ($currentFitness > $bestFitness) {
                $bestFitness = $currentFitness;
                $bestIndividualIndex = $i;
            }
        }

        return $this->GetIndividu($bestIndividualIndex);
    }
    //end ambil jadwal fitness tertinggi
    
}