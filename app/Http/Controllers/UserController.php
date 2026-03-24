<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Department;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {

        return view('users.index',);
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $users = User::with(['department', 'position', 'roles'])
                ->select('users.*')
                ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
                ->leftJoin('positions', 'users.position_id', '=', 'positions.id');

            // Filters
            if ($request->filled('status')) {
                $users->where('users.status', $request->status);
            }
            if ($request->filled('department_id')) {
                $users->where('users.department_id', $request->department_id);
            }
            if ($request->filled('employment_type_id')) {
                $users->where('users.employment_type', $request->employment_type_id);
            }
            if ($request->filled('role')) {
                $users->whereHas('roles', function ($q) use ($request) {
                    $q->where('roles.id', $request->role);
                });
            }

            return DataTables::of($users)
                ->addIndexColumn()

                ->editColumn('gender', function ($user) {
                    return $user->gender === 0
                        ? '<span class="badge badge-info">Nam</span>'
                        : '<span class="badge badge-danger">Nữ</span>';
                })
                ->editColumn('status', function ($user) {
                    return $user->status === 0
                        ? '<span class="badge badge-success">Đang làm việc</span>'
                        : '<span class="badge badge-secondary">Đã nghỉ việc</span>';
                })
                ->editColumn('employment_type', function ($user) {
                    return $user->employment_type === 0 ? 'Full-time' : 'Part-time';
                })
                ->addColumn('role', function ($user) {
                    return $user->roles->first()?->name;
                })
                ->addColumn('action', function ($user) {
                    return view('partials.action', compact('user'))->render();
                })
                ->rawColumns(['gender', 'status', 'action'])
                ->make(true);
        }
    }

    public function getFilterData(Request $request)
    {
        $status_data = collect([
            ['id'  => 1, 'text' => 'Đã nghỉ'],
            ['id' => 0, 'text' => 'Đang làm'],
        ]);
        $employment_type_data = collect([
            ['id' => 0, 'text' => 'Full-time'],
            ['id' => 1, 'text' => 'Part-time'],
        ]);
        $department_data = Department::select('id', 'name as text')->orderBy('name')->get();

        $team_data = Team::select('id', 'name as text')
            ->when($request->filled('department_id'), function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            }, function ($q) {
                $q->whereRaw('1 = 0');
            })
            ->orderBy('id')
            ->get();


        $role_data = Role::select('id', 'name as text')->orderBy('id')->get();

        return response()->json([
            'status_data' => $status_data,
            'department_data' => $department_data,
            'team_data' => $team_data,
            'employment_type_data' => $employment_type_data,
            'role_data' => $role_data,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'phone'    => 'nullable|string|max:20',
                'birthday' => 'nullable|date',
                'address'  => 'nullable|string|max:255',
                'gender'   => 'required|in:0,1',
                'department_id' => 'required|exists:departments,id',
                'team_id' => 'nullable|exists:teams,id',
                'start_date' => 'nullable|date',
                'employment_type' => 'required|in:0,1',
            ],
            [
                'name.required'  => 'Vui lòng nhập họ và tên.',
                'email.required' => 'Vui lòng nhập địa chỉ email.',
                'email.email'    => 'Định dạng email không hợp lệ.',
                'email.unique'   => 'Email này đã tồn tại trong hệ thống.',
                'password.required' => 'Vui lòng tạo mật khẩu đăng nhập.',
                'password.min'    => 'Mật khẩu phải có ít nhất 6 ký tự.',
                'gender.required' => 'Vui lòng chọn giới tính.',
                'department_id.required' => 'Vui lòng chọn phòng ban.',
                'department_id.exists' => 'Phòng ban không hợp lệ.',
                'team_id.exists' => 'Đội nhóm không hợp lệ.',
                'employment_type.required' => 'Vui lòng chọn hình thức làm việc.',
            ]
        );

        try {
            $data['password'] = Hash::make($data['password']);
            $data['status'] = 0;

            User::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Thêm nhân viên thành công.',
            ], 200);
        } catch (Exception $e) {
            Log::error('Create user failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi hệ thống. Vui lòng thử lại!',
            ], 500);
        }
    }
}
