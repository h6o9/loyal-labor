<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSavedAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SavedAddressController extends Controller
{
    public function index(Request $request)
    {
        if (!Schema::hasTable('user_saved_addresses')) {
            return response()->json([
                'success' => false,
                'message' => 'Saved addresses table is missing. Run user_saved_addresses SQL on live.',
            ], 500);
        }

        $items = $this->listForUser((int) $request->user()->id);

        return response()->json([
            'success' => true,
            'total' => $items->count(),
            'data' => $items,
        ]);
    }

 public function store(Request $request)
{
    $user = $request->user();

    $data = $this->validatedAddress($request);

    // Check if user already has any saved address
    $hasAddresses = UserSavedAddress::where('user_id', $user->id)->exists();

    // First address will automatically become default
    // Otherwise use the value sent by user
    $isDefault = !$hasAddresses || $request->boolean('is_default');

    // If this address is default, remove default from all other addresses
    if ($isDefault) {
        UserSavedAddress::where('user_id', $user->id)
            ->update(['is_default' => false]);
    }

    // Create new address
    $address = UserSavedAddress::create([
        'user_id'    => $user->id,
        'label'      => $data['label'],
        'address'    => $data['address'],
        'city'       => $data['city'],
        'is_default' => $isDefault,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Address saved successfully.',
        'data'    => $address->toApiArray(),
    ], 201);
}

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $address = UserSavedAddress::where('user_id', $user->id)->findOrFail($id);
        $data = $this->validatedAddress($request, false);

        $payload = array_filter([
            'label' => $data['label'] ?? null,
            'address' => $data['address'] ?? null,
            'city' => array_key_exists('city', $data) ? $data['city'] : null,
        ], fn ($value) => $value !== null);

        if ($request->has('city') && !$request->filled('city')) {
            $payload['city'] = null;
        }

        if ($request->exists('is_default') && $request->boolean('is_default')) {
            UserSavedAddress::where('user_id', $user->id)->update(['is_default' => false]);
            $payload['is_default'] = true;
        }

        $address->update($payload);

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully.',
            'data' => $address->fresh()->toApiArray(),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $address = UserSavedAddress::where('user_id', $user->id)->findOrFail($id);
        $wasDefault = (bool) $address->is_default;
        $address->delete();

        if ($wasDefault) {
            $next = UserSavedAddress::where('user_id', $user->id)->orderByDesc('id')->first();
            if ($next) {
                $next->update(['is_default' => true]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Address deleted successfully.',
        ]);
    }

    public function makeDefault(Request $request, $id)
    {
        $user = $request->user();
        $address = UserSavedAddress::where('user_id', $user->id)->findOrFail($id);

        UserSavedAddress::where('user_id', $user->id)->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Default address updated.',
            'data' => $address->fresh()->toApiArray(),
        ]);
    }

    public static function listForUser(int $userId)
    {
        if (!Schema::hasTable('user_saved_addresses')) {
            return collect();
        }

        return UserSavedAddress::where('user_id', $userId)
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->get()
            ->map(fn (UserSavedAddress $item) => $item->toApiArray())
            ->values();
    }

    private function validatedAddress(Request $request, bool $creating = true): array
    {
        $rules = [
            'label' => ($creating ? 'nullable' : 'sometimes') . '|string|max:50',
            'type' => 'nullable|string|max:50',
            'address' => ($creating ? 'required' : 'sometimes') . '|string|max:500',
            'city' => 'nullable|string|max:100',
            'is_default' => 'nullable|boolean',
        ];

        $request->validate($rules);

        $label = trim((string) ($request->input('label') ?: $request->input('type') ?: ($creating ? 'Home' : null)));

        return [
            'label' => $label !== '' ? $label : ($creating ? 'Home' : null),
            'address' => $request->has('address') ? trim((string) $request->address) : null,
            'city' => $request->has('city') ? (trim((string) $request->city) ?: null) : ($creating ? null : null),
        ];
    }
}
