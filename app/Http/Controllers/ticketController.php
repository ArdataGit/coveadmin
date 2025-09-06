<?php

namespace App\Http\Controllers;

use App\Mail\TicketCreatedMail;
use App\Mail\TicketRespondedMail;
use App\Mail\TicketUpdatedMail;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ticketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::latest()->get();
        return view('admin.ticket', compact('tickets')); // Menggunakan tickets.blade.php
    }

    public function data(Request $request)
    {
        $search = $request->query('search');
        $tickets = Ticket::when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
        })->latest()->get();

        return response()->json($tickets);
    }
    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'user_id.required' => 'The user ID is required.',
            'user_id.exists' => 'The selected user does not exist.',
            'title.required' => 'The title is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'description.required' => 'The description is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg.',
            'image.max' => 'The image may not be greater than 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        // Prepare ticket data
        $ticketData = [
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'status' => 'open',
        ];

        try {
            // Handle image upload
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $imageFilename = time() . '_' . $request->file('image')->getClientOriginalName();
                $ticketData['image'] = $imageFilename;
            }

            // Create the ticket
            $ticket = Ticket::create($ticketData);

            Mail::to($ticket->user->email)->send(new TicketCreatedMail($ticket));
            // Move the image to a user-specific directory
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $file = $request->file('image');
                $destinationPath = public_path("img/tickets/{$ticket->user_id}");
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $imageFilename);
                if (!file_exists("{$destinationPath}/{$imageFilename}")) {
                    throw new \Exception('Failed to save ticket image to permanent location.');
                }
            }

            return response()->json(['success' => true, 'message' => 'Tiket pengaduan berhasil dibuat!']);
        } catch (\Exception $e) {
            // Rollback: Delete the ticket and image if created
            if (isset($ticket) && $ticket->exists) {
                $imagePath = public_path("img/tickets/{$ticket->user_id}/{$imageFilename}");
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $ticket->delete();
            }
            return response()->json(['success' => false, 'message' => 'Failed to create ticket: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // Pastikan hanya admin atau pengguna dengan user_id yang sama yang dapat mengedit
        if (!Auth::user()->is_admin && $request->user_id != $ticket->user_id) {
            return redirect()->route('tickets.index')->with('error', 'Anda tidak memiliki izin untuk mengedit tiket ini.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $ticketData = [
            'user_id' => $request->user_id, // Update user_id dari input
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ];

        // Menangani unggahan gambar baru ke public/img
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($ticket->image) {
                Storage::disk('public_img')->delete($ticket->image);
            }
            $imagePath = $request->file('image')->store('img', 'public_img');
            $ticketData['image'] = $imagePath;
        }

        $ticket->update($ticketData);

        
        Mail::to($ticket->user->email)->send(new TicketUpdatedMail($ticket));

        return redirect()->route('tickets.index')->with('success', 'Tiket pengaduan berhasil diperbarui!');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(Ticket $ticket, Request $request)
    {
        try {
            // Delete image if exists
            if ($ticket->image) {
                $imagePath = public_path("img/tickets/{$ticket->user_id}/{$ticket->id}/{$ticket->image}");
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $ticket->delete();

            return response()->json(['success' => true, 'message' => 'Tiket pengaduan berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete ticket: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle admin response for a ticket.
     */
    public function adminResponse(Request $request, Ticket $ticket)
    {
        // Check if the user is an admin
        // if (!Auth::user()->is_admin) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Anda tidak memiliki izin untuk memberikan respon.'
        //     ], 403);
        // }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'admin_response' => 'required|string',
            'status' => 'required|in:open,in_progress,resolved,closed',
        ], [
            'admin_response.required' => 'Admin response is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status value.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Update the ticket with admin response and status
            $ticket->update([
                'admin_response' => $request->admin_response,
                'status' => $request->status,
            ]);

            
            Mail::to($ticket->user->email)->send(new TicketRespondedMail($ticket));

            return response()->json([
                'success' => true,
                'message' => 'Respon admin berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save response: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all tickets by the specified user_id.
     */
    public function getTicketsByUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', // Validasi user_id dari input
        ]);

        $tickets = Ticket::where('user_id', $request->user_id)->latest()->get();

        return view('tickets.index', compact('tickets'));
    }
  
  /**
   * Get all tickets by the specified user_id.
   */
  public function getTicketsByUserJson(Request $request)
  {
      $request->validate([
          'user_id' => 'required|exists:users,id', // Validasi user_id dari input
      ]);

      $tickets = Ticket::where('user_id', $request->user_id)->latest()->get();

      return response()->json([
          'success' => true,
          'data' => $tickets,
          'meta' => [
              'count' => $tickets->count(),
              'timestamp' => now()->toIso8601String(),
          ]
      ]);
  }
}