<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Schedule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Util\Common as CommonUtil;
use Illuminate\Support\Facades\Session;

class ActivityController extends Controller
{
	public function getActivityType(Request $request)
	{
		$user_profile = $this->initProfile();
		$data = array_merge(array(), $user_profile);
		$data['activities_type'] = Activity::all();	
		return view('admin.activity.type.index', $data);
	}

	public function showCreateActivityTypeForm(Request $request)
	{
		$user_profile = $this->initProfile();
		$data = array_merge(array(), $user_profile);
		$data['item'] = NULL;
		$data['days'] = array_values(CommonUtil::getDayOptions());
		return view('admin.activity.type.form', $data);	
	}

	public function showUpdateActivityTypeForm(Request $request, $id)
	{
		$current_record = Activity::find($id);
		if (empty($current_record))
			return back()
				->with(['error' => 'Gagal mengupdate jenis kegiatan, entitas tidak ditemukan']);
		$user_profile = $this->initProfile();
		$data = array_merge(array(), $user_profile);
		$data['days'] = array_values(CommonUtil::getDayOptions());
		$current_record->start_time = date("Y-m-d\TH:i", $current_record->start_time);  
		$current_record->end_time = date("Y-m-d\TH:i", $current_record->end_time);  
		$data['item'] = $current_record;	
		$data['selected_days'] =  array_keys(CommonUtil::getDayOptionsFromValue($current_record->recurring_days));
		return view('admin.activity.type.form', $data);	
	}

	public function createActivityType(Request $request)
	{
		$user_input_field_rules = [
			'name' => 'required',
			'description' => 'required',
			'start_time' => 'required',
			'end_time'	=> 'required'
		];
		$user_input = $request->only('name', 'description', 'start_time', 'end_time', 'leader');

		$validator = Validator::make($user_input, $user_input_field_rules);
		if ($validator->fails())
			return back()
						->withErrors($validator)
						->withInput();
		
		if (!$request->hasFile('banner'))
			return back()->withErrors(['banner' => 'Banner wajib di isi'])
							->withInput();

		if (!$request->hasFile('icon'))
			return back()->withErrors(['icon' => 'Icon wajib di isi'])
							->withInput();
		
		$filename = time() . '.' . $request->file('banner')->getClientOriginalExtension();
		$path = $request->file('banner')->storeAs('public/activity', $filename);
		if (empty($path))
		{
			return back()->withErrors(['banner' => 'Gagal mengupload banner'])
						->withInput();
		}
		
		$user_input['banner'] = $filename;

		$filename = time() . '.' . $request->file('icon')->getClientOriginalExtension();
		$path = $request->file('icon')->storeAs('public/activity', $filename);
		if (empty($path))
		{
			return back()->withErrors(['icon' => 'Gagal mengupload icon'])
						->withInput();
		}
		$user_input['icon'] = $filename;

		if ($request->has('recurring') && $request->has('recurring_days'))
		{
			$selected_days = $request->input('recurring_days');
			$user_input['recurring'] = TRUE;
			$user_input['recurring_days'] = CommonUtil::getDayValueFromCheckOptionIds($selected_days);
		}
		
		if ($request->has('show_landing_page')) $user_input['show_landing_page'] = TRUE;
		
		$user_input['start_time'] = strtotime($request->input('start_time'));
		$user_input['end_time'] = strtotime($request->input('end_time'));
		
		Activity::create($user_input);
		return redirect()
					->route('activity.type.index')
					->with(['success' => 'Berhasil menambahkan jenis kegiatan baru']);
	} 

	public function updateActivityType(Request $request)
	{
		$current_record = Activity::find($request->id);
		if (empty($current_record))
			return back()
					->with(['error' => 'Berhasil mengupdate jenis kegiatan, entitas tidak ditemukan']);
		
		$user_input_field_rules = [
			'name' => 'required',
			'description' => 'required',
			'start_time' => 'required',
			'end_time'	=> 'required'
		];
		$user_input = $request->only('name', 'description', 'start_time', 'end_time', 'leader');

		$validator = Validator::make($user_input, $user_input_field_rules);
		if ($validator->fails())
			return back()
						->withErrors($validator)
						->withInput();
		
		if ($request->hasFile('banner'))
		{
			$filename = time() . '.' . $request->file('banner')->getClientOriginalExtension();
			$path = $request->file('banner')->storeAs('public/activity', $filename);
			if (empty($path))
			{
				return back()->withErrors(['banner' => 'Gagal mengupload banner'])
							->withInput();
			}
			$file_location = 'public/activity/' . CommonUtil::getFileName($current_record->banner);
			Storage::delete($file_location);
			$user_input['banner'] = $filename;
		}

		if ($request->hasFile('icon'))
		{
			$filename = time() . '.' . $request->file('icon')->getClientOriginalExtension();
			$path = $request->file('icon')->storeAs('public/activity', $filename);
			if (empty($path))
			{
				return back()->withErrors(['icon' => 'Gagal mengupload icon'])
							->withInput();
			}
			$file_location = 'public/activity/' . CommonUtil::getFileName($current_record->icon);
			Storage::delete($file_location);
			$user_input['icon'] = $filename;
		}

		$user_input['recurring'] = FALSE;
		if ($request->has('recurring_days') && count($request->input('recurring_days')) > 0)
		{
			$selected_days = $request->input('recurring_days');
			$user_input['recurring'] = TRUE;
			$user_input['recurring_days'] = CommonUtil::getDayValueFromCheckOptionIds($selected_days);
		}
		
		if ($request->has('recurring_days') && count($request->input('recurring_days')) == 0)
		{
			$user_input['recurring'] = FALSE;
			$user_input['recurring_days'] = 0;
		}
		
		if ($request->has('show_landing_page')) $user_input['show_landing_page'] = TRUE;
		
		$user_input['start_time'] = strtotime($request->input('start_time'));
		$user_input['end_time'] = strtotime($request->input('end_time'));
		
		Activity::where('id', $current_record->id)
				->update($user_input);
		return redirect()
					->route('activity.type.index')
					->with(['success' => 'Berhasil mengupdate jenis kegiatan']);
	}

	public function getSchedule(Request $request)
	{
		$user_profile = $this->initProfile();
		$data = array_merge(array(), $user_profile);
		$data['activity'] = Activity::all();
		return view('admin.activity.schedule.index', $data);	
	}

	public function getScheduleList(Request $request)
	{
		$data = Schedule::with('activity')->get();
		$schedules = [];
		foreach($data as $item) {
			if (array_key_exists($item->scheduled_date, $schedules))
			{
				$schedules[$item->scheduled_date][] = $item;
				continue;
			}
			$schedules[$item->scheduled_date] = [$item];
		}
		return response()->json([
			'data' => $schedules,
			'message' => 'Berhasil mendapatkan jadwal kegiatan'
		]);
	}

	public function createSchedule(Request $request)
    {
		$user_input_field_rules = [
            'activity_id' => 'required',
            'scheduled_date' => 'required|date_format:Y-m-d',
			'scheduled_start_time' => 'required|date_format:H:i',
			'scheduled_end_time' => 'required|date_format:H:i'
        ];

		$user_input = $request->only('activity_id', 'scheduled_date', 'scheduled_start_time', 'scheduled_end_time');
		$validator = Validator::make($user_input, $user_input_field_rules);
		if ($validator->fails())
		{
			return response()->json([
				'message' => 'Input tidak sesuai',
				'data' => $validator->errors()->all()
			], 400); 
		}
        
		Schedule::create($user_input);
		Session::flash('success', 'Berhasil membuat jadwal');
        return response()->json([
            'message' => 'Schedule berhasil dibuat',
            'data' => $user_input
        ]);
    }

	public function deleteSchedule(Request $request, $scheduleId)
	{
		Schedule::find($scheduleId)->delete();
		Session::flash('success', 'Berhasil manghapus jadwal');
		return response()->json([
			'data' => [],
			'message' => 'Berhasil menghapus jadwal'
		]);
	}
}
