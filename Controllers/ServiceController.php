<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Service;
use App\Models\Car;
use App\Models\OperatorCar;
use App\Models\OperatorService;

class ServiceController extends Controller
{
    public function service(Request $request)
    {
        $services = Service::all();
        $cars = Car::all();
    
        return view('admin.services', ['services' => $services, 'cars' => $cars]);
    }

    public function getService()
    {
        $services = Service::all();
        return response()->json($services);
    }

    public function addService(Request $request)
    {
        $this->validate($request, [
            'serviceType' => 'required',
            'serviceIcon' => 'required|image|mimes:svg|max:1024',
            'servicePrice' => 'required|numeric|min:0',
            'sDescription' => 'required',
        ]);

        $img_r = $this->uploadIcon($request->file('serviceIcon'), 'service');

        if (in_array($img_r, ['1', '10', '100'])) {
            return response()->json($img_r);
        } else {
            $service = new Service;
            $service->serviceIcon = $img_r;
            $service->serviceType = $request->input('serviceType');
            $service->servicePrice = $request->input('servicePrice');
            $service->sDescription = $request->input('sDescription');
            $service->save();

            return response()->json('Service added successfully');
        }
    }

    public function removeService(Request $request)
    {
        $this->validate($request, [
            'removeService' => 'required',
        ]);

        $serviceId = $request->input('removeService');
        $operatorServiceCount = OperatorService::where('service_id', $serviceId)->count();

        if ($operatorServiceCount == 0) {
            $service = Service::find($serviceId);

            if ($service) {
                $this->deleteIcon($service->serviceIcon, 'service');
                $service->delete();
                return response()->json('Service removed successfully');
            } else {
                return response()->json("Error deleting service from the database.");
            }
        } else {
            return response()->json('operator_added');
        }
    }

    public function getCar()
    {
        $cars = Car::all();
        return response()->json($cars);
    }

    public function addCar(Request $request)
    {
        $this->validate($request, [
            'carName' => 'required',
            'carModel' => 'required',
            'carType' => 'required',
        ]);
    
        $car = new Car;
        $car->carName = $request->input('carName');
        $car->carModel = $request->input('carModel');
        $car->carType = $request->input('carType');
        $car->save();
    
        return response()->json(['message' => 'Car added successfully']);
    }

    public function removeCar(Request $request)
    {
        $this->validate($request, [
            'removeCar' => 'required',
        ]);
    
        $carId = $request->input('removeCar');
        $operatorCarCount = OperatorCar::where('car_id', $carId)->count();
    
        if ($operatorCarCount == 0) {
            $car = Car::find($carId);
    
            if ($car) {
                $car->delete();
                return response()->json(['message' => 'Car removed successfully']);
            } else {
                return response()->json(['error' => 'Error deleting car from the database.']);
            }
        } else {
            return response()->json(['error' => 'operator_added']);
        }
    }

    private function uploadIcon($image, $folder)
    {
        $validMimeTypes = ['image/svg+xml'];
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

    private function deleteIcon($image, $folder)
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
