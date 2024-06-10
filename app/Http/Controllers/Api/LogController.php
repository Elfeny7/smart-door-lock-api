<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogResource;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Google\Cloud\BigQuery\BigQueryClient;

class LogController extends Controller
{
    private $bigQuery;

    public function __construct(BigQueryClient $bigQuery)
    {
        $this->bigQuery = $bigQuery;
    }

    public function index()
    {
        $logs = Log::latest()->get();
        return new LogResource(true, 'List Data Logs', $logs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'role'       => 'required',
            'class_name' => 'required',
            'image'      => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('image');
        $image->storeAs('public/image', $image->hashName());

        $log = Log::create([
            'name'       => $request->name,
            'role'       => $request->role,
            'class_name' => $request->class_name,
            'image'      => $image->hashName(),
        ]);

        $this->insertToBigQuery($log);

        return new LogResource(true, 'Data Log Berhasil Ditambahkan!', $log);
    }

    private function insertToBigQuery($log)
    {
        $dataset = $this->bigQuery->dataset(env('GOOGLE_DATASET_ID'));
        $table = $dataset->table(env('GOOGLE_TABLE_ID'));

        $row = [
            'name'       => $log->name,
            'role'       => $log->role,
            'class_name' => $log->class_name,
            'date_time'  => $log->created_at->toDateTimeString(),
        ];

        $insertResponse = $table->insertRows([['data' => $row]]);

        if (!$insertResponse->isSuccessful()) {
            $errors = [];
            foreach ($insertResponse->failedRows() as $row) {
                foreach ($row['errors'] as $error) {
                    $errors[] = sprintf('%s: %s', $error['reason'], $error['message']);
                }
            }
            Log::error('BigQuery insert failed: ' . implode(', ', $errors));
        }
    }
}
