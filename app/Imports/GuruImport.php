<?php
namespace App\Imports;


use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GuruImport implements ToModel, WithHeadingRow
{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Create a new User record for the student
        $user = User::create([
            'nama' => $row['nama'],
            'email' => $row['email'],
            'role' => 'guru',
            'password' => Hash::make($row['nip']), // Set NISN as the default password
        ]);

        // Handle the default profile image
        $defaultImagePath = public_path('img/teacher.png');
        if (file_exists($defaultImagePath)) {
            $defaultImageName = 'teacher.png';
            $uniqueImageName = uniqid() . '_' . $defaultImageName;
            $storagePath = 'storage/' . $uniqueImageName;
            copy($defaultImagePath, public_path($storagePath));
            $user->update([
                'profile_image' => $storagePath,
            ]);
        }

        Guru::create([
            'nip' => $row['nip'],
            'nama' => $row['nama'],
            'pangkat' => $row['pangkat'],
            'alamat' => $row['alamat'],
            'email' => $row['email'],
            'notelp' => $row['no_telp'],
            'id_user' => $user->id,
        ]);
    }
}
