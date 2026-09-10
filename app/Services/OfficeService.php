<?php

namespace App\Services;

use App\Models\Office;

class OfficeService
{
    
    public function getPaginatedOffices(int $perPage = 10)
    {
        return Office::latest()->paginate($perPage);
    }

    public function createOffice(array $data)
    {
        $data['created_by'] = auth()->id();
        return Office::create($data);
    }

    public function updateOffice(Office $office, array $data)
    {
        return $office->update($data);
    }

    public function deleteOffice(Office $office)
    {
        return $office->delete();
    }
}
