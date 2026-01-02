<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use App\Models\Pelajaran;
use App\Models\User;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use DataTables;

class MengajarController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $mengajar = Mengajar::with(['pelajaran', 'guru'])->get();
            $groupedMengajar = $mengajar->groupBy('guru.nama');
            $groupedMengajar = $groupedMengajar->map(function ($group) {
                $totalSks = 0;
        
                // Iterasi melalui setiap item dalam kelompok
                foreach ($group as $item) {
                    // Ekstrak dan decode data JSON untuk kelas
                    $kelas = json_decode($item->kelas, true);
        
                    // Hitung total SKS untuk setiap data
                    $totalSks += $item->sks * count($kelas);
                }
        
                // Perbarui'sks' untuk item pertama dalam kelompok
                $firstItem = $group->first();
                $firstItem->sks = $totalSks;
        
                return $firstItem;
            });
            return DataTables::of($groupedMengajar)
                ->addColumn('action', function ($mengajar) {
                    $button = '<button type="button" class="lihat btn icon icon-left btn-info" id="'.$mengajar->id_guru.'"><i class="bi bi-eye"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" class="deleteAll btn icon icon-left btn-danger" id="'.$mengajar->id_guru.'"><i class="bi bi-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action']) 
                ->addIndexColumn() 
                ->make(true); 
        }
        $dataGuru = Guru::all();
        $dataPelajaran = Pelajaran::select('*', \DB::raw('(SELECT nama_jurusan FROM jurusan WHERE id = id_jurusan) as jurusan'))->orderBy('id_jurusan')->get();
        $dataKelas = Kelas::all();
        $dataMengajar = Mengajar::all();
        return view('admin.mengajar.index', compact('dataGuru', 'dataPelajaran','dataKelas','dataMengajar'));
    }

    public function getGuru($id)
    {
        if (request()->ajax()) {
            $mengajar = Mengajar::with(['pelajaran', 'guru'])
                ->where('id_guru', $id)
                ->get();
    
            return DataTables::of($mengajar)
                ->addColumn('action', function ($mengajar) {
                    $button = '<button type="button" class="edit btn icon icon-left btn-success" id="'.$mengajar->id.'"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '&nbsp;&nbsp;';
                    $button .= '<button type="button" class="delete btn icon icon-left btn-danger" id="'.$mengajar->id.'"><i class="bi bi-trash"></i></button>';
                    return $button;
                })
                ->addColumn('kelas', function ($row) {
                    $kelas = json_decode($row->kelas, true);
                    return implode(', ', array_column($kelas, 'kelas'));
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
    }
    
    public function create()
    {
        return view('mengajar.create');
    }
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'data' => 'required|array',
            'data.*.name' => 'required|string',
            'data.*.value' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
    
        $data = collect($request->input('data'))->pluck('value', 'name');
        $datakelas = json_decode($data['kelas'], true); 
        $semester = $data['semester'];
        $kelasTerlampaui = [];
        foreach ($datakelas as $kelas) {
            $tingkat = $kelas['tingkat'];
            $kelasId = $kelas['id_kelas'];
            $kelasNama = $kelas['kelas'];
        // Menghitung total SKS untuk kelas saat ini
        $dataSKS = DB::select("SELECT SUM(sks) AS total_sks FROM mengajar WHERE JSON_CONTAINS(kelas, JSON_OBJECT('id_kelas', ?)) AND semester = ?",[$kelasId, $semester]);
        $totalSKS = $dataSKS[0]->total_sks;
            $sksLimit = ($tingkat == 10) ? 49 : 51;
            if ($totalSKS >= $sksLimit || $totalSKS + $data['sks'] >= $sksLimit) {
                $kelasTerlampaui[] = "$kelasNama total SKS saat ini : $totalSKS";
            }
        }
        if (!empty($kelasTerlampaui)) {
            $kelasList = implode(', ', $kelasTerlampaui);
            return response()->json(['error' => "SKS yang akan ditambahkan untuk kelas berikut pada semester $semester telah melebihi batas : $kelasList."], 400);
        }
    // Masukkan logika untuk input di sini
    switch ($data['sks']) {
        case 4:
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 2,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);

            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 2,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            break;
        case 5:
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);

            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 2,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            break;
        case 6:
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);

            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            break;
        case 7:
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 2,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);

            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 2,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            break;
        case 8:
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 2,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            break;
        case 9:
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            break;
        case 12:
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            break;
        case 13:
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 2,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);

            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 2,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => 3,
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            break;
        default:
            Mengajar::create([
                'id_guru' => $data['guru'],
                'id_pel' => $data['mapel'],
                'sks' => $data['sks'],
                'semester' => $data['semester'],
                'kelas' => $data['kelas'],
            ]);
            break;
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => 'Data mengajar berhasil ditambahkan.']);
        } else {
            return redirect()->back()->with('success', 'Data mengajar berhasil ditambahkan.');
        }
    }
    public function update(Request $request, $id)
    {
       $validator = \Validator::make($request->all(), [
            'data' => 'required|array',
            'data.*.name' => 'required|string',
            'data.*.value' => 'required|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        $data = collect($request->input('data'))->pluck('value', 'name');
        $mengajar = Mengajar::findOrFail($id);
        $datakelas = json_decode($data['kelas'], true);
        $semester = $data['semester'];
        $kelasTerlampaui = [];
    
        foreach ($datakelas as $kelas) {
            $tingkat = $kelas['tingkat'];
            $kelasId = $kelas['id_kelas'];
            $kelasNama = $kelas['kelas'];
    
            $dataSKS = DB::select(
                "SELECT SUM(sks) AS total_sks FROM mengajar WHERE JSON_CONTAINS(kelas, JSON_OBJECT('id_kelas', ?)) AND semester = ?",
                [$kelasId, $semester]
            );
            $totalSKS = $dataSKS[0]->total_sks;
            $sksLimit = ($tingkat == 10) ? 49 : 51;
    
            if ($totalSKS >= $sksLimit || $totalSKS + $data['sks'] - $mengajar->sks >= $sksLimit) {
                $kelasTerlampaui[] = "$kelasNama (Total SKS saat ini: $totalSKS)";
            }
        }
    
        if (!empty($kelasTerlampaui)) {
            $kelasList = implode(', ', $kelasTerlampaui);
            return response()->json(['error' => "Total SKS untuk kelas berikut pada semester $semester telah melebihi batas: $kelasList."], 400);
        }
        $mengajar = Mengajar::findOrFail($id);
        $mengajar->update([
            'id_guru' => $data['guru'],
            'id_pel' => $data['mapel'],
            'sks' => $data['sks'],
            'semester' => $data['semester'],
            'kelas' => $data['kelas'],
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => 'Data megajar berhasil diperbarui.']);
        } else {
            return redirect()->back()->with('success', 'Data mengajar berhasil diperbarui.');
        }
    }

    public function edit($id)
    {
        $mengajar = Mengajar::findOrFail($id);
        return response()->json($mengajar);
    }
    public function getKelas($id_pel)
    {
        $data_kelas = Kelas::join('mata_pelajaran', 'kelas.id_jurusan', '=', 'mata_pelajaran.id_jurusan')
            ->where('mata_pelajaran.id', '=', $id_pel)
            ->get(['kelas.*']);
    
        // Return data kelas
        return response()->json($data_kelas);
    }
    
    public function destroy($id)
    {
        $mengajar = Mengajar::findOrFail($id);
        $mengajar->delete();

        return response()->json(['success' => 'Data mengajar berhasil dihapus.']);
    }
    public function deleteAll($id)
    {
        // Use where to find all records with the specified id_guru
        Mengajar::where('id_guru', $id)->delete();
    
        return response()->json(['success' => 'Data mengajar berhasil dihapus semua.']);
    }
    
    
}
