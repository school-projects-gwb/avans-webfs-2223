<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Table;
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
}
