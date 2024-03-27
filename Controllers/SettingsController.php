<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\ContactInfo;
use App\Models\TeamInfo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class SettingsController extends Controller
{
    public function settings(Request $request)
    {
        $teamMembers = TeamInfo::all();
    
        return view('admin.settings')->with('data', $teamMembers);
    }
    
    public function getGeneral()
    {
        $setting = Settings::where('siteID', 1)->first();
        return response()->json($setting);
    }

    public function updateGeneral(Request $request)
    {
        try {
            $data = $request->only(['siteTitle', 'siteAbout']);
            $setting = Settings::where('siteID', 1)->first(); 

            if ($setting->siteTitle != $data['siteTitle'] || $setting->siteAbout != $data['siteAbout']) {
                Settings::where('siteID', 1)->update($data);
                return response()->json(['success' => true]);
            } else {
                return response()->json(['error' => 'No changes made']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    
    }

    public function updateShutDown(Request $request)
    {
        $shutDownValue = $request->input('updateShutDown') == 0 ? 1 : 0;
        Settings::where('siteID', 1)->update(['shutdown' => $shutDownValue]);
        return response()->json(['success' => true]);
    }

    public function getContacts()
    {
        $contactInfo = ContactInfo::where('conID', 1)->first();
        return response()->json($contactInfo);
    }    

    public function updateContacts(Request $request)
    {
        try {
            $data = $request->only(['address', 'gmap', 'tel1', 'tel2', 'email', 'twt', 'fb', 'ig', 'iframe']);
            $contactInfo = ContactInfo::where('conID', 1)->first();
    
            if (!$contactInfo) {
                return response()->json(['error' => 'Contact information not found']);
            }
    
            $contactInfo->fill($data);
    
            if ($contactInfo->isDirty()) {
                $contactInfo->update();
            } else {
                return response()->json(['success' => 0, 'message' => 'No changes made']);
            }
    
            $updatedContactInfo = ContactInfo::where('conID', 1)->first();
    
            if ($updatedContactInfo) {
                return response()->json(['success' => true, 'data' => $updatedContactInfo]);
            } else {
                return response()->json(['error' => 'Failed to fetch updated contact information']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getMembers()
    {
        $teamMembers = TeamInfo::all();
    
        return response()->json($teamMembers);
    }
    
    public function addMember(Request $request) 
    {
        $imageName = $this->uploadImg($request->file('mPicture'), 'about');
    
        if ($imageName === '1') {
            return response()->json(['error' => 'Invalid file format: only JPEG or PNG are allowed']);
        } elseif ($imageName === '10') {
            return response()->json(['error' => 'Image should be less than 2MB']);
        } elseif ($imageName === '100') {
            return response()->json(['error' => 'Upload failed']);
        } else {
            $teamMember = new TeamInfo;
            $teamMember->mName = $request->input('mName');
            $teamMember->mTitle = $request->input('mTitle');
            $teamMember->mPicture = $imageName;
            $teamMember->save();
    
            return response()->json(['image' => asset("storage/images/about/$imageName")]);
        }
    }
    
    public function removeMember(Request $request)
    {
        $teamMemberId = $request->input('removeMember');
        $teamMember = TeamInfo::find($teamMemberId);
    
        if (!$teamMember) {
            return response()->json(['error' => 'Member not found']);
        }
    
        $imageName = $teamMember->mPicture;
        $folder = 'about';
        $imagePath = "images/$folder/$imageName";
    
        try {
            if (Storage::disk('public')->exists($imagePath)) {
                $deleteResult = $this->deleteImg($imageName, $folder);
    
                if ($deleteResult) {
                    $teamMember->delete();
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['error' => 'Failed to delete member image file']);
                }
            } else {
                return response()->json(['error' => 'Member image file not found']);
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
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }
}


