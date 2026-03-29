<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Department;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
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

    public function create(){
        $user = new User();
        return view('users.create', compact('user'));
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
                'role_id' => 'nullable|exists:roles,id',
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
                'role_id.exists' => 'Loại tài khoản không hợp lệ.',
                'employment_type.required' => 'Vui lòng chọn hình thức làm việc.',
            ]
        );

        try {
            $data['password'] = Hash::make($data['password']);
            $data['status'] = 0;

            $roleId = $data['role_id'] ?? null;
            unset($data['role_id']);

            $user = User::create($data);

            if ($roleId) {
                $role = Role::findById($roleId);
                $user->assignRole($role);
            }

            return response()->json([
                'success' => true,
                'msg' => 'Thêm nhân viên thành công',
            ], 200);
        } catch (Exception $e) {
            Log::error('Create user failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'msg' => 'Lỗi hệ thống',
            ], 500);
        }
    }

    public function edit($id)
    {
        $user = User::with('role')->findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate(
            [
                'name'     => 'required|string|max:255',
                'email'    => ['required', 'email', Rule::unique('users')->ignore($id)],
                'phone'    => 'nullable|string|max:20',
                'birthday' => 'nullable|date',
                'address'  => 'nullable|string|max:255',
                'gender'   => 'required|in:0,1',
                'department_id' => 'required|exists:departments,id',
                'team_id' => 'nullable|exists:teams,id',
                'role_id' => 'nullable|exists:roles,id',
                'start_date' => 'nullable|date',
                'employment_type' => 'required|in:0,1',
            ],
            [
                'name.required'  => 'Vui lòng nhập họ và tên.',
                'email.required' => 'Vui lòng nhập địa chỉ email.',
                'email.email'    => 'Định dạng email không hợp lệ.',
                'email.unique'   => 'Email này đã tồn tại trong hệ thống.',
                'gender.required' => 'Vui lòng chọn giới tính.',
                'department_id.required' => 'Vui lòng chọn phòng ban.',
                'department_id.exists' => 'Phòng ban không hợp lệ.',
                'team_id.exists' => 'Đội nhóm không hợp lệ.',
                'role_id.exists' => 'Loại tài khoản không hợp lệ.',
                'employment_type.required' => 'Vui lòng chọn hình thức làm việc.',
            ]
        );

        try {
            $data = $request->except('password');

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            $user->update($data);

            return response()->json([
                'success' => true,
                'msg' => 'Cập nhật thành công'
            ], 200);
        } catch (Exception $e) {
            Log::error('Update user failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'msg' => 'Lỗi hệ thống'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent delete myself
            if (Auth::id() === $user->id) {
                return response()->json([
                    'msg' => 'Không thể tự xóa tài khoản của chính mình!'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'msg' => 'Đã xóa nhân viên'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'msg' => 'Đã xảy ra lỗi!'
            ], 500);
        }
    }

    public function restore($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();

            return response()->json([
                'success' => true,
                'msg' => 'Đã khôi phục nhân viên thành công'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => 'Lỗi hệ thống, không thể khôi phục!'
            ], 500);
        }
    }
}
