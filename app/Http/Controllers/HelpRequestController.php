<?php

namespace App\Http\Controllers;

use App\Models\HelpRequest;
use App\Models\Table;
use Illuminate\Http\Request;

class HelpRequestController extends Controller
{
    public function index(Request $request) {

    }

    public function create(Request $request, $tableId) {
        $table = Table::with('helpRequests')->find($tableId);
        if (count($table->helpRequests) == 0) {
            $helpRequest = new HelpRequest();
            $helpRequest->table_id = $table->id;
            $helpRequest->save();
            return true;
        }

        return false;
    }

    public function update(Request $request, HelpRequest $helpRequest) {

    }

    public function destroy(Request $request, HelpRequest $helpRequest) {

    }

    public function show(Request $request, $tableId) {
        $table = Table::with('helpRequests')->find($tableId);
        return $table ? count($table->helpRequests) > 0 : response('Tafel niet gevonden.', 404);
    }
}
