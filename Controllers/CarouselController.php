<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carousel;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class CarouselController extends Controller
{
    public function carousel(Request $request)
    {
        $carouselImages = Carousel::all();
        $services = Service::all(); 
        return view('admin.assets', compact('services', 'carouselImages'));
    }

    public function getCarousel()
    {
        $carouselImages = Carousel::all();
        return response()->json($carouselImages);
    }

    public function addImage(Request $request)
    {
        $image = $request->file('cPicture');
        $img_r = $this->uploadImg($image, 'carousel');

        if ($img_r == '1') {
            return response()->json(['error' => 'Invalid file format: only JPEG or PNG are allowed']);
        } elseif ($img_r == '10') {
            return response()->json(['error' => 'Image should be less than 2MB']);
        } elseif ($img_r == '100') {
            return response()->json(['error' => 'Upload failed']);
        } else {
            $carousel = new Carousel();
            $carousel->cPicture = $img_r;
            $carousel->save();

            return response()->json(['image' => asset("storage/images/carousel/$img_r")]);
        }
    }

    public function removeImage(Request $request)
    {
        $entryID = $request->input('removeImage');
        $carousel = Carousel::find($entryID);
    
        if (!$carousel) {
            return response()->json(['error' => 'Image not found']);
        }
    
        $imagePath = '/images/carousel/' . $carousel->cPicture;
    
        try {
            if (Storage::disk('public')->exists($imagePath)) {
                $deleteResult = $this->deleteImg($carousel->cPicture, 'carousel');
    
                if ($deleteResult) {
                    $carousel->delete();
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['error' => 'Failed to delete image file']);
                }
            } else {
                return response()->json(['error' => 'Image file not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Exception: ' . $e->getMessage()]);
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
