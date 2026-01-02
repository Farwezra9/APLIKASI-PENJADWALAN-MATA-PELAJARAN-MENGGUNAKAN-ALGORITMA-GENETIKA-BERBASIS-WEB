<?php

namespace App\Http\Controllers;
use App\Algoritma\Genetika;
use Illuminate\Support\Facades\Session;
use App\Models\Generate;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Mengajar;
use App\Models\Hari;
use App\Models\Jam;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class GenerateController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $jadwal = DB::select("SELECT a.id AS id, e.nama_hari AS hari, CONCAT_WS('-', MID(g.range_jam, 1, 5), 
            (SELECT MID(range_jam, 7, 5) FROM jam WHERE kode_jam = ( CASE 
                WHEN (SELECT jeda FROM jam WHERE kode_jam = g.kode_jam + (b.sks - 1)) = 'Istirahat'
                     OR (SELECT jeda FROM jam WHERE kode_jam = g.kode_jam + 1) = 'Istirahat'
                THEN g.kode_jam + b.sks 
                ELSE g.kode_jam + (b.sks - 1) 
            END))) AS jam_sekolah, 
            c.nama_pel AS nama_pel, b.sks AS sks, b.semester AS semester, a.kelas AS kelas, 
            JSON_UNQUOTE(JSON_EXTRACT(b.kelas, '$[0].tingkat')) AS tingkat, d.nama AS guru, h.nama_jurusan AS jurusan
            FROM generate_jadwal a 
            LEFT JOIN mengajar b ON a.id_mengajar = b.id 
            LEFT JOIN mata_pelajaran c ON b.id_pel = c.id 
            LEFT JOIN guru d ON b.id_guru = d.id 
            LEFT JOIN hari e ON a.id_hari = e.id 
            LEFT JOIN jam g ON a.id_jam = g.id 
            LEFT JOIN jurusan h ON c.id_jurusan = h.id 
            LEFT JOIN kelas i ON JSON_UNQUOTE(JSON_EXTRACT(b.kelas, '$[*].id_kelas')) = i.id");

            return DataTables::of($jadwal)
                ->make(true);
        }
        $dataMengajar = Mengajar::distinct()->pluck('semester');
        $dataHari = Hari::all();
        $dataJam = Jam::whereNull('jeda')->orWhere('jeda', '!=', 'Istirahat')->get();
        $dataJamJeda = Jam::whereRaw('JSON_CONTAINS(jeda, \'["Jumat"]\')')->get();
        $dataKelas = Kelas::all();
        $dataJurusan = Jurusan::all();
        $user = User::where('nama', Session::get('nama'))->first();
        return view('admin.generate.index', compact('dataMengajar', 'dataHari', 'dataJam', 'dataKelas', 'dataJurusan','user','dataJamJeda'));

        
    }
    
    public function create()
    {
        return view('generate.create');
    }

    //algoritma genetika
    public function generatejadwal(Request $request)
    {
        set_time_limit(0);
        $jenis_semester = $request->input('jenis_semester');
        $jumlah_populasi = intval($request->input('populasi'));
        $crossOver = floatval($request->input('crossover'));
        $mutasi = floatval($request->input('mutasi'));
        $jumlah_generasi = intval($request->input('generasi'));
    
        $genetik = new Genetika(
            $jenis_semester,
            $jumlah_populasi,
            $crossOver,
            $mutasi
        );
    
        $genetik->AmbilData();
        $genetik->Inisialisasi();
        $bestIndividu = null;
        $bestFitness = 0;
    
        for ($i = 0; $i < $jumlah_generasi; $i++) {
            $fitness = $genetik->HitungFitness();
            $genetik->Seleksi($fitness);
            $genetik->StartCrossover();
            $fitnessAfterMutation = $genetik->Mutasi();
            foreach ($fitnessAfterMutation as $j => $fitValueAfterMutation) {
                if ($fitValueAfterMutation == 1) {
                    Generate::truncate();
    
                    $jadwal = $genetik->GetIndividu($j);
    
                    foreach ($jadwal as $data) {
                        $id_mengajar = intval($data[0]);
                        $kode_jam = $data[1];
                        $kode_hari = $data[2];
                        $kelas = $data[3];
                        $hari = Hari::where('kode_hari', $kode_hari)->first();
                        $jam = Jam::where('kode_jam', $kode_jam)->first();
                        Generate::create([
                            'id_mengajar' => $id_mengajar,
                            'id_jam' => $jam->id,
                            'id_hari' => $hari->id,
                            'kelas' => $kelas,
                        ]);
                    }
    
                    $response['status'] = true;
                    $response['bestFitness'] = $fitValueAfterMutation;
                    return response()->json($response);
                } elseif ($fitValueAfterMutation > $bestFitness) {
                    $bestFitness = $fitValueAfterMutation;
                    $bestIndividu = $genetik->GetIndividu($j);
                }
            }
        }
    
        Generate::truncate();
    
        foreach ($bestIndividu as $data) {
            $id_mengajar = intval($data[0]);
            $kode_jam = $data[1];
            $kode_hari = $data[2];
            $kelas = $data[3];
            $hari = Hari::where('kode_hari', $kode_hari)->first();
            $jam = Jam::where('kode_jam', $kode_jam)->first();
            Generate::create([
                'id_mengajar' => $id_mengajar,
                'id_jam' => $jam->id,
                'id_hari' => $hari->id,
                'kelas' => $kelas,
            ]);
        }
    
        $response['status'] = false;
        $response['bestFitness'] = $bestFitness;
        return response()->json($response);
    }
    
        public function simpanJadwal(Request $request)
        {
            try {
                $tahunAkademik = $request->input('tahun_akademik');
                Jadwal::truncate();
        
                $jadwalData = DB::select("SELECT a.id AS id, e.nama_hari AS hari, CONCAT_WS('-', MID(g.range_jam, 1, 5), (SELECT MID(range_jam, 7, 5) FROM jam 
                WHERE kode_jam = (CASE WHEN (SELECT jeda FROM jam WHERE kode_jam = g.kode_jam + (b.sks - 1)) = 'Istirahat'
                OR (SELECT jeda FROM jam WHERE kode_jam = g.kode_jam + 1) = 'Istirahat' THEN g.kode_jam + b.sks  
                ELSE g.kode_jam + (b.sks - 1)
                END))) AS jam_sekolah, c.nama_pel AS nama_pel, b.sks AS sks, b.semester AS semester, a.kelas AS kelas, JSON_UNQUOTE(JSON_EXTRACT(b.kelas, '$[0].tingkat')) AS tingkat,
                d.nama AS guru, h.nama_jurusan AS jurusan FROM generate_jadwal a LEFT JOIN mengajar b ON a.id_mengajar = b.id LEFT JOIN mata_pelajaran c ON b.id_pel = c.id 
                LEFT JOIN guru d ON b.id_guru = d.id LEFT JOIN hari e ON a.id_hari = e.id LEFT JOIN jam g ON a.id_jam = g.id LEFT JOIN jurusan h ON c.id_jurusan = h.id 
                LEFT JOIN kelas i ON JSON_UNQUOTE(JSON_EXTRACT(b.kelas, '$[*].id_kelas')) = i.id;");
                foreach ($jadwalData as $generateData) {                      
                    Jadwal::updateOrCreate(
                        ['guru' => $generateData->guru,
                        'mata_pelajaran' => $generateData->nama_pel,
                        'sks' => $generateData->sks,
                        'kelas' => $generateData->kelas, 
                        'semester' => $generateData->semester,
                        'tahun_akademik' => $tahunAkademik,
                        'jam' => $generateData->jam_sekolah, 
                        'hari' => $generateData->hari,]
                    );
                }
        
                return response()->json(['success' => 'Jadwal Mata Pelajaran Berhasil Disimpan!']);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Gagal Menyimpan Jadwal Mata Pelajaran!'], 500);
            }
        }
        

        public function truncateJadwal()
        {
            Generate::truncate();

            return response()->json(['success' => 'Semua data berhasil dihapus.']);
        }
}
 