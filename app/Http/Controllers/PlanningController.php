<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Table;
use App\Models\TableUser;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlanningController extends Controller
{
    public function getData() {
        $planningData = Table::all();

        foreach ($planningData as $table) {
            $weekdayUsers = [];

            for ($weekday = 1; $weekday <= 7; $weekday++) {
                $users = $table->users()->wherePivot('weekday', $weekday)->get();
                $usersNotAttached = User::role('Cashier')
                    ->whereDoesntHave('tables', function ($query) use ($table, $weekday) {
                        $query->where('tables.id', $table->id)->where('table_user.weekday', $weekday);
                    })->get();

                $weekdayUsers[$weekday] = [
                    'attached' => $users,
                    'notAttached' => $usersNotAttached,
                ];
            }

            $table->weekdayUsers = $weekdayUsers;
        }

        return $planningData;
    }

    public function index(Request $request)
    {
        $planningData = $this->getData();

        return Inertia::render('Planning/Index', [
            'planning_data' => $planningData
        ]);
    }

    public function createTable(Request $request) {
        $highestTableNumber = Table::max('table_number');
        $tableNumber = $highestTableNumber + 1;

        $table = new Table();
        $table->table_number = $tableNumber;
        $table->save();

        return response()->json(['message' => 'Tafel succesvol aangemaakt']);
    }

    public function destroyTable(Request $request, $tableId) {
        $table = Table::findOrFail($tableId);
        $tableNumber = $table->table_number;
        $table->delete();

        Table::where('table_number', '>', $tableNumber)
            ->decrement('table_number');

        return response()->json(['message' => 'Tafel succesvol verwijderd']);
    }

    public function unassign(Request $request, $tableId, $userId, $weekday) {
        $tableUser = TableUser::where('user_id', $userId)
            ->where('table_id', $tableId)
            ->where('weekday', $weekday)
            ->first();

        if ($tableUser) {
            $tableUser->delete();
            return response('Gebruiker verwijderd van tafel.');
        } else {
            return response('Gebruiker of tafel niet gevonden.', 404);
        }
    }

    public function assign(Request $request, $tableId, $userId, $weekday) {
        TableUser::create([
            'table_id' => $tableId,
            'user_id' => $userId,
            'weekday' => $weekday,
        ]);

        return response('Gebruiker toegewezen aan tafel.');
    }
}
