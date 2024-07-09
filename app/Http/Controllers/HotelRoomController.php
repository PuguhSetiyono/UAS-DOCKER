<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HotelRoom;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class HotelRoomController extends Controller
{
    public function index()
    {
        return HotelRoom::all();
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'room_name' => 'required|string|max:255',
                'room_type' => 'required|string|max:255',
                'price' => 'required|numeric',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024',
            ]);

            if ($request->hasFile('photo')) {
                $uploadedFile = $request->file('photo');

                if ($uploadedFile->isValid()) {
                    $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
                    $filePath = $uploadedFile->storeAs('public/img', $fileName);
                    
                    $hotelRoom = HotelRoom::create([
                        'room_name' => $validatedData['room_name'],
                        'room_type' => $validatedData['room_type'],
                        'price' => $validatedData['price'],
                        'photo' => $filePath,
                    ]);

                    return response()->json(['message' => 'Hotel berhasil ditambahkan', 'hotel_room' => $hotelRoom], 201);
                } else {
                    return response()->json(['error' => 'File tidak valid'], 400);
                }
            } else {
                return response()->json(['error' => 'Masukkan foto'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        return HotelRoom::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        try {
            $hotelRoom = HotelRoom::findOrFail($id);

            $validatedData = $request->validate([
                'room_name' => 'sometimes|required|string|max:255',
                'room_type' => 'sometimes|required|string|max:255',
                'price' => 'sometimes|required|numeric',
                'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            ]);

            // Handle file upload
            if ($request->hasFile('photo')) {
                $uploadedFile = $request->file('photo');

                if ($uploadedFile->isValid()) {
                    // Delete old photo file if exists
                    if ($hotelRoom->photo) {
                        Storage::delete($hotelRoom->photo);
                    }

                    // Generate unique file name
                    $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
                    // Store file in public/img directory
                    $filePath = $uploadedFile->storeAs('public/img', $fileName);

                    $hotelRoom->photo = $filePath;
                } else {
                    return response()->json(['error' => 'Invalid photo file'], 400);
                }
            }

            // Update fields if provided in request
            if (isset($validatedData['room_name'])) {
                $hotelRoom->room_name = $validatedData['room_name'];
            }
            if (isset($validatedData['room_type'])) {
                $hotelRoom->room_type = $validatedData['room_type'];
            }
            if (isset($validatedData['price'])) {
                $hotelRoom->price = $validatedData['price'];
            }

            $hotelRoom->save();

            return response()->json(['message' => 'Hotel room updated successfully', 'hotel_room' => $hotelRoom], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    
    public function destroy($id)
    {
        try {
            $hotelRoom = HotelRoom::findOrFail($id);
            
            // Hapus file foto jika ada
            if ($hotelRoom->photo) {
                Storage::delete($hotelRoom->photo);
            }

            $hotelRoom->delete();

            return response()->json(['message' => 'Hotel berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
