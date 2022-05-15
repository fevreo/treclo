<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'category_id', 'user_id', 'order'];

    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function category()
    {
        return $this->hasOne(Category::class);
    }

    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task = Task::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'user_id' => $request->user_id,
            'order' => $request->order
        ]);

        return response()->json([
            'status' => (bool) $task,
            'data' => $task,
            'message' => $task ? 'Task Created!' : 'Error Creating Task'
        ]);
    }

    /**
     * Update the specified patch in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $sort_num = 0;
        $cat_id = 1;
        $count = 2;
        for ($i = 0; $i <= $count; $i++) {
            $orders = $request->tasks[$i]['tasks'];
            foreach ($orders as $order) {
                $status = Task::where('id', $order['id'])->first();
                $status->order = $sort_num;
                $status->category_id = $cat_id;
                $status->update();
                $sort_num++;
                if (count($orders) == $sort_num) {
                    $sort_num = 0;
                }
            }
            $cat_id++;
        }

        return response()->json([
            'status' => (bool) $status,
            'message' => $status ? 'Task Updated!' : 'Error Updating Task'
        ]);
    }
}
