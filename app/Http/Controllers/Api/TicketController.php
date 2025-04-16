<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        try {
            $tickets = Ticket::with(['user'])
                ->when($request->status, function($query) use ($request) {
                    return $query->where('status', $request->status);
                })
                ->when($request->order_id, function($query) use ($request) {
                    return $query->where('order_id', $request->order_id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'status' => 'success',
                'data' => $tickets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch tickets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|string',
                'order_datetime' => 'required|date',
                'order_amount' => 'required|numeric',
                'order_status' => 'required|string',
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
               // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $imageUrl = null;
            if ($request->hasFile('image_url')) {
                $image = $request->file('image_url');
                $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
                $path = 'tickets/' . date('Y/m');
                $imageUrl = Storage::disk('s3')->putFileAs($path, $image, $filename,'public');
                $imageUrl = Storage::disk('s3')->url($imageUrl);
            }

            $ticket = Ticket::create([
                'user_id' => Auth::id(),
                'order_id' => $request->order_id,
                'order_datetime' => $request->order_datetime,
                'order_amount' => $request->order_amount,
                'order_status' => $request->order_status,
                'status' => 'opened',
                'subject' => $request->subject,
                'description' => $request->description,
                'image_url' => $imageUrl
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Ticket created successfully',
                'data' => $ticket
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create ticket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $ticket = Ticket::with(['user'])->findOrFail($id);

            if (Auth::id() !== $ticket->user_id && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ], 403);
            }

            return response()->json([
                'status' => 'success',
                'data' => $ticket
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch ticket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);

            if (Auth::id() !== $ticket->user_id && !Auth::user()->hasRole('admin')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'subject' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->only(['subject', 'description']);

            if ($request->hasFile('image_url')) {
                // Delete old image if exists
                if ($ticket->image_url) {
                    $oldImagePath = str_replace(Storage::disk('s3')->url(''), '', $ticket->image_url);
                    Storage::disk('s3')->delete($oldImagePath);
                }

                $image = $request->file('image');
                $filename = Str::random(40) . '.' . $image->getClientOriginalExtension();
                $path = 'tickets/' . date('Y/m');
                $imageUrl = Storage::disk('s3')->putFileAs($path, $image, $filename,  'public' );
                // $file->storeAs('website/Categories/images', $fileName, [
                //     'disk' => 's3',
                //     'visibility' => 'public'
                // ]);      


                $data['image_url'] = Storage::disk('s3')->url($imageUrl);
            }

            $ticket->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Ticket updated successfully',
                'data' => $ticket
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update ticket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);

            // if (Auth::id() !== $ticket->user_id && !Auth::user()->hasRole('admin')) {
            //     return response()->json([
            //         'status' => 'error',
            //         'message' => 'Unauthorized access'
            //     ], 403);
            // }

            // Delete image from S3 if exists
            // if ($ticket->image_url && !empty($ticket->image_url) ) {
            //     $imagePath = str_replace(Storage::disk('s3')->url(''), '', $ticket->image_url);
            //     Storage::disk('s3')->delete($imagePath);
            // }

            $ticket->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Ticket deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete ticket',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);

            if (!Auth::user()->hasRole('admin')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:inactive,opened,approved,closed'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ticket->status = $request->status;
            $ticket->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Ticket status updated successfully',
                'data' => $ticket
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update ticket status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function userTickets(Request $request)
    {
        try {
            if($request->filled('status')) {
                $status = strtolower($request->status);
            }
        
            $tickets = Ticket::where('user_id', Auth::id())
                ->when($request->filled('status'), function($query) use ($request) {
                    $status = strtolower($request->status);
                    return $query->whereRaw('LOWER(status) = ?', [$status]);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        
            return response()->json([
                'status' => 'success',
                'data' => $tickets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch user tickets',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
