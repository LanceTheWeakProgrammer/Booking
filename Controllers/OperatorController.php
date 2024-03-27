<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Operator; 
use App\Models\OperatorService;
use App\Models\OperatorCar;
use App\Models\Car;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class OperatorController extends Controller
{
    public function operators()
    {
        $service = Service::all();
        $car = Car::all();
        return view('admin.operators', compact('service','car'));
    }

    public function addOperator(Request $request)
    {
        $car = json_decode($request->input('car'));
        $service = json_decode($request->input('service'));
        $operatorData = $request->except(['car', 'service', 'addOperator']);
    
        $imgResult = $this->uploadImg($request->file('operatorImg'), 'operators');
    
        if ($imgResult == '1') {
            return '1';
        } elseif ($imgResult == '10') {
            return '10';
        } elseif ($imgResult == '100') {
            return '100';
        }
    
        $operatorData['operatorImg'] = $imgResult;
    
        $operatorData['serialNumber'] = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    
        try {
            $operator = Operator::create($operatorData);
    
            $operator->services()->attach($service);
    
            $operator->cars()->attach($car);
    
            return '3'; 
        } catch (\Exception $e) {
            return '0'; 
        }
    }

    public function toggleStatus(Request $request) 
    {
        $id = $request->input('toggleStatus');
        $value = $request->input('val');
    
        $removedValue = ($value == 0) ? 0 : 1;
    
        $operator = Operator::find($id);
        
        if ($operator) {
            $operator->status = $value;
            $operator->removed = $removedValue;
            
            if ($operator->save()) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        } else {
            return response()->json(['success' => false]);
        }
    }    

    public function getAllOperators() 
    {
        $operators = Operator::all();
        $i = 1;
    
        $data = "";
    
        foreach ($operators as $operator) {
            $status = '';
    
            if ($operator->status == 1) {
                $status = "<button onclick='toggle_status($operator->operatorID, 0)' class='btn custom-bg btn-sm text-white shadow-none'>Active</button>";
            } elseif ($operator->status == 2) {
                $status = "<button class='btn btn-warning text-white btn-sm shadow-none' disabled>Busy</button>";
            } else {
                $status = "<button onclick='toggle_status($operator->operatorID, 1)' class='btn btn-secondary btn-sm shadow-none'>Inactive</button>";
            }
    
            $data .= "
            <tr class='align-middle'>
                <td width='2%'>$i</td>
                <td>$operator->operatorName</td>
                <td>$operator->opAddress</td>
                <td>$operator->operatorTel</td>
                <td>$operator->operatorEmail</td>
                <td>$operator->jobAge</td>
                <td>₱$operator->hRate per hr.</td>
                <td class='text-center'>$status</td>
                <td width='12%'>
                    <button type='button' onclick='edit_details($operator->operatorID)' class='btn custom-bg shadow-none btn-sm me-2' data-bs-toggle='modal' data-bs-target='#edit-operator'>
                        <i class='bi bi-pencil-square text-white'></i>
                    </button>
                    <button type='button' onclick='view_details($operator->operatorID)' class='btn btn-success shadow-none btn-sm me-2' data-bs-toggle='modal' data-bs-target='#view-operator'>
                        <i class='bi bi-person-circle'></i>
                    </button>
                    <button type='button' onclick='delete_operator($operator->operatorID)' class='btn btn-danger shadow-none btn-sm'>
                        <i class='bi bi-trash'></i>
                    </button>
                </td>
            </tr>
            ";
    
            $i++;
        }
        
        return $data;
    }

    public function getOperator(Request $request)
    {
        $f_data = $request->all();

        $operatorData = DB::table('operator')->where('operatorID', $f_data['getOperator'])->first();
        $car = DB::table('operator_car')->where('operator_id', $f_data['getOperator'])->pluck('car_id')->toArray();
        $service = DB::table('operator_service')->where('operator_id', $f_data['getOperator'])->pluck('service_id')->toArray();

        $data = ["operatorData" => $operatorData, "car" => $car, "service" => $service];

        return json_encode($data);
    }
    
    public function editOperator(Request $request)
    {
        $validatedData = $request->validate([
            'operatorID' => 'required|exists:operator,operatorID',
            'operatorImg' => 'nullable|image|max:1024',
            'operatorName' => 'required|string',
            'opAddress' => 'required|string',
            'operatorTel' => 'required|string',
            'operatorEmail' => 'required|email',
            'jobAge' => 'required|integer|min:0',
            'opDescription' => 'nullable|string',
            'hRate' => 'required|numeric|min:0',
        ]);

        $operatorId = $validatedData['operatorID'];
        $operator = Operator::find($operatorId);

        if (!$operator) {
            return response()->json(0);
        }

        $operator->update([
            'operatorName' => $validatedData['operatorName'],
            'opAddress' => $validatedData['opAddress'],
            'operatorTel' => $validatedData['operatorTel'],
            'operatorEmail' => $validatedData['operatorEmail'],
            'jobAge' => $validatedData['jobAge'],
            'opDescription' => $validatedData['opDescription'],
            'hRate' => $validatedData['hRate'],
        ]);

        if ($request->hasFile('operatorImg')) {
            if ($operator->operatorImg) {
                $this->deleteImg($operator->operatorImg, 'operators');
            }
            $imgPath = $this->uploadImg($request->file('operatorImg'), 'operators');
            if ($imgPath === false) {
                return response()->json(100); 
            }
            $operator->operatorImg = $imgPath;
        }

        $car = json_decode($request->input('car'));
        $service = json_decode($request->input('service'));   
        $operator->cars()->sync($car);
        $operator->services()->sync($service);        

        $operator->save();

        return response()->json(1);
    }

    public function deleteOperator(Request $request)
    {
        $request->validate([
            'operator_id' => 'required|numeric',
        ]);
    
        $f_data = $request->all();
        $flag = 0;
    
        if (isset($f_data['operator_id'])) {
            $operatorData = Operator::select('status', 'removed')->where('operatorID', $f_data['operator_id'])->first();
    
            if ($operatorData) {
                $operatorStatus = $operatorData->status;
                $operatorRemoved = $operatorData->removed;
    
                if ($operatorStatus == 0 && $operatorRemoved == 0) {
                    $imageData = Operator::select('operatorImg')->where('operatorID', $f_data['operator_id'])->first();
    
                    if ($imageData) {
                        $operatorImage = $imageData->operatorImg;
    
                        if ($operatorImage) {
                            $this->deleteImg($operatorImage, 'operators');
                        }
                    }

                    Operator::find($f_data['operator_id'])->services()->detach();
                    Operator::find($f_data['operator_id'])->cars()->detach();

                    Operator::destroy($f_data['operator_id']);
    
                    $flag = 1;
                } else {
                    return response()->json(['error' => true, 'message' => 'Cannot delete an active operator!']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'Error fetching operator data']);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'Missing operator ID']);
        }
    
        if ($flag) {
            return response()->json(['success' => true, 'message' => 'Operator deleted successfully']);
        } else {
            return response()->json(['error' => false, 'message' => 'Failed to delete operator']);
        }
    }
    
    private function uploadImg($image, $folder)
    {
        $validMimeTypes = ['image/jpeg', 'image/png'];
        $ext = $image->getClientOriginalExtension();
        $imgMimeType = $image->getClientMimeType();

        if (!in_array($imgMimeType, $validMimeTypes)) {
            return '1';
        }

        if ($image->getSize() > 2 * 1024 * 1024) {
            return '10';
        }

        $imageName = 'IMG_' . now()->format('Ymd_His') . '.' . $ext;

        try {
            $image->storeAs("public/images/$folder", $imageName);
            return $imageName;
        } catch (\Exception $e) {
            return '100';
        }
    }

    private function deleteImg($image, $folder)
    {
        $filePath = "images/$folder/$image"; 
        try {
            $result = Storage::disk('public')->delete($filePath);
            \Log::info("File deleted successfully: $filePath");
            return $result;
        } catch (\Exception $e) {
            \Log::error("Error deleting file: $filePath - " . $e->getMessage());
            return false;
        }
    }
}

