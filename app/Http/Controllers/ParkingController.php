<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParkingBlock;
use App\Models\ParkingSlot;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API Parkir",
 *      description="Dokumentasi API Parkir",
 * )
 */ 
class ParkingController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/parking-blocks",
     *      operationId="getAvailableBlocks",
     *      tags={"Parking"},
     *      summary="Mengecek ketersediaan blok parkir dan slot",
     *      @OA\Response(response="200", description="Daftar blok parkir yang tersedia"),
     * )
     */
    public function getAvailableBlocks()
    {
        $availableBlocks = ParkingBlock::whereHas('slots', function ($query) {
            $query->where('is_occupied', false);
        })->get();

        return response()->json($availableBlocks);
    }

    /**
     * @OA\Post(
     *      path="/api/parking",
     *      operationId="parkVehicle",
     *      tags={"Parking"},
     *      summary="Kendaraan parkir di slot parkir pada blok tertentu",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"block_id"},
     *              @OA\Property(property="block_id", type="integer", description="ID blok parkir")
     *          )
     *      ),
     *      @OA\Response(response="200", description="Kendaraan berhasil parkir"),
     *      @OA\Response(response="422", description="Semua slot sudah terisi"),
     * )
     */
    public function parkVehicle(Request $request)
    {
        $data = $request->validate([
            'block_id' => 'required|exists:parking_blocks,id',
        ]);

        $slot = ParkingSlot::where('parking_block_id', $data['block_id'])
            ->where('is_occupied', false)
            ->first();

        if (!$slot) {
            return response()->json(['message' => 'Semua slot sudah terisi'], 422);
        }

        $slot->update(['is_occupied' => true]);

        return response()->json(['message' => 'Kendaraan berhasil parkir']);
    }

    /**
     * @OA\Delete(
     *      path="/api/parking/{blockId}/{slotId}",
     *      operationId="exitParking",
     *      tags={"Parking"},
     *      summary="Kendaraan keluar dari slot parkir pada blok tertentu",
     *      @OA\Parameter(
     *          name="blockId",
     *          in="path",
     *          required=true,
     *          description="ID blok parkir",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Parameter(
     *          name="slotId",
     *          in="path",
     *          required=true,
     *          description="ID slot parkir",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(response="200", description="Kendaraan berhasil keluar"),
     *      @OA\Response(response="404", description="Slot parkir tidak tersedia"),
     * )
     */
    public function exitParking($blockId, $slotId)
    {
        $slot = ParkingSlot::where('parking_block_id', $blockId)
            ->where('id', $slotId)
            ->where('is_occupied', true)
            ->first();

        if (!$slot) {
            return response()->json(['message' => 'Slot parkir tidak tersedia'], 404);
        }

        $slot->update(['is_occupied' => false]);
        return response()->json(['message' => 'Kendaraan berhasil keluar']);
    }
}
