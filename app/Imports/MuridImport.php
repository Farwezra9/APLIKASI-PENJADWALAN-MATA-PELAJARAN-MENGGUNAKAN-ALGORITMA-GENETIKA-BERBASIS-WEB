<?php
namespace App\Imports;

use App\Models\Kelas;
use App\Models\Murid;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MuridImport implements ToModel, WithHeadingRow
{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
       
        // Cari kelas berdasarkan nama_kelas
        $kelas = Kelas::where('nama_kelas', $row['kelas'])->first();

        // Create a new User record for the student
        $user = User::create([
            'nama' => $row['nama'],
            'email' => $row['email'],
            'role' => 'murid',
            'password' => Hash::make($row['nisn']), // Set NISN as the default password
        ]);

        // Handle the default profile image
        $defaultImagePath = public_path('img/student.png');
        if (file_exists($defaultImagePath)) {
            $defaultImageName = 'student.png';
            $uniqueImageName = uniqid() . '_' . $defaultImageName;
            $storagePath = 'storage/' . $uniqueImageName;
            copy($defaultImagePath, public_path($storagePath));
            $user->update([
                'profile_image' => $storagePath,
            ]);
        }

        // Create or update the student record (Murid)
        return Murid::updateOrCreate(
            ['nisn' => $row['nisn']],
            [
                'nama' => $row['nama'],
                'alamat' => $row['alamat'],
                'email' => $row['email'],
                'notelp' => $row['no_telp'],
                'id_kelas' => $kelas->id ?? null, 
                'id_user' => $user->id,  
            ]
        );
    }
}
