<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Borrowing;
use App\Models\Asset;

class CustomController extends Controller
{
    public function updateBorrowerAndAssetStatus(Request $request) {
        if($request->status == 'borrowed') {
            $borrowing = Borrowing::find($request->id);
            $asset = Asset::find($borrowing->asset_id);

            $borrowing->status = 'borrowed';
            $asset->status = 'borrowed';

            $borrowing->save();
            $asset->save();
        } elseif($request->status == 'returned') {
            $borrowing = Borrowing::find($request->id);
            $asset = Asset::find($borrowing->asset_id);

            $borrowing->status = 'returned';
            $asset->status = 'available';

            $borrowing->save();
            $asset->save();
        }

        return redirect('/admin/borrowing');
    }
}
