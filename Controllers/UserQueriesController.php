<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserQueries;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class UserQueriesController extends Controller
{
    public function update(Request $request)
    {
        if ($request->has('action')) {
            $f_data = $request->all();
        
            if ($f_data['action'] == 'all') {
                UserQueries::query()->update(['action' => 1]);
                return redirect()->route('admin.user_queries')->with('success', 'Marked all as read!');
            } else {
                $this->markAsRead($f_data['action']);
                return redirect()->route('admin.user_queries')->with('success', 'Marked as read!');
            }
        }
        
        if ($request->has('delete')) {
            $f_data = $request->all();
        
            if ($f_data['delete'] == 'all') {
                UserQueries::query()->delete();
                return redirect()->route('admin.user_queries')->with('success', 'All messages deleted!');
            } else {
                $this->deleteMessage($f_data['delete']);
                return redirect()->route('admin.user_queries')->with('success', 'Message deleted!');
            }
        }
    }

    public function store(Request $request)
    {   
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $userQuery = UserQueries::create($validatedData);

        Notification::create([
            'admin_id' => Auth::guard('admin')->id(), 
            'admin_message' => 'You have received a new query from user: ' . $userQuery->name, 
            'isRead_admin' => 0, 
        ]);
    
        return redirect()->back()->with('success', 'Message sent successfully!');
    }

    public function markAsRead($queryID)
    {
        UserQueries::where('queryID', $queryID)->update(['action' => 1]);
    }

    public function deleteMessage($queryID)
    {
        UserQueries::where('queryID', $queryID)->delete();
    }

    public function userqueries()
    {
        $userQueries = UserQueries::orderBy('queryID', 'DESC')->get();
        return view('admin.user_queries', compact('userQueries'));
    }
}
