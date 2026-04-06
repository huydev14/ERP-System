<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;

use App\Models\User;
use App\Models\Department;
use App\Models\Team;
use Spatie\Permission\Models\Role;

use Yajra\DataTables\DataTables;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AuditLogService;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function show($id)
    {
        $user = User::with(['roles', 'department', 'position', 'team'])->findOrFail($id);
        $activities = $user->activities()->latest()->get();

        return view('users.show', compact('user', 'activities'));
    }

    public function create()
    {
        $user = new User();
        return view('users.create', compact('user'));
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

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

            return response()->json(['success' => true, 'msg' => 'Thêm nhân viên thành công'], 200);
        } catch (Exception $e) {
            Log::error('Create user failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['status' => 'error', 'msg' => 'Lỗi hệ thống'], 500);
        }
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validated();

        try {
            $data = $request->except('password');

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            $user->update($data);

            return response()->json(['success' => true, 'msg' => 'Cập nhật thành công'], 200);
        } catch (Exception $e) {
            Log::error('Update user failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString(),]);
            return response()->json(['success' => false, 'msg' => 'Lỗi hệ thống'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            if (Auth::id() === $user->id) {
                return response()->json(['msg' => 'Không thể tự xóa tài khoản của chính mình!'], 403);
            }
            $user->delete();

            return response()->json(['success' => true, 'msg' => 'Đã xóa nhân viên']);
        } catch (Exception $e) {
            Log::error('Remove user failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString(),]);
            return response()->json(['msg' => 'Đã xảy ra lỗi!'], 500);
        }
    }

    public function restore($id)
    {
        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();

            return response()->json(['success' => true, 'msg' => 'Đã khôi phục nhân viên thành công']);
        } catch (Exception $e) {
            Log::error('Restore user failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString(),]);
            return response()->json(['success' => false, 'msg' => 'Lỗi hệ thống, không thể khôi phục!'], 500);
        }
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
                        ? '<span class="tw-inline-flex tw-items-center tw-px-2 tw-rounded-xs tw-text-xs tw-font-medium tw-bg-green-100 tw-text-green-800">
                            Active
                        </span>'
                        : '<span class="tw-inline-flex tw-items-center tw-px-2 tw-rounded-xs tw-text-xs tw-font-medium tw-bg-gray-100 tw-text-gray-800">
                            Leave
                        </span>';
                })
                ->editColumn('employment_type', function ($user) {
                    return $user->employment_type === 0 ? 'Full-time' : 'Part-time';
                })
                ->addColumn('role', function ($user) {
                    return $user->roles->first()->name ?? '<div class="tw-text-gray-400 tw-text-xs">---</div>';
                })
                ->addColumn('action', function ($user) {
                    return view('users._users-action', compact('user'))->render();
                })
                ->rawColumns(['gender', 'status', 'role', 'action'])
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
}
